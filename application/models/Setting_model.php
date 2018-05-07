<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/core/MY_Model.php';

class Setting_model extends MY_Model
{
    protected $table = 'tbl_settings';

    public function __construct()
    {
        parent::__construct();
    }

    public function updateLastClearedCache()
    {
        $this->db->where(['SettingID' => 7]);
        return $this->db->update($this->table, ['SettingValue' => date('Y-m-d H:i:s')]);
    }

    public function updateLastDbUpdate()
    {
        $this->db->where(['SettingID' => 6]);
        return $this->db->update($this->table, ['SettingValue' => date('Y-m-d H:i:s')]);
    }

    public function cronCheckCleanCache()
    {
        $settings = $this->getData();
        $settings = array_column($settings, NULL, 'SettingID');
        $lastUpdateDB = $settings[6]['SettingValue'];
        $lastCleanCache = $settings[7]['SettingValue'];

        if (strtotime($lastUpdateDB) > strtotime($lastCleanCache)) {
            return true;
        }
        return false;
    }
}

?>