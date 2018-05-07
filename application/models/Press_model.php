<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Press_model extends MY_Model
{
    protected $table = 'tbl_press';

    public function __construct()
    {
        parent::__construct();
    }

    public function getPressMention()
    {
        $sql = "Select * from tbl_press Where PressImage <> '' Order By PressDate DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}

?>