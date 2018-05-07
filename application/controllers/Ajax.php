<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';
require_once 'application/libraries/CSProject.php';

class Ajax extends MY_Controller
{
    public $jsonData = [
        'status' => 0,
        'message' => '',
        'error' => '',
    ];

    public function __construct()
    {
        parent::__construct(true);

        $this->requiredLoginPage = array('sendContact', 'updateProfile', 'listingSaveProject', 'voteProject', 'favourProject');
        $action = $this->router->fetch_method();
        if (in_array($action, $this->requiredLoginPage) && !$this->session->has_userdata(self::SS_USER)) {
            $this->jsonData['message'] = "Permission Denied";
            $this->renderJson($this->jsonData);
            exit();
        }

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function sendContact()
    {
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'Message', 'min_length[6]');

        if ($this->form_validation->run() === TRUE) {
            $formData = $this->input->post(NULL, TRUE);
            $ssUser = $this->session->userdata(self::SS_USER);

            // send mail
            $this->load->helper('email');
            $mailBody = $this->load->view('emails/contact', $formData, true);
            $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
            $sendTo1 = array('email' => $this->layoutData['config']['admin_email'], 'name' => 'moon');
            $subject = $formData['category']." from user ".$ssUser['username']." - ".$formData['subject'];
            send_email($sendFrom, $sendTo1, $subject, $mailBody);
            $this->jsonData['status'] = 1;
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = validation_errors();
        }

        $this->renderJson($this->jsonData);
    }

    public function updateProfile()
    {
        $ssUser = $this->session->userdata(self::SS_USER);
        $userId = $ssUser['id'];
        $this->form_validation->set_rules('tx_username', 'Username', 'required|alpha_numeric|min_length[3]|is_unique_mg_update[tx_username]');
        $this->form_validation->set_rules('tx_email', 'Email', 'required|valid_email|is_unique_mg_update[tx_email]');
        $this->form_validation->set_rules('tx_newpass', 'Password', 'min_length[6]');

        if ($this->input->post('tx_email', TRUE) != $ssUser['email'] || !empty($this->input->post('tx_email', TRUE))) {
            $this->form_validation->set_rules('tx_pass', 'Password', 'required|min_length[6]|valid_current_password');
        } else {
            $this->form_validation->set_rules('tx_pass', 'Password', 'min_length[6]|valid_current_password');
        }

        if ($this->form_validation->run() === TRUE) {
            $this->load->model('User_model', 'UserModel');
            $formData = $this->input->post(NULL, TRUE);
            $arrUpdate = array(
                'tx_username' => $formData['tx_username'],
            );

            if (!empty($formData['tx_pass'])) {
                $arrUpdate['tx_email'] = $formData['tx_email'];
            }

            if (!empty($formData['tx_newpass'])) {
                $arrUpdate['tx_password'] = encryptPassword($formData['tx_newpass']);
            }

            $this->UserModel->update($arrUpdate, ['id_user' => $userId]);
            $this->jsonData['status'] = 1;

            // reset session
            $userInfo = $this->UserModel->getUserInfo($ssUser['id']);
            $userSession = array(
                'id'        => $ssUser['id'],
                'email'     => $userInfo['tx_email'],
                'username'  => $userInfo['tx_username'],
                'level'     => $userInfo['level'],
            );
            $this->session->set_userdata(array(self::SS_USER => $userSession));
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = strip_tags(validation_errors());
        }

        $this->renderJson($this->jsonData);
    }

    public function deleteTeamMember()
    {
        try {
            $this->load->model('Submission_model', 'SubmissionModel');
            $this->load->model('Submission_team_model', 'SubmissionTeamModel');
            $subHash = $this->input->post('hashcode', TRUE);
            $teamMember = $this->input->post('team_member', TRUE);
            $submission = $this->SubmissionModel->getSubmissionByHash($subHash, TRUE);
            if (empty($submission)) {
                $this->jsonData['message'] = "Permission Denied";
                $this->renderJson($this->jsonData);
                exit();
            }
            $this->SubmissionTeamModel->delete(['SubID' => $submission['SubID'], 'SubTeamNumber' => $teamMember]);
            $this->jsonData['status'] = 1;
        } catch (\Exception $e) {
            $this->jsonData['status'] = 0;
        }

        $this->renderJson($this->jsonData);
    }

    public function listingSaveProject()
    {
        if (!$this->verifyListingRequest()) {
            $this->jsonData['message'] = "Invalid request";
            $this->renderJson($this->jsonData);
            exit();
        }

        $this->load->model('Setting_model', 'SettingModel');
        $this->SettingModel->updateLastDbUpdate();

        $ssUser = $this->session->userdata(self::SS_USER);
        $this->load->model('Project_log_model', 'ProjectLogModel');
        $this->load->model('Event_model', 'EventModel');
        $project = $this->ProjectModel->getProjectEventEdit($this->input->post('ProjID', TRUE));
        $this->form_validation->set_rules('ProjSymbol', 'Symbol', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('ProjDesc', 'Description', 'trim|required|max_length[500]');
        $this->form_validation->set_rules('ProjTotalSupp', 'Total Supply', 'trim|required|numeric_wcommas');
        $this->form_validation->set_rules('EventDesc', 'Event Details', 'trim|max_length[500]');
        $this->form_validation->set_rules('ProjPreMined', 'Pre-mined', 'trim|numeric');
        $this->form_validation->set_rules('ProjPreMinedNote', 'Pre-mined Note', 'trim|max_length[500]');
        $this->form_validation->set_rules('ProjType', 'Project Type', 'trim|required|valid_projectType');

        $formData = $this->input->post(NULL, TRUE);

        // platform required
        if ($formData['ProjType'] == 2) {
            $this->form_validation->set_rules('ProjPlatform', 'Platform', 'trim|required');
        }

        // algo required
        if ($formData['ProjType'] == 1) {
            $this->form_validation->set_rules('ProjAlgo', 'Algorithm', 'trim|required');
        }

        // start - end required
        if (!isset($formData['dates_defined'])) {
            $this->form_validation->set_rules('EventStartDate', 'Start', 'trim|required');
            $this->form_validation->set_rules('EventEndDate', 'End', 'trim|required|valid_ico_enddate[EventStartDate]|valid_time_range[EventStartDate]');
        }

        if (isset($formData['EventStartDate']) && $formData['EventStartDate'] != '') {
            $this->form_validation->set_rules('EventStartDate', 'Start', 'trim');
            $this->form_validation->set_rules('EventEndDate', 'End', 'trim|required|valid_ico_enddate[EventStartDate]|valid_time_range[EventStartDate]');
        }

        if ($this->form_validation->run() === TRUE) {
            // upload logo
            $errorsLogo = array();
            $uploadLogo16 = $this->uploadLogo('ProjImage', $project['ProjID'], $errorsLogo);
            $uploadLogo48 = $this->uploadLogo('ProjImageLarge', $project['ProjID'], $errorsLogo);

            if ($uploadLogo16 === FALSE || $uploadLogo48 === FALSE) {
                $this->jsonData['errors'] = $errorsLogo[0];
                $this->jsonData['message'] = 'Please make sure you upload valid logo.';
                $this->renderJson($this->jsonData);
                exit();
            }

            // update project
            $platform = $formData['ProjPlatform'];
            if (is_array($formData['ProjPlatform'])) {
                $platform = implode(',', $formData['ProjPlatform']);
            }

//            $algo = '';
//            if ($project['ProjType'] == 1) {
//                if (is_array($formData['hdalgoID'])) {
//                    $algo = implode(',', $formData['hdalgoID']);
//                } else {
//                    $algo = $formData['hdalgoID'];
//                }
//            }

            $arrUpdateProject = array(
                'ProjType' => cleanInput($formData['ProjType']),
                'ProjPlatform' => str_replace("'", '', $platform),
                'ProjSymbol' => cleanInput($formData['ProjSymbol']),
                'ProjDesc' => cleanInput($formData['ProjDesc']),
                'ProjLocation' => cleanInput($formData['ProjLocation']),
                'ProjTotalSupp' => str_replace(",","",cleanInput($formData['ProjTotalSupp'])),
                'ProjPreMined' => cleanInput($formData['ProjPreMined']),
                'ProjPreMinedNote' => cleanInput($formData['ProjPreMinedNote']),
                'ProjAlgo' => cleanInput($formData['ProjAlgo']),
                'ProjYouTubeVid' => cleanInput($formData['ProjYouTubeVid']),
            );

            if ($uploadLogo16) {
                $ProjImage = $this->processImage(self::PATH_IMAGE_LOGO . $uploadLogo16, 16, 16);
                if ($ProjImage) {
                    $arrUpdateProject['ProjImage'] = $ProjImage;
                }
            }

            if ($uploadLogo48) {
                $ProjImageLarge = $this->processImage(self::PATH_IMAGE_LOGO . $uploadLogo48, 48, 48);
                if ($ProjImageLarge) {
                    $arrUpdateProject['ProjImageLarge'] = $ProjImageLarge;
                }
            }

            $EventStartDate = '0000-00-00 00:00:00';
            $EventEndDate = '0000-00-00 00:00:00';
            if ($formData['EventStartDate'] != '') {
                $EventStartDate = $formData['EventStartDate'];
            }
            if ($formData['EventEndDate'] != '') {
                $EventEndDate = $formData['EventEndDate'];
            }

            // update project
            $arrUpdateProjectLog = $arrUpdateProject;
            $arrUpdateProjectLog['EventStartDate'] = cleanInput($EventStartDate);
            $arrUpdateProjectLog['EventEndDate'] = cleanInput($EventEndDate);
            $this->ProjectLogModel->updateProjectLog($ssUser['id'], $arrUpdateProjectLog, $project['ProjID']);
            $this->ProjectModel->update($arrUpdateProject, array('ProjID' => $formData['ProjID']));

            // update event
            $arrUpdateEvent = array(
                'EventDatesNotDefined' => isset($formData['dates_defined']) ? '1' : '0',
                'EventStartDate' => cleanInput($EventStartDate),
                'EventEndDate' => cleanInput($EventEndDate),
            );
            if (isset($formData['EventDesc']) && !empty($formData['EventDesc'])) {
                $arrUpdateEvent['EventDesc'] = cleanInput($formData['EventDesc']);
            }
            if (isset($formData['EventSoftCap']) && !empty($formData['EventSoftCap'])) {
                $arrUpdateEvent['EventSoftCap'] = cleanInput($formData['EventSoftCap']);
            }
            if (isset($formData['EventHardCap']) && !empty($formData['EventHardCap'])) {
                $arrUpdateEvent['EventHardCap'] = cleanInput($formData['EventHardCap']);
            }

            $arrUpdateEvent['EventStartDateType'] = isset($formData['dates_defined']) ? 3 : 1;

            $this->EventModel->update($arrUpdateEvent, array('EventProjID' => $formData['ProjID']));

            $this->jsonData['message'] = 'Project saved!';
            $this->jsonData['status'] = 1;
            $this->jsonData['data'] = array_merge($arrUpdateProject, $arrUpdateEvent);

            // send edit email
            $this->load->helper('email');
            $mailBody = $this->load->view('emails/listing_edit_admin', $project, true);
            $sendFrom = array('email' => $this->config->config['send_from'], 'name' => $this->config->config['send_from_name']);
            $sendTo = array('email' => $this->config->config['admin_email'], 'name' => '');
            $subject = sprintf("ICO %s Edited", $project['EventName']);
            //send_email($sendFrom, $sendTo, $subject, $mailBody);

        } else {
            $fields = array_keys($formData);
            $this->renderListingError($fields);
            $this->jsonData['status'] = 0;
            $this->jsonData['message'] = 'Please make sure you fill all the mandatory fields.';
        }

        $this->renderJson($this->jsonData);
    }

    public function listingSaveLink()
    {
        if (!$this->verifyListingRequest()) {
            $this->jsonData['message'] = "Invalid request";
            $this->renderJson($this->jsonData);
            exit();
        }

        $this->load->model('Link_type_model', 'LinkTypeModel');
        $this->load->model('Link_model', 'LinkModel');
        $linkTypes = $this->LinkTypeModel->getData();

        foreach ($linkTypes as $link) {
            if ($link['LinkTypeID'] == 1) {
                continue;
            }
            $fieldName = 'Links'.$link['LinkTypeID'];
            $linkValid = mappingLinkType($link['LinkTypeID']);

            if ($linkValid !== TRUE) {
                $this->form_validation->set_rules($fieldName, $link['LinkTypeName'], 'trim|valid_url|valid_mapping_link['.$linkValid.']');
            } else {
                $this->form_validation->set_rules($fieldName, $link['LinkTypeName'], 'trim|valid_url');
            }
        }
        $this->form_validation->set_rules('Links1', 'Website', 'trim|required|valid_url');

        $formData = $this->input->post(NULL, TRUE);
        if ($this->form_validation->run() === TRUE) {
            $this->jsonData['status'] = 1;
            $this->jsonData['message'] = 'Link Saved!';
            foreach ($linkTypes as $link) {
                $this->LinkModel->updateLink($link['LinkTypeID'], $formData, $link);
            }
        } else {
            $this->jsonData['status'] = 0;
            $fields = array_keys($formData);
            $this->renderListingError($fields);
            $this->jsonData['message'] = 'Please make sure you fill all the mandatory fields.';
        }

        $this->renderJson($this->jsonData);
    }

    function listingSaveDistribution()
    {
        if (!$this->verifyListingRequest()) {
            $this->jsonData['message'] = "Invalid request";
            $this->renderJson($this->jsonData);
            exit();
        }

        $totalSortOrder = $this->input->post('totalSortOrder', TRUE);
        $this->jsonData['status'] = 1;
        $this->jsonData['message'] = 'Distribution saved!';
        $this->load->model('Distribution_model', 'DistributionModel');

        if ($totalSortOrder > 0) {

            for ($i = 1; $i <= $totalSortOrder; $i ++) {
                $this->form_validation->set_rules($i.'_DistroDesc', 'Description '.$i, 'trim|required|max_length[255]');
                $this->form_validation->set_rules($i.'_DistroAmount', 'Amount '.$i, 'trim|required|numeric');
                $this->form_validation->set_rules($i.'_DistroPercent', 'Percent '.$i, 'trim|required|numeric');
                $this->form_validation->set_rules($i.'_DistroNote', 'Note '.$i, 'trim|required|max_length[255]');
            }

            $formData = $this->input->post(NULL, TRUE);
            if ($this->form_validation->run() === TRUE) {
                $this->DistributionModel->delete(array('DistroProjID' => $this->input->post('ProjID', TRUE)));
                $arrInsert = [];
                for ($i = 1; $i <= $totalSortOrder; $i ++) {
                    if (!isset($formData[$i.'_DistroDesc'])) {
                        continue;
                    }

                    $arrInsert[] = array(
                        'DistroProjID' => $formData['ProjID'],
                        'DistroDesc' => cleanInput($formData[$i.'_DistroDesc']),
                        'DistroAmount' => cleanInput($formData[$i.'_DistroAmount']),
                        'DistroPercent' => cleanInput($formData[$i.'_DistroPercent']),
                        'DistroNote' => cleanInput($formData[$i.'_DistroNote']),
                        'DistroSortOrder' => cleanInput($formData[$i.'_DistroSortOrder'])
                    );
                }

                $this->db->insert_batch('tbl_projdistribution', $arrInsert);
                $this->jsonData['message'] = 'Distribution saved!';
                $this->jsonData['status'] = 1;
            } else {
                $this->jsonData['status'] = 0;
                $fields = array_keys($formData);
                $this->renderListingError($fields);
                $this->jsonData['message'] = 'Please make sure you fill valid all the fields.';
            }
        } else {
            $this->DistributionModel->delete(array('DistroProjID' => $this->input->post('ProjID', TRUE)));
        }

        $this->renderJson($this->jsonData);
    }

    public function listingSaveTeam()
    {
        if (!$this->verifyListingRequest()) {
            $this->jsonData['message'] = "Invalid request";
            $this->renderJson($this->jsonData);
            exit();
        }

        $formData = $this->input->post(NULL, TRUE);
        $teamMember = self::LIMIT_TEAM_MEMBER;
        $errorsUpload = array();
        $uploadFile = array();
        $isMustvalid = false;
        for ($i = 1; $i <= $teamMember; $i ++) {
            if (!isset($formData['validation'.$i]) || empty($formData['validation'.$i])) {
                continue;
            }

            $isMustvalid = true;
            $this->form_validation->set_rules('inputFullname'.$i, 'Full Name '.$i, 'trim|required|max_length[255]');
            $this->form_validation->set_rules('inputPosition'.$i, 'Position '.$i, 'trim|required|max_length[255]');
            $this->form_validation->set_rules('inputShortBio'.$i, 'Short Bio '.$i, 'trim|required|max_length[500]');

            $uploadPath = FCPATH . self::PATH_IMAGE_TEAM;
            $uploadPathPassport = FCPATH . self::PATH_IMAGE_PASSPORT;
            $inputPicture = $this->uploadFileTeam('inputPicture'.$i, $uploadPath, $errorsUpload);
            $inputPassport = $this->uploadFileTeam('inputPassport'.$i, $uploadPathPassport, $errorsUpload, TRUE);

            if ($inputPicture) {
                $uploadFile[$i]['inputPicture'] = $inputPicture;
            }
            if ($inputPassport) {
                $uploadFile[$i]['inputPassport'] = $inputPassport;
            }
        }

        $this->jsonData['message'] = 'Team saved!';
        $this->jsonData['status'] = 1;

        if ($isMustvalid) {
            if ($this->form_validation->run() === TRUE && empty($errorsUpload)) {

                $this->load->model('People_project_model', 'PeopleProjectModel');
                $this->load->model('People_model', 'PeopleModel');
                $this->load->model('Link_model', 'LinkModel');
                $this->PeopleProjectModel->delete(array('PeopleProjProjID' => $formData['ProjID']));

                $this->db->trans_strict(TRUE);
                $this->db->trans_begin();

                for ($i = 1; $i <= $teamMember; $i ++) {
                    $peopleID = 0;
                    if (isset($formData['peopleID'.$i]) && !empty($formData['peopleID'.$i])) {
                        // update tbl_people
                        $peopleID = $formData['peopleID'.$i];
                        $arrUpdate = array(
                            'PeopleName' => cleanInput($formData['inputFullname'.$i]),
                            'PeopleDesc' => cleanInput($formData['inputShortBio'.$i])
                        );
                        if (isset($uploadFile[$i])) {
                            if (!empty($uploadFile[$i]['inputPicture'])) {
                                $arrUpdate['PeoplePicture'] = cleanInput($uploadFile[$i]['inputPicture']);
                            }
                            if (!empty($uploadFile[$i]['inputPassport'])) {
                                $arrUpdate['PeoplePassport'] = cleanInput($uploadFile[$i]['inputPassport']);
                            }
                        }
                        $this->PeopleModel->update($arrUpdate, array('PeopleID' => $peopleID));
                    } else {
                        // insert tbl_people
                        if (!empty($formData['inputFullname'.$i])) {
                            $arrInsert = array(
                                'PeopleName' => cleanInput($formData['inputFullname'.$i]),
                                'PeopleDesc' => cleanInput($formData['inputShortBio'.$i])
                            );
                            if (isset($uploadFile[$i])) {
                                if (!empty($uploadFile[$i]['inputPicture'])) {
                                    $arrInsert['PeoplePicture'] = cleanInput($uploadFile[$i]['inputPicture']);
                                }
                                if (!empty($uploadFile[$i]['inputPassport'])) {
                                    $arrInsert['PeoplePassport'] = cleanInput($uploadFile[$i]['inputPassport']);
                                }
                            }
                            $peopleID = $this->PeopleModel->insert($arrInsert);
                        }
                    }

                    // insert tbl_links
                    if (!empty($formData['inputLinkedin'.$i])) {
                        $link = $this->LinkModel->getLinkByListing($peopleID);
                        if (empty($link)) {
                            $arrInsert = array(
                                'LinkType' => 10,
                                'LinkParentType' => 4,
                                'LinkParentID' => $peopleID,
                                'Link' => cleanInput($formData['inputLinkedin'.$i]),
                            );
                            $this->LinkModel->insert($arrInsert);
                        } else {
                            $this->LinkModel->update(array('Link' => $formData['inputLinkedin'.$i]), array('LinkID' => $link['LinkID']));
                        }
                    }

                    // insert tbl_people_projects
                    if (!empty($peopleID)) {
                        $arrInsertPP = array(
                            'PeopleProjPeopleID' => $peopleID,
                            'PeopleProjProjID' => $formData['ProjID'],
                            'PeopleProjPosition' => cleanInput($formData['inputPosition'.$i]),
                            'PeopleProjSortOrder' => $i,
                        );
                        $this->PeopleProjectModel->insert($arrInsertPP);
                    }
                }

                // reorder tbl_people_projects
                $this->PeopleProjectModel->reorderPeopleList($formData['ProjID']);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                $this->jsonData['message'] = 'Team saved!';
                $this->jsonData['status'] = 1;
            } else {
                $fields = array_keys($formData);
                $this->renderListingError($fields);
                $this->jsonData['status'] = 0;
                $this->jsonData['message'] = 'Please make sure you fill valid all the fields.';
            }
        }


        sleep(2); // 2 seconds
        $this->setICORankProject($formData['ProjID']);
        $this->renderJson($this->jsonData);
    }

    public function listingDeleteMember()
    {
        if (!$this->verifyListingRequest()) {
            $this->jsonData['message'] = "Invalid request";
            $this->renderJson($this->jsonData);
            exit();
        }

        $formData = $this->input->post(NULL, TRUE);
        if (isset($formData['peopleID']) && !empty($formData['peopleID'])) {
            $this->load->model('People_model', 'PeopleModel');
            $this->load->model('People_project_model', 'PeopleProjectModel');

            $this->PeopleModel->delete(array('PeopleID' => $formData['peopleID']));
            $this->PeopleProjectModel->delete(array('PeopleProjPeopleID' => $formData['peopleID']));
            $this->PeopleProjectModel->reorderPeopleList($formData['ProjID']);
        }

        $this->jsonData['message'] = 'Team Member deleted!';
        $this->jsonData['status'] = 1;
        $this->renderJson($this->jsonData);
    }

    public function scrapeWebsite()
    {
        $csProject = new CSProject();
        $csProject->website = $this->input->post('website', TRUE);
        $csProject->scrapeWebsite();
        $this->jsonData['status'] = 1;
        $this->jsonData['data'] = array_values($csProject->links);
        $this->renderJson($this->jsonData);
    }

    public function scrapeLogo()
    {
        $csProject = new CSProject();
        $this->load->model('Project_model', 'ProjectModel');
        $formData = $this->input->post(NULL, TRUE);
        $isLarge = false;
        $updateField = 'ProjImage';
        if (isset($formData['eleID']) && $formData['eleID'] == 'ProjImageLarge') {
            $isLarge = true;
            $updateField = 'ProjImageLarge';
        }

        $base64Image = $csProject->scrapeLogo($formData['website'], $isLarge);
        if ($base64Image) {
            $this->ProjectModel->update(array($updateField => $base64Image), array('ProjID' => $formData['projectID']));
            $this->jsonData['status'] = 1;
            $this->jsonData['data'] = $base64Image;
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['message'] = 'Not found logo';
        }

        $this->renderJson($this->jsonData);
    }

    public function clickProjectLink()
    {
        $this->form_validation->set_rules('projectId', 'projectId', 'required');

        if ($this->form_validation->run() === TRUE) {
            $formData = $this->input->post(NULL, TRUE);
            $this->load->model('Project_model', 'ProjectModel');
            $this->load->model('Project_model', 'ProjectModel');
            $projectDetail = $this->ProjectModel->getProjectDetail(false, $formData['projectId']);

            if (!empty($projectDetail)) {
                $this->ProjectModel->updateProjectClick($formData['projectId']);
                $this->insertUserActivity($this->userActID['click_link'], $projectDetail);
                $this->jsonData['status'] = 1;
            }
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = validation_errors();
        }

        $this->renderJson($this->jsonData);
    }

    public function clickPartner()
    {
        $this->form_validation->set_rules('id', 'id', 'required');

        if ($this->form_validation->run() === TRUE) {
            $formData = $this->input->post(NULL, TRUE);
            $this->load->model('Partner_model', 'PartnerModel');
            $this->PartnerModel->updatePartnerClick($formData['id']);
            $this->jsonData['status'] = 1;
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = validation_errors();
        }

        $this->renderJson($this->jsonData);
    }

    public function calcIcoRank()
    {
        $this->form_validation->set_rules('projID', 'projID', 'required');

        if ($this->form_validation->run() === TRUE) {
            $formData = $this->input->post(NULL, TRUE);
            $this->jsonData['status'] = 1;

            $this->load->model('Cron_model', 'CronModel');
            $this->load->model('Project_model', 'ProjectModel');
            $project = $this->CronModel->getCronIcoRankProject($formData['projID']);

            if (empty($project)) {
                $this->jsonData['data'] = 0;
                $this->renderJson($this->jsonData);
                return FALSE;
            }

            $ICOrank=0; //initialise variable
            //get all links of this project
            $links_st = $this->CronModel->getProjectLinks($project['ProjID']);
            foreach ($links_st as $links) {
                //if it's a website link give 7 points (type 1)
                if ($links['LinkType']==1)$ICOrank=$ICOrank+7;

                //if it's a PDF white paper give 10 points (type 14)
                if ($links['LinkType']==14)$ICOrank=$ICOrank+10;

                //if it's Twitter give 2 points (type 5)
                if ($links['LinkType']==5)$ICOrank=$ICOrank+2;

                //if it's reddit give 2 points (type 6)
                if ($links['LinkType']==6)$ICOrank=$ICOrank+2;

                //if it's slack give 5 points (type 9)
                if ($links['LinkType']==9)$ICOrank=$ICOrank+6;

                //if it's bitcointalk give 5 points (type 4)
                if ($links['LinkType']==4)$ICOrank=$ICOrank+5;
            }


            //get all team members of this project
            $team_st = $this->CronModel->getProjectPeople($project['ProjID']);
            if (!empty($team_st)) {
                //if it's 1 team member give 4 points if 2 or more give 10 points
                if(count($team_st)==1)$ICOrank=$ICOrank+5;
                if(count($team_st)>=1)$ICOrank=$ICOrank+9;

                //if it's 1 linkedin 2 points if 2 or more give 5 points
                $links2 = $this->CronModel->getProjectLinksSt($project['ProjID']);
                if (!empty($links2)){
                    if(count($links2)==1)$ICOrank=$ICOrank+3;
                    if(count($links2)>=1)$ICOrank=$ICOrank+5;
                }
            }

            //get editorial grade of this project and assign to ICOrank
            $ICOrank=$ICOrank+$project['ProjEditorialGrade'];

            //get paid level of this project
            if ($project['ProjSponsored']==1){
                //if project is SILVER, give 6 points
                //**** SILVER DOES NOT EXIST IN COINSCHEDULE YET

                //if project is GOLD, give 14 points
                //AT the moment, non platinum means is gold
                if ($project['ProjPlatinum']==0)$ICOrank=$ICOrank+11;

                //if project is PLATINUM give 22 points
                if ($project['ProjPlatinum']==1)$ICOrank=$ICOrank+17;
            }

            //record in DB
            $this->jsonData['data'] = $ICOrank;
            $this->ProjectModel->update(['ProjICORank' => $ICOrank], ['ProjID' => $project['ProjID']]);

        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = validation_errors();
        }

        $this->renderJson($this->jsonData);
    }

    public function voteProject()
    {
        $this->form_validation->set_rules('id', 'id', 'required');
        $this->form_validation->set_rules('type', 'type', 'required');

        if ($this->form_validation->run() === TRUE) {
            $formData = $this->input->post(NULL, TRUE);
            $this->load->model('Vote_model', 'VoteModel');
            $this->load->model('Project_model', 'ProjectModel');
            $projectDetail = $this->ProjectModel->getProjectDetail(false, $formData['id']);

            if (!empty($projectDetail)) {
                $user = $this->session->userdata(self::SS_USER);
                $updateVote = $this->VoteModel->updateVotes($user['id'], $formData);
                if ($updateVote === true) {
                    $actID = 0;
                    if ($formData['type'] == 1) {
                        $actID = $this->userActID['vote_up'];
                    } else if ($formData['type'] == 2) {
                        $actID = $this->userActID['vote_down'];
                    }

                    if ($actID) {
                        $this->insertUserActivity($actID, $projectDetail);
                    }

                    $this->jsonData['status'] = 1;
                }
            }
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = validation_errors();
        }

        $this->renderJson($this->jsonData);
    }

    public function favourProject()
    {
        $this->form_validation->set_rules('id', 'id', 'required');
        $this->form_validation->set_rules('type', 'type', 'required');

        if ($this->form_validation->run() === TRUE) {
            $formData = $this->input->post(NULL, TRUE);
            $this->load->model('Favourites_model', 'FavouritesModel');
            $this->load->model('Project_model', 'ProjectModel');
            $projectDetail = $this->ProjectModel->getProjectDetail(false, $formData['id']);

            if (!empty($projectDetail)) {
                $user = $this->session->userdata(self::SS_USER);
                $updateFavour = $this->FavouritesModel->updateFavour($user['id'], $formData);
                if ($updateFavour === true) {
                    if ($formData['type'] == 0) {
                        $actID = $this->userActID['add_fav'];
                    } else {
                        $actID = $this->userActID['remove_fav'];
                    }
                    $this->insertUserActivity($actID, $projectDetail);
                    $this->jsonData['status'] = 1;
                }
            }
        } else {
            $this->jsonData['status'] = 0;
            $this->jsonData['error'] = validation_errors();
        }

        $this->renderJson($this->jsonData);
    }

    public function statsMonthly()
    {
        $authUsername = 'bloomberg';
        $authPassword = '34rcJCKRsoXUEPi7';
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $cookie = $this->input->post('cookie');

        $isAuth = FALSE;
        if ($cookie == 1) {
            $cred = $this->input->post('value');
            $authCred = $authUsername.$authPassword;
            if ($cred == $authCred) {
                $isAuth = TRUE;
            }
        } else {
            if ($username == $authUsername && $password == $authPassword) {
                $isAuth = TRUE;
            }
        }

        if ($isAuth) {
            $this->load->model('Icohistory_model', 'IcoHistoryModel');
            $statsData = $this->IcoHistoryModel->getDataStatsMonthly();
            $resultData = [];
            $resultData['html'] = '';
            $resultData['total'] = 0;
            $resultData['num'] = 0;

            if (!empty($statsData)) {
                $resultData['html'] .= '<tr style="font-weight: bold;">
                                            <td>Month</td>
                                            <td align="right">Total USD</td>
                                            <td>No. of ICOs</td>
                                        </tr>';

                foreach ($statsData as $item) {
                    if ($item['Year'] == 0) {
                        continue;
                    }

                    $resultData['total'] += $item['Total'];
                    $resultData['num'] += $item['Num'];

                    $monthName = date('F', mktime(0, 0, 0, $item['Month'], 10));
                    $resultData['html'] .=
                        '<tr>
                                            <td>'.$monthName.' '.$item['Year'].'</td>
                                            <td align="right">$'.number_format($item['Total'],0).'</td>
                                            <td align="right">'.$item['Num'].'</td>
                                         </tr>';


                }

                $resultData['total'] = number_format($resultData['total'], 0);
                $resultData['html'] .= '<tr style="font-weight: bold;">
                                            <td>Total</td>
                                            <td align="right">$'.$resultData['total'].'</td>
                                            <td align="right">'.$resultData['num'].'</td>
                                        </tr>';
            }

            $this->jsonData['data'] = $resultData;
            $this->jsonData['status'] = 1;
        }

        $this->renderJson($this->jsonData);
    }

    private function renderListingError($fields)
    {
        $errors = [];
        $this->jsonData['error'] = [];
        foreach ($fields as $field) {
            if (form_error($field)) {
                $errors[$field] = strip_tags(form_error($field));
            }
        }

        $this->jsonData['error'] = $errors;
    }

    private function renderJson($jsonData, $xss = false)
    {
        if ($xss === true) {
            array_walk_recursive($this->jsonData, function(&$value) {
                $value = $this->xssClean($value);
            });
        }

        $jsonResponse = json_encode($jsonData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
        echo $jsonResponse;
    }

    private function xssClean($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $oldData = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($oldData !== $data);

        $data = strip_tags($data);

        // we are done...
        return $data;
    }

    private function verifyListingRequest()
    {
        $ssUser = $this->session->userdata(self::SS_USER);
        $this->load->model('Project_model', 'ProjectModel');
        $project = $this->ProjectModel->verifyPermisison($this->input->post('ProjID', TRUE), $ssUser['id']);

        if (!empty($project)) {
            return TRUE;
        }
        return FALSE;
    }

    private function uploadLogo($inputFile, $id, &$error)
    {
        if (isset($_FILES[$inputFile]) && $_FILES[$inputFile]['error'] == 0) {
            $config['upload_path']          = FCPATH . self::PATH_IMAGE_LOGO;
            $config['allowed_types']        = 'jpg|jpeg|png';
            $config['max_size']             = 2048; //KB
            $config['max_width']            = 1920;
            $config['max_height']           = 1080;
            $config['file_name']            = md5($id . '_' . time() . '_' . rand(100, 999));
            $config['file_ext_tolower']     = true;
            $config['remove_spaces']        = true;

            $this->load->library('upload');
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($inputFile)) {
                $error[] = $this->upload->display_errors();
                return false;
            } else {
                $data = $this->upload->data();
                return $data['file_name'];
            }
        }

        return '';
    }

    private function uploadFileTeam($inputFile, $path, &$error, $isPassport = FALSE)
    {
        if (isset($_FILES[$inputFile]) && $_FILES[$inputFile]['error'] == 0) {
            if ($isPassport) {
                $config['file_name'] = 'passport_' . md5(time() . '_' . rand(10000, 99999));
            } else {
                $config['file_name'] = 'team_' . md5(time() . '_' . rand(10000, 99999));
            }

            $config['upload_path']          = $path;
            $config['allowed_types']        = 'jpg|jpeg|png';
            $config['max_size']             = 2048; //KB
            $config['max_width']            = 1920;
            $config['max_height']           = 1080;
            $config['file_ext_tolower']     = true;
            $config['remove_spaces']        = true;

            $this->load->library('upload');
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($inputFile)) {
                $error[$inputFile] = $this->upload->display_errors();
                return false;
            } else {
                $data = $this->upload->data();
                return $data['file_name'];
            }
        }

        return '';
    }

}
