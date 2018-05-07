<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class User_Model extends MY_Model
{
    protected $table = 'tbl_users';

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserByEmail($email)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('tx_email' => $email));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getUserInfo($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('id_user' => $id));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function loginUser($email, $pass)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('tx_email', $email);
        $this->db->where('tx_password', $pass);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function checkEmail($email)
    {
        $this->db->select('tx_email');
        $this->db->from($this->table);
        $this->db->where('tx_email', $email);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function insertUser($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}

?>