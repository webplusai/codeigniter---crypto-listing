<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Submission_model extends MY_Model
{
    protected $table = 'tbl_submissions';

    public function __construct()
    {
        parent::__construct();
    }

    public function getSubmissionByHash($hash, $isCheckPermission = true)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('SubHashCode', $hash);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $submission = $query->row_array();
            if ($isCheckPermission) {
                $ssUser = $this->session->userdata(self::SS_USER);
                if ($submission['SubUsers'] != $ssUser['id']) {
                    return FALSE;
                }
            }

            return $submission;
        }

        return FALSE;
    }

    public function getSubmissionById($subID)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('SubID', $subID);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getSubmissionProject($subID)
    {
        $sql = "Select * from tbl_submissions where (SubProjID IS NULL OR SubProjID = 0) AND SubID = ?";
        $query = $this->db->query($sql, array($subID));
        return $query->row_array();
    }

    public function sendMailSubmit()
    {
        $sql = "SELECT * FROM `tbl_submissions` S
                INNER JOIN tbl_payments P ON S.SubID = P.SubID
                WHERE P.PayStatus = '0' AND `SubSendMail` = 0 AND SubDate < (NOW() - INTERVAL 120 MINUTE)";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getSubmissionByProject($id)
    {
        $sql = "Select * from tbl_submissions where SubProjID = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row_array();
    }

    public function getSubmissionPaymentByHash($hash)
    {
        $sql = "SELECT tx_email, PayID, PaySecret, SubHashCode,PayAmount,PayAddress,SubCoinName
                FROM tbl_submissions S
                INNER JOIN tbl_users U ON S.SubUsers = U.id_user
                INNER JOIN tbl_payments P on S.SubID = P.SubID
                WHERE P.PayStatus = '0' and P.PayRequestServer = 0 and S.SubHashCode = ?";

        $query = $this->db->query($sql, array($hash));
        return $query->row_array();
    }
}

?>