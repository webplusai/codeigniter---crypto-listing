<?php
require BASEPATH . "/vendor/autoload.php";
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Validation\Validator;

$validator = new Validator();
$driver = new Mysql([
    'host' => DB_HOST,
    'database' => DB_DATABASE,
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
    'encoding' => DB_ENCODING,
    'timezone' => DB_TIMEZONE
]);
$connection = new Connection([
    'driver' => $driver
]);


if (!function_exists('prepareErrorsFormat')) {
    function prepareErrorsFormat($errors) {
        $arrError = array();
        foreach ($errors as $key => $err) {
            if ($key == 'ProjAlgo' || $key == 'ProjPlatform') {
                $key .= 'ID';
            }
            $arrError[] = array('key' => $key, 'value' => current($err));
        }
        return $arrError;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($element, $field, $pathFile = 'uploads/logo') {
        if (!isset($_FILES[$element]) || empty($_FILES[$element])) {
            return '';
        }

        $uploadFile = $_FILES[$element];
        $arrError = array();

        if ($uploadFile && !$uploadFile['error']) {
            $generateFileName = rand(100, 999).time().'.'.pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
            $validFile = true;

            if (!in_array(strtolower(pathinfo($uploadFile['name'], PATHINFO_EXTENSION)), array('jpg', 'png'), true)) {
                $validFile = false;
                $arrError[$element] = $field.' invalid extension file (JPG, PNG).';
            }

            if ($uploadFile['size'] > 2*1024000) {
                $validFile = false;
                $arrError[$element] = $field.' file\'s size is to large. > 2MB';
            }

            if ($validFile) {
                if (!file_exists($pathFile)) {
                    mkdir($pathFile, 0777, true);
                }
                move_uploaded_file($uploadFile['tmp_name'], $pathFile.'/'.$generateFileName);
                return $generateFileName;
            }
        } else {
            $arrError[$element] = $field.' upload triggered the following error: '.$uploadFile['error'];
        }

        if (!empty($arrError)) {
            return $arrError;
        }

        return '';
    }
}


if (!function_exists('resizeImage')) {
    function resizeImage($pathFile, $w, $h)
    {
        if (file_exists($pathFile)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileinfo = finfo_file($finfo, $pathFile);
            if (strpos($fileinfo, 'image') !== FALSE) {

                $image = new \Eventviva\ImageResize($pathFile);
                $image->resize($w, $h);
                $resizePath = pathinfo($pathFile, PATHINFO_DIRNAME).'/'.pathinfo($pathFile, PATHINFO_FILENAME).'.'.pathinfo($pathFile, PATHINFO_EXTENSION);
                $image->save($resizePath);
                if (file_get_contents($resizePath)) {
                    return base64_encode(file_get_contents($resizePath));
                }
            }
        }

        return '';
    }
}


if (!function_exists('grabImage')) {
    function grabImage($url, $saveto)
    {
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
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


if (!function_exists('getSetting')) {
    function getSetting($connection, $id) {
        $sql = "SELECT * FROM `tbl_settings` WHERE `SettingID` = :SettingID";
        $statement = $connection->prepare($sql);
        $statement->bind(['SettingID' => $id], ['SettingID' => 'integer']);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $setting = $statement->fetch('assoc');;
            return $setting['SettingValue'];
        }
        return false;
    }
}


if (!function_exists('getSettingICORank')) {
    function getSettingICORank($connection) {
        return getSetting($connection, 1);
    }
}

if (!function_exists('filterSelect')) {
    function filterSelect($selected) {
        $arr = explode(',', $selected);
        if (!empty($arr)) {
            foreach ($arr as &$item) {
                preg_match('!\d+!', $item, $matches);
                $item = $matches[0];
            }
            return "'".implode("','",$arr)."'";
        }
        return '';
    }
}

if (!function_exists('filterRemoveSingleQuotes')) {
    function filterRemoveSingleQuotes($str) {
        return str_replace("'", '', $str);
    }
}

if (!function_exists('validUpdatePackage')) {
    function validUpdatePackage($project, $package) {
        $icoRank = $project['ProjICORank'];
        if ($package == 'silver') {
            return $icoRank >= 25;
        } else if ($package == 'gold') {
            return $icoRank >= 35;
        } else if ($package == 'platinum') {
            return $icoRank >= 50;
        }

        return false;
    }
}

function dd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}