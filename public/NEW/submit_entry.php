<? 
  require('includes/global_icons.php');
  require('includes/global_data.php');
  require('includes/submit_entry_data.php');
?>
<!doctype html>
<html lang="en">
<head>
  <? require ('includes/global_meta.php'); ?>
  <title>Coinschedule - The best cryptocurrency ICOs (Initial Coin Offering) Crowdsale and Token Sales List</title>
  <link rel="stylesheet" type="text/css" href="css/css_global.php" />
  <link rel="stylesheet" type="text/css" href="css/css_submit_entry.php" />
  <script async src="js/site.js"></script>
  <script async src="js/profile.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>   
        <h1>Submit Entry</h1>
        <? require ('includes/submit_entry_progress.php'); ?>
        <h2><? echo $stepheading; ?></h2>
        <form method="post" action="submit_entry_next.php?step=<? echo $current_step; ?>">
        <? require ('includes/submit_entry_form'.$current_step.'.php'); ?>
        <? if ($current_step<6) { ?><button type="submit">Save and Continue to Step <? echo $current_step+1; ?></button><? } ?>
        </form>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>