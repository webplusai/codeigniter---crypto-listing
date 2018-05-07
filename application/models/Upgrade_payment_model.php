<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Upgrade_payment_model extends MY_Model
{
    protected $table = 'tbl_upgrade_payments';

    public function getCronList()
    {
        $sql = "SELECT * FROM `tbl_upgrade_payments` WHERE PayStatus = '0' ORDER BY PayProcessedDate ASC LIMIT 10";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function updateBalance($address, $balanceTotal)
    {
        $this->update(array('PayBalance' => $balanceTotal), array('PayAddress' => $address));
    }

    public function getPaymentByAddress($address)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('PayAddress', $address);
        $query = $this->db->get();
        return $query->row_array();
    }
}

?>