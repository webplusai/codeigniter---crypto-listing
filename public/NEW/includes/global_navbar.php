<div class="navbar">
  <div class="container">
    <div class="nav-container">      
      <div style="float: left;">
        <a href="<? echo $baseurl; ?>index.php">
          <div class="logo inline"><img src="<? echo $CSLogo; ?>" alt="Coinschedule Logo"><div class="logo-text"><span>Coin</span>Schedule</div></div>
        </a>
          
      <? require ('global_mediasquares.php'); ?>
      </div>
      
      <? if((isset($_SESSION['user']) )){ ?>
        <input type="checkbox" id="usermenu-trigger" class="usermenu-trigger" onChange="$('.nav-trigger').prop('checked', false);"/>
        <label for="usermenu-trigger" class="usermenubutton">
        <span class="username"><? echo $userName; ?></span>
        <span class="userinitial"><? echo strtoupper(substr($userName,0,1)); ?></span>
        </label>
        
        <div class="usermenu">
          <div class="usermenu-item"><a href="<? echo $baseurl; ?>profile.php"><img src="<? echo $profile_icon; ?>" class="inline">Profile</a></div>
          <div class="usermenu-item"><a href=""><img src="<? echo $listings_icon; ?>" class="inline">Listings</a></div>
          <div class="usermenu-item"><a href=""><img src="<? echo $logout_icon; ?>" class="inline">Logout</a></div>
        </div>
      <? } ?>
        
      <input type="checkbox" id="nav-trigger" class="nav-trigger" onChange="$('.usermenu-trigger').prop('checked', false);"/>
      <label for="nav-trigger">
        <span class="burger inline">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </label>
        
      <div class="nav inline">
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>index.php#">ICOs</a></div>
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>index.php#blog">Blog</a></div>
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>index.php#events">Events</a></div>
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>icos.php">Results</a></div>
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>stats.php">Stats</a></div>
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>submit_entry.php">Submit</a></div>
        <div class="nav-item inline"><a href="<? echo $baseurl; ?>advertise.php">Advertise</a></div>
        <? if((!isset($_SESSION['user']) )){ ?><div class="nav-item inline"><a href="<? echo $baseurl; ?>login.php">Login</a></div><? } ?>
      </div>
    </div>     
  </div>
</div>
