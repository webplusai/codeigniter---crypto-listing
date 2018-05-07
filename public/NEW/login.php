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
  <link rel="stylesheet" type="text/css" href="css/css_login.php" />
  <script async src="js/site.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section style="text-align:center;">   
      <h1>Log In</h1>
      <form method="post">
          <div class="form-group">
            <input type="email" name="tx_email" class="form-control" placeholder="Your Email" value="<?php echo $tx_email; ?>" maxlength="40" />
          </div>
          <div class="form-group">
            <input type="password" name="tx_password" class="form-control" placeholder="Your Password" maxlength="15" />
          </div>
          <div class="form-group"><hr /></div>
          <button type="submit">Log In</button>
          <div class="form-group"><hr /></div>
          <div class="form-group">
             <a style="float: left;" href="<? echo $baseurl; ?>register.php">New user? Register here...</a>
             <a style="float: right;" href="<? echo $baseurl; ?>forgot_password.php">Forgot password</a>
          </div>
      </form>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>