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
  <script async src="js/site.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>   
      <h1>Press Mentions</h1>
      <?
        $pressmentions = mysqli_query($db,"Select * from tbl_press Where PressImage <> '' Order By PressDate DESC");
        
        while ($press = mysqli_fetch_array($pressmentions))
        {
          echo '
          <a href="'.$press['PressLink'].'" rel="nofollow" target="_blank"><img class="inline" style="padding-right: 10px;" src="images/press/'.$press['PressImage'].'">
          <div class="inline">
          <h3 style="margin-bottom:2px;">'.$press['PressHeadline'].'</h3></a>
          <i>'.date("F jS, Y",strtotime($press['PressDate'])).'</i>
          </div>
          <hr>
         ';
        }
      ?>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>