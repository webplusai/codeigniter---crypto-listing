<? 
 date_default_timezone_set('UTC'); 
 ob_start();
 session_start();
 require "./lib/bd.php";
 require_once "./lib/functions.php";
 require_once "/home/coinschedule/cron/btcusd.php";
 
 if((isset($_SESSION['user']) )) {
	 // select loggedin users detail
	 $res=mysqli_query($db,"SELECT * FROM tbl_users WHERE id_user=".$_SESSION['user']);
	 $userRow=mysqli_fetch_array($res);
 }


 // Make sure prices don't go outside USD price ranges
 $USDpricetol_percent = 20;
 
 // Sponsered Listing
 $sponsprice =2;
 
  // Sponsered Listing
 $silvprice = 0.5;
 //$sponsUSDprice = 2250;
 //$sponsUSDminprice = $sponsUSDprice * ((100-$USDpricetol_percent)/100);
 //$sponsUSDmaxprice = $sponsUSDprice * ((100+$USDpricetol_percent)/100);
 //if (($sponsprice * $btcusdprice) < $sponsUSDminprice) { $sponsprice = round($sponsUSDminprice/$btcusdprice,4); }
 //elseif (($sponsprice * $btcusdprice) > $sponsUSDmaxprice) { $sponsprice = round($sponsUSDmaxprice/$btcusdprice,4); } 
   
 // Banner 
 $banprice = 0.853125;
 $banUSDpprice = 2500;
 $banUSDminprice = $banUSDpprice * ((100-$USDpricetol_percent)/100);
 $banUSDmaxprice = $banUSDpprice * ((100+$USDpricetol_percent)/100);
 if (($banprice * $btcusdprice) < $banUSDminprice) { $banprice = round($banUSDminprice/$btcusdprice,4); }
 elseif (($banprice * $btcusdprice) > $banUSDmaxprice) { $banprice = round($banUSDmaxprice/$btcusdprice,4); } 

 // Blog 
 $blogprice = 0.9375;
 $blogUSDprice = 3000;
 $blogUSDminprice = $blogUSDprice * ((100-$USDpricetol_percent)/100);
 $blogUSDmaxprice = $blogUSDprice * ((100+$USDpricetol_percent)/100);
 if (($blogprice * $btcusdprice) < $blogUSDminprice) { $blogprice = round($blogUSDminprice/$btcusdprice,4); }
 elseif (($blogprice * $btcusdprice) > $blogUSDmaxprice) { $blogprice = round($blogUSDmaxprice/$btcusdprice,4); } 
 
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>Coinschedule - Marketing Packages</title>

    <? require_once('lib/sections/head.php'); ?> 
</head>
<body class="landing-page">
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<?php
$page = 'advertise';
require_once("lib/icons.php");
require_once("lib/sections/nav_header.php");
?>


<div class="page-container">
<header id="page-top">
<div class="container">
  <h2 style="padding-bottom: 10px;">Marketing Packages</h2>

Every day many thousands of people from around the world visit Coinschedule looking for ICOs to join. As of September 2017, Coinschedule is serving over 3 million pages views per month and is the biggest ICO listing website in the world. <br><br>

We are regularly mentioned in the main stream media press (<a href="https://www.coinschedule.com/press.php" target="_blank">https://www.coinschedule.com/press.php</a>) and our traffic is highly valuable, with visitors coming mostly from USA, UK, Australia, Japan and Canada.<br><br>

Some of the most successful projects in 2017 used Coinschedule's marketing tools including Paragon ($183 million raised), MobileGo ($53 million raised), STORJ ($29 million raised) and many others.<br><br>

The options below are designed to help your ICO attract more participants. Prices are automatically calculated by our system based on current demand. We have limited slots for each one of the options below, so we recommend you don't leave it to late as your preferred option may not be available anymore, or the prices may have changed.<br><br><br>
<b>1. Plus Listing -  0.29 BTC</b><br>
Highlights your ICO in the upcoming list and shows in standard box but in gold colour (as supposed to purple) when ICO is live. We will show your links (Twitter etc) on the details page.<br><br><br>
<b>2. Silver Listing - <? echo $silvprice ?> BTC</b><br>
Highlights your ICO in the upcoming list and shows on Silver plate when ICO is live.  Will show your links (Twitter etc) on the details page. No adverts will be shown on your project page.<br><br><br>
<b>3. Gold Listing - <? echo $sponsprice ?> BTC</b><br>
Highlights your ICO in the upcoming list and shows on Gold plate when ICO is live. Gives you an improved details page with full information. No adverts will be shown on your project page.<br><br><br>
<b>4. Top of Upcoming List (for Gold projects only) - 0.3 BTC Per Month</b><br>
Highlights your ICO in the upcoming list at the very top.  This placement is limited to 2 projects max.<br><br><br>
<b>5. Platinum Listing - 5 BTC Per Month</b><br>
Same as gold plus highlights your ICO in the upcoming list at the very top and gives you static banner with timer at the top of our website (just above the live ico section).  We will also promote your ICO on twitter. Please contact us for availability since spaces are very limited.<br><br><br>
<b>6. Ad Banner - <? echo $banprice; ?> BTC for each 100,000 views</b><br> 
Shows your banner on rotation basis for any campaign length 2 weeks or greater.  Our system will serve the number of views you purchase over the campaign time.  Our audience is highly targeted, people visiting our website are looking for ICOs to invest.  Banner size is 728 x 90 pixels.
<br><br><br>
<b>7. Blog Post - <? echo $blogprice; ?> BTC</b><br>
Allows you to explain the ICO and provide visitors with your story. We make it look like a conversation as if we were interviewing you over the phone or you can write your own article. The blog is a great way to explain to people more about your ICO in a format that is easy to read and looks unbiased.  We will also tweet the blog out to our followers. Blog picture is 900 x 300 pixels.
<br><br><br>
<b>8. Tweet Post + Telegram - 0.05 BTC</b><br>
We will send a tweet out to our followers, you may provide the text and optional image. <a href="https://twitter.com/coinschedule" target="_blank">https://twitter.com/coinschedule</a>.  We will also post your message in our telegram group. 
<br><br><br>To purchase any or all of the options above, please send an email to <a href="mailto:moon@coinschedule.com">moon@coinschedule.com</a> stating: Which options you will want (1, 2, 3, 4, 5, 6, 7, 8). If you want ad banners, how many views you want (maximum of 200k views per project, to be delivered over a month as default).
<br><br>
Note: We are also open to taking part of the payment (upto 1/3) in your own tokens if we like your project.  Tokens must be transfered immediately and not after the ICO has finished. 
<br><br>
If you are just wanting to list your project you need to go to <a href="https://www.coinschedule.com/submit_entry.php">https://www.coinschedule.com/submit_entry.php</a> You have to log in/register.  We have small listing fee of 0.1BTC.
</div>


</header>
<? require_once('lib/sections/footer.php'); ?>
</div>

<div style="height: 20px;"></div>
<script src="/homer/vendor/jquery/dist/jquery.min.js"></script>
<script src="/homer/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-6586972-28', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>