<? 
  require('includes/global_icons.php');
  require('includes/index_icons.php');  
  require('includes/global_data.php'); 
  require('includes/index_data.php');
?>
<!doctype html>
<html lang="en">
<head>
  <? require ('includes/global_meta.php'); ?>
  <meta http-equiv="refresh" content="300">
  <title>Coinschedule - The best cryptocurrency ICOs (Initial Coin Offering) Crowdsale and Token Sales List</title>
  <link rel="stylesheet" type="text/css" href="css/css_global.php" />
  <link rel="stylesheet" type="text/css" href="css/css_index.php" />
  <script async src="js/site.js"></script> 
  <? require_once('includes/global_banner_footer.php'); ?>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header">
      <? require_once('includes/global_banner.php'); ?>
      <div class="heading">
        <h1>The Best Cryptocurrency Token Sales and ICO List</h1>
        <h2>Listing Cryptocurrency ICOs, Token Sales, Blockchain Events and ICO Stats</h2>
      </div>
    </div>
    <div class="main">
    <? 
      require('includes/index_platinum.php'); 
      require('includes/index_filter.php'); 
      require('includes/index_liveicos.php'); 
      require('includes/index_upcomingicos.php'); 
      require('includes/index_fromblog.php'); 
      require('includes/index_events.php'); 
      require('includes/index_pressmentions.php'); 
    ?>
    </div>
    <? require ('includes/global_footer.php'); ?>
  </div>
</body>
</html>