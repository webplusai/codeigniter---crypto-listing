<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../public_html/config.php";
//require_once "../config.php";
require_once BASEPATH . "/lib/cake.php";
require_once BASEPATH . '/includes/email.php';
require_once BASEPATH . "/lib/functions.php";


$sql = "SELECT * FROM `tbl_submissions` WHERE `SubSendMail` = 0 AND SubDate < (NOW() - INTERVAL 60 MINUTE)";
$statement = $connection->prepare($sql);
$statement->execute();

if ($statement->rowCount() > 0) {
    $arrSubmission = $statement->fetchAll('assoc');
    echo '<pre>'; print_r($arrSubmission); echo '</pre>';
    foreach ($arrSubmission as $sub) {
        $arrUpdate = array('SubSendMail' => 1);
        $connection->update('tbl_submissions', $arrUpdate, array('SubID' => $sub['SubID']));
        sendMail($sub);
    }
} else {
    echo 'Not found data';
}

function sendMail($submit)
{
    $email_subject = 'Submission process started';
    $email_message = "Hello ".$submit['SubName'].", \n\n";
    $email_message .= "You have started your project ".$submit['SubEventName']." submission process in Coinschedule, but please note that your project will not be listed until you have finished the submission and made the payment.\n\n";
    $email_message .= "Feel free to reply to this e-mail in case you have any questions.\n\n";
    $email_message .= "All the best,\n";
    $email_message .= "Alex\n";

    send_mail($submit['SubEmail'],"moon@coinschedule.com",$email_subject,$email_message);
}
