<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Page_model extends MY_Model
{
    protected $table = 'tbl_pages';

    public function __construct()
    {
        parent::__construct();
    }
}

?>