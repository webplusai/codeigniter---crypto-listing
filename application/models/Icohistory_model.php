<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Icohistory_model extends MY_Model
{
    protected $table = 'tbl_icohistory';

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataStatsMonthly()
    {
        $sql = "Select MONTH(EndDate) as Month,Year(EndDate) as Year, Count(*) as Num,SUM(TotalUSD) as Total
                  from tbl_icohistory GROUP BY MONTH(EndDate), Year(EndDate) Order by EndDate DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

?>