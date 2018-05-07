<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class People_groups_model extends MY_Model
{
    protected $table = 'tbl_peoplegroups';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('PeopleGroupSort', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }


}

?>