<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Payment_model extends MY_Model
{
    protected $table = 'tbl_payments';

    public function __construct()
    {
        parent::__construct();
    }

    public function isPaid($subID)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('SubID', $subID);
        $this->db->where('PayStatus', '1');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getPaymentBySubmission($subID)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('SubID', $subID);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getPaymentCronProcess()
    {
        $sql = "Select S.SubID,SubStatus,SubCoinName,SubHashCode,PaySecret,SubProjID,P.PayAddress,P.PayAmount
                from tbl_submissions S
                left join tbl_payments P on S.SubID = P.SubID
                Where SubStatus = 1 and SubCoinName <> '' ORDER BY PayScreenLastViewed DESC LIMIT 50";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function updateBalance($address, $balanceTotal)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('PayAddress', $address);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $payment = $query->row_array();
            $this->update(['PayBalance' => $balanceTotal], ['PayID' => $payment['PayID']]);
        }
    }

    public function getPaymentByAddress($address, $notPaidYet = FALSE)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('PayAddress', $address);
        if ($notPaidYet) {
            $this->db->where('PayStatus', '0');
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getPaymentBySub($subID)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('SubID', $subID);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updatePayStatus($sub)
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_begin();

        // update payment
        $arrUpdatePayment = array(
            'PayStatus' => '1',
            'PayDatetime' => date('Y-m-d H:i:s')
        );
        $this->db->where(['SubID' => $sub['SubID']]);
        $this->db->update($this->table, $arrUpdatePayment);


        // update SubStatus
        $arrUpdateSub = array(
            'SubStatus' => 2, //Approved
            'SubStatusUpdatedOn' => date('Y-m-d H:i:s')
        );
        $this->db->where(['SubID' => $sub['SubID']]);
        $this->db->update('tbl_submissions', $arrUpdateSub);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function getPushPayment($hash)
    {
        $sql = "Select S.SubID,SubStatus,SubCoinName,SubHashCode,PaySecret,SubProjID,P.PayAddress,P.PayAmount
                from tbl_submissions S
                left join tbl_payments P on S.SubID = P.SubID
                Where (SubProjID = 0 OR SubProjID IS NULL) AND SubCoinName <> '' AND SubHashCode = ?";
        $query = $this->db->query($sql, array($hash));
        return $query->result_array();
    }

    public function getPaymentCronProcess1000()
    {
        $sql = "Select S.SubID,SubStatus,SubCoinName,SubHashCode,PaySecret,SubProjID,P.PayAddress,P.PayAmount
                from tbl_submissions S
                left join tbl_payments P on S.SubID = P.SubID
                Where SubStatus = 1 and SubCoinName <> '' ORDER BY PayScreenLastViewed DESC LIMIT 1000";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function fixPayForward()
    {
        $sql = "SELECT P.*, S.SubHashCode FROM `tbl_payments` P
                INNER JOIN tbl_submissions S ON S.SubID = P.SubID
                WHERE P.`PayStatus` = '0' AND (P.`PayForwardID` IS NULL OR P.`PayForwardID` = '')";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function updateAmountByHash($subHash, $amount)
    {
        $sqlHash = "SELECT SubID FROM tbl_submissions WHERE SubHashCode = ?";
        $queryHash = $this->db->query($sqlHash, array($subHash));
        $submission = $queryHash->row_array();

        if (isset($submission['SubID']) && $submission['SubID']) {
            $this->db->select('PayID');
            $this->db->from($this->table);
            $this->db->where('SubID', $submission['SubID']);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $payment = $query->row_array();
                $this->update(['PayAmount' => $amount], ['PayID' => $payment['PayID']]);
                return TRUE;
            }
        }

        return FALSE;
    }
}

?>