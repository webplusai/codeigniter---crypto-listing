<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/core/MY_Controller.php';

class Welcome extends MY_Controller
{
    const MEMCACHE_EXPIRATION = 3600; // 1 hour

    public function __construct($isAjax = false)
    {
        parent::__construct();
        $this->load->model('Project_model', 'ProjectModel');
        $this->load->model('Link_model', 'LinkModel');
    }

    public function index()
    {
        $this->load->model('Filterpanel_model', 'FilterpanelModel');
        $this->load->model('Filter_model', 'FilterModel');

        // Filer Panel Cookie - Session
        $cookieState = 0;
        if (isset($_COOKIE['cs_filter_state'])) {
            $cookieState = $_COOKIE['cs_filter_state'];
        }
        $this->layoutData['isOpenFilter'] = $cookieState;
        if ($this->layoutData['isOpenFilter'] == 1) {
            $this->layoutData['filterIcon'] = $this->layoutData['config']['icon_filter_close'];
        } else {
            $this->layoutData['filterIcon'] = $this->layoutData['config']['icon_filter_open'];
        }

        $this->layoutData['isHomepage'] = true;
        $filterID = 0;
        if ($this->uri->segment(1)) {
            $filterID = preg_replace('/[^0-9]/', '', $this->uri->segment(1));
        } else {
            $this->session->unset_userdata('ssFilter');
        }

        // Filter Panel
        $filterPanel = $this->FilterpanelModel->getFilterPanel();
        $this->renderFilterPanelCate($filterPanel);
        $this->renderFilterPanelPlat($filterPanel);

        // ICO Data
        $this->renderICO($filterID);
        $cachedDataHomepage = $this->cache->redis->get('cachedDataHomepage');
        if (!$cachedDataHomepage) {
            $cachedDataHomepage = [];
            $cachedDataHomepage['platinumData'] = $this->ProjectModel->getPlatinum();
            $cachedDataHomepage['eventData'] = $this->ProjectModel->getEventHomepage();
            $this->cache->redis->save('cachedDataHomepage', $cachedDataHomepage, CACHE_EXPIRE);
        }

        $blogPost = $this->cache->redis->get('cachedBlogPost');
        if (!$blogPost) {
            $blogPost = curl_get_content('https://www.coinschedule.com/blog/wp-json/wp/v2/posts/?per_page=4&_embed&categories=3+4');
            $blogPost = json_decode($blogPost, true);
            if (!empty($blogPost)) {
                $this->cache->redis->save('cachedBlogPost', $blogPost, CACHE_EXPIRE);
            }
        }

        $pressMentions = $this->cache->redis->get('cachedPressMentions');
        if (!$pressMentions) {
            $pressMentions = $this->ProjectModel->getPressMentionsHomepage();
            if (!empty($pressMentions)) {
                $this->cache->redis->save('cachedPressMentions', $pressMentions, CACHE_EXPIRE);
            }
        }

        // Render View
        $this->layoutData['reviveBanner'] = $this->renderReviveBanner('d5637580a638bdd0c5d72837efc8cf42', 1, 'home-top-banner top-banner');
        $this->renderSelectLiveView();
        $this->renderPlatinumSection($cachedDataHomepage['platinumData']);
        $this->renderEventHomepage($cachedDataHomepage['eventData']);
        $this->renderFromBlog($blogPost);
        $this->renderPageInfo($this->mappingPage['Homepage']);
        $this->renderView('homepage.twig');
    }

    public function applyFilter()
    {
        if (empty($this->input->post(NULL, TRUE))) {
            redirect('');
            exit;
        }

        $filter = '';
        foreach ($this->input->post(NULL, TRUE) as $key => $value) {
            if (strpos($key, 'plat') === 0) {
                $filter.= "$key,";
            }

            if (strpos($key, 'cat') === 0) {
                $filter.= "$key,";
            }
        }

        if (!$filter || $filter == "plat," || $filter == "cat," || $filter == "plat,cat,") {
            redirect('');
            exit;
        }

        $this->load->model('Filter_model', 'FilterModel');
        $filterID = $this->FilterModel->applyFilterDb($filter);
        $filter = array(
            'filterID' => $filterID,
            'filterName' => $filter,
        );
        $this->session->set_userdata(array('ssFilter' => $filter));
        redirect('ico-list-'.$filterID);
        exit;
    }

    public function projectDetail()
    {
        $this->load->model('Project_model', 'ProjectModel');
        $this->load->model('Event_model', 'EventModel');

        $segment = $this->uri->segment(2);
        if (strpos($segment, 'e') !== false) {
            $eventID = str_replace('e', '', $this->uri->segment(2));
            $projectDetail = $this->cache->redis->get('cachedProjDetailE_' . $eventID);
            if (!$projectDetail) {
                $projectDetail = $this->ProjectModel->getProjectDetail($eventID, false);
                if (!empty($projectDetail)) {
                    $this->cache->redis->save('cachedProjDetailE_' . $eventID, $projectDetail, CACHE_EXPIRE);
                }
            }
        } else {
            $projectID = $segment;
            $projectDetail = $this->cache->redis->get('cachedProjDetailP_' . $projectID);
            if (!$projectDetail) {
                $projectDetail = $this->ProjectModel->getProjectDetail(false, $projectID);
                if (!empty($projectDetail)) {
                    $this->cache->redis->save('cachedProjDetailP_' . $projectID, $projectDetail, CACHE_EXPIRE);
                }
            }
        }

        if (empty($projectDetail)) {
            $this->render404();
            exit();
        }

        $segmentLinked = $this->uri->segment(1);

        $this->layoutData['deletedProject'] = false;
        if ($projectDetail['ProjDeleted'] == 1) {
            $this->layoutData['deletedProject'] = true;
            $projectTitle = $projectDetail['ProjName'].($projectDetail['ProjSymbol']==''?'':' ('.$projectDetail['ProjSymbol'].')');

            $this->layoutData['projectDetail'] = $projectDetail;
            $this->layoutData['projTitle'] = $projectTitle;

            if ($segmentLinked == 'projects') {
                $this->layoutData['head']['title'] = $projectTitle . ' ' . $projectDetail['ProjTypeName'] . ' - Coinschedule - DELISTED';
            } else {
                $this->layoutData['head']['title'] = $projectTitle . ' - DELISTED';
            }

            if ($projectDetail['ProjDelisted'] == 0) {
                header("HTTP/1.1 410 Gone");
            }
        } else {
            $projectID = $projectDetail['ProjID'];
            $eventID = $projectDetail['EventID'];
            $crowdFund = $this->EventModel->getCrowdfund($eventID, $projectID);
            if (!empty($crowdFund)) {
                $this->renderProjectRates($crowdFund['EventID']);
            }

            if ($projectDetail['ProjSponsored'] == 0) {
                $this->renderSimilarIco($projectDetail);
            }

            $this->renderFeaturedPosts($projectDetail);
            $this->renderVoteFavourties($projectID);
            $this->renderProjectDistros($projectID);
            $this->renderProjectLinks($projectID);
            $this->renderProjectPeople($projectID);
            $this->renderProjectEvent($projectID);
            $this->renderProjectValue($projectDetail, $crowdFund, $segmentLinked);
            $this->renderListIco($projectDetail, $segmentLinked);

            $this->layoutData['gruntStyleFile'] = 'parts/login.css';
        }

        $this->renderView('project_detail.twig');
    }

    public function personDetail() {
    	$this->load->model('Link_model', 'LinkModel');
    	$this->load->model('People_model', 'PeopleModel');
    	$this->load->model('People_project_model', 'PeopleProjectModel');
    	$this->load->model('Project_model', 'ProjectModel');
		$this->load->model('Event_model', 'EventModel');

		$segment = $this->uri->segment(2);

		$personID = str_replace('e', '', $segment);
		$personDetail = $this->cache->redis->get('cachedPersonDetailE_' . $personID);
		if (!$personDetail) {
			$personDetail = $this->PeopleModel->getPersonDetail($personID);
			if (!empty($personDetail)) {
				$this->cache->redis->save('cachedPersonDetailE_' . $personID, $personDetail, CACHE_EXPIRE);
			}
		}
		if (!$personDetail[0]['PeoplePicture']) {
            $personDetail[0]['PeoplePicture'] = base_url() . '/public/images/default_team.png?nocache='.time();
        } else {
            $personDetail[0]['PeoplePicture'] = '/public/uploads/' . $personDetail[0]['PeoplePicture'];
        }

		$personLinks = $this->cache->redis->get('cachedPersonLinksE_' . $personID);
		if (!$personLinks) {
			$personLinks = $this->LinkModel->getLinkByPeople([$personID]);
			if (!empty($personLinks)) {
				$this->cache->redis->save('cachedPersonLinksE_' . $personID, $personLinks, CACHE_EXPIRE);
			}
		}

		foreach($personLinks as $personLink) {
		    if ($personLink['LinkTypeName'] == 'LinkedIn') {
		        $personDetail[0]['LinkedIn'] = $personLink;
            } else if ($personLink['LinkTypeName'] == 'Twitter') {
		        $personDetail[0]['Twi tter'] = $personLink;
            }
        }

		$personProjects = $this->cache->redis->get('cachedPersonProjectsE_' . $personID);
		if (!$personProjects) {
			$personProjects = $this->PeopleProjectModel->getPeopleProjects([$personID]);
			if (!empty($personProjects)) {
				$this->cache->redis->save('cachedPersonProjectsE_' . $personID, $personProjects, CACHE_EXPIRE);
			}
		}

		foreach ($personProjects as $personProject) {
		    $personProject['ProjLink'] = $this->getLinkByProject($personProject['ProjID'], 1);
        }

		$this->layoutData['personDetail'] = $personDetail[0];
		$this->layoutData['personLinks'] = $personLinks;
		$this->layoutData['personProjects'] = $personProjects;

		if (strpos($segment, 'e') !== false) {
			$eventID = str_replace('e', '', $this->uri->segment(2));
			$projectDetail = $this->cache->redis->get('cachedProjDetailE_' . $eventID);
			if (!$projectDetail) {
				$projectDetail = $this->ProjectModel->getProjectDetail($eventID, false);
				if (!empty($projectDetail)) {
					$this->cache->redis->save('cachedProjDetailE_' . $eventID, $projectDetail, CACHE_EXPIRE);
				}
			}
		} else {
			$projectID = $segment;
			$projectDetail = $this->cache->redis->get('cachedProjDetailP_' . $projectID);
			if (!$projectDetail) {
				$projectDetail = $this->ProjectModel->getProjectDetail(false, $projectID);
				if (!empty($projectDetail)) {
					$this->cache->redis->save('cachedProjDetailP_' . $projectID, $projectDetail, CACHE_EXPIRE);
				}
			}
		}

		if (empty($projectDetail)) {
			$this->render404();
			exit();
		}

		$segmentLinked = $this->uri->segment(1);

		$this->layoutData['deletedProject'] = false;
		if ($projectDetail['ProjDeleted'] == 1) {
			$this->layoutData['deletedProject'] = true;
			$projectTitle = $projectDetail['ProjName'].($projectDetail['ProjSymbol']==''?'':' ('.$projectDetail['ProjSymbol'].')');

			$this->layoutData['projectDetail'] = $projectDetail;
			$this->layoutData['projTitle'] = $projectTitle;

			if ($segmentLinked == 'projects') {
				$this->layoutData['head']['title'] = $projectTitle . ' ' . $projectDetail['ProjTypeName'] . ' - Coinschedule - DELISTED';
			} else {
				$this->layoutData['head']['title'] = $projectTitle . ' - DELISTED';
			}

			if ($projectDetail['ProjDelisted'] == 0) {
				header("HTTP/1.1 410 Gone");
			}
		} else {
			$projectID = $projectDetail['ProjID'];
			$eventID = $projectDetail['EventID'];
			$crowdFund = $this->EventModel->getCrowdfund($eventID, $projectID);
			if (!empty($crowdFund)) {
				$this->renderProjectRates($crowdFund['EventID']);
			}

			if ($projectDetail['ProjSponsored'] == 0) {
				$this->renderSimilarIco($projectDetail);
			}

			$this->renderFeaturedPosts($projectDetail);
			$this->renderVoteFavourties($projectID);
			$this->renderProjectDistros($projectID);
			$this->renderProjectLinks($projectID);
			$this->renderProjectPeople($projectID);
			$this->renderProjectEvent($projectID);
			$this->renderProjectValue($projectDetail, $crowdFund, $segmentLinked);
			$this->renderListIco($projectDetail, $segmentLinked);

			$this->layoutData['gruntStyleFile'] = 'parts/login.css';
		}

		$this->renderView('person_detail.twig');
    }

    private function renderSelectLiveView()
    {
        $liveIcovView = isset($_GET['live_view'])!=""?$_GET['live_view']:(isset($_COOKIE["live_icoview"])?$_COOKIE["live_icoview"]:3);
        setcookie("live_icoview",$liveIcovView,time()+31556926 ,'/');
        $_COOKIE["live_icoview"] = $liveIcovView;
        $liveIcovView = $_COOKIE["live_icoview"];
    
        $arrOptions = [
            '1' => 'Plates',
            '2' => 'List',
        ];
        $this->layoutData['htmlLiveView'] = '';
        foreach ($arrOptions as $key => $val) {
            if ($key == $liveIcovView) {
                $this->layoutData['htmlLiveView'] .= '<option selected value="'.$key.'">'.$val.'</option>';
            } else {
                $this->layoutData['htmlLiveView'] .= '<option value="'.$key.'">'.$val.'</option>';
            }
        }
    }

    private function renderProjectRates($crowdfundID)
    {
        $projectRates = $this->cache->redis->get('cachedProjectRates'.$crowdfundID);
        if (!$projectRates) {
            $projectRates = $this->ProjectModel->getProjectRates($crowdfundID);
            if (!empty($projectRates)) {
                $this->cache->redis->save('cachedProjectRates'.$crowdfundID, $projectRates, CACHE_EXPIRE);
            }
        }

        $htmlRates = false;
        if (!empty($projectRates)) {
            $htmlRates .= '<div class="column"><ul class="characteristics-list fullsize-list">';
            foreach ($projectRates as $rate) {
                $htmlRates .= '<li>
                                    <span class="text text-rates">'.escape_string($rate['CrowdBonusName']).'</span>
                                </li>';
            }
            $htmlRates .= '</ul></div>';
        }
        $this->layoutData['htmlRates'] = $htmlRates;
    }

    private function renderVoteFavourties($projectID)
    {
        $this->load->model('Vote_model', 'VoteModel');
        $this->load->model('Favourites_model', 'FavouritesModel');

        $votes = $this->VoteModel->getVoteByProject($projectID);
        $this->layoutData['votes'] = $votes;

        $this->layoutData['isLiked'] = false;
        $isUpVote = false;
        $isDownVote = false;
        if ($this->session->has_userdata(self::SS_USER)) {
            $this->layoutData['isLiked'] = $this->FavouritesModel->isUserLiked($projectID, $this->layoutData['ssUser']['id']);

            $userVotes = $this->VoteModel->getUserVoteByProject($projectID, $this->layoutData['ssUser']['id']);
            if (!empty($userVotes)) {
                if ($userVotes['VoteType'] == 1) {
                    $isUpVote = true;
                } else if ($userVotes['VoteType'] == 2) {
                    $isDownVote = true;
                }
            }
        }

        $this->layoutData['isUpVote'] = $isUpVote;
        $this->layoutData['isDownVote'] = $isDownVote;
    }

    private function renderProjectDistros($projectID)
    {
        $projectDistros = $this->ProjectModel->getProjectDistros($projectID);
        $htmlDistros = false;
        if (!empty($projectDistros)) {
            foreach ($projectDistros as $distro) {
                if ($distro['DistroAmount'] == 0 && $distro['DistroPercent'] == 0) {
                    continue;
                }
                $htmlDistros .= '<li>
                                    <span class="title">'.escape_string($distro['DistroDesc']).'</span>
                                    <span class="text">'.($distro['DistroAmount']==0?'':number_format(($distro['DistroAmount'] + 0)).' '.escape_string($distro['ProjSymbol'])).'</span>
                                    <span class="text">'.($distro['DistroPercent']==0?'':$distro['DistroPercent'].'%').'</span>
                                </li>';
            }
        }
        $this->layoutData['htmlDistros'] = $htmlDistros;
    }

    private function renderProjectLinks($projectID)
    {
        $projectLinks = $this->ProjectModel->getProjectLinks($projectID);
        $htmlLinks = false;
        if (!empty($projectLinks)) {
            $chunkSize = ceil(count($projectLinks) / 4);
            $projectChunk = array_chunk($projectLinks, $chunkSize, true);
            foreach ($projectChunk as $projectChunkLink) {
                $htmlLinks .= '<ul class="socials-list">';
                foreach ($projectChunkLink as $link) {
                    if ($link['ProjAffilLinks'] && $link['LinkTypeID']==1) {
                        $link['Link'] = base_url() . "link?l=".$link['LinkID'];
                    }

                    $htmlLinks .= '<li data-id="'.$projectID.'" class="projectLink">
                                        <a rel="nofollow" target="_blank" href="'.$link['Link'].'">
                                            <img class="inline" src="data:image/png;base64,'.$link['LinkTypeImage'].'" height="16" width="16" alt="'.$link['LinkTypeName'].' Link">
                                            <span class="linkType">'.$link['LinkTypeName'].'</span>
                                        </a>
                                    </li>';
                }
                $htmlLinks .= '</ul>';
            }

        }
        $this->layoutData['htmlLinks'] = $htmlLinks;
    }

    private function renderProjectPeople($projectID)
    {
        $projectPeople = $this->ProjectModel->getProjectPeople($projectID);
        $htmlPeople = false;
        if (!empty($projectPeople)) {

            // people group
            $peopleGroup = $this->cache->redis->get('cachedPeopleGroup');
            if (!$peopleGroup) {
                $this->load->model('People_groups_model', 'PeopleGroupsModel');
                $peopleGroup = $this->PeopleGroupsModel->getAll();
                if (!empty($peopleGroup)) {
                    $this->cache->redis->save('cachedPeopleGroup', $peopleGroup, CACHE_EXPIRE);
                }
            }
            $peopleGroup = array_column($peopleGroup, NULL, 'PeopleGroupID');

            $arrPeopleID = array_column($projectPeople, 'PeopleID');
            $arrLinkPeople = $this->LinkModel->getLinkByPeople($arrPeopleID);
            $arrLinks = array();
            if (!empty($arrLinkPeople)) {
                foreach ($arrLinkPeople as $l) {
                    $arrLinks[$l['LinkParentID']][] = $l;
                }
            }

            $mProjectPeopleGroup = [];
            $projectPeopleGroup = [];
            foreach ($projectPeople as $p) {
                if (isset($projectPeopleGroup[$p['PeopleProjGroupID']])) {
                    $projectPeopleGroup[$p['PeopleProjGroupID']][] = $p;
                } else {
                    $projectPeopleGroup[$p['PeopleProjGroupID']] = [$p];
                }
                $mProjectPeopleGroup[$p['PeopleProjGroupID']] = $p['PeopleProjGroupID'];
            }
            ksort($projectPeopleGroup);

            $mPeopleGroup = array_column($peopleGroup, 'PeopleGroupID', 'PeopleGroupSort');
            $multiSort1 = array_column($peopleGroup, 'PeopleGroupID', 'PeopleGroupSort');
            foreach ($multiSort1 as $key => $sort) {
                if (!in_array($sort, $mProjectPeopleGroup, true)) {
                    unset($multiSort1[$key]);
                    unset($mPeopleGroup[$key]);
                }
            }
            $mPeopleGroup = array_values($mPeopleGroup);
            array_multisort($multiSort1, SORT_ASC,  $projectPeopleGroup);


            $isFirstGroup = 1;
            $classGroup = '';
            $classTeamList = '';
            foreach ($projectPeopleGroup as $key => $pG) {

                $groupId = $mPeopleGroup[$key];
                if (!$isFirstGroup) {
                    $classGroup = 'group-name';
                    $classTeamList = 'team-list-after';
                }

                if (isset($peopleGroup[$groupId])) {
                    $htmlPeople .= '<h2 class="section-title '.$classGroup.'">' . $peopleGroup[$groupId]['PeopleGroupName'] . '</h2><div class="team-list '.$classTeamList.'">';
                }

                foreach ($pG as $people) {
                    $links = '';
                    if (isset($arrLinks[$people['PeopleID']]) && !empty($arrLinks[$people['PeopleID']])) {
                        $peopleLinks = $arrLinks[$people['PeopleID']];
                        $links = '';
                        foreach ($peopleLinks as $peopleLink) {
                            $links .= '<a data-id="'.$projectID.'" class="projectLink peopleLink" rel="nofollow" target="_blank" href="'.$peopleLink['Link'].'">
                                            <img class="inline" src="data:image/png;base64,'.$peopleLink['LinkTypeImage'].'" height="16" width="16" alt="'.$peopleLink['LinkTypeName'].' Link">
                                       </a>';

                        }
                    }

                    $peopleImg = base_url() . 'public/images/default_team.png?nocache='.time();
                    if ($people['PeoplePicture'] != '') {
                        $peopleImg = base_url() . 'public/uploads/team/' . $people['PeoplePicture'];
                    }


                    // People Desc
                    $htmlDesc = '<div class="people-desc"></div>';
                    if ($people['PeopleDesc'] != '') {
                        $maxDesc = 90;
                        $isMore = isShowMore($people['PeopleDesc'], $maxDesc);
                        $shortText = escape_string(truncate($people['PeopleDesc'], $maxDesc));
                        $fullText = escape_string(appStripTags($people['PeopleDesc']));
                        $htmlMore = '';
                        if ($isMore) {
                            $htmlMore .= '<a href="javascript:void(0);" class="people-more" data-id="'.$people['PeopleID'].'">More</a>';
                        }

                        $htmlDesc = '<div class="people-desc" id="people-id-'.$people['PeopleID'].'">
                                        <div class="show-text">'.$shortText.'</div>'.$htmlMore.'
                                        <div class="toggle-text">'.$fullText.'</div>
                                     </div>';
                    }


                    $htmlPeople .= '<div class="item">
                                    <div class="avatar-container">
                                        <img onerror=\'onErrorTeam(this);\' class="img-error-team peopleImg" src="'.$peopleImg.'" alt="'.escape_string($people['PeopleName']).'">
                                    </div>
                                    <div class="description-container">
                                        <span class="name">
                                            '.escape_string($people['PeopleName']).'
                                            '.$links.'
                                        </span>
                                        <span class="position">'.escape_string($people['PeopleProjPosition']).'</span>
                                        '.$htmlDesc.'
                                    </div>
                                </div>';
                }

                if (isset($peopleGroup[$groupId])) {
                    $htmlPeople .= '</div>';
                }

                $isFirstGroup = 0;
            }

        }

        $this->layoutData['htmlPeople'] = $htmlPeople;
    }

    private function renderProjectEvent($projectID)
    {
        $projectEvent = $this->ProjectModel->getProjectEvent($projectID);
        $htmlEvent = false;
        $isMobile = false;
        if ($this->agent->is_mobile()) {
            $isMobile = true;
        }

        if (!empty($projectEvent)) {
            foreach ($projectEvent as $event) {
                $eventDate = date_create($event['EventDate']);
                $eventName = $projectID == '' && $event['EventProjID'] != 0 && $event['Bonus'] == 0 && stripos($event['EventName'], $event['ProjName']) === false ? $event['ProjName'] . ' - ' . $event['EventName'] : $event['EventName'];
                $eventTitle = $eventName . ($event['EventLocation'] == '' ? '' : ' - <i>' . $event['EventLocation'] . '</i>') . (date_format($eventDate, "H:i") != "00:00" && $event['EventType'] == 1 ? ' (' . date_format($eventDate, "H:i") . ' UTC)' : '');

                if ($event['EventStartDateType'] == 2) {
                    $eventDate = 'Q'.ceil(date_format($eventDate,'n')/3).' '.date_format($eventDate,'Y');
                } elseif ($event['EventStartDateType'] == 3) {
                    $eventDate = $eventDate ? date_format($eventDate, "M Y") : '';
                } else {
                    if ($event['EventDate'] == "0000-00-00 00:00:00") {
                        $eventDate = "TBA";
                    } else {
                        $eventDate = $eventDate ? date_format($eventDate, $isMobile ? "d/m/y" : "M Y") : '';
                    }
                }

                $htmlEvent .= '<li>
                                    <span class="title">'.$eventDate.'</span>
                                    <p class="text">'.$eventTitle.'</p>
                                </li>';
            }
        }

        $this->layoutData['htmlEvent'] = $htmlEvent;
    }

    private function renderListIco($projectDetail, $segmentLinked)
    {
        $this->layoutData['htmlListIco'] = false;
        if ($segmentLinked != 'projects') {
            return false;
        }

        $this->load->model('Event_model', 'EventModel');
        $listIcoEvent = $this->EventModel->getListIcoEvent($projectDetail['ProjID']);

        if (!empty($listIcoEvent)) {
            foreach ($listIcoEvent as $event) {
                $url = slugEvent($event['EventID'], $event['EventName']);
                $this->layoutData['htmlListIco'] .= '<a href="'.$url.'"><div class="info-row table-row">
                                <div class="table-col"><span class="text-row">'.escape_string($event['EventName']).'</span></div>
                                <div class="table-col"><span>'.dateFormatEvent($event['EventStartDate']).'</span></div>
                                <div class="table-col hidden-xs"><span>'.dateFormatEvent($event['EventEndDate']).'</span></div>
                                <div class="table-col hidden-xs"><span>'.$event['EventStatus'].'</span></div>
                            </div></a>';
            }
        }
    }

    private function renderProjectValue($projectDetail, $crowdFund, $segmentLinked)
    {
        $start = '';
        $startDays = '- -';
        $startHours = '- -';
        $startMins = '- -';
        $startSecs = '- -';
        $end = '';
        $endDays = '';
        $endHours = '';
        $endMins = '';
        $endSecs = '';
        $crowdFundDesc = '';
        $timerDate = '';
        $scriptEndDate = '';
        $initializeClock = '';
        $isInitializeClock = false;

        $projectID = $projectDetail['ProjID'];
        $eventName = $projectDetail['EventName'];
        $projectTitle = $projectDetail['ProjName'].($projectDetail['ProjSymbol']==''?'':' ('.$projectDetail['ProjSymbol'].')');

        $projTotalSuppNote = $projectDetail['ProjTotalSuppNote'];
        $this->layoutData['projTotalsupp'] = $projTotalSuppNote ? $projTotalSuppNote : number_format($projectDetail['ProjTotalSupp'],0,'.',',');
        if ($this->layoutData['projTotalsupp'] === 0) {
            $this->layoutData['projTotalsupp'] = '';
        }

        // whitepaper
        $whitePaperLink = false;
        $whitePaper = $this->getLinkByProject($projectID, 14);
        if (!empty($whitePaper)) {
            $whitePaperLink = $whitePaper['Link'];
        }
        $this->layoutData['whitePaperLink'] = $whitePaperLink;

        // bitcointalk
        $bitcoinTalkLink = false;
        $bitcoinTalk = $this->getLinkByProject($projectID, 4);
        if (!empty($bitcoinTalk)) {
            $bitcoinTalkLink = $bitcoinTalk['Link'];
        }
        $this->layoutData['bitcoinTalkLink'] = $bitcoinTalkLink;

        // projSponsoredbadge
        $this->layoutData['projSponsoredbadge'] = '';
        if (!$projectDetail['ProjDisableRibbon'] && $projectDetail['ProjSponsored']) {
            if ($projectDetail['ProjPlatinum']) {
                $this->layoutData['projSponsoredbadge'] = '<img class="sponsbadge inline d-tooltip" src="'.$this->layoutData['config']['icon_plat_badge'].'" title="This is a platinum project" alt="Platinum Project" >';
            } else if ($projectDetail['ProjPackage'] == 2) {
                $this->layoutData['projSponsoredbadge'] = '<img class="sponsbadge inline d-tooltip" src="'.$this->layoutData['config']['icon_gold_badge_large'].'" title="This is a gold project" alt="Gold Project">';
            }
        } else if ($projectDetail['ProjPackage'] == 1) {
            $this->layoutData['projSponsoredbadge'] = '<img class="sponsbadge inline d-tooltip" src="'.$this->layoutData['config']['icon_silver_badge_large'].'" title="This is a silver project" alt="Silver Project">';
        }

        // project website
        $projWebsite = $this->getLinkByProject($projectID, 1);
        if (!empty($projWebsite)) {
            if ($projectDetail['ProjAffilLinks']) {
                $projWebsite = base_url() . 'link?l='.$projWebsite['LinkID'];
            } else {
                $projWebsite = $projWebsite['Link'];
            }
        } else {
            $projWebsite = '';
        }


        $startDateAttr = '';
        $endDateAttr = '';
        $this->layoutData['jsonStart'] = '';
        $this->layoutData['jsonEnd'] = '';
        // crowdFund
        if (!empty($crowdFund)) {
            if ($crowdFund['EventStartDateType'] == 1) {
                // end time
                $endStamp = strtotime($crowdFund['EventEndDate']);
                $endDate = date_create($crowdFund['EventEndDate']);
                $end = date_format($endDate,"F jS Y").(date_format($endDate,"H:i")!="00:00"?' '.date_format($endDate,"H:i").' UTC':'');
                $endDateAttr = date_format($endDate,"F j Y H:i") . ' GMT+0000';

                $this->layoutData['jsonStart'] = date("Y-m-d H:i", strtotime($crowdFund['EventStartDate'])) . 'UTC';
                $this->layoutData['jsonEnd'] = date("Y-m-d H:i", strtotime($crowdFund['EventEndDate'])) . 'UTC';

                // start time
                $startStamp = strtotime($crowdFund['EventStartDate']);
                $startDate = date_create($crowdFund['EventStartDate']);
                $start = date_format($startDate,"F jS Y").(date_format($startDate, "H:i") != "00:00"?' '.date_format($startDate, "H:i").' UTC':'');
                $startDateAttr = date_format($startDate,"F j Y H:i") . ' GMT+0000';
                $startDays = (time() < $startStamp || time() > $endStamp ?'- -':'L');
                $startHours = (time() < $startStamp || time() > $endStamp?'- -':'I');
                $startMins = (time() < $startStamp || time() > $endStamp?'- -':'V');
                $startSecs = (time() < $startStamp || time() > $endStamp?'- -':'E');

                // timer
                $timerDate = (time() < $startStamp ? $startDate : $endDate);
                $timerDate = date_format($timerDate,'Y').",".(date_format($timerDate,'n')-1).",".date_format($timerDate,'d,H,i,s');
                if (time() < $endStamp) {
                    $isInitializeClock = true;
                    $scriptEndDate = date_format($endDate, 'D M d Y H:i:s') . 'Z';
                    $initializeClock = (time() < $startStamp ? 'startclock' : 'endclock');
                }
            } else {
                $start = "TBA";
                $end = "TBA";
                $startDays = '- -';
                $startHours = '- -';
                $startMins = '- -';
                $startSecs = '- -';
            }

            $crowdFundDesc = nl2br($crowdFund['EventDesc']);
        }

        $this->layoutData['reviveTopBanner'] = false;
        $this->layoutData['reviveWidgetBanner'] = false;
        if ($projectDetail['ProjPackage'] == 0) {
            $this->layoutData['reviveBanner'] = true;
            $this->layoutData['reviveTopBanner'] = $this->renderReviveBanner('d5637580a638bdd0c5d72837efc8cf42', 1, 'top-banner');
            $this->layoutData['reviveWidgetBanner'] = $this->renderReviveBanner('d5637580a638bdd0c5d72837efc8cf42', 5, 'widget-banner');
        }

        $projectPlatform = '';
        if ($projectDetail['ProjType'] == 2) {
            $platforms = $this->ProjectModel->getProjectPlatform($projectID);
            if (!empty($platforms)) {
                foreach ($platforms as $platform) {
                    $projectPlatform .= ($platform['PlatformImage']!=''?'<img alt="'.$platform['Platform'].'" src="data:image/png;base64,'.$platform['PlatformImage'].'" height="16" width="16"> ':'').$platform['Platform'].'<br>';
                }
            }
        }

        $projImg = '';
        if ($projectDetail['ProjImageLarge'] != '') {
            $projImg = 'data:image/png;base64,'.$projectDetail['ProjImageLarge'];
        }

        $projectDetail['ProjAlgo'] = showAlgorithm($projectDetail['ProjAlgo']);
        $projectDetail['ProjTypeName'] = ucwords($projectDetail['ProjTypeName']);
        $projectDetail['preMinedValue'] = '';
        if ($projectDetail['ProjSponsored'] && $projectDetail['ProjPreMined'] != 0) {
            $projectDetail['preMinedValue'] = number_format($projectDetail['ProjPreMined'],0,'.',',').' '.$projectDetail['ProjPreMinedNote'];
        }

        $arrTwitterLink = $this->getLinkByProject($projectID, 5);
        $twitterLink = '';
        if ($arrTwitterLink != false && $projectDetail['ProjPackageID'] != 1) {
            $twitterLink = rtrim($arrTwitterLink['Link'], '/');
        }


        $this->layoutData['headerImage'] = false;
        if ($projectDetail['ProjSponsored']) {
            $projectHeaderImagePNG = FCPATH . 'public/uploads/project_headers/' . $projectDetail['ProjID'] . '.png';
            $projectHeaderImageJPG = FCPATH . 'public/uploads/project_headers/' . $projectDetail['ProjID'] . '.jpg';

            $dbPath = FCPATH . 'public/uploads/project_headers/' . $projectDetail['ProjHeaderImage'];
            if ($projectDetail['ProjHeaderImage'] != '' && file_exists($dbPath)) {
                $this->layoutData['headerImage'] = base_url() . 'public/uploads/project_headers/' . $projectDetail['ProjHeaderImage'];
            } else if (file_exists($projectHeaderImagePNG)) {
                $this->layoutData['headerImage'] = base_url() . 'public/uploads/project_headers/' . $projectDetail['ProjID'] . '.png';
            } else if (file_exists($projectHeaderImageJPG)) {
                $this->layoutData['headerImage'] = base_url() . 'public/uploads/project_headers/' . $projectDetail['ProjID'] . '.jpg';
            }
        }

        $projectDetail['projectLogo'] = 'https://api.coinschedule.com/logos/'.$projectDetail['ProjID'].'.png';

        $this->layoutData['defaultText'] = 'Not Supplied';
        $this->layoutData['twitterLink'] = $twitterLink;
        $this->layoutData['projectPlatform'] = $projectPlatform;
        $this->layoutData['projWebsite'] = $projWebsite;
        $this->layoutData['crowdFundDesc'] = $crowdFundDesc;
        $this->layoutData['isInitializeClock'] = $isInitializeClock;
        $this->layoutData['initializeClock'] = $initializeClock;
        $this->layoutData['scriptEndDate'] = $scriptEndDate;
        $this->layoutData['timerDate'] = $timerDate;

        $this->layoutData['start'] = $start;
        $this->layoutData['startDateAttr'] = $startDateAttr;
        $this->layoutData['startDays'] = $startDays;
        $this->layoutData['startHours'] = $startHours;
        $this->layoutData['startMins'] = $startMins;
        $this->layoutData['startSecs'] = $startSecs;
        $this->layoutData['end'] = $end;
        $this->layoutData['endDateAttr'] = $endDateAttr;
        $this->layoutData['endDays'] = $endDays;
        $this->layoutData['endHours'] = $endHours;
        $this->layoutData['endMins'] = $endMins;
        $this->layoutData['endSecs'] = $endSecs;

        $this->layoutData['projectDetail'] = $projectDetail;
        $this->layoutData['projName'] = $projectDetail['ProjName'];
        $this->layoutData['projTitle'] = $projectTitle;
        $this->layoutData['projImg'] = $projImg;
        $this->layoutData['isFullTitle'] = true;


        $metaTitle = $eventName && !$projectDetail['EventDeleted'] ? $eventName : $projectTitle;
        $projectName = $eventName && !$projectDetail['EventDeleted'] ? $eventName : $projectTitle;
        if ($segmentLinked == 'projects') {
            $isLinkedProject = true;
            $metaTitle .= ' ' . $projectDetail['ProjTypeName'];
            $description = 'List of ICOs and Token Sales by ' . $projectName . ': ' . truncate($projectDetail['ProjDesc'], 250);
            $this->layoutData['mainProjectName'] = $projectTitle;

            $this->layoutData['head']['canonical'] = canonicalProject($projectDetail['ProjID']);
            $this->layoutData['head']['alternate'] = $this->layoutData['head']['canonical'];
        } else {
            $isLinkedProject = false;
            $metaTitle .= ' ('.$projectDetail['ProjSymbol'].')';

            $eventStartDate = ' ';
            if ($projectDetail['EventStartDate'] && (int) $projectDetail['EventStartDate'] != 0) {
                $eventStartDate = ' ' . date('M jS Y', strtotime($projectDetail['EventStartDate']));
            }
            $description = 'Everything about ' . $projectName . ' starting on' . $eventStartDate . ': Dates, timer, white paper, team, open discussions with the community and a lot more - Coinschedule the most trusted source of information for ICOs';
            $this->layoutData['mainProjectName'] = $projectDetail['EventName'].($projectDetail['ProjSymbol']==''?'':' ('.$projectDetail['ProjSymbol'].')');

            $this->layoutData['head']['canonical'] = canonicalEvent($projectDetail['EventID'], $projectDetail['EventName']);
            $this->layoutData['head']['alternate'] = $this->layoutData['head']['canonical'];
        }

        if (strpos($metaTitle, 'ICO') === false && strpos($metaTitle, 'Token Sale') === false) {
            $metaTitle .= ' ICO';
        }
        $metaTitle .= ' - Coinschedule';

        $this->layoutData['isLinkedProject'] = $isLinkedProject;
        $this->layoutData['head']['title'] = $metaTitle;
        $this->layoutData['head']['description'] = $description;
    }

    private function renderICO($filterID)
    {
        if ($filterID) {
            $filterData = $this->FilterModel->getFilterById($filterID);
            if (empty($filterData)) {
                redirect(''); exit();
            }
            $filter = $filterData['Filter'];
            $filters = explode(',', $filter);

            $icoData = $this->ProjectModel->getICO($filters);
        } else {
            $icoData = $this->cache->redis->get('cachedICOData');
            if (!$icoData) {
                $icoData = $this->ProjectModel->getICO();
                if (!empty($icoData)) {
                    $this->cache->redis->save('cachedICOData', $icoData, CACHE_EXPIRE);
                }
            }
        }

        foreach ($icoData as $key => &$ico) {
            $endDate = strtotime($ico['EventEndDate']);
            // Remove if ICO is over
            if (time() > $endDate && $ico['EventStartDateType'] ==1 ) {
                unset($icoData[$key]);
            } else {
                // Live ICO
                $startDate = strtotime($ico['EventStartDate']);
                if (time() >= $startDate && $ico['EventStartDateType'] == 1) {
                    // Percent Done
                    $ico['Percent'] = ((time() - $startDate)/($endDate-$startDate))*100;
                    $ico['Status'] = 1;
                } else {
                    // Percent Done
                    $ico['Percent'] = 0;
                    $ico['Status'] = 2;
                }
            }
        }

        // Sort Upcoming ICOs
        $upComing = $icoData;
        array_multisort(
            array_column($upComing, 'Status'),  SORT_DESC,
            array_column($upComing, 'ProjTopOfUpcoming'), SORT_DESC,
            array_column($upComing, 'EventStartDateType'), SORT_ASC,
            array_column($upComing, 'EventStartDate'), SORT_ASC,
            $upComing
        );

        // Sort LIVE ICOs
        array_multisort(
            array_column($icoData, 'Status'),  SORT_ASC,
            array_column($icoData, 'ProjPackage'), SORT_DESC,
            array_column($icoData, 'Percent'), SORT_DESC,
            $icoData
        );

        $this->renderLiveICOSection($icoData);
        $this->renderUpComingSection($upComing);
    }

    private function renderFilterPanelCate($filterPanel)
    {
        $ssFilterName = '';
        if ($this->session->has_userdata('ssFilter') && $this->session->userdata('ssFilter')) {
            $ssFilter = $this->session->userdata('ssFilter');
            $ssFilterName = $ssFilter['filterName'];
        }

        $this->layoutData['filterPanel'] = '';
        foreach ($filterPanel as $filter) {
            if ($filter['FilterSection'] == 'Cat') { break; }

            $filterName = $filter['FilterName'];
            $filterImg = $filter['FilterImage'];
            $filterText = $filter['FilterText'];
            
            $this->layoutData['filterPanel'] .=
                '<div class="filteritem">
                    <table>
                        <tr>
                          <td>
                            <input name="'.$filterName.'" id="'.$filterName.'" type="checkbox" '.(strpos($ssFilterName, $filterName.',')!==false?'checked':'').'>
                          </td>
                          '.($filterImg?'<td onClick="togglechk(\''.$filterName.'\');">
                            <img class="inline" src="data:image/png;base64,'.$filterImg.'" alt="'.escape_string($filterText).' Logo"></td>':'').'
                          <td onClick="togglechk(\''.$filterName.'\');" class="filteritemtext">
                            '.escape_string($filterText).'
                          </td>
                        </tr>
                    </table>
                </div>';
        }
    }

    private function renderFilterPanelPlat($filterPanel)
    {
        $ssFilterName = '';
        if ($this->session->has_userdata('ssFilter') && $this->session->userdata('ssFilter')) {
            $ssFilter = $this->session->userdata('ssFilter');
            $ssFilterName = $ssFilter['filterName'];
        }

        $this->layoutData['filterPanelCategory'] = '';
        foreach ($filterPanel as $filter) {

            if ($filter['FilterSection'] == 'Plat') { continue; }

            $filterName = $filter['FilterName'];
            $filterText = $filter['FilterText'];

            $this->layoutData['filterPanelCategory'] .=
                '<div class="filteritem">
                    <table>
                        <tr>
                          <td>
                            <input name="'.$filterName.'" id="'.$filterName.'" type="checkbox" '.(strpos($ssFilterName, $filterName.',')!==false?'checked':'').'>
                          </td>
                          <td onClick="togglechk(\''.$filterName.'\');" class="filteritemtext">
                            '.escape_string($filterText).'
                          </td>
                        </tr>
                    </table>
                </div>';
        }
    }

    private function renderUpComingSection($upComingProject)
    {
        $this->layoutData['upComingProject'] = '';
        if (empty($upComingProject)) {
            return false;
        }

        $htmlRender = '';
        $topOfUpcoming = 0;
        foreach ($upComingProject as $upComing) {

            if ($upComing['Status']==1) { break; }

            // Dates
            if ($upComing['EventStartDateType'] == 1) {
                $startDate = date_create($upComing['EventStartDate']);
                $start = dateFormatUTC($startDate);
                $dateNumberFrom = date("U",strtotime($upComing['EventStartDate']));
                $secondsLeftFrom = $dateNumberFrom-date("U");
                $dateLeftFrom = timeToDate($secondsLeftFrom);
            } else {
                $start = "TBA";
                $dateLeftFrom = "TBA";
            }

            // Sponsored Icon
            if ($upComing['ProjPlatinum']) {
                $sponsoredStar = '<img src="'.$this->layoutData['config']['icon_plat_badge'].'" class="inline tooltip" title="Platinum Level Event" alt="Platinum Level Event">';
            } else if($upComing['ProjPackage']==1) {
                $sponsoredStar = '<img src="'.$this->layoutData['config']['icon_silver_badge'].'" class="inline tooltip" title="Silver Project" alt="Silver Project">';
            } else {
                $sponsoredStar = ($upComing['ProjSponsored'] && $upComing['ProjDisableRibbon'] == 0 ? '<img src="'.$this->layoutData['config']['icon_gold_badge'].'" class="inline tooltip" title="Gold Project" alt="Gold Project">':'');
            }

            $url = slugEvent($upComing['EventID'], $upComing['EventName']);
            $targetLink = '_self';
            $onClickLink = 'onclick="window.location=\''.$url.'\'"';
            if ($upComing['ProjDirectLink'] == 1) {
                $projectWebsite = $this->getLinkByProject($upComing['ProjID'], 1);
                if (!empty($projectWebsite)) {
                    $url = $projectWebsite['Link'];
                    $targetLink = '_blank';
                    $onClickLink = 'onclick="window.open(\''.$url.'\',\'_blank\')"';
                }
            }

            if (!$upComing['ProjTopOfUpcoming'] && $topOfUpcoming == 1) {
                $htmlRender .= '<tr><td></td><td></td><td></td><td></td></tr>';
                $topOfUpcoming = 0;
            }

            $isBold = false;
            if ($upComing['ProjPlatinum'] || $upComing['ProjHighlighted'] == 1) {
                $isBold = true;
            }

            if ($upComing['ProjPlatinum']) {
                $imageLogo = ($upComing['ProjImageLarge']?'<img class="projlogo" src="data:image/png;base64,'.$upComing['ProjImageLarge'].'" height="48" width="48" alt="'.$upComing['EventName'].' Logo">':'');
            } else {
                $imageLogo = ($upComing['ProjImage']?'<img class="projlogo" src="data:image/png;base64,'.$upComing['ProjImage'].'" height="16" width="16" alt="'.$upComing['EventName'].' Logo">':'');
            }
            
            $htmlRender .=
                '<tr '.($upComing['ProjSponsored'] || $isBold ? ' class="tr-upBold"':'').' '.$onClickLink.' >
                    <td class="tooltip td1" title="<b>'.escape_string($upComing['EventName']).'</b><br>'.htmlentities($upComing['ProjDesc']).'">
                        <table class="link">
                            <tr>
                            <td class="td5">
                            '.$imageLogo.'
                            </td>
                            <td>
                                <a target="'.$targetLink.'" href="'.$url.'">'.$upComing['EventName'].'</a>
                                '.$sponsoredStar.'
                                <div class="details">'.$start.'</div>
                            </td>
                            </tr>
                        </table>
                    </td>
                    <td class="td2">
                    '.$upComing['ProjCatName'].'
                    </td>
                    <td class="td3">
                    '.$start.'
                    </td>
                    <td class="td4">
                    '.$dateLeftFrom.'
                    </td>
                </tr>';

                if ($upComing['ProjTopOfUpcoming']) {
                    $topOfUpcoming = 1;
                }
        }

        $this->layoutData['upComingProject'] = $htmlRender;
    }

    private function renderPlatinumSection($platinumProject)
    {
        $this->layoutData['isPlatinumSection'] = false;
        if (!empty($platinumProject)) {
            $this->layoutData['isPlatinumSection'] = true;
            $this->layoutData['platinumProject'] = $platinumProject;

            $projWebsite = $this->getLinkByProject($platinumProject['ProjID'], 1);
            $this->layoutData['platUrlTarget'] = '_self';
            if (!empty($projWebsite) && $platinumProject['ProjDirectLink'] == 1) {
                $this->layoutData['platUrl'] = $projWebsite['Link'];
                $this->layoutData['platUrlTarget'] = '_blank';
            } else {
                $this->layoutData['platUrl'] = slugEvent($platinumProject['EventID'], $platinumProject['EventName']);
            }

            $startStamp = strtotime($platinumProject['EventStartDate']);
            $startDate = date_create($platinumProject['EventStartDate']);
            $startFormat = date_format($startDate,"M jS Y").(date_format($startDate,"H:i")!="00:00"?' '.date_format($startDate,"H:i").' UTC':'');

            $endStamp = strtotime($platinumProject['EventEndDate']);
            $endDate = date_create($platinumProject['EventEndDate']);
            $endFormat = date_format($endDate,"M jS Y").(date_format($endDate,"H:i")!="00:00"?' '.date_format($endDate,"H:i").' UTC':'');

            $timerDate = (time() < $startStamp ? $startDate : $endDate);
            $timerDate = date_format($timerDate,'Y').",".(date_format($timerDate,'n')-1).",".date_format($timerDate,'d,H,i,s');

            $this->layoutData['timerDate'] = $timerDate;
            $this->layoutData['startFormat'] = $startStamp > time() ? 'Starts ' . $startFormat : 'Ends ' . $endFormat;
            $this->layoutData['startClockID'] = $startStamp > time() ? 'startclock' : 'endclock';
            $this->layoutData['endDateJS'] = date_format($endDate,'D M d Y H:i:s');
            $this->layoutData['isInitializeClock'] = time() < $endStamp;
        }
    }

    private function renderLiveICOSection($liveICOProject)
    {
        $this->layoutData['liveICOSection'] = '';
        if (empty($liveICOProject)) {
            return false;
        }

        $liveIcovView = isset($_GET['live_view'])!=""?$_GET['live_view']:(isset($_COOKIE["live_icoview"])?$_COOKIE["live_icoview"]:3);
        if ($liveIcovView == 2) {
            $this->renderLiveICOList($liveICOProject);
        } else {
            $this->renderLiveICOPlates($liveICOProject);
        }
    }

    private function renderLiveICOList($liveICOProject)
    {
        $this->layoutData['liveICOSection'] = '<div>
                                     <div class="live upcoming list-table div-upcoming">
                                        <div class="list-container">
                                          <table>
                                          <thead>
                                          <tr>
                                            <th class="th1">Name<div>Name</div></th>
                                            <th>Category<div>Category</div></th>
                                            <th class="th2">End Date<div>End Date</div></th>
                                            <th>Ends In<div>Ends In</div></th>
                                          </tr>
                                          </thead>
                                          <tbody>';


        foreach ($liveICOProject as $liveico) {
            if ($liveico['Status']==2) { break; }

            $enddate = date_create($liveico['EventEndDate']);
            $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':'');
            $date_number_from=date("U",strtotime($liveico['EventEndDate']));
            $seconds_left_from=$date_number_from-date("U");
            $date_left_from=timeToDate($seconds_left_from);

            // Tooltip
            $tooltip = "<center><b>".$liveico['EventName']."</b><br>".htmlentities($liveico['ProjDesc'])."<br><br><B>Ends $end</b></center>";

            // Url
            $url = slugEvent($liveico['EventID'], $liveico['EventName']);
            $targetLink = '_self';
            $onClickLink = 'onclick="window.location=\''.$url.'\'"';
            if ($liveico['ProjDirectLink'] == 1) {
                $projectWebsite = $this->getLinkByProject($liveico['ProjID'], 1);
                if (!empty($projectWebsite)) {
                    if ($liveico['ProjAffilLinks']) {
                        $url = base_url() . "link?fl=".urlencode($projectWebsite['Link']);
                    } else {
                        $url = $projectWebsite['Link'];
                    }
                    $targetLink = '_blank';
                    $onClickLink = 'onclick="window.open(\''.$url.'\',\'_blank\')"';
                }
            }

            // Sponsored Icon
            if ($liveico['ProjPlatinum']) {
                $sponsoredstar='';
            } else if($liveico['ProjPackage']==1) {
                $sponsoredstar = '<img class="liveImg" src="'.$this->layoutData['config']['icon_silver_badge'].'" title="Silver Project" alt="Silver Project">';
            } else {
                $sponsoredstar = ($liveico['ProjSponsored']&&$liveico['ProjDisableRibbon']==0?'<img src="'.$this->layoutData['config']['icon_gold_badge'].'" class="tooltip liveImg" title="Gold Project" alt="Gold Project">':'');
            }

            $projectImg = '';
            if ($liveico['ProjPlatinum'] && $liveico['ProjImageLarge'] != '') {
                $projectImg = '<img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" alt="'.$liveico['EventName'].' Logo">';
            } else if (!$liveico['ProjPlatinum'] && $liveico['ProjImage'] != '') {
                $projectImg = '<img class="projlogo" src="data:image/png;base64,'.$liveico['ProjImage'].'" height="16" width="16" alt="'.$liveico['EventName'].' Logo">';
            }

            $this->layoutData['liveICOSection'] .= '
                    <tr '.($liveico['ProjSponsored']?' class="'.($liveico['ProjPlatinum']?'tr2':'tr1').'"':'').' '.$onClickLink.'>
                      <td  class="tooltip" title="'.escape_string($tooltip).'">
                        <table class="link">
                        <tr>
                          <td class="'.($liveico['ProjPlatinum']?'td2':'td1').'">
                            '.$projectImg.'
                          </td>
                          <td>
                            <a target="'.$targetLink.'" href="'.$url.'">'.escape_string($liveico['EventName']).'</a>
                            '.$sponsoredstar.'
                            <div class="details">'.$end.'</div>
                          </td>
                        </tr>
                        </table>
                      </td>

                      <td class="td3">
                        '.$liveico['ProjCatName'].'
                      </td>
                      <td class="td4">
                        '.$end.'
                      </td>
                      <td class="td5">
                        '.$date_left_from.'
                      </td>
                    </tr>';
        }


        $this->layoutData['liveICOSection'] .= '</tbody></table></div></div>';
        $this->layoutData['liveICOSection'] .= '<div class="disclaimer">Note: This is not investment advice. By using Coinschedule you agree to our Disclaimer. <a href="'.base_url().'disclaimer.html" target="_blank">FULL DISCLAIMER</a></div></div>';
    }

    private function renderLiveICOPlates($liveICOProject)
    {
        $this->layoutData['liveICOSection'] = '<div class="liveicos">';
        $lastGoldReached = false;
        $lastSilvReached = false;
        $widget = false;
        foreach ($liveICOProject as $liveico) {

            if ($liveico['Status'] == 2) { break; }

            // Percentage Time
            $percentTime = $liveico['Percent'];
            if ($percentTime > 100) {
                $percentTime = 100;
            }
            $percentTime = number_format($percentTime, 2);

            // End Date
            $endDate = date_create($liveico['EventEndDate']);
            $end = date_format($endDate, "M jS Y") . (date_format($endDate, "H:i") != "00:00" ? ' ' . date_format($endDate, "H:i") . ' UTC' : '');

            // Tooltip
            $tooltip = "<center><b>".$liveico['EventName']."</b><br>".htmlentities($liveico['ProjDesc'])."<br><br><B>Ends $end</b></center>";

            // Url
            $url = slugEvent($liveico['EventID'], $liveico['EventName']);
            $targetLink = '_self';
            if ($liveico['ProjDirectLink'] == 1) {
                $projectWebsite = $this->getLinkByProject($liveico['ProjID'], 1);
                if (!empty($projectWebsite)) {
                    if ($liveico['ProjAffilLinks']) {
                        $url = base_url() . "link?fl=".urlencode($projectWebsite['Link']);
                    } else {
                        $url = $projectWebsite['Link'];
                    }
                    $targetLink = '_blank';
                }
            }

            $projectImg = '';
            if ($liveico['ProjImageLarge'] != '') {
                $projectImg = '<img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" alt="'.$liveico['EventName'].' Logo"/>';
            }
            // GOLD
            if ($liveico['ProjSponsored']) {
                $this->layoutData['liveICOSection'] .= '<div class="icobox gold tooltip" title="'.escape_string($tooltip).'">
                    <a target="'.$targetLink.'" href="'.$url.'">
                        <table>
                            <tr>
                                <td class="icobox-text">
                                    '.$projectImg.'
                                    <h4>'.$liveico['EventName'].'</h4>
                                    <div class="'.($percentTime > 90 ? 'red' : '').($percentTime <= 10 ? 'green' : '').' done">
                                        <b>'.$percentTime.'% done</b>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="category">
                                    '.strtoupper($liveico['ProjCatName']).'
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>';
            } else if ($liveico['ProjPackage'] == 1) { // SILVER
                if (!$lastGoldReached) {
                    $lastGoldReached = 1;
                    $this->layoutData['liveICOSection'] .= '<div class="liveSilver"></div>';
                }

                $this->layoutData['liveICOSection'] .=
                    '<div class="icobox silver tooltip" title="'.escape_string($tooltip).'">
                        <a target="'.$targetLink.'" href="'.$url.'">
                        <table>
                            <tr>
                                <td>
                                    '.$projectImg.'
                                </td>
                                <td class="icobox-text">
                                    <h4>'.$liveico['EventName'].'</h4>
                                    <div class="'.($percentTime > 90 ? 'red' : '').($percentTime <= 10 ? 'green' : '').' done"><b>'.$percentTime.'% done</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="category" colspan="2">
                                    '.strtoupper($liveico['ProjCatName']).'
                                </td>
                            </tr>
                        </table>
                        </a>
                    </div>';
            } else { // STANDARD
                if (!$lastSilvReached) {
                    $lastSilvReached = 1;
                    $this->layoutData['liveICOSection'] .= '<div class="liveStandard"></div>';
                }

                $this->layoutData['liveICOSection'] .=
                    '<div class="icobox standardbox standard tooltip '.($liveico['ProjHighlighted']?'highlighted':'').'" title="'.$tooltip.'">
                        <a target="'.$targetLink.'" href="'.$url.'">
                            <table>
                                <tr>
                                <td class="td1">
                                  <h4 class="tooltip_new '.($liveico['ProjHighlighted']?' td1h':'').'" title="'.escape_string($liveico['ProjDesc']).'">'.$liveico['EventName'].'</h4>
                                  <p class="tooltip_new p-color '.($percentTime>90?'red':'').($percentTime<=10?'green':'').'" title="This ICO has already gone through '.$percentTime.'% of its planned crowdfunding time"><b>'.$percentTime.'% done</b></p>
                                </td>
                                </tr>
                                <tr><td class="category '.($liveico['ProjHighlighted']?' td2':'').'">'.($liveico['ProjCatName']).'</td></tr>
                            </table>
                        </a>
                    </div>';
            }

        }

        $this->layoutData['liveICOSection'] .= '<div class="disclaimer">Note: This is not investment advice. By using Coinschedule you agree to our Disclaimer. <a href="'.base_url().'disclaimer.html" target="_blank">FULL DISCLAIMER</a></div></div>';
    }

    private function renderFromBlog($blogPost)
    {
        $this->layoutData['blogSection'] = '';
        if (empty($blogPost)) {
            return false;
        }

        foreach ($blogPost as $post) {
            $imageUrl = $post["_embedded"]["wp:featuredmedia"][0]["media_details"]["sizes"]["medium"]["source_url"];
            $image = 'https://www.coinschedule.com/' . trim(parse_url($imageUrl, PHP_URL_PATH), "/");
            $imageHtml = '';

            if ($image) {
                $imageHtml = ' <a href="'.$post['link'].'" target="_blank"><img src="'.$image.'" alt="'.$post['title']['rendered'].'"></a>';
            }
            $date = date_create($post['date']);
            $date = date_format($date,"jS F Y");
            $sentence = strip_tags($post['content']['rendered']);
            $sentence = getWords($sentence, 50 + (700/strlen($post['title']['rendered'])));

            $this->layoutData['blogSection'] .=
                '<div class="blogpost inline">
                    <table>
                    <tr>
                      <td>
                        '.$imageHtml.'
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <a href="'.$post['link'].'" target="_blank">
                          <h3>'.$post['title']['rendered'].'</h3>
                        </a>
                        <span class="posted_on">Posted On '.$date.'</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="details">
                        <em>'.$sentence.'</em>
                      </td>
                    </tr>
                    </table>
                 </div>';
        }
    }

    private function renderEventHomepage($events)
    {
        $this->layoutData['eventSection'] = '';
        if (empty($events) || !is_array($events)) {
            return false;
        }

        foreach ($events as $event) {

            $startDate = date_create($event['EventStartDate']);
            $date = '';
            if ($event['EventEndDate'] != '0000-00-00 00:00:00' && (int) $event['EventEndDate'] != 0) {
                $endDate = date_create($event['EventEndDate']);
                if (date_format($startDate, "M") == date_format($endDate, "M")) {
                    $date = date_format($startDate,"M jS")." - ".date_format($endDate,"jS")." ".date_format($startDate,"Y");
                }
            } else {
                $date = date_format($startDate,"M jS Y");
            }
            $sponsoredStar = (isset($event['ProjSponsored']) && $event['ProjSponsored']) ? '<span class="glyphicon glyphicon-star tooltip_new label sponsoredStar" title="Sponsored project"> </span>' : '';

            $boldStyle = '';
            $eventName = $event['EventName'];
            if ($event['EventFeatured']) {
                $boldStyle = 'class="bold"';
                $eventName = $event['EventName'] . ' - FEATURED';
            }
            $eventName = escape_string($eventName);

            $this->layoutData['eventSection'] .=
                '<tr onclick="window.open(\''.$event['EventWebsite'].'\')" '.$boldStyle.'>
                  <td class="td1">
                    <table class="link">
                    <tr>
                      <td class="td2">
                        '.($event['EventImage']?'<img class="eventlogo" src="data:image/png;base64,'.$event['EventImage'].'" height="16" width="16" alt="'.str_replace('"','',$eventName).' Logo">':'').'
                      </td>
                      <td>
                        <a href="'.$event['EventWebsite'].'" target="_blank" rel="nofollow">'.$eventName.'</a>
                        '.$sponsoredStar.'
                        <div class="details">'.$date.'<br>'.$event['EventLocation'].'</div>
                      </td>
                    </tr>
                    </table>
                  </td>
                  <td class="td3">
                    '.$date.'
                  </td>
                  <td>
                    '.$event['EventLocation'].'
                  </td>
                </tr>';
        }
    }

    private function renderPressMentions($pressMentions)
    {
        $this->layoutData['pressMentionSection'] = '';
        if (empty($pressMentions)) {
            return false;
        }

        foreach ($pressMentions as $press) {
            $this->layoutData['pressMentionSection'] .=
                '<div class="press inline">
                    <a href="'.$press['PressLink'].'" rel="nofollow" target="_blank"><img src="'.base_url().'public/uploads/press/'.$press['PressImage'].'" alt="'.escape_string($press['PressName']).' Logo" width="189" height="59"></a>
                </div>';
        }
    }

    private function renderSimilarIco($projectDetail)
    {
        $this->layoutData['htmlSimilarIco'] = false;
        $similarIco = $this->ProjectModel->getSimilarIco($projectDetail);

        if (!empty($similarIco)) {
            foreach ($similarIco as $key => &$sim) {
                $endDate = strtotime($sim['EventEndDate']);

                // Remove if ICO is over
                if (time() > $endDate && $sim['EventStartDateType'] ==1 ) {
                    unset($similarIco[$key]);
                } else {
                    // Live ICO
                    $startDate = strtotime($sim['EventStartDate']);
                    if (time() >= $startDate && $sim['EventStartDateType'] == 1) {
                        // Percent Done
                        $sim['Percent'] = ((time() - $startDate)/($endDate-$startDate))*100;
                        $sim['Status'] = 1;
                    } else {
                        // Percent Done
                        $sim['Percent'] = 0;
                        $sim['Status'] = 2;
                        unset($similarIco[$key]);
                    }
                }
            }

            $sortPercent = array_column($similarIco, 'Percent');
            array_multisort(
                $sortPercent, SORT_DESC,
                $similarIco
            );

            foreach ($similarIco as $key => $item) {
                // Percentage Time
                $percentTime = $item['Percent'];
                if ($percentTime > 100) {
                    $percentTime = 100;
                }
                $percentTime = number_format($percentTime, 2);

                // Url
                $url = slugEvent($item['EventID'], $item['EventName']);
                $targetLink = '_self';
                if ($item['ProjDirectLink'] == 1) {
                    $projectWebsite = $this->getLinkByProject($item['ProjID'], 1);
                    if (!empty($projectWebsite)) {
                        if ($item['ProjAffilLinks']) {
                            $url = base_url() . "link?fl=".urlencode($projectWebsite['Link']);
                        } else {
                            $url = $projectWebsite['Link'];
                        }
                        $targetLink = '_blank';
                    }
                }

                $projectImg = '';
                if ($item['ProjImageLarge'] != '') {
                    $projectImg = '<img src="data:image/png;base64,'.$item['ProjImageLarge'].'" alt="'.escape_string($item['EventName']).' Logo"/>';
                }

                $this->layoutData['htmlSimilarIco'] .=
                        '<div class="item">
                            <div class="logo-container">
                                <a href="'.$url.'">'.$projectImg.'</a>
                            </div>
                            <div class="info-container">
                                <a class="l-similar title" href="'.$url.'" target="'.$targetLink.'" title="'.escape_string($item['EventName']).'">'.escape_string($item['EventName']).'</a>
                                <span class="description">'.$percentTime.'% done</span>
                            </div>
                        </div>';
            }


        }
    }

    private function renderFeaturedPosts($projectDetail)
    {
        $this->load->model('Data_model', 'DataModel');
        $projectID = $projectDetail['ProjID'];
        $posts = $this->DataModel->getFeaturedPost($projectID);
        $this->layoutData['featuredPost'] = false;

        if (!empty($posts)) {

            foreach ($posts as $item) {
                $url = 'https://www.coinschedule.com/blog/' . ltrim($item['permalink'], '/');
                if ($item['category'] != '') {
                    $htmlTitle = '<span class="title">'.dateFormatBlog($item['post_date']) . ' - ' . escape_string($item['category']) . ': ' .escape_string($item['post_title']).'</span>';
                } else {
                    $htmlTitle = '<span class="title">'.dateFormatBlog($item['post_date']) . ' - ' .escape_string($item['post_title']).'</span>';
                }

                $this->layoutData['featuredPost'] .=
                    '<div class="item">
                        <a class="blog-post" href="'.$url.'" title="'.escape_string($item['post_title']).'">
                            '.$htmlTitle.'
                        </a>
                    </div>';
            }
        }
    }
}
