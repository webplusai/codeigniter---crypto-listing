<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/controllers/Submit.php';

class Listing extends Submit
{
    public function __construct()
    {
        parent::__construct();
        $this->layoutData['isDefer'] = true;
    }

    public function listings()
    {
        $this->load->model('Data_model', 'DataModel');
        $this->load->model('Event_model', 'EventModel');
        $ssUser = $this->session->userdata(self::SS_USER);
        $listingData = $this->DataModel->getListings($ssUser['id']);
        $icoRank = $this->getSetting(1);

        $this->layoutData['listingTable'] = '';
        if (!empty($listingData)) {
            foreach ($listingData as $project) {

                // start - end
                $start = "";
                $end = "";
                $events = array();
                $editId = $project['ProjID'];
                if (isset($project['ProjID']) && !empty($project['ProjID'])) {

                    $events = $this->EventModel->getEventByProjectID($project['ProjID']);

                    if ($events['EventStartDate']!='0000-00-00 00:00:00') {
                        $startdate = date_create($events['EventStartDate']);
                        $start = date_format($startdate,"M jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':'');
                    }

                    if ($events['EventEndDate']!='0000-00-00 00:00:00') {
                        $enddate = date_create($events['EventEndDate']);
                        $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':'');
                    }
                }

                // status
                $statusHtml = '';
                $textStatus = '';
                if (!empty($events) && $project['ProjDeleted'] == 0 && $project['ProjICORank'] > $icoRank
                    && $events['EventStartDateType'] != 3 && $events['EventDisabled'] == 0 && $events['EventType'] == 1 && $project['SubStatus'] == 2) {

                    if ($events['EventStartDate'] < date('Y-m-d H:i:s') && $events['EventEndDate'] > date('Y-m-d H:i:s')) {
                        $textStatus = 'Live';
                    } else if ($events['EventStartDate'] > date('Y-m-d H:i:s')) {
                        $textStatus = 'Upcoming';
                    } else if ($events['EventEndDate'] < date('Y-m-d H:i:s')) {
                        $textStatus = 'Ended';
                    }


                    $url = slugEvent($events['EventID'], $events['EventName']);
                    $statusHtml = '<a href="'.$url.'">'.$textStatus.'</a>';

                } else if ($editId) {
                    if (empty($events)) {
                        $statusHtml = '<a href="submission_entry?h='.$project['SubHashCode'].'">Offline</a>';
                    } else {
                        $url = slugEvent($events['EventID'], $events['EventName']);
                        $statusHtml = '<a href="'.$url.'">Offline</a>';
                    }
                } else if (empty($project['PayID'])) {
                    $statusHtml = '<a href="submission_entry?h='.$project['SubHashCode'].'">Incomplete</a>';
                } else if ($project['SubStatus'] == 1) {
                    $statusHtml = '<a class="plink" href="payment?h='.$project['SubHashCode'].'">Payment Required</a>';
                }

                $name = $project['ProjName'];
                if (empty($name)) {
                    $name = $project['SubCoinName'];
                }

                if ($project['ProjImage']) {
                    $icon = '<img src="data:image/png;base64,'.$project['ProjImage'].'">';
                } else {
                    $icon = '<i class="fa fa-question" aria-hidden="true"></i>';
                }

                $subId = $project['SubID'];
                $subHash = $project['SubHashCode'];
                $eventName = $project['SubEventName'];
                if ($editId) {
                    $this->layoutData['listingTable'] .= '<tr onclick="editProject(\''.$editId.'\')">';
                } else if (empty($project['PayID'])) {
                    $this->layoutData['listingTable'] .= '<tr onclick="editSubmission(\'' . $subHash . '\')">';
                } else if ($project['PayID']) {
                    $this->layoutData['listingTable'] .= '<tr onclick="paymentRedirect(\'' . $subHash . '\')">';
                } else {
                    $this->layoutData['listingTable'] .= '<tr>';
                }

                $this->layoutData['listingTable'] .= '<td align="center">'.$icon.'
                          </td><td>'.$name.'</td>
                          <td>'.$eventName.'</td>
                          <td>'.$start.'</td>
                          <td>'.$end.'</td>
                          <td>'.$project['ProjICORank'].'</td>
                          <td>'.$statusHtml.'</td>
                          </tr>';
            }
        }

        $this->layoutData['head']['title'] = 'Listing Submission';
        $this->renderView('listing.twig');
    }

    public function edit()
    {
        if (empty($this->input->get('id', TRUE))) {
            redirect('');
            exit();
        } else {
            $this->load->model('Project_model', 'ProjectModel');
            $ssUser = $this->session->userdata(self::SS_USER);
            $project = $this->ProjectModel->getProjectListing($this->input->get('id', TRUE), $ssUser['id']);
            if (empty($project)) {
                redirect('');
                exit();
            }
        }

        $this->renderScriptListing();
        $this->renderProjectTab($project);
        $this->renderLinkTab($project['ProjID']);
        $this->renderDistributionTab($project);
        $this->renderTeamTab($project);

        $this->layoutData['head']['title'] = 'Edit Listing';
        $this->renderView('listing_edit.twig');
    }

    private function renderProjectTab($project)
    {
        $algoDisabled = 'disabled';
        if ($project['ProjType'] == 1) {
            $algoDisabled = '';
        }

        $this->layoutData['htmlAlgorithmOption'] = '';
        $algoList = getAlgorithm();
        foreach ($algoList as $k => $v) {
            $this->layoutData['htmlAlgorithmOption'] .= '<option value="'.$k.'">'.$v.'</option>';
        }

        $this->layoutData['dates_defined'] = formSetValueCheckbox('dates_defined', $project['EventDatesNotDefined']);
        $project['EventStartDate'] = displayDate($project['EventStartDate']);
        $project['EventEndDate'] = displayDate($project['EventEndDate']);
        $this->layoutData['project'] = $project;
        $this->layoutData['platform'] = $project['ProjPlatform'];
        $this->layoutData['algo'] = $project['ProjAlgo'];
        $this->layoutData['ProjYouTubeVid'] = $project['ProjYouTubeVid'];
        $this->layoutData['algoDisabled'] = $algoDisabled;
        $this->renderPlatform();
        $this->renderProjectType($project);
    }

    private function renderLinkTab($projectID)
    {
        $this->load->model('Link_model', 'LinkModel');
        $this->layoutData['htmlLink'] = '';
        $projectLink = $this->LinkModel->getProjectLinks($projectID);
        if (empty($projectLink)) {
            return false;
        }

        $lastLinkID = 0;
        foreach ($projectLink as $link) {
            $mandatory = '';
            $helptext = '';
            $style = '';
            $lastLinkID ++;

            switch($link['LinkTypeID']) {
                case 1:
                    $mandatory = '<span style="color:#F00">*</span>';
                    $style = 'style="width: 87%; margin-right: 20px;"';
                    $helptext = '<a href="javascript:void();" class="btn btn-primary btn-primary-old" id="scrape_link">Scrape Links</a>
                        <span class="help-block">e.g.: http://www.google.com (please include http:// or https://)</span>';
                    break;
                case 5:
                    $helptext = '<span class="help-block">e.g.: https://twitter.com/your-name</span>';
                    break;
                case 6:
                    $helptext = '<span class="help-block">e.g.: https://www.reddit.com/r/your-name</span>';
                    break;
                case 9:
                    $helptext = '<span class="help-block">e.g.: https://your-team.slack.com</span>';
                    break;
                case 4:
                    $helptext = '<span class="help-block">e.g.: https://bitcointalk.org/your-topic</span>';
                    break;
                default:
                    break;
            }

            $this->layoutData['htmlLink'] .= '<tr>
            <td>
                <div style="vertical-align: middle;display:inline-block;width: 25px;"><img src="data:image/png;base64,' . $link['LinkTypeImage'] . '"></div>' . $link['LinkTypeName'] . '
                '.$mandatory.'
            </td>
            <td>
                <input '.$style.' class="url_link" type="text" id="Links'.$link['LinkTypeID'].'" name="Links'.$link['LinkTypeID'].'" value="'.$link['Link'].'" />
                '.$helptext.'
            </td>
            </tr>';
        }

        $this->layoutData['lastLinkID'] = $lastLinkID;
    }

    private function renderDistributionTab($project)
    {
        $this->layoutData['htmlDistribution'] = '';
        $this->layoutData['highestTotalRow'] = 0;
        $this->load->model('Distribution_model', 'DistributionModel');
        $projectDistribution = $this->DistributionModel->getProjectDistribution($project['ProjID']);
        if (empty($projectDistribution)) {
            return false;
        }

        $row = 1;
        foreach ($projectDistribution as $distro) {
            $this->layoutData['htmlDistribution'] .= '<tr>
                <td style="cursor: all-scroll;" align="center">' . $distro['DistroSortOrder'] . '</td>
                <td>
                    <input class="sortval" type="hidden" id="tbl_distro_sort' . $distro['DistroSortOrder'] . '" value="' . $distro['DistroSortOrder'] . '" name="' . $distro['DistroSortOrder'] . '_DistroSortOrder">
                    <input type="text" value="' . htmlentities($distro['DistroDesc']) . '" name="' . $distro['DistroSortOrder'] . '_DistroDesc" id="' . $distro['DistroSortOrder'] . '_DistroDesc">
                </td>
                <td><input type="text" value="' . $distro['DistroAmount'] . '" name="' . $distro['DistroSortOrder'] . '_DistroAmount" id="' . $distro['DistroSortOrder'] . '_DistroAmount"></td>
                <td><input type="text" value="' . $distro['DistroPercent'] . '" name="' . $distro['DistroSortOrder'] . '_DistroPercent" id="' . $distro['DistroSortOrder'] . '_DistroPercent"></td>
                <td><input type="text" value="' . htmlentities($distro['DistroNote']) . '" name="' . $distro['DistroSortOrder'] . '_DistroNote" id="' . $distro['DistroSortOrder'] . '_DistroNote"></td>
                <td align="center">' . $this->layoutData['icon_del'] . '</td>
            </tr>';
            $row++;
        }

        $this->layoutData['highestTotalRow'] = count($projectDistribution);
    }

    private function renderTeamTab($project)
    {
        $this->load->model('Submission_model', 'SubmissionModel');
        $this->load->model('Submission_team_model', 'SubmissionTeamModel');
        $sub = $this->SubmissionModel->getSubmissionByProject($project['ProjID']);
        $this->SubmissionTeamModel->migrateTeamData($sub, $project['ProjID']);

        $subTeamData = $this->SubmissionTeamModel->getProjectTeam($project['ProjID']);
        $subTeamData = array_column($subTeamData, NULL, 'PeopleProjSortOrder');

        $htmlTeamPanel = '';
        $currentMember = 1;
        for ($i = 1; $i <= self::LIMIT_TEAM_MEMBER; $i ++) {

            $style = '';
            if ($i != 1 && empty($subTeamData[$i]['SubTeamFullName'])) {
                $style = 'style="display: none"';
            }

            $pictureLink = '';
            $passportLink = '';
            $PeopleID = '';
            if (isset($subTeamData[$i]) && !empty($subTeamData[$i])) {
                $inputFullname = formSetValue('inputFullname'.$i, $subTeamData[$i]['PeopleName']);
                $inputPosition = formSetValue('inputPosition'.$i, $subTeamData[$i]['PeopleProjPosition']);
                $inputShortBio = formSetValue('inputShortBio'.$i, $subTeamData[$i]['PeopleDesc']);
                $inputLinkedin = formSetValue('inputLinkedin'.$i, $subTeamData[$i]['Link']);
                $PeopleID = $subTeamData[$i]['PeopleID'];
                if (!empty($subTeamData[$i]['PeoplePicture'])) {
                    $pictureLink = '<a target="_blank" class="teamlink" href="'.base_url() . self::PATH_IMAGE_TEAM . $subTeamData[$i]['PeoplePicture'].'"><strong>'.$subTeamData[$i]['PeoplePicture'].'</strong></a>';
                }
                if (!empty($subTeamData[$i]['PeoplePassport'])) {
                    $passportLink = '<a target="_blank" class="teamlink" href="'.base_url() . self::PATH_IMAGE_TEAM . $subTeamData[$i]['PeoplePassport'].'"><strong>'.$subTeamData[$i]['PeoplePassport'].'</strong></a>';
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
                    <input type="hidden" name="peopleID'.$i.'" id="peopleID'.$i.'" value="'.$PeopleID.'">
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

    private function renderProjectType($project)
    {
        $this->layoutData['htmlProjectType'] = false;
        $projectType = $this->ProjectModel->getProjectTypes();

        if (!empty($projectType)) {
            $subProjType = formSetValue('ProjType', $project['ProjType']);
            foreach ($projectType as $key => $value) {
                if ($key == $subProjType) {
                    $this->layoutData['htmlProjectType'] .= '<option selected value="'.$key.'">'.$value.'</option>';
                } else {
                    $this->layoutData['htmlProjectType'] .= '<option value="'.$key.'">'.$value.'</option>';
                }
            }
        }
    }

    private function renderScriptListing()
    {
        $this->layoutData['icon_del'] = '<img class="icon-delete" style="cursor: pointer;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAjhJREFUOI2Fk+9PUlEYx7/Hi9B2x8V7b8wyV5ltMfs5YzUUdQhUb1z2H7Qp/hX+I01b4NZ7XlSWrBFkupaLRmuVTFKqoSJcfoztcjmX04uE8av1bOfNeT7fz/OcFwc4rgAgr0/bk34L78I/akUQvO88t3aWAKmlEQDkTzNT2cKij/3wzdJukhVB8O4uPKCFRR+LzTgzdQkJAPK1e+PbF2zDDWs2c6RHgxHvw3I5DABPBeHu+P3J56IscXUm+SVxtBXavETWRy8nr9qvDLVPzGZyenRtw2vkmGnM43wmnhS5dia+9TlB/DzvmnDaQ3KTvSHJ5vQe0gNR6uvo5ZQ8fRPenCYA4Od518To9ZAktoGMgVU1oErBdB2o6QBjyBVLNPJ9xz2vqlFSZ/0873KOjIQkQeBYtQqoKpimAYy1OJVymYZ3d6cXKH0LAKS56TeZXI5TgyGJM3SsDAC5iqqv/95zzwGR+p2hGSCUGqFqqPWSzjSAWrXWcdcglznuzph14IWl19R1euMJWkV/f/CzsQUBgCWO8zqsZ1bN/wnXq6Cp+odjCXnMcZ6b1sGXZmNnuKipOgAIxhNdex/3U26yahYTw5L1YjtQ0lQaS6fcBCA3Tp99be4iSSiH33p+lZTbqVI+QwlB/ShahcbSKfc8EJ0DIlvplFvRKnozs1dUDg6LeQeAv59pTe7PxIdsbGPgXHUZmGyf9giGqY2B8zQ+ZGOvpP79J0BfCxAA5KBF3O4WbpYELeLX5vAfETvzqIeEC14AAAAASUVORK5CYII=">';
        $this->layoutData['icon_add'] = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNS8zMC8xN4w6FvUAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAABwUlEQVQ4jY1SzU5TQRg938zc25aWFqjJXdCYdOEr6MIYYEPCirVxzQMQY3THGxCXLtzwDi4M/qzUuBIoARJDiAkEaEgEa63tvXfmuGjR9nZIPMlszpw53zcnR0gii5enL6pv2++eNtPzlkBJDoGdKzx4/qy+1slqzdhrAB87n4ofem9Wu/gNAiih7ObU4gaA/zMgRKXomRQxCMCJxYQuBz6t8pF9SMbUeVVeAwcmWS6U/BgHAGb7cqd43mkaRwcRgTjBrKlVIQCG8jVU1dcnm20KQToUdFGichTLwsG99aPk64qD+7sNBarNn3kM1hZolGSyC9Jde4aSi+/m7z8xCXpTLVyV7PA4jv6NsPjBq/zQNQLq4DQ+TgxBqyAkKMOCLAT/YiUABWWNGDEKWmnkBLQjaot4NCyE/ZfSt9AIc0q0kuX9paiZns1wMJd0mApv1bbTz5s9DookFcyHS4uH3b0TEQWAMAhYD++cgeTYWT16HEWNAisNsNwAa7vTfNV8H/m03h58txeFLHcYH4xxwA1FkkwLb+L62XgQM7ECnSqo68SRMvF22WvwaPqhSi5/bWx1vnwjHG6H9ckZU2n5tH8A22HP+bXtAFQAAAAASUVORK5CYII=">';

        $scriptCSS = [];
        $scriptCSS[] = base_url() . 'public/libs/bootstrap/my.bootstrap.css';
        $scriptCSS[] = base_url() . 'public/libs/datatables/datatables.css';
        $scriptCSS[] = base_url() . 'public/libs/flatpickr/flatpickr.min.css';
        $scriptCSS[] = base_url() . 'public/libs/bootstrap/bootstrap-multiselect.css';
        $scriptCSS[] = base_url() . 'public/libs/fontawesome/css/font-awesome.min.css';
        $scriptCSS[] = base_url() . 'public/css/tab.css';

        $scriptJS = [];
        $scriptJS['new_common'] = base_url() . 'public/dist/new-common-min.js';
        $scriptJS['jquery'] = base_url() . 'public/js/jquery.js';
        $scriptJS['bootstrap'] = base_url() . 'public/libs/bootstrap/bootstrap.min.js';
        $scriptJS['bootstrap_multiselect'] = base_url() . 'public/libs/bootstrap/bootstrap-multiselect.js';
        $scriptJS['flatpickr'] = base_url() . 'public/libs/flatpickr/flatpickr.js';
        $scriptJS['tab'] = base_url() . 'public/js/tab.js';
        $scriptJS['datatables'] = base_url() . 'public/libs/datatables/datatables.min.js';


        $this->renderCSS($scriptCSS, false);
        $this->renderJS($scriptJS, false);

    }
}
