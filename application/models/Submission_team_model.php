<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Submission_team_model extends MY_Model
{
    protected $table = 'tbl_submissions_team';

    public function __construct()
    {
        parent::__construct();
    }

    public function reorderTeam($subID)
    {
        $subTeamData = $this->getData(['SubID' => $subID], false, false, ['SubTeamNumber' => 'ASC']);
        if (!empty($subTeamData)) {
            $arrUpdate = array();
            $orderTeam = 1;
            foreach ($subTeamData as $item) {
                $arrUpdate[] = array(
                    'id' => $item['id'],
                    'SubTeamNumber' => $orderTeam
                );
                $orderTeam ++;
            }
            $this->db->update_batch($this->table, $arrUpdate, 'id');
        }
    }

    public function getTeamByNumber($subId, $order)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('SubID' => $subId));
        $this->db->where(array('SubTeamNumber' => $order));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function migrateTeamData($sub, $ProjID)
    {
        $teamData = $this->getData(['migrate' => '0', 'SubID' => $sub['SubID']]);
        if (empty($teamData)) {
            return true;
        }

        $this->db->trans_strict(TRUE);
        $this->db->trans_begin();
        foreach ($teamData as $member) {
            // tbl_people
            $arrPeopleInsert = array(
                'PeopleName' => $member['SubTeamFullName'],
                'PeopleDesc' => $member['SubTeamShortBio'],
                'PeoplePicture' => $member['SubTeamPicture'],
                'PeoplePassport' => $member['SubTeamPassport']
            );
            $this->db->insert('tbl_people', $arrPeopleInsert);
            $peopleID = $this->db->insert_id();

            // tbl_links
            $arrLinkInsert = array(
                'LinkType' => 10,
                'LinkParentType' => 4,
                'LinkParentID' => $peopleID,
                'Link' => $member['SubTeamLinkedin']
            );
            $this->db->insert('tbl_links', $arrLinkInsert);

            // tbl_people_projects
            $arrInsertPP = array(
                'PeopleProjPeopleID' => $peopleID,
                'PeopleProjProjID' => $ProjID,
                'PeopleProjPosition' => $member['SubTeamPosition'],
                'PeopleProjSortOrder' => $member['SubTeamNumber']
            );
            $this->db->insert('tbl_people_projects', $arrInsertPP);

            // tbl_submissions_team
            $this->db->where(array('id' => $member['id']));
            $this->db->update('tbl_submissions_team', array('migrate' => '1'));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function getProjectTeam($ProjID)
    {
        $sql = "SELECT * FROM `tbl_people_projects` PP
        LEFT JOIN tbl_people P ON PP.PeopleProjPeopleID = P.PeopleID
        LEFT JOIN tbl_links L ON P.PeopleID = L.LinkParentID AND L.LinkType = 10
        WHERE PP.PeopleProjProjID = ?
        ORDER BY PP.PeopleProjSortOrder ASC";

        $query = $this->db->query($sql, array($ProjID));
        return $query->result_array();
    }

    public function getTeamPassport()
    {
        $sql = "SELECT SubID, SubTeamPassport FROM `tbl_submissions_team` WHERE `SubTeamPassport` != ''";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

?>