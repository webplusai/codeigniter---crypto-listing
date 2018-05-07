<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <? echo $page=="index"?'<meta http-equiv="refresh" content="300">':''; ?>
    <meta name="google-site-verification" content="QXk4tW89qd3r_SrYOPt2lSXPnkGty3phT2hL9fC7_Pc" />
    <meta name="description" content="<? echo $description ?>" />
    <meta name="keywords" content="<? echo $keywords ?>" />
    <meta name="robots" content="index,follow" />

    <!-- Page title -->
    <title><? echo $title ?></title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->
    <link rel="apple-touch-icon" sizes="57x57" href="/img/logo/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/logo/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/logo/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/logo/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/logo/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/logo/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/logo/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/logo/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/logo/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/img/logo/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/img/logo/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/logo/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/img/logo/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Vendor styles -->                                       
    <link rel="stylesheet" href="/homer/vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="/homer/vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="/homer/vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="/homer/vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="/homer/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="/homer/fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="/homer/styles/style.css?5">
    <link rel="stylesheet" type="text/css" href="/lib/tooltipster/css/tooltipster.bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="/lib/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-punk.min.css" />

    <script src="/homer/vendor/jquery/dist/jquery.min.js"></script>
    <script src="/homer/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/js/notify.min.js"></script>
    <script src="/js/notification.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-6586972-28', 'auto');
  ga('send', 'pageview');
 
</script> 

<style>
iframe {
    max-width: 90vw;
    max-height: 10vw;
}
img {
    max-width: 100%;
    height: auto;
}
</style>
</head>

<body class="landing-page">
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<nav class="navbar navbar-default navbar-fixed-top">   
    <div class="container">       
        <table style="width: 100%;">
        <tr>   
        <td>
        <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button> 
            <a href="https://www.coinschedule.com">  
            <div style="vertical-align:middle;display:table-cell;float: none;<? echo $is_mobile?'padding-left: 10px':''; ?>;padding-right: 30px;height: 50px;font-size: 1.9em;"><b>Coinschedule</b></div>
            </a> 
        </div>  
        <div id="navbar" class="navbar-collapse collapse" style="padding-right: 20px;">
            <ul class="nav navbar-nav navbar-right" >
                <li<? echo $page=="index"?' class="active"':''; ?>><a href="<? echo $page=="index"?'#page-top':'https://www.coinschedule.com'; ?>">Crowdfunds & ICOs</a></li>
                <li><a class="page-scroll" page-scroll href="<? echo $page=="index"?'#events':'https://www.coinschedule.com#events'; ?>">Events</a></li>
                <li><a class="page-scroll" page-scroll href="<? echo $page=="index"?'#blog':'https://www.coinschedule.com#blog'; ?>">Blog</a></li>
                <li<? echo $page=="about"?' class="active"':''; ?>><a href="https://www.coinschedule.com/about.php">About</a></li>
                <li<? echo $page=="submit"?' class="active"':''; ?>><a href="https://www.coinschedule.com/submit_entry.php">Submit Entry</a></li>
                <li<? echo $page=="advertise"?' class="active"':''; ?>><a href="https://www.coinschedule.com/advertise.php">Advertise</a></li>
                <? if((isset($_SESSION['user']) )){ echo''; }else{ echo'<li'.($page=="login"?' class="active"':'').'><a href="https://www.coinschedule.com/login">Login</a></li>'; } ?>
            </ul>     
        </div>
         </div> 
        </td> 
        <? if((isset($_SESSION['user']) )){ ?>
        <td valign="top" align="right" <? echo !$is_mobile?'width="20"':''; ?>>            
        <div class="dropdown" style="padding-top: 8px;">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="mnu_main"><? echo $is_mobile?strtoupper(substr($userinfo['tx_username'],0,1)):$userinfo['tx_username'].' <span class="caret"></span>'; ?>
            </button>
            <ul class="dropdown-menu" <? echo $is_mobile?'style="left: -110px;top:30px;font-size: 1.1em;"':'style="left: -80px;font-size: 1.1em;"';?>>
              <!--<li><a href="https://www.coinschedule.com/alerts.php"><span style="padding-right: 15px;"><? echo $icon_alert; ?></span>Alerts</a></li>-->
              <li><a href="https://www.coinschedule.com/profile.php"><span style="padding-right: 15px;"><? echo $icon_profile; ?></span>Profile</a></li>
              <li class="divider"></li>
              <li><a href="https://www.coinschedule.com/listings.php"><span style="padding-right: 15px;"><? echo $icon_listings; ?></span>Listings</a></li>
              <!--<li><a href="https://www.coinschedule.com/widgets.php"><span style="padding-right: 15px;"><? echo $icon_widgets; ?></span>Widgets</a></li>-->
              <!--<li class="divider"></li>-->
              <!--<li><a href="https://www.coinschedule.com/contact.php"><span style="padding-right: 15px;"><? echo $icon_contact; ?></span>Contact</a></li>-->
              <li class="divider"></li>
              <li><a href="https://www.coinschedule.com/logout.php"><span style="padding-right: 15px;"><? echo $icon_logout; ?></span>Logout</a></li>
            </ul>
         </div>
                                           
        </td>
        <? } ?>
        </tr>
        </table>
   
</nav>
<div class="page-container">