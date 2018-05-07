<? 
  require ('/home/coinschedule/public_html/NEW/includes/global_data.php');
  
  function clean($string)
  {
    return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($string))))));
  }
  
  if ($userID)
  {
    $username = clean($_POST['username']);
    $email =  $_POST['email'];
    $pass =  $_POST['pass'];
    $newpass =  $_POST['newpass'];
    
   
    if ($newpass)
    {
      if (hash('sha256', $pass) == $userinfo['tx_password'])
      {
        $newpass = hash('sha256', $newpass);
        mysqli_query($db,"Update tbl_users Set tx_username = '$username',tx_email = '$email',tx_password = '$newpass' Where id_user = $userID");
        echo 'OK';
      }
      else
      {
        echo $is_mobile?'Current password incorrect':'Unable to update your password. Current password was incorrect';  
      }  
    }
    else
    if ($email !== $userEmail)
    {
      if (hash('sha256', $pass) == $userinfo['tx_password'])
      {
        mysqli_query($db,"Update tbl_users Set tx_username = '$username',tx_email = '$email' Where id_user = $userID");
        echo 'OK';
      }
      else
      {
        echo $is_mobile?'Incorrect password saving email':'Unable to update your email address. Password was incorrect';
      }
    }
    else 
    if ($username !== $userName)
    {   
      mysqli_query($db,"Update tbl_users Set tx_username = '$username' Where id_user = $userID");
      echo 'OK';
    }
    
    
  }
?>
