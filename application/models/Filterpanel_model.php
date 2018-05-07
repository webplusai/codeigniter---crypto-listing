<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Filterpanel_model extends MY_Model
{
    protected $table = 'tbl_filterpanel';

    public function __construct()
    {
        parent::__construct();
    }

    public function getFilterPanel()
    {
        $sql = "Select FilterSection,FilterName,FilterText,FilterImage from tbl_filterpanel
                Order By FilterSection DESC, CASE WHEN FilterName = 'Plat_0' THEN 0 ELSE FilterText END";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}

?>