<?php

if (!function_exists('truncate')) {
    function truncate($string, $length = 155, $append = "...")
    {
        $string = trim(strip_tags(htmlspecialchars_decode($string)));
        $string = preg_replace('/\s+/', ' ', $string);
        if (strlen($string) > $length) {
            $string = wordwrap($string, $length, "<TRUNCATE>", true);
            $string = explode("<TRUNCATE>", $string);
            $string = $string[0] . $append;
        }
        return $string;
    }
}

if (!function_exists('isShowMore')) {
    function isShowMore($string, $length = 90)
    {
        $string = trim(strip_tags(htmlspecialchars_decode($string)));
        $string = preg_replace('/\s+/', ' ', $string);
        if (strlen($string) > $length) {
            return true;
        }
        return false;
    }
}

if (!function_exists('appStripTags')) {
    function appStripTags($string)
    {
        $string = trim(strip_tags(htmlspecialchars_decode($string)));
        $string = preg_replace('/\s+/', ' ', $string);
        return $string;
    }
}

if (!function_exists('curl_get_content')) {
    function curl_get_content($url, $referer = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

if (!function_exists('encryptPassword')) {
    function encryptPassword($password)
    {
        $password = trim($password);
        $password = strip_tags($password);
        $password = htmlspecialchars($password);
        return hash('sha256', $password); // password hashing using SHA256
    }
}

if (!function_exists('hashSubmission')) {
    function hashSubmission($email, $eventName)
    {
        return hash("ripemd128", time(). $email . $eventName);
    }
}

if (!function_exists('slugify')) {
    function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

if (!function_exists('timeToDate')) {
    function timeToDate($ts)
    {
        $val = 0;

        if ($ts>31536000) {
            $val = round($ts/31536000,0).' year';
        } else if ($ts>2419200) {
            $val = round($ts/2419200,0).' month';
        } else if ($ts>950400) {
            $val = round($ts/604800,0).' week';
        } else if ($ts>86400) {
            $val = round($ts/86400,0).' day';
        } else if ($ts>3600) {
            $val = round($ts/3600,0).' hour';
        } else if ($ts>60) {
            $val = round($ts/60,0).' min';
        }

        if($val>1) $val .= 's';

        if(!trim($val)) $val .= 'now';
        return $val.'';
    }
}

if (!function_exists('dateFormatUTC')) {
    function dateFormatUTC($date)
    {
        return date_format($date, "M jS Y").(date_format($date,"H:i")!="00:00"?' '.date_format($date,"H:i").' UTC':'');
    }
}

if (!function_exists('dateFormatEvent')) {
    function dateFormatEvent($date)
    {
        return date('M jS Y', strtotime($date));
    }
}

if (!function_exists('dateFormatBlog')) {
    function dateFormatBlog($date)
    {
        return date('j F Y', strtotime($date));
    }
}

if (!function_exists('getWords')) {
    function getWords($sentence, $count = 70) {
        $sentence = preg_replace('/\s+/', ' ', $sentence);
        return implode(' ', array_slice(explode(' ', $sentence), 0, $count)).' ...';
    }
}

if (!function_exists('formSetValue')) {
    function formSetValue($field, $dbValue) {
        $ci =& get_instance();
        if ($ci->input->post($field, TRUE) !== NULL) {
            return $ci->input->post($field, TRUE);
        }

        return $dbValue;
    }
}

if (!function_exists('formSetValueMultiSelect')) {
    function formSetValueMultiSelect($field, $dbValue) {
        $ci =& get_instance();
        if ($ci->input->post($field, TRUE) !== NULL) {
            return $ci->input->post($field, TRUE);
        }

        return $dbValue;
    }
}

if (!function_exists('formSetValueCheckbox')) {
    function formSetValueCheckbox($field, $dbValue) {
        $ci =& get_instance();
        $checked = '';
        $value = $dbValue;
        if ($ci->input->post($field, TRUE) !== NULL) {
            $value = $ci->input->post($field, TRUE) == 'on' ? 1 : 0;
        }

        if ($value == 1) {
            $checked = 'checked';
        }

        return $checked;
    }
}

if (!function_exists('displayDate')) {
    function displayDate($date) {
        if ($date == "0000-00-00 00:00:00" || $date == '' || $date == NULL) {
            return "";
        } else {
            return $date;
        }
    }
}

if (!function_exists('cleanInput')) {
    function cleanInput($string) {
        return strip_tags(trim(htmlspecialchars_decode($string, ENT_QUOTES)));
    }
}

if (!function_exists('escape_string')) {
    function escape_string($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }
}

if (!function_exists('grab_url_payment')) {
    function grab_url_payment($url, $referer = '') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        if ($referer) {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        } else {
            curl_setopt($ch, CURLOPT_REFERER, 'http://google.com/search');
        }

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}

if (!function_exists('grab_image')) {
    function grab_image($url, $saveto) {
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);

        if(file_exists($saveto)){
            unlink($saveto);
        }

        if ($raw) {
            $fp = fopen($saveto,'x');
            fwrite($fp, $raw);
            fclose($fp);
            chmod($saveto, 0777);
            return true;
        }
        return false;
    }
}

if (!function_exists('dd')) {
    function dd($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

}

function mappingLinkType($type) {
    $arrLinkType = array(
        4 => 'bitcointalk.org',
        5 => 'twitter.com',
        6 => 'reddit.com',
        7 => 'youtube.com',
        8 => 'facebook.com',
        9 => 'slack',
        10 => 'linkedin.com',
        11 => 't.me',
        12 => 'instagram.com',
        13 => 'github.com',
        15 => 'meetup.com',
        16 => 'google.com',
        18 => 'wikipedia.org',
        19 => 'gitter.im',
        20 => 'stackexchange.com',
        21 => 'discord',
    );

    if (!isset($arrLinkType[$type])) {
        return true;
    }

    return $arrLinkType[$type];
}

function grab_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0");
    $cookie_file = "cookie.txt";
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    return curl_exec($ch);
}

function grab_file($url,$saveto)
{
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
    curl_close ($ch);
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);
}

function getUrlasDOMXPath($url,$html='')
{
    if($html==''){ $html = grab_url($url); }

    //$html = file_get_contents($url);

    $doc = new DOMDocument();
    libxml_use_internal_errors(TRUE);
    if(!empty($html))
    {
        $doc->loadHTML($html);
        libxml_clear_errors();


        $xpath = new DOMXPath($doc);
        return $xpath;
    }
    else
    {
        return false;
    }
}

function getAlgorithm() {
    return [
        1 => 'SHA-256',
        2 => 'Scrypt',
        3 => 'Ethash',
        4 => 'SHA-3',
        5 => 'POS',
    ];
}

function showAlgorithm($value) {
    $algorithm = getAlgorithm();
    if (isset($algorithm[$value])) {
        return $algorithm[$value];
    }

    return '';
}

function slugEvent($eventId, $eventName) {
    return site_url('icos/e' . $eventId . '/' . slugify($eventName));
}

function slugProject($projectId, $projectName) {
    return site_url('projects/' . $projectId . '/' . slugify($projectName));
}

function canonicalEvent($eventId, $eventName) {
    return site_url('icos/e' . $eventId . '/' . slugify($eventName));
}

function canonicalProject($projectId) {
    return site_url('projects/' . $projectId);
}