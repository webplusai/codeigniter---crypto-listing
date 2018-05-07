<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Distribution_model extends MY_Model
{
    protected $table = 'tbl_projdistribution';

    public function __construct()
    {
        parent::__construct();
    }

    public function getProjectDistribution($id)
    {
        $sql = "Select DistroID,DistroDesc,	DistroAmount,DistroPercent,DistroNote,DistroSortOrder
                    from tbl_projdistribution
                    Where DistroProjID = ?
                    Order By DistroSortOrder";

        $query = $this->db->query($sql, array($id));
        return $query->result_array();
    }
}

?>