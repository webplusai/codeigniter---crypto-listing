<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Project_category_model extends MY_Model
{
    protected $table = 'tbl_project_categories';

    public function __construct()
    {
        parent::__construct();
    }
}

?>