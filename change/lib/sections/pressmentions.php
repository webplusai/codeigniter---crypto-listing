<div class="container"><center> 
  <?
      $pressmentions = mysqli_query($db,"Select * from tbl_press Order By PressDate DESC LIMIT ".($is_mobile?'5':'14'));
      
      while ($press = mysqli_fetch_array($pressmentions))
      {
        echo '
        <div style="display: inline-block;width:210px;height:80px;position: relative;margin: 0px;padding:0px;">
        <a href="'.$press['PressLink'].'" rel="nofollow" target="_blank"><img src="img/'.$press['PressImage'].'"></a>
        </div>
       ';
      }
   ?> 
</center>   
</div>