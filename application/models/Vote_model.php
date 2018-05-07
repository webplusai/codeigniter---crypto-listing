<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Vote_model extends MY_Model
{
    protected $table = 'tbl_votes';

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoteByProject($projectID)
    {
        $sql = "SELECT
                    COUNT(CASE WHEN VoteType = 1 THEN 1 END) AS UpVotes,
                    COUNT(CASE WHEN VoteType = 2 THEN 1 END) AS DownVotes
                FROM tbl_votes
                WHERE VoteProjID = $projectID";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function getUserVoteByProject($projectID, $userID)
    {
        $this->db->select('VoteID, VoteUserID, VoteProjID, VoteType');
        $this->db->from($this->table);
        $this->db->where('VoteUserID', $userID);
        $this->db->where('VoteProjID', $projectID);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updateVotes($userID, $formData)
    {
        $voteType = (int) $formData['type'];
        $projectID = $formData['id'];
        $validType = [0,1,2];
        if (!in_array($voteType, $validType, true)) {
            return false;
        }

        $arrUpdate = [
            'VoteUserID' => $userID,
            'VoteProjID' => $projectID,
            'VoteType' => $voteType,
        ];
        switch ($voteType) {
            case 0:
                $arrUpdate['VoteRemovedDateTime'] = date("Y-m-d H:i:s");
                break;
            case 1:
                $arrUpdate['VoteUpDateTime'] = date("Y-m-d H:i:s");
                break;
            case 2:
                $arrUpdate['VoteDownDateTime'] = date("Y-m-d H:i:s");
                break;
            default:
                break;
        }

        $this->db->select('VoteID, VoteType');
        $this->db->from($this->table);
        $this->db->where('VoteUserID', $userID);
        $this->db->where('VoteProjID', $projectID);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $vote = $query->row_array();
            if ($vote['VoteType'] != $arrUpdate['VoteType']) {
                $this->update($arrUpdate, ['VoteID' => $vote['VoteID']]);
                return true;
            }
        } else {
            $this->insert($arrUpdate);
            return true;
        }

        return false;
    }

}

?>