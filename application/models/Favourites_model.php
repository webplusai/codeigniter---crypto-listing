<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Favourites_model extends MY_Model
{
    protected $table = 'tbl_favourties';

    public function __construct()
    {
        parent::__construct();
    }

    public function getFavouritesByProject($projectID)
    {
        $sql = "SELECT COUNT(FavID) as Favourites
                FROM tbl_favourties
                WHERE FavProjID = ? AND FavRemoved = 0";
        $query = $this->db->query($sql, array($projectID));
        return $query->row_array();
    }

    public function isUserLiked($projectID, $userID)
    {
        $this->db->select('FavID');
        $this->db->from($this->table);
        $this->db->where('FavUserID', $userID);
        $this->db->where('FavProjID', $projectID);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function updateFavour($userID, $formData)
    {
        $favFlag = (int) $formData['type'];
        $projectID = $formData['id'];
        $validType = [0,1];
        if (!in_array($favFlag, $validType, true)) {
            return false;
        }

        $arrUpdate = [
            'FavUserID' => $userID,
            'FavProjID' => $projectID,
            'FavRemoved' => $favFlag,
        ];
        switch ($favFlag) {
            case 0:
                $arrUpdate['FavDateTime'] = date("Y-m-d H:i:s");
                break;
            case 1:
                $arrUpdate['FavRemovedDateTime'] = date("Y-m-d H:i:s");
                break;
            default:
                break;
        }

        $this->db->select('FavID, FavRemoved');
        $this->db->from($this->table);
        $this->db->where('FavUserID', $userID);
        $this->db->where('FavProjID', $projectID);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            if ($data['FavRemoved'] != $arrUpdate['FavRemoved']) {
                $this->update($arrUpdate, ['FavID' => $data['FavID']]);
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