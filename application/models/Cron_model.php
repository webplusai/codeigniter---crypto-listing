<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Cron_model extends MY_Model
{
    protected $table = 'tbl_crons';

    public function __construct()
    {
        parent::__construct();
    }

    public function getIcoTotalLive($platWaves)
    {
        $where = '';
        if ($platWaves) {
            $where = "and CONCAT(',',ProjPlatform,',') LIKE '%,11,%'";
        }

        $sql = "Select count(*) as Total
                  From tbl_events E
                  inner join tbl_projects P
                  On E.EventProjID = P.ProjID
                  Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1
                  and EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP
                  and EventEndDate > UTC_TIMESTAMP $where";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function getUCTotalLive($platWaves)
    {
        $where = '';
        if ($platWaves) {
            $where = "and CONCAT(',',ProjPlatform,',') LIKE '%,11,%'";
        }

        $sql = "Select count(*) as Total
                From tbl_events E
                inner join tbl_projects P
                On E.EventProjID = P.ProjID
                Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1
                and (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3) $where";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function getCronIcoRank()
    {
        $sql="Select *
                From tbl_events E
                inner join tbl_projects P
                On E.EventProjID = P.ProjID
                Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3)
                UNION
                Select * from
                    (
                    Select *
                    From tbl_events E
                    inner join tbl_projects P
                    On E.EventProjID = P.ProjID
                    Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP
                    ) as E";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getCronIcoRankProject($projID)
    {
        $sql="Select *
                From tbl_events E
                inner join tbl_projects P
                On E.EventProjID = P.ProjID
                Where P.ProjID = ? and ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3)
                UNION
                Select * from
                    (
                    Select *
                    From tbl_events E
                    inner join tbl_projects P
                    On E.EventProjID = P.ProjID
                    Where P.ProjID = ? and ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP
                    ) as E";

        $query = $this->db->query($sql, array($projID, $projID));
        return $query->row_array();
    }

    public function getProjectLinks($projectID)
    {
        $sql = "Select * from tbl_links L WHERE L.LinkParentType=1 AND L.LinkParentID = ?";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getProjectPeople($projectID)
    {
        $sql = "Select * from tbl_people_projects P WHERE P.PeopleProjProjID = ?";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function getProjectLinksSt($projectID)
    {
        $sql = "Select * from tbl_links L WHERE L.LinkParentType=4 AND L.LinkType=10 AND L.LinkParentID = ?";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }

    public function setFilterPanel()
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_begin();

        $sql = "Delete from tbl_filterpanel";
        $this->db->query($sql);

        $sql = "Insert IGNORE Into tbl_filterpanel (FilterSection,FilterName,FilterText,FilterImage)
        Select DISTINCT 'Plat',CONCAT('plat_',Plat.ProjID), Plat.ProjName , Plat.ProjImage
        From tbl_events E
        inner join tbl_projects P On E.EventProjID = P.ProjID
        left join tbl_submissions S ON S.SubProjID = P.ProjID
        left join tbl_projects Plat on Plat.ProjID = P.ProjPlatform
        Where P.ProjType = 2 and Plat.ProjName IS NOT NULL and P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16
        and (S.SubStatus = 2 or S.SubStatus IS NULL) and
        EventEndDate > UTC_TIMESTAMP
        Order By ProjName";
        $this->db->query($sql);


        $sql = "Insert IGNORE Into tbl_filterpanel (FilterSection,FilterName,FilterText,FilterImage)
        Select DISTINCT 'Plat','plat_0', 'New Blockchain' , ''
        From tbl_events E
        inner join tbl_projects P On E.EventProjID = P.ProjID
        left join tbl_submissions S ON S.SubProjID = P.ProjID
        Where ProjType = 1 and P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16
        and (S.SubStatus = 2 or S.SubStatus IS NULL) and
        EventEndDate > UTC_TIMESTAMP";
        $this->db->query($sql);


        $sql = "Insert IGNORE Into tbl_filterpanel (FilterSection,FilterName,FilterText)
        Select DISTINCT 'Cat',CONCAT('cat_',P.ProjCatID), PC.ProjCatName
        From tbl_events E
        inner join tbl_projects P On E.EventProjID = P.ProjID
        left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
        left join tbl_submissions S ON S.SubProjID = P.ProjID
        Where P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16
        and (S.SubStatus = 2 or S.SubStatus IS NULL) and
        EventEndDate > UTC_TIMESTAMP
        Order By ProjCatName";
        $this->db->query($sql);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function deleteOldRecords()
    {
        $sql = "DELETE FROM `tbl_crons` WHERE date < (NOW() - INTERVAL 2 DAY)";
        $query = $this->db->query($sql);
    }

    public function setProjPackage()
    {
        $sql = "Update `tbl_projects` Set ProjPackageID = CASE WHEN `ProjPlatinum`=1 THEN 5 WHEN `ProjPackage` = 2 THEN 4  WHEN `ProjPackage` = 1 THEN 3 WHEN `ProjHighlighted` = 1 THEN 2 ELSE 1 END";
        $this->db->query($sql);
    }

    public function getSubmissionNotPaidRequestServer()
    {
        $sql = "Select id_user, tx_email, PayID, PaySecret, SubHashCode,PayAmount,PayAddress,SubCoinName from tbl_submissions S
                INNER JOIN tbl_users U ON S.SubUsers = U.id_user
                INNER JOIN tbl_payments P on S.SubID = P.SubID
                WHERE Year(PayCreatedDate)=2018 and PayScreenLastViewed IS NOT NULL and PayStatus = '0' and PayRequestServer = 0 ORDER BY PayID";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

?>