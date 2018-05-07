<?

  date_default_timezone_set('UTC'); 
  ob_start();
  session_start();
  
  require ('/home/coinschedule/public_html/lib/bd.php');
  
  if((isset($_SESSION['user']) )) {
      // select loggedin users detail
      $res=mysqli_query($db,"SELECT * FROM tbl_users WHERE id_user=".$_SESSION['user']);
      $userinfo=mysqli_fetch_array($res);
      $userID = $userinfo['id_user'];
      $userName = $userinfo['tx_username'];
      $userEmail = $userinfo['tx_email'];
  }


  $baseurl = 'https://www.coinschedule.com/NEW/';
?>