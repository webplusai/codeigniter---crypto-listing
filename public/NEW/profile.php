<? 
  require('includes/global_icons.php');
  require('includes/global_data.php');
?>
<!doctype html>
<html lang="en">
<head>
  <? require ('includes/global_meta.php'); ?>
  <title>Coinschedule - The best cryptocurrency ICOs (Initial Coin Offering) Crowdsale and Token Sales List</title>
  <link rel="stylesheet" type="text/css" href="css/css_global.php" />
  <link rel="stylesheet" type="text/css" href="css/css_profile.php" />
  <script src="js/site.js"></script>
  <script src="js/bootstrap.bundle.js"></script>
  <script src="js/notify.min.js"></script>
  <script src="js/profile.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>   
      <h1 class="inline">User Profile</h1>
        <div class="form-group">
          <label for="tx_name">User Name </label>
          <input type="text" maxlength="50" class="form-control" id="tx_username" name="username" placeholder="User Name" value="<?=$userinfo['tx_username']?>">
        </div>
        <div class="form-group">
          <label for="tx_email">Email Address</label> &nbsp;(requires password to change)
          <label for="tx_name2"> </label>
      <input type="email" class="form-control" id="tx_email" name="email" placeholder="Email Address" value="<?=$userinfo['tx_email']?>">
        </div>
        <div class="form-group">
          <label for="tx_link">Change Password</label>
          <input type="password" data-toggle="password" class="form-control" id="tx_pass" name="pass" placeholder="Current Password" value="<?=$_REQUEST['tx_eventname']?>">
        </div>
        <div class="form-group">
          <input type="password" data-toggle="password" class="form-control" id="tx_newpass" name="newpass" placeholder="New Password" value="<?=$_REQUEST['tx_eventname']?>">
        </div>
        <div class="form-group">
        <button onClick="saveprofile();" id="btn_update" class="btn btn-primary inline">Update</button>
        </div>
        <div class="form-group">
        Forgot password? <a href="#">Send a password reset link</a>
        </div>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>