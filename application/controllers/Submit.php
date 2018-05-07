<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';

use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Rest\ApiContext;
use BlockCypher\Client\PaymentForwardClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class Submit extends MY_Controller
{

    public $isMaintenance = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Submission_model', 'SubmissionModel');
        $this->load->model('Project_category_model', 'ProjectCategoryModel');
        $this->load->model('Submission_team_model', 'SubmissionTeamModel');
        $this->load->model('Project_model', 'ProjectModel');

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        if ($this->isMaintenance) {
            $this->layoutData['head']['title'] = 'Site Maintenance';
            $this->renderView('maintenance.twig');
            exit();
        }
    }

    public function submit_entry()
    {
        if (!empty($this->input->get('h', TRUE))) {
            $submission = $this->SubmissionModel->getSubmissionByHash($this->input->get('h', TRUE));
            if (!$submission) {
                redirect('');
                exit();
            }
        }

        if (isset($submission) && !empty($submission)) {
            $this->layoutData['formData'] = [
                'tx_name' => $submission['SubName'],
                'tx_email' => $submission['SubEmail'],
                'tx_eventname' => $submission['SubEventName'],
                'tx_info' => $submission['SubInfo'],
                'nm_responsible' => $submission['SubResponsible']
            ];
        } else {
            $ssUser = $this->session->userdata(self::SS_USER);
            $this->layoutData['formData'] = [
                'tx_email' => $ssUser['email']
            ];
        }

        $listingFee = $this->getSetting(2);
        $this->layoutData['isValid'] = true;
        $this->layoutData['isSuccess'] = false;
        $this->form_validation->set_error_delimiters('<p class="diverror">', '</p>');

        if ($this->input->post('submit', TRUE)) {

            $this->layoutData['isValid'] = false;
            $formData = $this->input->post(NULL, TRUE);
            $this->layoutData['formData'] = $formData;

            $this->form_validation->set_rules('tx_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('tx_email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('tx_eventname', 'Event Name', 'trim|required');
            $this->form_validation->set_rules('tx_info', 'Info', 'trim|max_length[256]');

            if ($this->form_validation->run() === TRUE) {
                $this->layoutData['isValid'] = true;
                $this->layoutData['isSuccess'] = true;
                $this->layoutData['formData'] = [];
                $ssUser = $this->session->userdata(self::SS_USER);
                $arrData = array(
                    'SubName' => cleanInput($formData['tx_name']),
                    'SubEmail' => cleanInput($formData['tx_email']),
                    'SubEventName' => cleanInput($formData['tx_eventname']),
                    'SubInfo' => cleanInput($formData['tx_info']),
                    'SubResponsible' => isset($formData['nm_responsible']) ? 1 : 0,
                );

                if (isset($submission) && !empty($submission)) {
                    $this->SubmissionModel->update($arrData, ['SubID' => $submission['SubID']]);
                    $subHashCode = $submission['SubHashCode'];
                } else {
                    $arrData['SubHashCode'] = hashSubmission($formData['tx_email'], $formData['tx_eventname']);
                    $arrData['SubSendMail'] = 0;
                    $arrData['SubUsers'] = $ssUser['id'];
                    $this->SubmissionModel->insert($arrData);
                    $subHashCode = $arrData['SubHashCode'];

                    // send mail
                    $this->load->helper('email');
                    $mailBody = $this->load->view('emails/submit_entry_admin', $arrData, true);
                    $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
                    $sendTo1 = array('email' => $this->layoutData['config']['admin_email'], 'name' => 'Moon');
                    $subject = "New coinschedule entry - ".$arrData['SubEventName']." (".$arrData['SubName'].")";
                    send_email($sendFrom, $sendTo1, $subject, $mailBody);
                }

                redirect('submission_entry?h=' . $subHashCode);
            } else {
                $fields = array('tx_name', 'tx_email', 'tx_eventname', 'tx_info');
                $this->renderFormError($fields);
            }
        }

        $this->layoutData['listingFee'] = $listingFee;
        $this->layoutData['bodyClass'] = 'landing-page';
        $this->layoutData['head']['title'] = 'Submit an Entry';
        $this->renderView('submit_entry.twig');
    }

    public function submitEntryNew()
    {
        $this->layoutData['bodyClass'] = 'landing-page';
        $this->layoutData['head']['title'] = 'Submit an Entry';
        $this->renderView('submit_entry_new.twig');
    }

    public function submission()
    {
        if (empty($this->input->get('h', TRUE))) {
            redirect('');
            exit();
        } else {
            $submission = $this->SubmissionModel->getSubmissionByHash($this->input->get('h', TRUE));
            if (empty($submission)) {
                redirect('');
                exit();
            }
        }

        // init form
        $this->SubmissionTeamModel->reorderTeam($submission['SubID']);
        $subTeamData = $this->SubmissionTeamModel->getData(['SubID' => $submission['SubID']], false, false, ['SubTeamNumber' => 'ASC']);
        $subTeamData = array_column($subTeamData, NULL, 'SubTeamNumber');

        // post form
        $this->layoutData['formData'] = [];
        $this->layoutData['isValid'] = true;
        $this->form_validation->set_error_delimiters('<p class="diverror">', '</p>');

        if ($this->input->post('hashcode', TRUE)) {
            $this->layoutData['isValid'] = false;
            $formData = $this->input->post(NULL, TRUE);

            $this->form_validation->set_rules('txt_symbol', 'Symbol', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('txt_supply', 'Total Supply', 'trim|required|numeric_wcommas');
            $this->form_validation->set_rules('txt_coinname', 'Token/Coin Name', 'trim|required|max_length[255]');
            $this->form_validation->set_rules('project_type', 'Project Type', 'trim|required|valid_projectType');
            $this->form_validation->set_rules('project_category', 'Project Category', 'trim|required');
            $this->form_validation->set_rules('txt_website', 'Project Website', 'trim|required|valid_url');
            $this->form_validation->set_rules('txt_whitepaper', 'Link to Whitepaper', 'trim|valid_url');
            $this->form_validation->set_rules('txt_twitter', 'Twitter', 'trim|valid_url|valid_link[twitter]');
            $this->form_validation->set_rules('txt_reddit', 'Reddit', 'trim|valid_url|valid_link[reddit]');
            $this->form_validation->set_rules('txt_slack', 'Slack', 'trim|valid_url|valid_link[slack]');
            $this->form_validation->set_rules('txt_bctann', 'Bitcointalk Announcement Thread', 'trim|valid_url|valid_link[bctann]');

            // start - end required
            if (!isset($formData['dates_defined'])) {
                $this->form_validation->set_rules('start_date', 'Start', 'trim|required');
                $this->form_validation->set_rules('end_date', 'End', 'trim|required|valid_ico_enddate[start_date]|valid_time_range[start_date]');
            }

            // platform required
            if ($formData['project_type'] == 2) {
                $this->form_validation->set_rules('platforms', 'Platform', 'trim|required');
            }

            // algo required
            if ($formData['project_type'] == 1) {
                $this->form_validation->set_rules('ProjAlgo', 'Algorithm', 'trim|required');
            }

            // logo validate
            if ($_FILES['logo_file']['error'] == 4 && empty($submission['SubLogo'])) {
                $this->form_validation->set_rules('txt_logo', 'Logo', 'trim|required|valid_url|valid_url_logo');
            } else {
                $this->form_validation->set_rules('txt_logo', 'Logo', 'trim|valid_url|valid_url_logo');
            }

            // team validate
            $arrMustValidate = $this->validTeamMember(self::LIMIT_TEAM_MEMBER, $subTeamData);

            if ($this->form_validation->run() === TRUE) {
                $this->layoutData['isValid'] = true;
                $formData['logoFileName'] = '';
                if ($submission['SubLogo']) {
                    $formData['logoFileName'] = $submission['SubLogo'];
                }

                if ($_FILES['logo_file']['error'] == 0) {
                    $isUploaded = $this->uploadLogo('logo_file', $submission);
                    if (!$isUploaded) {
                        $this->layoutData['isValid'] = false;
                    } else {
                        $formData['logoFileName'] = $this->layoutData['dataUpload']['file_name'];
                    }
                }

                $this->updateSubmission($submission, $formData);
                $isUpdated = $this->updateTeamMember($arrMustValidate, $subTeamData, $submission['SubID']);
                if (!$isUpdated) {
                    $this->layoutData['isValid'] = false;
                } else {
                    redirect('payment?h='.$submission['SubHashCode']);
                }
            } else {
                $fields = array_keys($formData);
                $this->renderFormError($fields);
            }
        }

        $this->layoutData['teamMemberLimit'] = self::LIMIT_TEAM_MEMBER;
        $this->layoutData['teamData'] = $subTeamData;
        $this->layoutData['submission'] = $submission;
        $this->layoutData['hash'] = $this->input->get('h', TRUE);

        if ($submission['SubLogo']) {
            $this->layoutData['logoLink'] = base_url() . self::PATH_IMAGE_LOGO . $submission['SubLogo'];
        }

        $this->renderFormValue($submission);
        $this->renderPlatform();
        $this->renderAlgorithm();
        $this->renderProjectCategory($submission);
        $this->renderProjectType($submission);
        $this->renderTeamData($subTeamData);
        $this->renderScript();

        $this->layoutData['head']['title'] = 'Listing Submission';
        $this->renderView('submission_entry.twig');
    }

    public function payment()
    {
        if (empty($this->input->get('h', TRUE))) {
            redirect('');
            exit();
        } else {
            $submission = $this->SubmissionModel->getSubmissionByHash($this->input->get('h', TRUE));
            if (empty($submission)) {
                redirect('');
                exit();
            }
        }

        $this->load->model('Payment_model', 'PaymentModel');
        $this->layoutData['isPaid'] = $this->PaymentModel->isPaid($submission['SubHashCode']);
        $this->layoutData['listingFee'] = $this->getSetting(2);
        $this->layoutData['paymentAddress'] = $this->initAddressPayment($submission, $this->layoutData['listingFee']);
        $this->layoutData['submission'] = $submission;

        $this->layoutData['head']['title'] = 'Basic Listing Payment';
        $this->renderView('payment.twig');
    }

    private function renderProjectCategory($submission)
    {
        $this->layoutData['htmlProjectCategory'] = false;
        $projectCategory = $this->ProjectCategoryModel->getData(array(),FALSE,FALSE,array('ProjCatName' => 'asc'));
        if (!empty($projectCategory)) {
            $this->layoutData['htmlProjectCategory'] .= '<option selected value="">Select</option>';
            $subProjCat = formSetValue('project_category', $submission['SubProjCatID']);
            foreach ($projectCategory as $category) {
                if ($category['ProjCatID'] == $subProjCat) {
                    $this->layoutData['htmlProjectCategory'] .= '<option selected value="'.$category['ProjCatID'].'">'.$category['ProjCatName'].'</option>';
                } else {
                    $this->layoutData['htmlProjectCategory'] .= '<option value="'.$category['ProjCatID'].'">'.$category['ProjCatName'].'</option>';
                }
            }
        }
    }

    private function renderProjectType($submission)
    {
        $this->layoutData['htmlProjectType'] = false;
        $projectType = $this->ProjectModel->getProjectTypes();

        if (!empty($projectType)) {
            $subProjType = formSetValue('project_type', $submission['SubProjType']);
            foreach ($projectType as $key => $value) {
                if ($key == $subProjType) {
                    $this->layoutData['htmlProjectType'] .= '<option selected value="'.$key.'">'.$value.'</option>';
                } else {
                    $this->layoutData['htmlProjectType'] .= '<option value="'.$key.'">'.$value.'</option>';
                }
            }
        }
    }

    protected function renderPlatform()
    {
        $this->layoutData['htmlPlatform'] = '';
        $platforms = $this->ProjectModel->getPlatforms();
        $this->layoutData['htmlPlatform'] .= '<option value="">Select</option>';
        if (!empty($platforms)) {
            foreach ($platforms as $item) {
                $this->layoutData['htmlPlatform'] .= '<option value="'.$item['ProjID'].'">'.$item['ProjName'].'</option>';
            }
        }
    }

    protected function renderAlgorithm()
    {
        $this->layoutData['htmlAlgorithmOption'] = '';
        $algoList = getAlgorithm();
        foreach ($algoList as $k => $v) {
            $this->layoutData['htmlAlgorithmOption'] .= '<option value="'.$k.'">'.$v.'</option>';
        }
    }

    private function renderTeamData($subTeamData)
    {
        $htmlTeamPanel = '';
        $currentMember = 1;
        for ($i = 1; $i <= self::LIMIT_TEAM_MEMBER; $i ++) {

            $style = '';
            if ($i != 1 && empty($subTeamData[$i]['SubTeamFullName'])) {
                $style = 'style="display: none"';
            }

            $pictureLink   = '';
            $passportLink   = '';
            if (isset($subTeamData[$i]) && !empty($subTeamData[$i])) {
                $inputFullname = formSetValue('inputFullname'.$i, $subTeamData[$i]['SubTeamFullName']);
                $inputPosition = formSetValue('inputPosition'.$i, $subTeamData[$i]['SubTeamPosition']);
                $inputShortBio = formSetValue('inputShortBio'.$i, $subTeamData[$i]['SubTeamShortBio']);
                $inputLinkedin = formSetValue('inputLinkedin'.$i, $subTeamData[$i]['SubTeamLinkedin']);
                if (!empty($subTeamData[$i]['SubTeamPicture'])) {
                    $pictureLink = '<a target="_blank" class="teamlink" href="'.base_url() . self::PATH_IMAGE_TEAM . $subTeamData[$i]['SubTeamPicture'].'"><strong>'.$subTeamData[$i]['SubTeamPicture'].'</strong></a>';
                }
                if (!empty($subTeamData[$i]['SubTeamPassport'])) {
                    $passportLink = '<strong>Uploaded</strong>';
                }
            } else {
                $inputFullname = formSetValue('inputFullname'.$i, '');
                $inputPosition = formSetValue('inputPosition'.$i, '');
                $inputShortBio = formSetValue('inputShortBio'.$i, '');
                $inputLinkedin = formSetValue('inputLinkedin'.$i, '');
            }

            if (!empty($inputFullname) || !empty($inputPosition) || !empty($inputShortBio) || !empty($inputLinkedin)) {
                $style = '';
                if ($i != 1) {
                    $currentMember += 1;
                }
            }

            $htmlTeamPanel .=
                '<div id="panelTeamMember'.$i.'" class="panel panel-default" '.$style.'>
                    <div class="panel-heading" role="tab" id="heading'.$i.'">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse'.$i.'">
                                Team Member #'.$i.'
                            </a>
                            <i data-id="'.$i.'" style="float: right; cursor: pointer" class="fa fa-times deleteMember" aria-hidden="true"></i>
                        </h4>
                    </div>

                    <div id="collapse'.$i.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading'.$i.'">
                        <div class="panel-body">
                            <div class="form-horizontal" id="panelTeamMember'.$i.'">
                                <div class="form-group">
                                    <label for="inputFullname'.$i.'" class="col-sm-2 control-label">Full Name <span style="color:#F00">*</span></label>
                                    <div class="col-sm-6">
                                        <input value="'.$inputFullname.'" type="text" class="form-control" id="inputFullname'.$i.'" name="inputFullname'.$i.'" placeholder="Full Name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPicture'.$i.'" class="col-sm-2 control-label">Picture</label>
                                    <div class="col-sm-6">
                                        <input type="file" class="form-control" id="inputPicture'.$i.'" name="inputPicture'.$i.'">
                                        '.$pictureLink.'
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPosition'.$i.'" class="col-sm-2 control-label">Position <span style="color:#F00">*</span></label>
                                    <div class="col-sm-6">
                                        <input value="'.$inputPosition.'" type="text" class="form-control" id="inputPosition'.$i.'" name="inputPosition'.$i.'" placeholder="Position">
                                        <span class="help-block">e.g.: Founder, Co-Founder, Developer, Tester</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputShortBio'.$i.'" class="col-sm-2 control-label">Short Bio <span style="color:#F00">*</span></label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control" id="inputShortBio'.$i.'" name="inputShortBio'.$i.'">'.$inputShortBio.'</textarea>
                                        <span class="help-block">Maximum 1024 characters</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputLinkedin'.$i.'" class="col-sm-2 control-label">Linkedin</label>
                                    <div class="col-sm-6">
                                        <input value="'.$inputLinkedin.'" type="text" class="form-control" id="inputLinkedin'.$i.'" name="inputLinkedin'.$i.'" placeholder="Linkedin link">
                                        <span class="help-block">e.g.: https://www.linkedin.com/your-profile</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassport'.$i.'" class="col-sm-2 control-label">Upload ID (Passport)</label>
                                    <div class="col-sm-6">
                                        <input type="file" class="form-control" id="inputPassport'.$i.'" name="inputPassport'.$i.'">
                                        '.$passportLink.'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        }

        $this->layoutData['isAddMoreTeam'] = false;
        if ($currentMember < self::LIMIT_TEAM_MEMBER) {
            $this->layoutData['isAddMoreTeam'] = true;
        }
        $this->layoutData['currentMember'] = $currentMember;
        $this->layoutData['htmlTeamPanel'] = $htmlTeamPanel;

    }

    private function updateSubmission($submission, $formData)
    {
        $algo = '';
        $platforms = str_replace("'", "", $formData['platforms']);

        if (isset($formData['ProjAlgo']) && $formData['ProjAlgo'] != '') {
            $algo = str_replace(",","", $formData['ProjAlgo']);
        }

        $arrUpdate = array(
            'SubLink'           => cleanInput($formData['txt_website']),
            'SubDatesNotDefined' => isset($formData['dates_defined']) ? '1' : '0',
            'SubProjType'       => (int) $formData['project_type'],
            'SubProjCatID'      => (int) $formData['project_category'],
            'SubPlatform'       => cleanInput($platforms),
            'SubAlgo'           => cleanInput($formData['ProjAlgo']),
            'SubCoinName'       => cleanInput($formData['txt_coinname']),
            'SubSymbol'         => cleanInput($formData['txt_symbol']),
            'SubSupply'         => str_replace(",","",cleanInput($formData['txt_supply'])),
            'SubLogo'           => $formData['logoFileName'],
            'SubLogoLink'       => cleanInput($formData['txt_logo']),
            'SubWhitePaper'     => cleanInput($formData['txt_whitepaper']),
            'SubTwitter'        => cleanInput($formData['txt_twitter']),
            'SubReddit'         => cleanInput($formData['txt_reddit']),
            'SubSlack'          => cleanInput($formData['txt_slack']),
            'SubBitcoinTalk'    => cleanInput($formData['txt_bctann']),
        );


        if ($arrUpdate['SubDatesNotDefined'] == '0') {
            $arrUpdate['SubStart'] = cleanInput(date('Y-m-d H:i:s', strtotime($formData['start_date'])));
            $arrUpdate['SubEnd'] = cleanInput(date('Y-m-d H:i:s', strtotime($formData['end_date'])));
        }

        $this->SubmissionModel->update($arrUpdate, ['SubID' => $submission['SubID']]);
    }

    private function updateTeamMember($arrMustValidate, $subTeamData, $SubID)
    {
        $result = TRUE;
        foreach ($arrMustValidate as $i) {
            $errors = [];
            $mappingTeam = $this->mappingTeam($i, $subTeamData);
            $SubTeamFullName = $mappingTeam['inputFullname'.$i];
            $SubTeamPosition = $mappingTeam['inputPosition'.$i];
            $SubTeamShortBio = $mappingTeam['inputShortBio'.$i];
            $SubTeamLinkedin = $mappingTeam['inputLinkedin'.$i];

            // Upload Files
            $this->load->library('upload');
            $SubTeamPassport = $mappingTeam['inputPassport'.$i];
            $SubTeamPicture = $mappingTeam['inputPicture'.$i];
            if (isset($_FILES['inputPicture'.$i]['name']) && !empty($_FILES['inputPicture'.$i]['name'])) {
                $SubTeamPicture = $this->uploadFileTeam('inputPicture'.$i, $SubID, $errors);
            }
            if (isset($_FILES['inputPassport'.$i]['name']) && !empty($_FILES['inputPassport'.$i]['name'])) {
                $SubTeamPassport = $this->uploadFileTeam('inputPassport'.$i, $SubID, $errors, TRUE);
            }

            if (empty($errors)) {
                $dataMember = $this->SubmissionTeamModel->getTeamByNumber($SubID, $i);
                if (empty($dataMember)) {
                    $arrInsert = array(
                        'SubID' => $SubID,
                        'SubTeamNumber' => $i,
                        'SubTeamFullName' => $SubTeamFullName,
                        'SubTeamPicture' => $SubTeamPicture,
                        'SubTeamPosition' => $SubTeamPosition,
                        'SubTeamShortBio' => $SubTeamShortBio,
                        'SubTeamLinkedin' => $SubTeamLinkedin,
                        'SubTeamPassport' => $SubTeamPassport
                    );
                    $this->SubmissionTeamModel->insert($arrInsert);
                } else {
                    $arrUpdate = array(
                        'SubTeamNumber'   => $i,
                        'SubTeamFullName' => $SubTeamFullName,
                        'SubTeamPicture'  => $SubTeamPicture,
                        'SubTeamPosition' => $SubTeamPosition,
                        'SubTeamShortBio' => $SubTeamShortBio,
                        'SubTeamLinkedin' => $SubTeamLinkedin,
                        'SubTeamPassport' => $SubTeamPassport,
                    );
                    $this->SubmissionTeamModel->update($arrUpdate, ['id' => $dataMember['id']]);
                }
            } else {
                $result = FALSE;
                break;
            }
        }

        return $result;
    }

    private function validTeamMember($teamMember, $subTeamData)
    {
        $arrMustValidate = array();
        for ($i = 1; $i <= $teamMember; $i ++) {
            $mappingTeam = $this->mappingTeam($i, $subTeamData);
            $isMust = 0;
            foreach ($mappingTeam as $team) {
                if ($team != '') {
                    $isMust = 1;
                    break;
                }
            }

            if ($isMust) {
                $arrMustValidate[] = $i;
            }
        }

        if (!empty($arrMustValidate)) {
            foreach ($arrMustValidate as $i) {
                $mappingTeam = $this->mappingTeamValid($i);
                foreach ($mappingTeam as $key => $value) {
                    if ($value == 'Linkedin') {
                        $this->form_validation->set_rules($key, $value . ' #' . $i, 'valid_url|valid_link[linkedin]');
                    } else if ($value == 'Short Bio') {
                        $this->form_validation->set_rules($key, $value . ' #' . $i, 'required|max_length[1024]');
                    } else {
                        $this->form_validation->set_rules($key, $value . ' #' . $i, 'required');
                    }
                }
            }
        }

        return $arrMustValidate;
    }

    private function mappingTeamValid($i)
    {
        $mapping = array(
            'inputFullname'.$i => 'Full Name',
            'inputPosition'.$i => 'Position',
            'inputShortBio'.$i => 'Short Bio',
            'inputLinkedin'.$i => 'Linkedin',
        );

        return $mapping;
    }

    private function mappingTeam($i, $subTeamData)
    {
        $mapping = array(
            'inputFullname'.$i => $this->input->post('inputFullname'.$i, TRUE),
            'inputPosition'.$i => $this->input->post('inputPosition'.$i, TRUE),
            'inputShortBio'.$i => $this->input->post('inputShortBio'.$i, TRUE),
            'inputLinkedin'.$i => $this->input->post('inputLinkedin'.$i, TRUE),
            'inputPassport'.$i => '',
            'inputPicture'.$i  => ''
        );

        if (isset($subTeamData[$i])) {
            $mapping['inputPicture'.$i] = $subTeamData[$i]['SubTeamPicture'];
            $mapping['inputPassport'.$i] = $subTeamData[$i]['SubTeamPassport'];
        }

        if (isset($_FILES['inputPassport'.$i]['name']) && !empty($_FILES['inputPassport'.$i]['name'])) {
            $mapping['inputPassport'.$i] = $_FILES['inputPassport'.$i]['name'];
        }

        if (isset($_FILES['inputPicture'.$i]['name']) && !empty($_FILES['inputPicture'.$i]['name'])) {
            $mapping['inputPicture'.$i] = $_FILES['inputPicture'.$i]['name'];
        }

        return $mapping;
    }

    private function renderScript()
    {
        $scriptCSS = [];
        $scriptCSS[] = base_url() . 'public/libs/flatpickr/flatpickr.min.css';
        $scriptCSS[] = base_url() . 'public/libs/bootstrap/bootstrap-multiselect.css';
        $scriptCSS[] = base_url() . 'public/libs/fontawesome/css/font-awesome.min.css';

        $scriptJS = [];
        $scriptJS['new_common'] = base_url() . 'public/dist/new-common-min.js';
        $scriptJS['jquery'] = base_url() . 'public/js/jquery.js';
        $scriptJS['bootstrap'] = base_url() . 'public/libs/bootstrap/bootstrap.min.js';
        $scriptJS['bootstrap_multiselect'] = base_url() . 'public/libs/bootstrap/bootstrap-multiselect.js';
        $scriptJS['flatpickr'] = base_url() . 'public/libs/flatpickr/flatpickr.js';

        $this->renderCSS($scriptCSS);
        $this->renderJS($scriptJS, false);
    }

    private function renderFormValue($submission)
    {
        $formData = [];
        $formData['txt_website'] = formSetValue('txt_website', $submission['SubLink']);
        $formData['dates_defined'] = formSetValueCheckbox('dates_defined', $submission['SubDatesNotDefined']);
        $formData['start_date'] = formSetValue('start_date', displayDate($submission['SubStart']));
        $formData['end_date'] = formSetValue('end_date', displayDate($submission['SubEnd']));
        $formData['platforms'] = formSetValue('platforms', $submission['SubPlatform']);
        $formData['ProjAlgo'] = formSetValue('ProjAlgo', $submission['SubAlgo']);
        $formData['txt_coinname'] = formSetValue('txt_coinname', $submission['SubCoinName']);
        $formData['txt_symbol'] = formSetValue('txt_symbol', $submission['SubSymbol']);
        $formData['txt_supply'] = formSetValue('txt_supply', $submission['SubSupply']);
        $formData['txt_logo'] = formSetValue('txt_logo', $submission['SubLogoLink']);
        $formData['txt_whitepaper'] = formSetValue('txt_whitepaper', $submission['SubWhitePaper']);
        $formData['txt_twitter'] = formSetValue('txt_twitter', $submission['SubTwitter']);
        $formData['txt_reddit'] = formSetValue('txt_reddit', $submission['SubReddit']);
        $formData['txt_slack'] = formSetValue('txt_slack', $submission['SubSlack']);
        $formData['txt_bctann'] = formSetValue('txt_bctann', $submission['SubBitcoinTalk']);

        $this->layoutData['formData'] = $formData;
    }

    private function uploadLogo($inputFile, $submission)
    {
        $config['upload_path']          = FCPATH . self::PATH_IMAGE_LOGO;
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['max_size']             = 2048; //KB
        $config['max_width']            = 1920;
        $config['max_height']           = 1080;
        $config['file_name']            = md5($submission['SubID'] . '_' . time() . '_' . rand(100, 999));
        $config['file_ext_tolower']     = true;
        $config['remove_spaces']        = true;

        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($inputFile)) {
            $this->layoutData['errorUpload'] = $this->upload->display_errors();
            return false;
        } else {
            $this->layoutData['dataUpload'] = $this->upload->data();
            return true;
        }
    }

    private function uploadFileTeam($field, $SubID, &$errors, $isPassport = FALSE)
    {
        $config = [];
        if ($isPassport) {
            $config['upload_path'] = FCPATH . self::PATH_IMAGE_PASSPORT;
            $config['file_name'] = 'passport_' . md5($SubID . time() . '_' . rand(10000, 99999));
        } else {
            $config['upload_path'] = FCPATH . self::PATH_IMAGE_TEAM;
            $config['file_name'] = 'team_' . md5($SubID . time() . '_' . rand(10000, 99999));
        }

        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['max_size']             = 2048; //KB
        $config['max_width']            = 1920;
        $config['max_height']           = 1080;
        $config['file_ext_tolower']     = true;
        $config['remove_spaces']        = true;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $errors[] = strip_tags($this->upload->display_errors());
            $this->layoutData['errorUploadTeam'] = strip_tags($this->upload->display_errors());
        } else {
            $uploadData = $this->upload->data();
            return $uploadData['file_name'];
        }
    }

    private function initAddressPayment($submission, $listingFee)
    {
        // Provide your Token. Replace the given one with your app Token
        // https://accounts.blockcypher.com/dashboard
        $token = $this->layoutData['config']['blockcypher'];
        $destinationAddress = $this->layoutData['config']['destination_address'];

        // SDK config
        $config = array(
            'mode' => 'live',
            'log.LogEnabled' => true,
            'log.FileName' => FCPATH . 'application/logs/BlockCypher_NEW.log',
            'log.LogLevel' => 'INFO', // PLEASE USE 'INFO' LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
        );

        $apiContext = ApiContext::create(
            'main', 'btc', 'v1',
            new SimpleTokenCredential($token),
            $config
        );

        // get payment address
        $payment = $this->PaymentModel->getPaymentBySubmission($submission['SubID']);
        if (empty($payment)) {
            $secret = md5(uniqid(rand(), TRUE).time());
            $my_callback_url = base_url().'payment?h='.$submission['SubHashCode'].'&secret='.$secret;
            $paymentForwardClient = new PaymentForwardClient($apiContext);
            $options = array(
                'callback_url' => $my_callback_url
            );
            $paymentForward = $paymentForwardClient->createForwardingAddress($destinationAddress, $options);
            $paymentForward = $paymentForward->toArray();
            $address = $paymentForward['input_address'];
            $PayForwardID = $paymentForward['id'];

            if (!empty($address)) {
                $arrInsert = array(
                    'SubID' => $submission['SubID'],
                    'PaySecret' => $secret,
                    'PayAmount' => $listingFee,
                    'PayStatus' => '0',
                    'PayAddress' => $address,
                    'PayForwardID' => $PayForwardID,
                    'PayCreatedDate' => date('Y-m-d H:i:s')
                );
                $this->PaymentModel->insert($arrInsert);

                // request to payment server
                $this->requestPaymentServer($arrInsert, $submission);
            }
            $this->layoutData['paySecret'] = $secret;
        } else {
            $address = $payment['PayAddress'];
            $this->layoutData['paySecret'] = $payment['PaySecret'];
        }

        return $address;
    }

    private function requestPaymentServer($payment, $submission)
    {
        $ssUser = $this->session->userdata(self::SS_USER);
        $endpoint = 'https://pay.coinschedule.com/order_create.php';
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $requestBody = [
            'total' => $payment['PayAmount'],
            'payaddress' => $payment['PayAddress'],
            'details' => '[{"desc": "Standard Listing","qty": "1","amt":"'.$payment['PayAmount'].'"}]',
            'hash' => $submission['SubHashCode'],
            'email' => $ssUser['email'],
            'projectname' => $submission['SubCoinName']
        ];

        $client = new Client();
        $response = $client->request('POST', $endpoint, [
            'headers' => $headers,
            'form_params' => $requestBody
        ]);


        // update PayRequestServer
        $this->load->model('Payment_model', 'PaymentModel');
        $statusCode = $response->getStatusCode();
        $payRequestServer = 3;
        if ($statusCode == 200) {
            $payRequestServer = 2;
        }
        $this->PaymentModel->update(['PayRequestServer' => $payRequestServer], ['PaySecret' => $payment['PaySecret']]);
    }

}
