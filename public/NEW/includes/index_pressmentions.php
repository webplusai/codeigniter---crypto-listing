<div class="pressmentions">
  <?
    while ($press = mysqli_fetch_array($pressmentions))
    {
      echo '
      <div class="press inline">
      <a href="'.$press['PressLink'].'" rel="nofollow" target="_blank"><img src="images/press/'.$press['PressImage'].'?1" alt="'.$press['PressName'].' Logo" width="189" height="59"></a>
      </div>
      ';
    }
  ?>
</div> 
