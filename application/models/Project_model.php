<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Project_model extends MY_Model
{
    protected $table = 'tbl_projects';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getICO($filters = array())
    {
        $platFilter = '';
        $catFilter = '';

        if (!empty($filters)) {
            $newBlockchain = 0;
            foreach ($filters as $value) {
                if (strpos($value, 'plat_') !== false) {
                    if ($value=='plat_0') {
                        $newBlockchain = 1;
                    } else {
                        $platFilter .= "ProjPlatform = '".str_replace('plat_','',$value)."' or ";
                    }
                }

                if (strpos($value,'cat_') !== false) {
                    $catFilter.= 'P.ProjCatID = '.str_replace('cat_','',$value).' or ';
                }
            }

            if ($platFilter) {
                $platFilter = ' and ('.($newBlockchain?'ProjType = 1 or':'').'(ProjType = 2 and ('.substr($platFilter,0,-3).')))';
            } else if ($newBlockchain) {
                $platFilter = ' and ProjType = 1';
            }

            if ($catFilter) {
                $catFilter = " and (".substr($catFilter,0,-3).")";
            }
        }

        $icorankThreshold = $this->getSettingValue(1);
        $sql = "Select * from
                (
                  Select 
                  ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjDirectLink,ProjSponsored,ProjPlatinum,ProjDisableRibbon,ProjTopOfUpcoming,
                  EventID,EventName,ProjDesc,EventStartDate,EventEndDate,EventStartDateType,ProjCatName,ProjHighlighted,ProjAffilLinks
                  From tbl_events E 
                  inner join tbl_projects P On E.EventProjID = P.ProjID
                  left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                  left join tbl_submissions S ON S.SubProjID = P.ProjID  
                  Where ProjDeleted = 0 and EventDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > ? and (S.SubStatus = 2 or S.SubStatus IS NULL) and
                  (EventEndDate > UTC_TIMESTAMP or (EventStartDateType = 3 and DATE_ADD(ProjAddedOn, INTERVAL 3 MONTH) > UTC_TIMESTAMP)) 
                  $platFilter
                  $catFilter
                ) as E";

        $query = $this->db->query($sql, array($icorankThreshold));
        return $query->result_array();
    }

    public function getPlatinum()
    {
        $sql = "Select ProjDirectLink,ProjID,ProjImage,ProjSponsored,ProjDisableRibbon,EventName,ProjDesc,EventStartDate,EventEndDate,EventStartDateType,EventID
                From tbl_events E
                inner join tbl_projects P
                On E.EventProjID = P.ProjID
                Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjPlatinum = 1 and (EventEndDate > UTC_TIMESTAMP)
                Order By EventStartDateType,EventStartDate,ProjID,CASE WHEN EventEndDate > Now() THEN 0 ELSE 1 END,EventEndDate LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function getLiveICO()
    {
        $icorankThreshold = $this->getSettingValue(1);
        $sql = "Select * from
                      (
                          Select ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjDirectLink,ProjSponsored,ProjPlatinum,ProjDisableRibbon,EventID,EventName,ProjDesc,EventStartDate,EventEndDate,
                          ProjCatName,ProjCatColor,
                          ((unix_timestamp() - unix_timestamp(EventStartDate))/(unix_timestamp(EventEndDate)-unix_timestamp(EventStartDate)))*100 as Percent
                          From tbl_events E
                          inner join tbl_projects P On E.EventProjID = P.ProjID
                          left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                          left join tbl_submissions S ON S.SubProjID = P.ProjID
                          Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > $icorankThreshold and (S.SubStatus = 2 or S.SubStatus IS NULL) and
                          EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP
                      ) as E
                 Order By ProjPackage DESC, Percent DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getUpComingICO()
    {
        $sql = "Select ProjID,ProjImage,ProjSponsored,EventName,ProjDesc,EventStartDate
                From tbl_events E
                inner join tbl_projects P
                On E.EventProjID = P.ProjID
                Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and EventStartDate > UTC_TIMESTAMP
                Order By EventStartDate";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getEventHomepage()
    {
        $sql = "Select EventID,EventName,EventImage,EventTypeImage,EventStartDate,EventEndDate,EventWebsite,EventLocation,EventFeatured
                From tbl_events E
                inner join tbl_eventtypes ET
                On E.EventType = ET.EventTypeID
                Where EventDisabled = 0 and EventDeleted = 0 and EventType <> 1 and EventType <> 4 and EventStartDate > UTC_TIMESTAMP
                Order By EventStartDate";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPressMentionsHomepage()
    {
        $sql = "SELECT o.*
                FROM tbl_press o
                LEFT JOIN tbl_press b
                ON o.PressName = b.PressName AND o.PressDate < b.PressDate and b.PressImage <> ''
                WHERE b.PressDate is NULL and o.PressImage <> ''
                Order By PressDate DESC, PressID DESC LIMIT 24";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getProjectDetail($eventID, $projectID = false)
    {
        if ($eventID) {
            $id = $eventID;
        } else {
            $id = $projectID;
        }

        $icorankThreshold = $this->getSettingValue(1);
        $sql = "Select P.ProjID,P.ProjName,P.ProjSymbol,P.ProjDesc,P.ProjType,P.ProjImageLarge,ProjTypeName,P.ProjPackage,P.ProjICORank,
                P.ProjSponsored,P.ProjHighlighted,P.ProjPlatinum,P.ProjDisableRibbon,P.ProjLocation,P.ProjAlgo,P.ProjTotalSupp,
                P.ProjTotalSuppNote,P.ProjPreMined,P.ProjPreMinedNote,P.ProjAffilLinks,ProjCatName,ProjPageHtmlHeadCode,P.ProjPackageID,
                E.EventStartDate,E.EventName,E.EventDeleted,E.EventID,PC.ProjCatID,P.ProjDeleted,P.ProjDelisted,P.ProjHeaderImage,C.Country
              from tbl_projects P
              inner join tbl_projecttypes PT
              on P.ProjType = PT.ProjTypeID
              left join tbl_countries C on C.CountryID = P.ProjCountryID
              left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
              left join tbl_submissions S ON S.SubProjID = P.ProjID
              left join tbl_events E ON P.ProjID = E.EventProjID
              where ProjICORank > $icorankThreshold and ".($eventID?"E.EventID = ?":"P.ProjID = ?")." and (S.SubStatus = 2 or S.SubStatus IS NULL)";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    public function getProjectRates($crowdfundID)
    {
        $sql = "Select CrowdBonusName,CrowdBonusStartDate,CrowdBonusEndDate,CrowdFundBonusDateNote
                from tbl_crowdfundbonus Where CrowdBonusEventID = ? Order By CrowdBonusStartDate";
        $query = $this->db->query($sql, array($crowdfundID));
        return $query->result_array();
    }

    public function getProjectDistros($projectID)
    {
        $sql = "Select DistroDesc,DistroAmount,DistroPercent,DistroNote,ProjSymbol
                from tbl_projdistribution PD
                inner join tbl_projects P on PD.DistroProjID = P.ProjID
                Where DistroProjID = ? Order By DistroSortOrder";

        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getProjectLinks($projectID)
    {
        $sql = "Select LinkTypeID,LinkTypeName,LinkID,Link,LinkTypeImage,ProjAffilLinks
                from tbl_links L
                inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID
                inner join tbl_projects P on L.LinkParentID = P.ProjID
                where L.LinkParentType = 1 and L.LinkParentID = ? Order By LinkTypeSortOrder";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getProjectPeople($projectID)
    {
        $sql = "Select PeopleID,PeopleName,PeopleProjPosition,PeoplePicture,PeopleProjGroupID,PeopleDesc
	            from tbl_people P
                Inner join tbl_people_projects PP on P.PeopleID = PP.PeopleProjPeopleID
                where PeopleProjProjID = ?
                Order By PeopleProjSortOrder";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getProjectEvent($projectID)
    {
        $sql = "(Select EventID,EventProjID,ProjName,ProjImage,EventType,EventTypeName,
                      EventName,EventDesc,EventStartDate as EventDate,
                      EventStartDateType,EventLocation,EventEndDate,EventTypeImage
                from tbl_events E
                Inner join tbl_eventtypes ET on E.EventType = ET.EventTypeID
                Left Join tbl_projects P on E.EventProjID = P.ProjID
                where EventTypeID <> 1 and EventProjID = ?)
                Order By EventStartDate,EventID";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getPlatforms()
    {
        $sql = "Select ProjID,ProjName from tbl_projects where ProjAllowsTokens = 1 Order By ProjName";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function submissionToProject($sub)
    {
        $this->load->model('Submission_team_model', 'SubmissionTeamModel');
        $this->load->model('Project_log_model', 'ProjectLogModel');

        $this->db->trans_strict(TRUE);
        $this->db->trans_begin();

        // Update tbl_projects
        $ProjImage = '';
        $ProjImageLarge = '';
        $arrInsertPrj = array(
            'ProjCatID' => $sub['SubProjCatID'],
            'ProjUsers' => $sub['SubUsers'],
            'ProjType' => $sub['SubProjType'],
            'ProjPlatform' => $sub['SubPlatform'],
            'ProjAlgo' => $sub['SubAlgo'],
            'ProjDesc' => $sub['SubInfo'],
            'ProjName' => $sub['SubCoinName'],
            'ProjSymbol' => $sub['SubSymbol'],
            'ProjTotalSupp' => $sub['SubSupply'],
            'ProjImage' => $ProjImage,
            'ProjImageLarge' => $ProjImageLarge,
            'ProjAddedOn' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tbl_projects', $arrInsertPrj);
        $ProjID = $this->db->insert_id();

        if ($ProjID) {

            // Update tbl_submissions
            $arrUpdateSub = array('SubProjID' => $ProjID);
            $this->db->where(['SubID' => $sub['SubID']]);
            $this->db->update('tbl_submissions', $arrUpdateSub);

            // Update tbl_events
            $EventName = $sub['SubEventName'];
            $EventStartDate = '0000-00-00 00:00:00';
            $EventEndDate = '0000-00-00 00:00:00';
            $EventStartDateType = 1;
            if ($sub['SubDatesNotDefined']) {
                $EventStartDateType = 3;
            } else {
                $EventStartDate = date("Y-m-d H:i:s", strtotime($sub['SubStart']));
                $EventEndDate = date("Y-m-d H:i:s", strtotime($sub['SubEnd']));
            }

            $arrInsertEvent = array(
                'EventProjID' => $ProjID,
                'EventName' => $EventName,
                'EventStartDate' => $EventStartDate,
                'EventEndDate' => $EventEndDate,
                'EventStartDateType' => $EventStartDateType
            );
            $this->db->insert('tbl_events', $arrInsertEvent);

            // Update tbl_links
            $mappingLink = array(
                1 => 'SubLink',
                14 => 'SubWhitePaper',
                5 => 'SubTwitter',
                6 => 'SubReddit',
                9 => 'SubSlack',
                4 => 'SubBitcoinTalk'
            );

            $linkInsertBatch = [];
            foreach ($mappingLink as $key => $value) {
                if (!empty($sub[$value])) {
                    $LinkType = $key;
                    $LinkParentType = 1;
                    $Link = $sub[$value];

                    $linkInsertBatch[] = array(
                        'LinkType' => $LinkType,
                        'LinkParentType' => $LinkParentType,
                        'LinkParentID' => $ProjID,
                        'Link' => $Link
                    );
                }
            }
            $this->db->insert_batch('tbl_links', $linkInsertBatch);

            // update team member
            $this->SubmissionTeamModel->migrateTeamData($sub, $ProjID);

            // insert project logs
            $arrInsertPrj['ProjID'] = $ProjID;
            $arrInsertPrj['EventName'] = $EventName;
            $arrInsertPrj['EventStartDate'] = $EventStartDate;
            $arrInsertPrj['EventEndDate'] = $EventEndDate;
            $arrInsertPrj['EventStartDateType'] = $EventStartDateType;
            $this->ProjectLogModel->updateProjectLog('cron', $arrInsertPrj, $ProjID);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $ProjID;
    }

    public function getProjectByID($projectID)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('ProjID', $projectID);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getProjectListing($ProjID, $ProjUsers)
    {
        $sql = "Select ProjID, ProjType, ProjPlatform, ProjName, ProjSymbol, ProjDesc, ProjImage, ProjSponsored,
                      ProjImageLarge, ProjLocation, ProjTotalSupp, ProjPreMined, ProjPreMinedNote, ProjAlgo,
                      EventID, EventName, EventStartDate, EventEndDate, EventDesc, ProjYouTubeVid, EventSoftCap, 
                      EventHardCap, EventDatesNotDefined
                from tbl_projects P
                inner join tbl_events E
                on P.ProjID = E.EventProjID
                where ProjID = ? and ProjUsers = ? and EventType = 1";
        $query = $this->db->query($sql, array($ProjID, $ProjUsers));
        return $query->row_array();
    }

    public function verifyPermisison($projectID, $userID)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('ProjID', $projectID);
        $this->db->where('ProjUsers', $userID);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getProjectEventEdit($ProjID)
    {
        $sql = "Select ProjID, EventStartDate, EventEndDate, ProjType, EventName, EventDesc, ProjDesc, EventSoftCap, 
                  EventHardCap, EventDatesNotDefined
                from tbl_projects P
                inner join tbl_events E
                on P.ProjID = E.EventProjID
                where P.ProjID = ?";
        $query = $this->db->query($sql, array($ProjID));
        return $query->row_array();
    }

    public function getProjectPlatform($projectID)
    {
        $sql = "Select Plat.ProjName as Platform, Plat.ProjImage as PlatformImage 
                from tbl_projects P 
                left join tbl_projects Plat
                on FIND_IN_SET(Plat.ProjID, P.ProjPlatform) > 0
                where P.ProjPlatform <> '0' and P.ProjID = ?
                Order By Plat.ProjName";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getSitemapData()
    {
        $icorankThreshold = $this->getSettingValue(1);
        $sql = "Select EventID,EventName
            From tbl_events E
            inner join tbl_projects P On E.EventProjID = P.ProjID
            left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
            left join tbl_submissions S ON S.SubProjID = P.ProjID
            Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1
            and ProjICORank > ? and (S.SubStatus = 2 or S.SubStatus IS NULL)";
        $query = $this->db->query($sql, array($icorankThreshold));
        return $query->result_array();
    }

    public function updateProjectClick($projectId)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('ProjID' => $projectId));
        $query = $this->db->get();
        $rowData = $query->row_array();
        if (!empty($rowData)) {
            $this->update(
                ['ProjClicks' => $rowData['ProjClicks'] + 1],
                ['ProjID' => $projectId]
            );
        }
    }

    public function getSimilarIco($projectDetail)
    {
        if (!isset($projectDetail['ProjCatID']) || !$projectDetail['ProjCatID']) {
            return false;
        }

        $projectCatId = $this->db->escape($projectDetail['ProjCatID']);
        $projectId = $this->db->escape($projectDetail['ProjID']);
        $icorankThreshold = $this->getSettingValue(1);
        $buildQuery = " and PC.ProjCatID = $projectCatId ";

        $sql = "Select * from
                (
                  Select
                  ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjDirectLink,ProjSponsored,ProjPlatinum,ProjDisableRibbon,ProjTopOfUpcoming,
                  EventID,EventName,ProjDesc,EventStartDate,EventEndDate,EventStartDateType,ProjCatName,ProjHighlighted,ProjAffilLinks,PC.ProjCatID
                  From tbl_events E
                  inner join tbl_projects P On E.EventProjID = P.ProjID
                  left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                  left join tbl_submissions S ON S.SubProjID = P.ProjID
                  Where ProjDeleted = 0 and EventDeleted = 0 and EventDisabled = 0 and EventType = 1 $buildQuery
                  and ProjICORank > $icorankThreshold and (S.SubStatus = 2 or S.SubStatus IS NULL) and P.ProjID <> $projectId
                  and (EventEndDate > UTC_TIMESTAMP or (EventStartDateType = 3 and DATE_ADD(ProjAddedOn, INTERVAL 3 MONTH) > UTC_TIMESTAMP))
                ) as E LIMIT 4";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getConvertBase64()
    {
        $sql = "SELECT `ProjID`,`ProjImage`,`ProjImageLarge`,`ProjIsConvertBase64` FROM `tbl_projects` WHERE `ProjIsConvertBase64` = 0";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getProjectTypes()
    {
        $sql = "SELECT `ProjTypeID`, `ProjTypeName` FROM `tbl_projecttypes`";
        $query = $this->db->query($sql);

        $projectTypes = [];
        $projectTypes[0] = "Select";
        foreach ($query->result_array() as $row)
        {
            $projectTypes[$row['ProjTypeID']] = $row['ProjTypeName'];
        }
        unset($projectTypes[3]);   // get rid of [3 => 'Business'] before sending to controller

        return $projectTypes;
    }
}


