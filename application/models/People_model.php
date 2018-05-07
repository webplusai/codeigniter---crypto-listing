<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class People_model extends MY_Model
{
    protected $table = 'tbl_people';

    public function __construct()
    {
        parent::__construct();
    }

    public function getTeamPassport()
    {
        $sql = "SELECT PeoplePassport FROM `tbl_people` WHERE `PeoplePassport` != ''";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPersonDetail($id)
    {
    	$sql = "SELECT PeopleName, PeopleDesc, PeoplePicture from tbl_people where PeopleID = $id";
    	$query = $this->db->query($sql);
    	return $query->result_array();
    }
}

?>
