<?php
require "./vendor/autoload.php";
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Validation\Validator;

$driver = new Mysql([
    'host' => 'localhost',
    'database' => 'coinschedule_site',
    'username' => 'coinschedule_site',
    'password' => 'Tm^nn1qT=M?}',
    'encoding' => 'utf8',
    'timezone' => '+0:00'
]);
$connection = new Connection([
    'driver' => $driver
]);


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
                if (isset($matches[0])) {
                    $item = $matches[0];
                } else {
                    unset($item);
                }
            }

            if (implode("','",$arr)) {
                return "'".implode("','",$arr)."'";
            }
        }
        return '';
    }
}