<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Filter_model extends MY_Model
{
    protected $table = 'tbl_filters';

    public function __construct()
    {
        parent::__construct();
    }

    public function getFilterById($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('FilterID' => $id));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function applyFilterDb($Filter)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('Filter' => $Filter));
        $query = $this->db->get();
        if ($filter = $query->row_array()) {
            return $filter['FilterID'];
        } else {
            $this->insert(['Filter' => $Filter]);
            return $this->db->insert_id();
        }
    }
}

?>