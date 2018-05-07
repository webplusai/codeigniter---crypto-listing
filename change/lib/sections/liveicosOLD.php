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
	<h2 style="padding-bottom: 10px;color: #34495e;"><b>Live <? if (!$is_mobile) echo 'Crowdfunds & '; ?>ICOs<div style="font-size: 0.5em;float: right;margin-top: 10px;">View: <select onChange="location='index.php?live_view=' + this.options[this.selectedIndex].value;"><option value="1">Cards</option><option value="2" <? echo $live_icoview==2?'selected':''; ?>>List</option></select></div></b></h2>
  <div>
<?

  if ($live_icoview==2)
  {
  ?>
    <table class="table table-bordered tbl_hoverrow"><thead>
    <tr style="font-weight:bold;background-color: #5E4FA0;color: #fff"><th><? echo $is_mobile?'Details':'Name';?></th><? if (!$is_mobile) { echo'<th width="250">Category</th><th width="200">End Date</th>'; }?><th>Ends In</th></tr></thead><tbody>

<?
  }

                $liveicos = mysqli_query($db,"Select * from
                                              (
                                              Select ProjID,ProjImage,ProjImageLarge,ProjSponsored,ProjPlatinum,ProjDisableRibbon,EventName,ProjDesc,EventStartDate,EventEndDate,
                                              ProjCatName,ProjCatColor,
                                              ((unix_timestamp() - unix_timestamp(EventStartDate))/(unix_timestamp(EventEndDate)-unix_timestamp(EventStartDate)))*100 as Percent
                                              From tbl_events E 
                                              inner join tbl_projects P On E.EventProjID = P.ProjID
                                              left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                                              left join tbl_submissions S ON S.SubProjID = P.ProjID
                                              Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > 16 and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
                                              EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP
                                              ) as E
                                              Order By ProjSponsored DESC, Percent DESC
                                              ");
                           
                while ($liveico = mysqli_fetch_assoc($liveicos)) 
                {

	$url='projects/'.$liveico['ProjID'].'/'.str_replace("+","-",urlencode(strtolower($liveico['EventName']))).'.html';
	$percent_time=$liveico['Percent'];
	if($percent_time>100)$percent_time=100;
	$percent_time=number_format($percent_time,2);

?>

<? if ($live_icoview==2)
{
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
?>
<div class="icobox" style="color:#34495e; <? echo $is_mobile?'border-radius: 3px;display: block;width:100%;height:80px;margin-bottom: 10px;position: relative;padding: 8px;':'text-align: center;float: left;width:210px;height:160px;position: relative;margin: 0px 15px 15px 0px;padding:0px;'; ?>border: <? echo $liveico['ProjSponsored']?'#F7A61C 2px solid;font-weight:bold':'1px solid'; ?>; "> <a href="<? echo $url?>">   <? if($liveico['ProjSponsored'] && $liveico['ProjDisableRibbon']==0){?> <img src="img/gold.png" class="goldrib" <? echo $is_mobile?'width="80" height="80"':''; ?>><span class="ribbon" style="color:#fff;">Gold</span><? }?>
               
                <table width="100%" height="100%">
                <tr>
                <td<? echo $is_mobile?'':' align="center"'; ?>>
                <p<? echo $is_mobile?' style="float: left;margin-right: 10px;margin-top: 7px;"':' style="margin-top: 7px;" class="tooltip_new"'; ?>  title="<? echo $liveico['ProjDesc'];?>"><img src="data:image/png;base64,<? echo $liveico['ProjImageLarge'];?>" height="48" width="48" /></p>
                <h4 style="<? echo $is_mobile?'margin-top: 11px;margin-bottom:3px':'margin-bottom:0px';?>;<? if($liveico['ProjSponsored']){?> font-weight: bold; <? } ?>">
               
                  
                  <? echo $liveico['EventName'];?>
                                                            
                  </h4>
                <i <? echo $is_mobile?'':'class="tooltip_new"'; ?> style="font-size: 1.1em;<? if($percent_time>90) echo 'color:red;';?><? if($percent_time<=10) echo 'color:green';?>" title="This ICO has already gone through <? echo $percent_time?>% of its planned crowdfunding time"><b><? echo $percent_time?>% done</b></i>
                  
                </a> 
                </td></tr>
                <? if (!$is_mobile) { ?>
                <tr><td height="20" style="background-color: #5E4FA0;<? echo $liveico['ProjCatName']?'border-top: '.($liveico['ProjSponsored']?'#F7A61C 2px solid;font-weight:bold':'1px solid'):''; ?>"><div style="margin: 0 auto;color: #fff;font-weight: normal;"><? echo strtoupper($liveico['ProjCatName']); ?></div></td></tr>
                <? } ?>
                </table>
                </a>
                </div> 
                          
              
              <? 
        
              
              }
              
              }
              
              if ($live_icoview==2){ echo '</tbody></table>';}
              ?>

</div>
</div>
<div class="container"><small><i>Note: This is not investment advice. By using Coinschedule you agree to our Disclaimer. <a href="disclaimer.php" target="_blank">FULL DISCLAIMER</a></i></small> </div>