<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class People_project_model extends MY_Model
{
    protected $table = 'tbl_people_projects';

    public function __construct()
    {
        parent::__construct();
    }

    public function reorderPeopleList($id)
    {
        $sql = "SELECT * FROM `tbl_people_projects` WHERE `PeopleProjProjID` = ? ORDER BY `PeopleProjSortOrder` ASC";
        $query = $this->db->query($sql, array($id));
        $teamData = $query->result_array();
        $order = 1;
        foreach ($teamData as $member) {
            $member['PeopleProjSortOrder'] = $order;
            $this->update($member, array('PeopleProjID' => $member['PeopleProjID']));
            $order ++;
        }
    }

    public function setTeamGroup()
    {
        $sql = "Update tbl_people_projects Set PeopleProjGroupID = 2 WHERE PeopleProjPosition LIKE '%Advisor%' or PeopleProjPosition LIKE '%Adviser%'";
        $this->db->query($sql);

        $sql = "Update `tbl_people_projects` Set `PeopleProjGroupID` = 3 WHERE `PeopleProjPosition` LIKE '%Dev Team%' or `PeopleProjPosition` LIKE '%Developer%'";
        $this->db->query($sql);
    }

    public function getPeopleProjects($arrPeopleIds) {
		$sql = "Select ProjID, ProjName, ProjImageLarge, PeopleProjPosition from tbl_people_projects PP inner join tbl_projects P on PP.PeopleProjProjID = P.ProjID where ProjDeleted = 0 and PeopleProjPeopleID IN ? Order By ProjName";
		$query = $this->db->query($sql, array($arrPeopleIds));
		return $query->result_array();
    }
}

?>
