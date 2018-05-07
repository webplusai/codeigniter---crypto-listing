<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Requestapi_model extends MY_Model
{
    protected $table = 'tbl_apikey_requests';

    /**
     * RequestAPI_Model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getAllRequests()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insertRequest($data)
    {
//        $this->db->insert($this->table, $data);
//        return $this->db->insert_id();
        return true;
    }
}
