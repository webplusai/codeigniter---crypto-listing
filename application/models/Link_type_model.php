<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Link_type_model extends MY_Model
{
    protected $table = 'tbl_linktypes';

    public function __construct()
    {
        parent::__construct();
    }
}

?>