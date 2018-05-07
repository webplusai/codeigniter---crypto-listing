<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Password_reset_model extends MY_Model
{
    protected $table = 'tbl_password_resets';

    public function __construct()
    {
        parent::__construct();
    }

    public function updatePasswordReset($userInfo)
    {
        $userID   = $userInfo['id_user'];
        $token = md5(rand(100, 999).$userID.time());
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('PassUserID', $userID);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $arrUpdate = array(
                'PassToken' => $token,
                'PassCreatedAt' => date('Y-m-d H:i:s')
            );
            $this->update($arrUpdate, array('PassUserID' => $userID));
        } else {
            $arrInsert = array(
                'PassUserID' => $userID,
                'PassToken' => $token,
                'PassCreatedAt' => date('Y-m-d H:i:s')
            );
            $this->insert($arrInsert);
        }

        return $token;
    }

    public function getDataByToken($token)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('PassToken', $token);

        $query = $this->db->get();
        return $query->row_array();
    }

}

?>