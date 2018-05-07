<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    public function __construct($rules = array())
    {
        $this->CI =& get_instance();
        parent::__construct($rules);
    }

    public function valid_url( $param )
    {
        if( ! filter_var($param, FILTER_VALIDATE_URL) ){
            $this->CI->form_validation->set_message('valid_url', 'The {field} must be a valid url');
            return FALSE;
        }
        elseif (! preg_match("@^https?://@", $param)) {
            $this->CI->form_validation->set_message('valid_url', 'The {field} must begin with http:// or https://');
            return FALSE;
        }
        else{
            return TRUE;
        }

    }

    public function is_unique_mg($str, $field)
    {
        $this->CI->db->select('id_user');
        $this->CI->db->from('tbl_users');
        $this->CI->db->where(array($field . '=' => $str));
        $query = $this->CI->db->get();

        $name = '';
        switch ($field) {
            case 'tx_username':
                $name = 'Username';
                break;
            case 'tx_email':
                $name = 'Email';
                break;
            default:
                break;
        }

        if ($query->num_rows() > 0) {
            $this->CI->form_validation->set_message("is_unique_mg", 'The provided '.$name.' is already in use');
            return FALSE;
        }

        return TRUE;
    }

    public function is_unique_mg_update($str, $field)
    {
        $ssUser = $this->CI->session->userdata('ss_user');
        $this->CI->db->select('*');
        $this->CI->db->from('tbl_users');
        $this->CI->db->where($field, $str);
        $query = $this->CI->db->get();

        if ($field == 'tx_username') {
            $name = 'Username';
            $ssField = 'username';
        } else {
            $name = 'Email';
            $ssField = 'email';
        }

        $errMsg = 'The provided '.$name.' is already in use';
        $numRows = $query->num_rows();
        if ($numRows == 1) {
            $row = $query->row_array();
            if ($ssUser[$ssField] != $row[$field]) {
                $this->CI->form_validation->set_message("is_unique_mg_update", $errMsg);
                return FALSE;
            }
        } else if ($numRows > 1) {
            $this->CI->form_validation->set_message("is_unique_mg_update", $errMsg);
            return FALSE;
        }

        return TRUE;
    }

    public function valid_current_password($str)
    {
        $ssUser = $this->CI->session->userdata('ss_user');
        $this->CI->db->select('id_user');
        $this->CI->db->from('tbl_users');
        $this->CI->db->where('id_user', $ssUser['id']);
        $this->CI->db->where('tx_password', encryptPassword($str));
        $query=$this->CI->db->get();

        if ($query->num_rows() == 0) {
            $this->CI->form_validation->set_message("valid_current_password", 'Current password incorrect');
            return FALSE;
        }

        return TRUE;
    }

    public function valid_link($url, $website)
    {
        $isValid = TRUE;
        $name = '';
        switch ($website) {
            case 'twitter':
                if (strpos($url, 'twitter.com') === FALSE) {
                    $isValid = FALSE;
                }
                $name = 'Twitter';
                break;
            case 'reddit':
                if (strpos($url, 'reddit.com') === FALSE) {
                    $isValid = FALSE;
                }
                $name = 'Reddit';
                break;
            case 'slack':
                if (strpos($url, 'slack.com') === FALSE) {
                    $isValid = FALSE;
                }
                $name = 'Slack';
                break;
            case 'bctann':
                if (strpos($url, 'bitcointalk.org') === FALSE) {
                    $isValid = FALSE;
                }
                $name = 'Bitcointalk Announcement Thread';
                break;
            case 'linkedin':
                if (strpos($url, 'linkedin.com') === FALSE) {
                    $isValid = FALSE;
                }
                $name = 'Linkedin';
                break;
            default:
                break;
        }

        if (!$isValid) {
            $this->CI->form_validation->set_message("valid_link", 'The provided '.$name.' is not valid');
        }

        return $isValid;
    }

    public function valid_url_logo($url)
    {
        $arrValidLogo = array('jpg', 'png', 'jpeg');
        $logoExt = pathinfo($url, PATHINFO_EXTENSION);
        if (!in_array(strtolower($logoExt), $arrValidLogo)) {
            $this->CI->form_validation->set_message("valid_url_logo", 'Link to Logo url is not valid (file extension should be contain "jpg, jpeg, png")');
            return FALSE;
        }

        return TRUE;
    }

    public function valid_captcha($captcha)
    {
        $image = new Securimage();
        if ($image->check($captcha) != true) {
            $this->CI->form_validation->set_message("valid_captcha", 'The Security Code you entered was incorrect.');
            return FALSE;
        }

        return TRUE;
    }

    public function valid_recaptcha($gRecaptchaResponse)
    {
        $remoteIp = $this->CI->input->ip_address();
        $requestMethod = new \ReCaptcha\RequestMethod\CurlPost();
        $recaptcha = new \ReCaptcha\ReCaptcha($this->CI->config->config['recaptcha_secret'], $requestMethod);
        $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
        if (!$resp->isSuccess()) {
            $this->CI->form_validation->set_message("valid_recaptcha", 'The Security Code you entered was incorrect.');
            return FALSE;
        }

        return TRUE;
    }

    public function valid_mapping_link($value, $linkValid)
    {
        if ($linkValid === true) {
            return TRUE;
        }

        if (strpos(strtolower($value), strtolower($linkValid)) === false) {
            $this->CI->form_validation->set_message("valid_mapping_link", $linkValid . ' must be valid');
            return FALSE;
        }

        return TRUE;
    }

    public function valid_ico_enddate($endDate, $fieldStartDate)
    {
        $maxIcoTime = 60 * (3600 * 24); // 2 days
        $startDate = $this->CI->input->post($fieldStartDate);
        $icoTime = strtotime($endDate) - strtotime($startDate);
        if ($icoTime > $maxIcoTime) {
            $this->CI->form_validation->set_message("valid_ico_enddate", 'ICO event no longer than 2 months');
            return FALSE;
        }

        return TRUE;
    }

    public function valid_time_range($endDate, $fieldStartDate)
    {
        $startDate = $this->CI->input->post($fieldStartDate);
        if (strtotime($endDate) < strtotime($startDate)) {
            $this->CI->form_validation->set_message("valid_time_range", 'End must be larger than Start');
            return FALSE;
        }
        if (strtotime($endDate) < strtotime('today UTC')) {
            $this->CI->form_validation->set_message("valid_time_range", 'End must not be in the past');
            return FALSE;
        }

        return TRUE;
    }

    public function numeric_wcommas($numeric_str)
    {
        $numeric_str_wcommas = str_replace( ',', '', $numeric_str );

        if( is_numeric( $numeric_str_wcommas ) ) {
            if (floatval($numeric_str_wcommas) > 0) {
                return TRUE;
            }
            else {
                $this->CI->form_validation->set_message("numeric_wcommas", 'Total Supply value must be greater than or equal to 0');
                return FALSE;
            }
        }
        else {
            $this->CI->form_validation->set_message("numeric_wcommas", 'Total Supply value must be numeric');
            return FALSE;
        }
    }

    public function valid_projectType($numeric_str)
    {
        if ((int) $numeric_str > 0) {
            return TRUE;
        }
        else {
            $this->CI->form_validation->set_message("valid_projectType", 'The Project Type field is required.');
            return FALSE;
        }
    }
}