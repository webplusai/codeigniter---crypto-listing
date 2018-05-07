<?
 date_default_timezone_set('UTC'); 
 ob_start();
 session_start();
 require "./lib/bd.php";
 require_once "./lib/functions.php";
 
 if((isset($_SESSION['user']) )) {
	 // select loggedin users detail
	 $res=mysqli_query($db,"SELECT * FROM tbl_users WHERE id_user=".$_SESSION['user']);
	 $userinfo=mysqli_fetch_array($res);
   $userID = $userinfo['id_user'];
   $userName = $userinfo['tx_username'];
   $userEmail = $userinfo['tx_email'];
   
  require_once "./lib/icons.php";
   
 }
 else
 {
  if ($userpage)
  {
    header( 'Location: https://www.coinschedule.com/login.php?page='.$page );
    exit;
  }
 }
?>