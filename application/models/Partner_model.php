<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Partner_model extends MY_Model
{
    protected $table = 'tbl_partners';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('PartID', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatePartnerClick($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('PartID' => $id));
        $query = $this->db->get();
        $rowData = $query->row_array();
        if (!empty($rowData)) {
            $this->update(
                ['PartClicks' => $rowData['PartClicks'] + 1],
                ['PartID' => $id]
            );
        }
    }

}

?>