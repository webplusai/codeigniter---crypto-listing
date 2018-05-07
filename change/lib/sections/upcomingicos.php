<style>
<? if (!$is_mobile) { ?>.tbl_hoverrow > tbody > tr:nth-child(odd) {
  background-color: #EAF3F3;
}<? } ?>

.tbl_hoverrow tr:hover td{
  background-color: #D9E4E6;
  cursor: pointer;
}

.nohover tr:hover {
  cursor: default;
}
</style>
<div class="container">
	<h2 style="padding-bottom: 10px;color: #34495e;"><b>Upcoming <? if (!$is_mobile) echo 'Token Sales & '; ?>ICOs</b></h2>
   <div style="padding-right: 17px;background-color: #5E4FA0;">
   <table class="table" style="margin-bottom: 0px;border: 0px none;"> <tr style="font-weight:bold;background-color: #5E4FA0;color: #fff;cursor: default;"><th style="border-top: 0px;border-right: 1px solid #ddd;width: <? echo $is_mobile?'240px':'33%';?>"><? echo $is_mobile?'Details':'Name';?></th><? if (!$is_mobile) { echo'<th  style="border-top: 0px;border-right: 1px solid #ddd;width: 28%;">Category</th><th  style="border-top: 0px;border-right: 1px solid #ddd;width: 28%;">Start Date</th>'; }?><th style="border-top: 0px;">Starts In</th></tr></table></div>
  <div style="width:100%; max-height:<? echo $is_mobile?'690px':'700px'; ?>; overflow-y:scroll;">
  <table class="table table-bordered tbl_hoverrow"><tbody>
    <?
      $icorank_threshold = mysqli_fetch_array(mysqli_query($db,"SELECT SettingValue FROM tbl_settings WHERE SettingID = 1"))['SettingValue'];
    
      $crowdfunds = mysqli_query($db,"Select ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjSponsored,ProjPlatinum,ProjDisableRibbon,EventName,ProjDesc,EventID,EventStartDate,EventStartDateType,
                            ProjCatName,ProjCatColor 
                            From tbl_events E 
                            inner join tbl_projects P On E.EventProjID = P.ProjID
                            left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                            left join tbl_submissions S ON S.SubProjID = P.ProjID
                            Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > $icorank_threshold and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
                            (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3) 
                            $platfilter
                            $catfilter
                            Order By ProjPlatinum DESC,ProjPackage DESC,EventStartDateType,EventStartDate,ProjID
                            ");
                            
      while ($crowdfund = mysqli_fetch_assoc($crowdfunds)) 
      {
        if ($crowdfund['EventStartDateType']==1)
        {                                   
          $startdate = date_create($crowdfund['EventStartDate']);
          $start = date_format($startdate,"M jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':''); 
          $date_number_from=date("U",strtotime($crowdfund['EventStartDate']));
	        $seconds_left_from=$date_number_from-date("U");
	        $date_left_from=timeToDate($seconds_left_from);
        }
        else
        {
          $start = "TBA";
          $date_left_from = "TBA";         
        }
                                 
        if ($crowdfund['ProjPlatinum'])
        {
          $sponsoredstar = '<img style="padding-left: 15px" src="data:image/png;base64,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" class="tooltip_new" title="Platinum Level Event">';
        }
        else if($crowdfund['ProjPackage']==1)
        {
         $sponsoredstar = '<img style="padding-left: 5px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACyklEQVQ4T3VSXUhTYRh+jvvHpRNzkjvmReoQQe2ihEyKIDQEpYvAEUQaGXXTRUhK+LcMutIu1EFtGMq6mN1oUgtTk5Y1V5YpEfkDrUXLnEMdbu47P/EdmSnMAy/n8D7v83zPed6Pwa6n7O6EWFOZjXc/ViFwInhekErgBXCcgJNHUtFjn8ZHywUmRtv5oI2zrWNiTVUu3i4FtonctgBP31QgJw0W+wd8flQdX+BM0yuxtioXbxZWJEKmRkCWhmDkJyMJncrVo8fuwWzvxfgCpxtfiqZz2fBvRLD0J4QKA0GWToHHMxFoE7XQH1Ci9+knfO27FF+g9PbzNuNhXfNRYypWQ5s4kRREJstidPYXQrKDcM/4MPf9t/mbvbYlbga0WXxzqM2Yldxclq9BEZuItLQ0vHa9h+OLHHPz/j1kOi9ZmZyc1MjlcqMgCDJCCGwTq9fK81VXjxXkSAf5/X48GZ1/VnSItFGcVjgc/tvQ0OCVBNxut0+hUBh4ngfHcVKlpKQgISEBW1tbkMvlWF5exsrKikSm+Pr6OoLB4HYYLperkuM4u8Fg0Op0OqytrWFzcxORSASiKEImk0GtVkOr1UKlUmFqagrT09NDDMP8X4fT6czmeX44IyPDSIc3NjZAHVEBWrQnCAKcTidPCGkxm833djKIJepwOLSiKPbl5eWdp1ZjDiielJSEwcHBVUJItdlsHtl3C/39/ddZlu2hGYTDYWmOnkztDwwMeNvb27N2X/89V5kCVqt1uLi4uEKhUCAajSIQCCA9PR1KpZI6gM/nK+js7JyN66Cjo0OjVqsDJpNJ4/F44PF4FgghLpZlLxcWFsLr9WJ8fLyxq6vrflyB7u7ucr1e/4KuaXFx8SHHcbdaW1tD9fX15YQQa0lJiWFsbMxlsVhK93NwhRByJxqN3mhqanLu/te6urpkhmEecBx33Gaz5cewf29TZ4yje7V9AAAAAElFTkSuQmCC" class="tooltip_new" title="Silver Project">';
         }
        else
        {
          $sponsoredstar = ($crowdfund['ProjSponsored']&&$crowdfund['ProjDisableRibbon']==0?'<img style="padding-left: 5px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC10lEQVQ4T3WSbUiTURTH/8+2ZsqmpqWgokHLiSulKIXQkiC0As0PiVKEGlZK0IuESqlZBPXBkgIljQRlEWYoJqlkoWiGaZkvSZiYTc1tuq3ppnP3ee6NTSyFdeFyL+fc/++ce87hsG7F3+5kGYkKfPhpBOUZBIE6NxUoeJ4iZocvytWf8aniJLcm+3txGI7cfMcykkLxfsKwKuRXAYLjdAB2bkOFuh9fqlJdAw4XtrPMpFB0jc87BSq3XuyVtqNSe80JOhTqh3J1H4arT7kGxBW0sbSjCmgXbZjQWXDV/wYUnjoUfb0MkTwSfnIpqusHMFpzxjUgNu91iTLYu2iP0hfWRQ2yZachCUhDx8AcRmT56B2cxsjY7K1v6sxilzVwGKMvNZUoQ7yKMlVtiA5cgGjLQeg6LyJ37CmGv+s3iB3vnamwniB3wiRKUComBCjsST9/IbI1KzgiCxBJYdc8R22X+FXKrqESkNXYhCzOBaSYNE7ASof/NPMID2QMYIyBUeaMDI8QMGICI2YQwxCEJT0YOFBCYF+chN00v1oM60skUgK1ODxHJglMA7NrQe0mUGIEhCWA8QAnASeRg/IE5v5nMPSNNIm98a8d5looBB7N0rBkpTjoBJhdB8ZbwegSmLAMcGLwxl/QtrwQ7BYURz7Enb81WKuovg4yEY8a98i8ZM7NB5Q3gTkyoDZwIhmm6+8byQJSd5fizX+7YGyQZYv848s3+UeB2Q0AXQETLAAnx0xjmSasECHrx3/DKDschkaf5s3KK8c5Nw8I1hmQ31OQbt0OTuQBXedjWLTaiIgiDLvMYKouyN1NvGzwPFDpbv5YCkNPzzjj0C1TKtLlYXFYnv+B2e63Bfvu4a5LgL7OM4HzUrbYNHMwj05WcivIVZXDMpiPBCLgie/+2MCZrq7umEeIdQmYqcJZmx7XbfPIUT1A6/q/9p+Dl9mKspUlRB1rgGrN9wfR71VTy2FMbAAAAABJRU5ErkJggg==" class="tooltip_new" title="Gold Project">':'');
        }
        
        
       
        
        $url='icos/e'.$crowdfund['EventID'].'/'.str_replace("+","-",urlencode(strtolower($crowdfund['EventName']))).'.html';
        
        echo '<tr'.($crowdfund['ProjSponsored']?' style="font-weight: bold;font-size: '.($crowdfund['ProjPlatinum']?'1.3':'1.1').'em;"':'').' onclick="window.location=\''.$url.'\'"><td style="width: '.($is_mobile?'240px':'33%').';"><table class="link"><tr><td width="'.($crowdfund['ProjPlatinum']?'60':'25').'" valign="top">'.($crowdfund['ProjPlatinum']?'<img src="data:image/png;base64,'.$crowdfund['ProjImageLarge'].'" height="48" width="48">':($crowdfund['ProjImage']?'<img src="data:image/png;base64,'.$crowdfund['ProjImage'].'" height="16" width="16">':'')).'</td><td style="padding-top: 1px;"><a href="'.$url.'" title="'.$crowdfund['ProjDesc'].'"'.($is_mobile?' style="font-weight:bold;"':' class="tooltip_new"').'>'.$crowdfund['EventName'].'</a>'.$sponsoredstar.'</td></tr>'.($is_mobile?'<tr><td></td><td>'.$start.'</td></tr></table></td>':'</table></td><td style="vertical-align: middle;width: 28%;">'.$crowdfund['ProjCatName'].'</td><td style="vertical-align: middle;width: 28%;">'.$start.'</td>').'<td style="vertical-align: middle;">'.$date_left_from.'</td></tr>';
      }
    ?></tbody>
    </table>
</div>