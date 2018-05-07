<style>
.icobox:hover {
    background-color: #EEEEEE;
}

.goldrib
{
  position: absolute;
  z-index: 99;
  top:-7px;
  right: -7px;
}

.silvrib
{
  position: absolute;
  z-index: 99;
  top:-6px;
  right: -6px;
}

.silver
{
  width: 50px;
  height: 12px;
  line-height: 1px;
  right: 1px;
  top: 20px;
}

<? if ($is_mobile) { ?>
.ribbon
{
  width: 50px;
  height: 14px;
  line-height: 1px;
  right: 0px;
  top: 18px
  
}
<? } ?>
</style>
<div class="container">
	<h2 style="padding-bottom: 10px;color: #34495e;"><b>Live <? if (!$is_mobile) echo 'Token Sales & '; ?>ICOs<div style="font-size: 0.5em;float: right;margin-top: 10px;">View: <select onChange="location='index.php?live_view=' + this.options[this.selectedIndex].value;"><? if (!$is_mobile) { ?><option value="3" <? echo $live_icoview==3?'selected':''; ?>>Plates</option><? } ?><option value="1" <? echo $live_icoview==1?'selected':''; ?>>Cards</option><option value="2" <? echo $live_icoview==2?'selected':''; ?>>List</option></select></div></b></h2>
  <div>
<?

  if ($live_icoview==2)
  {
  ?>
    <table class="table table-bordered tbl_hoverrow"><thead>
    <tr style="font-weight:bold;background-color: #5E4FA0;color: #fff"><th><? echo $is_mobile?'Details':'Name';?></th><? if (!$is_mobile) { echo'<th width="250">Category</th><th width="200">End Date</th>'; }?><th>Ends In</th></tr></thead><tbody>

<?
  }
  else
  {
    echo $is_mobile?'':'<div>';
  }
    $icorank_threshold = mysqli_fetch_array(mysqli_query($db,"SELECT SettingValue FROM tbl_settings WHERE SettingID = 1"))['SettingValue'];

                $liveicos = mysqli_query($db,"Select * from
                                              (
                                              Select ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjDirectLink,ProjSponsored,ProjPlatinum,ProjDisableRibbon,EventID,EventName,ProjDesc,EventStartDate,EventEndDate,
                                              ProjCatName,ProjCatColor,
                                              ((unix_timestamp() - unix_timestamp(EventStartDate))/(unix_timestamp(EventEndDate)-unix_timestamp(EventStartDate)))*100 as Percent
                                              From tbl_events E 
                                              inner join tbl_projects P On E.EventProjID = P.ProjID
                                              left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                                              left join tbl_submissions S ON S.SubProjID = P.ProjID  
                                              Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > $icorank_threshold and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
                                              EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP
                                              $platfilter
                                              $catfilter
                                              ) as E
                                              Order By ProjPackage DESC, Percent DESC
                                              ");
                           
                while ($liveico = mysqli_fetch_assoc($liveicos)) 
                {

	
  
  if ($liveico['ProjDirectLink'])
  {
    $proj_website = mysqli_query($db,"Select LinkID,LinkType,Link from tbl_links where LinkParentType = 1 and LinkParentID = '".$liveico['ProjID']."' and LinkType = 1");
    if(mysqli_num_rows($proj_website)>0)
    { 
      if ($liveico['ProjAffilLinks'])
      {
        $proj_website= "https://www.coinschedule.com/link.php?l=".mysqli_fetch_assoc($proj_website)['LinkID'];
      }
      else
      {
        $proj_website= mysqli_fetch_assoc($proj_website)['Link'];
      }
    } 
    else
    { 
      $proj_website = '';
    }
    
    $url = $proj_website;
  }
  else
  {
    $url='icos/e'.$liveico['EventID'].'/'.str_replace("+","-",urlencode(strtolower($liveico['EventName']))).'.html';
  }
  
	 
  
  $percent_time=$liveico['Percent'];
	if($percent_time>100)$percent_time=100;
	$percent_time=number_format($percent_time,2);




?>

<? if ($live_icoview==2)
{
  $lastsponsreached = 0;
  
  $enddate = date_create($liveico['EventEndDate']);
  $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':''); 
  $date_number_from=date("U",strtotime($liveico['EventEndDate']));
	$seconds_left_from=$date_number_from-date("U");
	$date_left_from=timeToDate($seconds_left_from);
  
       if ($liveico['ProjPlatinum'])
        {
          $sponsoredstar = '<img style="padding-left: 5px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACyklEQVQ4T3VSXUhTYRh+jvvHpRNzkjvmReoQQe2ihEyKIDQEpYvAEUQaGXXTRUhK+LcMutIu1EFtGMq6mN1oUgtTk5Y1V5YpEfkDrUXLnEMdbu47P/EdmSnMAy/n8D7v83zPed6Pwa6n7O6EWFOZjXc/ViFwInhekErgBXCcgJNHUtFjn8ZHywUmRtv5oI2zrWNiTVUu3i4FtonctgBP31QgJw0W+wd8flQdX+BM0yuxtioXbxZWJEKmRkCWhmDkJyMJncrVo8fuwWzvxfgCpxtfiqZz2fBvRLD0J4QKA0GWToHHMxFoE7XQH1Ci9+knfO27FF+g9PbzNuNhXfNRYypWQ5s4kRREJstidPYXQrKDcM/4MPf9t/mbvbYlbga0WXxzqM2Yldxclq9BEZuItLQ0vHa9h+OLHHPz/j1kOi9ZmZyc1MjlcqMgCDJCCGwTq9fK81VXjxXkSAf5/X48GZ1/VnSItFGcVjgc/tvQ0OCVBNxut0+hUBh4ngfHcVKlpKQgISEBW1tbkMvlWF5exsrKikSm+Pr6OoLB4HYYLperkuM4u8Fg0Op0OqytrWFzcxORSASiKEImk0GtVkOr1UKlUmFqagrT09NDDMP8X4fT6czmeX44IyPDSIc3NjZAHVEBWrQnCAKcTidPCGkxm833djKIJepwOLSiKPbl5eWdp1ZjDiielJSEwcHBVUJItdlsHtl3C/39/ddZlu2hGYTDYWmOnkztDwwMeNvb27N2X/89V5kCVqt1uLi4uEKhUCAajSIQCCA9PR1KpZI6gM/nK+js7JyN66Cjo0OjVqsDJpNJ4/F44PF4FgghLpZlLxcWFsLr9WJ8fLyxq6vrflyB7u7ucr1e/4KuaXFx8SHHcbdaW1tD9fX15YQQa0lJiWFsbMxlsVhK93NwhRByJxqN3mhqanLu/te6urpkhmEecBx33Gaz5cewf29TZ4yje7V9AAAAAElFTkSuQmCC" class="tooltip_new" title="Platinum Level Event">';
        }
        else
        {
          $sponsoredstar = ($liveico['ProjSponsored']&&$liveico['ProjDisableRibbon']==0?'<img style="padding-left: 5px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC10lEQVQ4T3WSbUiTURTH/8+2ZsqmpqWgokHLiSulKIXQkiC0As0PiVKEGlZK0IuESqlZBPXBkgIljQRlEWYoJqlkoWiGaZkvSZiYTc1tuq3ppnP3ee6NTSyFdeFyL+fc/++ce87hsG7F3+5kGYkKfPhpBOUZBIE6NxUoeJ4iZocvytWf8aniJLcm+3txGI7cfMcykkLxfsKwKuRXAYLjdAB2bkOFuh9fqlJdAw4XtrPMpFB0jc87BSq3XuyVtqNSe80JOhTqh3J1H4arT7kGxBW0sbSjCmgXbZjQWXDV/wYUnjoUfb0MkTwSfnIpqusHMFpzxjUgNu91iTLYu2iP0hfWRQ2yZachCUhDx8AcRmT56B2cxsjY7K1v6sxilzVwGKMvNZUoQ7yKMlVtiA5cgGjLQeg6LyJ37CmGv+s3iB3vnamwniB3wiRKUComBCjsST9/IbI1KzgiCxBJYdc8R22X+FXKrqESkNXYhCzOBaSYNE7ASof/NPMID2QMYIyBUeaMDI8QMGICI2YQwxCEJT0YOFBCYF+chN00v1oM60skUgK1ODxHJglMA7NrQe0mUGIEhCWA8QAnASeRg/IE5v5nMPSNNIm98a8d5looBB7N0rBkpTjoBJhdB8ZbwegSmLAMcGLwxl/QtrwQ7BYURz7Enb81WKuovg4yEY8a98i8ZM7NB5Q3gTkyoDZwIhmm6+8byQJSd5fizX+7YGyQZYv848s3+UeB2Q0AXQETLAAnx0xjmSasECHrx3/DKDschkaf5s3KK8c5Nw8I1hmQ31OQbt0OTuQBXedjWLTaiIgiDLvMYKouyN1NvGzwPFDpbv5YCkNPzzjj0C1TKtLlYXFYnv+B2e63Bfvu4a5LgL7OM4HzUrbYNHMwj05WcivIVZXDMpiPBCLgie/+2MCZrq7umEeIdQmYqcJZmx7XbfPIUT1A6/q/9p+Dl9mKspUlRB1rgGrN9wfR71VTy2FMbAAAAABJRU5ErkJggg==" class="tooltip_new" title="Gold Project">':'');
        }

  echo '<tr'.($liveico['ProjSponsored']?' style="font-weight: bold;font-size: 1.1em;color: #34495e;"':'').' onclick="window.location=\''.$url.'\'"><td><table><tr><td width="25" valign="top">'.($liveico['ProjImage']?'<img src="data:image/png;base64,'.$liveico['ProjImage'].'" height="16" width="16">':'').'</td><td><a href="'.$url.'" title="'.$liveico['ProjDesc'].'"'.($is_mobile?' style="font-weight:bold;"':' class="tooltip_new"').'>'.$liveico['EventName'].'</a>'.$sponsoredstar.'</td></tr>'.($is_mobile?'<tr><td></td><td>'.$end.'</td></tr></table></td>':'</table></td><td>'.$liveico['ProjCatName'].'</td><td>'.$end.'</td>').'<td>'.$date_left_from.'</td></tr>';

}
else
{

if ($is_mobile)
{

  if ($liveico['ProjPackage']==0 && !$liveico['ProjSponsored'])
  {
   echo '<div class="icobox" style="color:#34495e;border-radius: 3px;display: inline-block;width:158px;height:75px;margin: 0px 5px 5px 0px;position: relative;padding: 0px;border: '.($liveico['ProjSponsored']?'#F7A61C 2px solid;font-weight:bold':'1px solid').'"> <a href="'.$url.'" '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>'.($liveico['ProjSponsored'] && $liveico['ProjDisableRibbon']==0?'<img src="img/gold.png" class="goldrib" width="80" height="80"><span class="ribbon" style="color:#fff;">Gold</span>':'').($liveico['ProjPackage']==1 && $liveico['ProjDisableRibbon']==0?'<img src="img/silver.png" class="silvrib" width="80" height="80"><span class="ribbon" style="color:#fff;">Silver</span>':'').'
               
                <table width="100%" height="100%">
                <tr>
              
                <td style="text-align:center;">
                <h4 style="font-size: 1.1em;margin:0px;padding-bottom:2px;'.($liveico['ProjSponsored']?'font-weight: bold;':'').'">'.$liveico['EventName'].'</h4>
                <div style="font-size: 1em;'.($percent_time>90?'color:red;':'').($percent_time<=10?'color:green;':'').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>

                </td></tr>
                    <tr>
            <td height="18" colspan="2" style="text-align:center;background-color: #5E4FA0;'.($liveico['ProjCatName']?'border-top: 1px solid':'').'" align="left">
              <div style="margin: 0 auto;color: #fff;font-weight: normal;font-size: 0.9em;">'.$liveico['ProjCatName'].'</div>
            </td>
          </tr>            
                </table>
                </a>
                </div>';
  }
  else
  {
  
echo '<div class="icobox" style="color:#34495e;border-radius: 3px;display: block;width:100%;height:75px;margin-bottom: 10px;position: relative;padding: 0px;border: '.($liveico['ProjSponsored']?'#F7A61C 2px solid;font-weight:bold':'1px solid').'"> <a href="'.$url.'" '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>'.($liveico['ProjSponsored'] && $liveico['ProjDisableRibbon']==0?'<img src="img/gold.png" class="goldrib" width="80" height="80"><span class="ribbon" style="color:#fff;">Gold</span>':'').($liveico['ProjPackage']==1 && $liveico['ProjDisableRibbon']==0?'<img src="img/silver.png" class="silvrib" width="80" height="80"><span class="ribbon" style="color:#fff;">Silver</span>':'').'
               
                <table width="100%" height="100%">
                <tr>
                <td rowspan="1" width="50" style="padding: 5px;" valign="top">
                  <img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" />
                </td>
                <td>
                <h4 style="margin:0px;padding-bottom:2px;'.($liveico['ProjSponsored']?'font-weight: bold;':'').'">'.$liveico['EventName'].'</h4>
                <div style="font-size: 1.1em;'.($percent_time>90?'color:red;':'').($percent_time<=10?'color:green;':'').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>

                </td></tr>
                    <tr>
            <td height="18" colspan="2" style="padding-left: 5px;background-color: #5E4FA0;'.($liveico['ProjCatName']?'border-top: 1px solid':'').'" align="left">
              <div style="margin: 0 auto;color: #fff;font-weight: normal;font-size: 0.9em;">'.strtoupper($liveico['ProjCatName']).'</div>
            </td>
          </tr>            
                </table>
                </a>
                </div>';
  } 
}
else
{

  if ($liveico['ProjSponsored'])
  {
   
    echo '<div class="icobox" style="'.($live_icoview==3?'background-image: url(\'img/goldplate.png?2\');':'').'color:#34495e; text-align: center;display: inline-block;width:210px;height:150px;position: relative;margin: 0px 15px 15px 0px;padding:0px;border: #F7A61C 2px solid;font-weight:bold;"> 
          <a href="'.$url.'" '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
          '.($live_icoview==3?'':($liveico['ProjDisableRibbon']==0?'<img src="img/gold.png" class="goldrib"><span class="ribbon" style="color:#fff;">Gold</span>':'')).'
          <table width="100%" height="100%">
          <tr>
            <td align="center">
              <p style="margin-top: 7px;" class="tooltip_new" title="'.$liveico['ProjDesc'].'">'.($liveico['ProjImageLarge']?'<img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" />':'').'</p>
              <h4 style="'.($live_icoview==3?'color:#000;':'').'margin-bottom:0px;'.($liveico['ProjSponsored']?'font-weight: bold;':'').'">'.$liveico['EventName'].'</h4>
              <div class="tooltip_new" style="font-size: 1.1em;'.($percent_time>90?'color:red;':'color:#000;').($percent_time<=10?'color:green;':'color:#000;').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>
            </td>
          </tr>
          <tr>
            <td height="20" style="background-color: #5E4FA0;'.($liveico['ProjCatName']?'border-top: '.($liveico['ProjSponsored']?'#F7A61C 2px solid;font-weight:bold':'1px solid'):''),'">
              <div style="margin: 0 auto;color: #fff;font-weight: normal;">'.strtoupper($liveico['ProjCatName']).'</div>
            </td>
          </tr>
          </table>
          </a>
          </div>'; 
  }
  else if ($liveico['ProjPackage']==1)
  {
    if (!$lastgoldreached)
    {
      $lastgoldreached = 1;
      echo '</div><div style="height: 10px;"></div><div>';  
    }
    
    if ($live_icoview==1)
    { 
    echo '<div class="icobox" style="color:#34495e;display: inline-block;width:210px;height:85px;position: relative;margin: 0px 15px 10px 0px;padding:0px;border: #A9A9A9 2px solid"> 
          <a href="'.$url.'" '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
          '.($liveico['ProjDisableRibbon']==0?'<img src="img/silver.png?1" class="silvrib" width="80" height="80"><span class="ribbon silver" style="color:#fff;">Silver</span>':'').'
          <table width="100%" height="100%">
          <tr>
            <td width="60">
              <p style="margin: 0px 0px 0px 5px;" class="tooltip_new" title="'.$liveico['ProjDesc'].'">'.($liveico['ProjImageLarge']?'<img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" />':'').'</p>
            </td>
            <td>    
              <h4 style="margin:0px 0px 2px 0px;font-size: 1.1em;width:80px;">'.$liveico['EventName'].'</h4>
              <div class="tooltip_new" style="font-size: 1em;'.($percent_time>90?'color:red;':'').($percent_time<=10?'color:green;':'').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>
            </td>
          </tr>
          <tr>
            <td height="20" colspan="2" style="background-color: #5E4FA0;'.($liveico['ProjCatName']?'border-top: 1px solid':'').'" align="center">
              <div style="margin: 0 auto;color: #fff;font-weight: normal;">'.strtoupper($liveico['ProjCatName']).'</div>
            </td>
          </tr>
          </table>
          </a>
          </div>'; 
    }
    else
    {
    echo '<div class="icobox" style="background-image: url(\'img/silverplate.png?2\');color:#34495e;display: inline-block;width:210px;height:85px;position: relative;margin: 0px 15px 10px 0px;padding:0px;border: #A9A9A9 2px solid"> 
          <a href="'.$url.'" '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
          <table width="100%" height="100%">
          <tr>
            <td width="60">
              <p style="margin: 0px 0px 0px 5px;" class="tooltip_new" title="'.$liveico['ProjDesc'].'"><img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" /></p>
            </td>
            <td>    
              <h4 style="color:#000;margin:0px 0px 2px 0px;font-size: 1.1em;width:140px;font-weight: bold;">'.$liveico['EventName'].'</h4>
              <div class="tooltip_new" style="font-size: 1em;'.($percent_time>90?'color:red;':'color:#000;').($percent_time<=10?'color:green;':'color:#000;').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>
            </td>
          </tr>
          <tr>
            <td height="20" colspan="2" style="background-color: #5E4FA0;'.($liveico['ProjCatName']?'border-top: 1px solid':'').'" align="center">
              <div style="margin: 0 auto;color: #fff;font-weight: normal;">'.strtoupper($liveico['ProjCatName']).'</div>
            </td>
          </tr>
          </table>
          </a>
          </div>'; 
     }
  }
  else
  {
    if (!$lastssilvreached)
    {
      $lastssilvreached = 1;                                
      echo '</div><div style="height: 10px;"></div><div>';  
    }
    
    echo '<div class="icobox" style="color:#34495e;display: inline-block;width:135px;height:68px;position: relative;margin: 0px 3px 3px 0px;padding:0px;border: 1px solid"> 
          <a href="'.$url.'" '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
          <table width="100%" height="100%">
          <tr>
          <td style="font-size: 0.9em;padding: 0px;text-align:center;">    
         
              <h4 style="font-size: 1.1em;margin:0px;" class="tooltip_new" title="'.$liveico['ProjDesc'].'">'.$liveico['EventName'].'</h4>
              <p class="tooltip_new" style="margin:0px;font-size: 0.9em;'.($percent_time>90?'color:red;':'').($percent_time<=10?'color:green;':'').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></p>
            </td>
          </tr>
          <tr><td style="background-color: #5E4FA0;color: #fff;font-size: 0.85em;height:5px;padding: 1px;border-top: 1px solid black;" align="center">'.($liveico['ProjCatName']).'</td></tr>
          </table>
          </a>
          </div>'; 
  }
}

                          
              
    
        
              
              }
              
              }
              
              if ($live_icoview==2){ echo '</tbody></table>';}else {echo '<div>'; }
              ?>

</div>
</div>
<div class="container" style="clear: left;padding: 0px;"><small><i>Note: This is not investment advice. By using Coinschedule you agree to our Disclaimer. <a href="disclaimer.php" target="_blank">FULL DISCLAIMER</a></i></small> </div>