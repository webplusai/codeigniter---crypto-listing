<?

//  require_once('../phpmailer/PHPMailerAutoload.php');
  
//  $mail = new PHPMailer;
//  $mail->isSMTP();                                      // Set mailer to use SMTP
//  $mail->Host = 'smtp.mailgun.org';                     // Specify main and backup SMTP servers
//  $mail->SMTPAuth = true;                               // Enable SMTP authentication
//  $mail->Username = 'moon@mg.coinschedule.com';   // SMTP username
//  $mail->Password = 'cs123**'; // SMTP password
//  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//  $mail->Port = 587;                                    // TCP port to connect to
  
  

  
 function send_mail($to,$from,$subject,$msg) {
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

 function send_html_mail($to,$from,$subject,$msg) {
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
  


  

?>