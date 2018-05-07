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
  <link rel="stylesheet" type="text/css" href="css/css_advertise.php" />
  <script async src="js/site.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>   
      <h1>Advertising</h1>
      <b>Please note that in order to advertise with us, your project needs to be listed first.<br> To submit your project for listing, please go here <a href="https://www.coinschedule.com/submit_entry.php" style="color:#0357FF">https://www.coinschedule.com/submit_entry.php</a> (you may need to create a Coinschedule account first).</b>
      <br><br>
      Our sponsorship packages help increase your brand and project awareness. We have millions of monthly visitors, that come to Coinschedule looking for the best crypto crowdfunding projects to follow and join.<br>
      <br>
      Advertisement spaces in Coinschedule are limited and prices are driven by demand. We recommend submitting your project and selecting a marketing package as soon as possible to avoid disappointment. <br>
      <br>
      Fill the form below to register your interest and learn more:
      <br><br>
      <?
        if($msg) {
        		$type="danger";
        		if ($msg_success)$type="success";
        		show_nice_msg2($msg,$type);
        	}
        ?>
        <? if (!$msg_success==$type="success"){?>     
        <form method="post">
          <div class="form-group">
            <label for="tx_name">Your Name</label>
            <span style="color:#F00">*</span>
            <input type="text" class="form-control" id="tx_name" name="tx_name" placeholder="Your Name" value="<?=$_REQUEST['tx_name']?>">
          </div>
          <div class="form-group">
            <label for="tx_email">Your E-mail</label>
            <label for="tx_name2"> </label>
            <span style="color:#F00">*</span>
        <input type="email" class="form-control" id="tx_email" name="tx_email" placeholder="Email" value="<?=$_REQUEST['tx_email']?>">
          </div>
          <div class="form-group"></div>
          <div class="form-group">
            <label for="tx_info">Any additional comments?</label>
            <textarea style="height: 200px;" rows="5" class="form-control" id="tx_info" name="tx_info" placeholder="More information" type="textarea"><?=$_REQUEST['tx_info']?></textarea>
          </div>
            <div class="form-group">
            <img id="captcha" src="/lib/securimage/securimage_show.php" alt="CAPTCHA Image" />
          </div>
          <div class="form-group">
            Type the text: <input type="text" name="captcha_code" size="10" maxlength="6" />
            <a href="#" onclick="document.getElementById('captcha').src = '/lib/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
          </div>
          <div class="form-group"></div>
          <button type="submit">Submit</button>
        </form>
      <? }?>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>