<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "config.php";
require_once "lib/cake.php";
require_once "lib/functions.php";
session_start();

if (!isset($_REQUEST['action'])) {
    exit();
}

$requestAction = $_REQUEST['action'];
$jsonData = array('code' => 100);

switch ($requestAction) {
    case 'delete_people_picture':
        $jsonData = delPeoplePicture();
        break;

    default:
        $jsonData['code'] = 101;
        break;
}
echo json_encode($jsonData);


function delPeoplePicture()
{
    global $jsonData, $connection, $validator;

    $validator->notEmpty('people_id', 'People ID can not be empty');

    $errors = $validator->errors($_POST);
    if (!empty($errors)) {
        $jsonData['errors'] = prepareErrorsFormat($errors);
        $jsonData['message'] = 'Please make sure you fill valid all the fields.';
        return $jsonData;
    }

    $connection->update('tbl_people', ['PeoplePicture' => ''], ['PeopleID' => $_POST['people_id']]);
    $jsonData['code'] = 1;
    return $jsonData;
}
