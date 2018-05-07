<style>
.sitefooter a:link, .sitefooter a:visited
{
  text-decoration: none;
  color: #D5D5D5;    
}
.sitefooter a:hover, .sitefooter a:active
{
  text-decoration: none;
  color: #0FBE7C;    
}
.sitefooter td
{
  border-bottom: 1px solid #444444;
  padding-top: 7px;
  padding-bottom: 7px;
}
.sitefooter 
{
  width: 100%;
  margin-bottom: 20px;
}
</style>
<div style="background-color: #333;color:#D5D5D5;">
  <div class="container">
    <div class="row m-t-lg">
      <div style="width: <? echo $is_mobile?'35%':'200px';?>;display: block;float: left;padding-left: 15px;padding-right: 15px;">
        <span style="font-size: 1.3em;border-bottom: 2px solid #0FBE7C;padding-bottom: 5px;">Website</span>
        <table class="sitefooter" style="margin-top: 15px;color: #D5D5D5;">
        <tr><td><a href="https://www.coinschedule.com">Home</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/about.php">About</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/submit_entry.php">Submit Entry</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/advertise.php">Advertise</a></td></tr>
        <tr><td> <? if((isset($_SESSION['user']) )){ echo'<a href="https://www.coinschedule.com/logout">Logout</a>'; }else{ echo'<a href="https://www.coinschedule.com/login">Login</a>'; } ?></td></tr>
        </table>
      </div> 
      <div style="width: <? echo $is_mobile?'30%':'200px';?>;display: block;float: left;padding-left: 15px;padding-right: 15px;">
        <span style="font-size: 1.3em;border-bottom: 2px solid #0FBE7C;padding-bottom: 5px;">Tools</span>
        <table class="sitefooter" style="margin-top: 15px;color: #D5D5D5;">
        <tr><td><a href="https://www.coinschedule.com/icos.php">Results</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/stats.php">Stats</a></td></tr>
        </table>
        <? if ($is_mobile) { ?>
        <span style="font-size: 1.3em;border-bottom: 2px solid #0FBE7C;padding-bottom: 5px;">Legal</span>
        <table class="sitefooter" style="margin-top: 15px;color: #D5D5D5;">
        <tr><td><a href="https://www.coinschedule.com/terms.php">Terms</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/disclaimer.php">Disclaimer</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/privacypolicy.php">Privacy Policy</a></td></tr>
        </table>
        <? } ?>
      </div> 
      <div style="width: <? echo $is_mobile?'35%':'200px';?>;display: block;float: left;padding-left: 15px;padding-right: 15px">
        <span style="font-size: 1.3em;border-bottom: 2px solid #0FBE7C;padding-bottom: 5px;">Media</span>
        <table class="sitefooter" style="margin-top: 15px;color: #D5D5D5;">
        <tr><td><a href="http://blog.coinschedule.com/" target="_blank">Blog</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/press.php">Press Mentions</a></td></tr>
        <tr><td><a href="https://twitter.com/coinschedule" target="_blank">Twitter</a></td></tr>
        <tr><td><a href="https://www.facebook.com/Coinschedule-673455652847426/" target="_blank">Facebook</a></td></tr>
        <tr><td><a href="https://join.slack.com/t/coinschedule/shared_invite/MjM2NjY2NDk4NzA0LTE1MDQ3NDEwMzYtZjFiYTVmOGUyZA" target="_blank">Slack</a></td></tr>
        <tr><td><a href="https://www.reddit.com/user/coinschedule/" target="_blank">Reddit</a></td></tr>
        <tr><td><a href="https://www.linkedin.com/company/coinschedule" target="_blank">LinkedIn</a></td></tr>
        </table>
      </div>
      <? if (!$is_mobile) { ?>
      <div style="width: <? echo $is_mobile?'30%':'200px';?>;display: block;float: left;padding-left: 15px;padding-right: 15px;">
        <span style="font-size: 1.3em;border-bottom: 2px solid #0FBE7C;padding-bottom: 5px;">Legal</span>
        <table class="sitefooter" style="margin-top: 15px;color: #D5D5D5;">
        <tr><td><a href="https://www.coinschedule.com/terms.php">Terms</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/disclaimer.php">Disclaimer</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/privacypolicy.php">Privacy Policy</a></td></tr>
        <tr><td><a href="https://www.coinschedule.com/cookies_policy.php">Cookie Policy</a></td></tr>
        </table>
      </div> 
      <? } ?>
    </div>
  </div>
</div>
<div style="padding: 7px;height:35px;">
  <div style="display: inline-block;margin-top: 2px;">Copyright &copy; <? echo date("Y"); ?>  <a href="https://www.coinschedule.com">Coinschedule</a></div>
  <div style="display: inline-block;float: right;">

  </div>
</div>