<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Send email to function,
 * return the debug result.
 *
 * @param  array $from Array('email'=>'sample@mail.com','name'=>'Sender Name')
 * @param  array $to Array('email'=>'sample@mail.com','name'=>'Send to name')
 * @param  string $subject Email subject mater
 * @param  string $body the body of the email in HTML format
 * @param  array $attachment array of attachment path ie array('/path/to/photo1.jpg','/path/to/photo2.jpg')
 * @return boolean
 */
define("DEBUG_MODE", FALSE);
function send_email_smtp($from = null, $to, $subject, $body, $attachments = array(), $cc = '')
{
    if (!$to || !isset($to['email']) || !isset($to['name'])) {
        if (DEBUG_MODE) {
            die("missing TO field");
        }
        return false;
    }
    if (!$subject) {
        if (DEBUG_MODE) {
            die("missing SUBJECT field");
        }
        return false;
    }
    if (!$body) {
        if (DEBUG_MODE) {
            die("missing BODY field");
        }
        return false;
    }

    $CI =& get_instance();
    $CI->load->library('email'); // load library
    $email_config = $CI->config->item('site_email_config');
    $CI->email->initialize($email_config);

    if ($from && isset($from['email']) && isset($from['name'])) {
        $CI->email->from($from['email'], $from['name']);
    } else {
        $from = $CI->config->item('site_email_sender');
        $CI->email->from($from['email'], $from['name']);
    }
    $CI->email->to($to['email'], $to['name']);
    if ($cc) {
        $CI->email->cc($cc);
    }
    $CI->email->subject($subject);
    $CI->email->message($body);
    foreach ($attachments as $attachment) {
        $CI->email->attach($attachment);
    }

    if (DEBUG_MODE) {
        if ($CI->email->send()) {
            return true;
        } else {
            die($CI->email->print_debugger());
        }
    } else {
        return $CI->email->send();
    }
}



function send_mail_text($to,$from,$subject,$msg) {
    $api_key="key-67d8e8cd3b46cb370e0e3cc99c64450f";/* Api Key got from https://mailgun.com/cp/my_account */
    $domain ="mg.coinschedule.com";/* Domain Name you given to Mailgun */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$api_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.$domain.'/messages');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
     'from' => $from,
     'to' => $to,
     'subject' => $subject,
     'text' => $msg
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
   }
   
    function send_email($from,$to,$subject,$msg) {
        if (is_array($from)) {
            $from = $from['email'];
        }

        if (is_array($to)) {
            $to = $to['email'];
        }

        $api_key="key-67d8e8cd3b46cb370e0e3cc99c64450f";/* Api Key got from https://mailgun.com/cp/my_account */
        $domain ="mg.coinschedule.com";/* Domain Name you given to Mailgun */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.$domain.'/messages');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'from' => $from,
        'to' => $to,
        'subject' => $subject,
        'html' => $msg
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
   }
     