<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $table = '';
    const SS_USER = 'ss_user';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($data, $arrWhere)
    {
        $this->db->where($arrWhere);
        return $this->db->update($this->table, $data);
    }

    public function delete($arrWhere)
    {
        return $this->db->delete($this->table, $arrWhere);
    }

    /**
     * get all data by where
     *
     * @param array $where
     * @param bool  $limit
     * @param bool  $offset
     * @param array $orderBy
     * @return mixed
     */
    public function getData($where = array(), $limit = FALSE, $offset = FALSE, $orderBy = array())
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if (is_array($where) && !empty($where)) {
            $this->db->where($where);
        }

        if ($limit !== FALSE) {
            $this->db->limit($limit, $offset);
        }

        if (is_array($orderBy) && !empty($orderBy)) {
            reset($orderBy);
            $this->db->order_by(key($orderBy), current($orderBy));
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSingleData($where = array())
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if (is_array($where) && !empty($where)) {
            $this->db->where($where);
        }

        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * count data by where
     *
     * @param array $where
     * @return mixed
     */
    public function countData($where = array())
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if (is_array($where) && !empty($where)) {
            $this->db->where($where);
        }

        return $this->db->count_all_results();
    }

    public function getSettingValue($settingID)
    {
        $settings = $this->cache->redis->get('cachedSetting');
        if (!$settings) {
            $this->db->select('*');
            $this->db->from('tbl_settings');
            $query = $this->db->get();
            $settings = $query->result_array();
            $this->cache->redis->save('cachedSetting', $settings, CACHE_EXPIRE);
        }

        $settings = array_column($settings, 'SettingValue', 'SettingID');
        if (isset($settings[$settingID])) {
            return $settings[$settingID];
        }

        return false;
    }
}