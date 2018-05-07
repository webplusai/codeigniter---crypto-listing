<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Event_model extends MY_Model
{
    protected $table = 'tbl_events';

    public function __construct()
    {
        parent::__construct();
    }

    public function getCrowdfund($eventID, $projectID)
    {
        if ($eventID) {
            $id = $eventID;
        } else {
            $id = $projectID;
        }

        $sql = "Select EventID,EventName,EventDesc,EventStartDate,EventEndDate,EventTotalRaised,EventStartDateType,
                  EventRatesNote,P.ProjSymbol as EventTotalRaisedSymbol,P.ProjID as EventTotalRaisedProjID,
                  EventParticipants from tbl_events E left join tbl_projects P on E.EventTotalRaisedProjID = P.ProjID
                  where EventType = 1 and ".($eventID?"EventID=?":"EventProjID = ?")."
                   and EventDisabled = 0 and EventDeleted = 0
                   Order By CASE WHEN EventEndDate > Now() THEN 0 ELSE 1 END,EventEndDate LIMIT 1";

        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    public function getEventByProjectID($projectID)
    {
        $sql = "SELECT EventID, EventStartDate,EventEndDate,EventDisabled,EventType,EventName,EventStartDateType,EventSoftCap,EventHardCap
                FROM `tbl_events` WHERE EventProjID = ?";

        $query = $this->db->query($sql, array($projectID));
        return $query->row_array();
    }

    public function getListIcoEvent($projectID)
    {
        $sql = "SELECT EventID, EventName, EventStartDate, EventEndDate,
                  CASE WHEN EventEndDate<=UTC_TIMESTAMP THEN 'Ended'
                  WHEN EventStartDate>UTC_TIMESTAMP THEN 'Upcoming' ELSE 'Live' END as EventStatus
                FROM tbl_events
                WHERE EventType = 1 and EventStartDate IS NOT NULL
                  and EventStartDate <> '0000-00-00 00:00:00'
                  and EventDeleted = 0
                  and EventProjID = ?
                Order By EventStartDate";
        $query = $this->db->query($sql, array($projectID));
        return $query->result_array();
    }
}

?>