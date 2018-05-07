<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Event_site_model extends MY_Model
{
    protected $table = 'tbl_eventsites';

    public function __construct()
    {
        parent::__construct();
    }

    public function getScrapeEvent()
    {
        $sql = "SELECT * FROM tbl_eventsites Order By EventSiteID,EventSiteURL";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function countEventWebsite($link, $name)
    {
        $sql = "Select Count(*) as Total from tbl_events where EventWebsite = ? or EventName = ?";
        $query = $this->db->query($sql, array($link, $name));
        return $query->row_array();
    }

}

?>