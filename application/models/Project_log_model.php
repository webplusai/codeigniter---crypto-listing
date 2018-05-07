<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Project_log_model extends MY_Model
{
    protected $table = 'tbl_projects_logs';

    public function __construct()
    {
        parent::__construct();
    }

    public function updateProjectLog($user, $data, $ProjID)
    {

        $sql = "Select P.*, EventStartDate, EventEndDate, EventName, EventDesc, EventStartDateType
                from tbl_projects P
                inner join tbl_events E
                on P.ProjID = E.EventProjID
                where P.ProjID = ?";
        $query = $this->db->query($sql, array($ProjID));
        $project = $query->row_array();

        if (strtotime($project['EventEndDate']) < 0) {
            $project['EventEndDate'] = '';
        }
        if (strtotime($project['EventStartDate']) < 0) {
            $project['EventStartDate'] = '';
        }

        $updateData = array_merge($project, $data);
        $changed = array_diff_assoc($data, $project);
        if (empty($changed)) {
            $updateData['ProjChanged'] = '';
        } else {
            $updateData['ProjChanged'] = json_encode($changed);
        }

        if ($user == 'cron') {
            $updateData['ProjUpdatedType'] = 'insert';
        } else {
            $updateData['ProjUpdatedType'] = 'update';
        }

        $updateData['ProjUpdatedUser'] = $user;
        $this->insert($updateData);

    }
}

?>