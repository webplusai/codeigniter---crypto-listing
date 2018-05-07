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
  <link rel="stylesheet" type="text/css" href="css/css_register.php" />
  <script async src="js/site.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section style="text-align:center;">   
      <h1>New User Registration</h1>
      <form method="post">
          <div class="form-group">
            <input type="text" name="tx_username" class="form-control" placeholder="Enter Username" value="<?php echo $tx_email; ?>" maxlength="40" />
          </div>
          <div class="form-group">
            <input type="email" name="tx_email" class="form-control" placeholder="Your Email" value="<?php echo $tx_email; ?>" maxlength="40" />
          </div>
          <div class="form-group">
            <input type="password" name="tx_password" class="form-control" placeholder="Your Password" maxlength="15" />
          </div>
           <div class="form-group div-agree">
              <label style="font-weight: normal">
                <input <?php echo $agreeCheckValue;?> type="checkbox" name="agree" id="agree"> Agree with the <a href="terms.php">terms & conditions</a> and <a href="disclaimer.php">disclaimer</a>
              </label><br>
              <span class="text-danger error-agree" style="display: none">Please agree to our terms, conditions and disclaimer</span>
          </div>
          <div class="form-group"><hr /></div>
          <button type="submit">Register</button>
          <div class="form-group"><hr /></div>
          <div class="form-group">
             <a style="float: left;" href="<? echo $baseurl; ?>login.php">Already registered? Log in here...</a>
          </div>
      </form>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>