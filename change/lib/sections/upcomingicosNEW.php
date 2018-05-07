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


.progress {
	overflow: hidden;
	height: 20px;
	background-color: #eee;
	border-radius: 2px;
	-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
	box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
	margin-bottom: 0px;
    
}
.progress-bar {
	width: 0;
	height: 100%;
	color: #fff;
	text-align: center;
	background-color: #888;
  padding: 0px;
}

.rank {
  position: absolute;
  right:0;
  font-weight: normal;
  margin-right: 4px;
  margin-top: 3px;
  font-size: 0.8em;
}

    

</style> 

<div class="container">
	<h2 style="padding-bottom: 10px;color: #34495e;"><b>Upcoming <? if (!$is_mobile) echo 'Crowdfunds & '; ?>ICOs</b></h1>
  <table class="table table-bordered tbl_hoverrow"><thead>
    <tr style="font-weight:bold;background-color: #5E4FA0;color: #fff;cursor: default;"><th><? echo $is_mobile?'Details':'Name';?></th><? if (!$is_mobile) { echo'<th width="250">Category</th><th width="200">Start Date</th>'; }?><th>Starts In</th><? if (!$is_mobile) { echo'<th>Ico Rank</th>'; } ?></tr></thead><tbody>
    <?
    
      $crowdfunds = mysqli_query($db,"Select ProjID,ProjImage,ProjSponsored,ProjPlatinum,ProjDisableRibbon,EventName,ProjDesc,EventStartDate,EventStartDateType,
                            ProjCatName,ProjCatColor,ProjICORank 
                            From tbl_events E 
                            inner join tbl_projects P On E.EventProjID = P.ProjID
                            left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                            left join tbl_submissions S ON S.SubProjID = P.ProjID
                            Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > 16 and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
                            (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3) 
                            Order By EventStartDateType,ProjSponsored DESC,EventStartDate,ProjID
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
          $sponsoredstar = '<img style="padding-left: 5px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACyklEQVQ4T3VSXUhTYRh+jvvHpRNzkjvmReoQQe2ihEyKIDQEpYvAEUQaGXXTRUhK+LcMutIu1EFtGMq6mN1oUgtTk5Y1V5YpEfkDrUXLnEMdbu47P/EdmSnMAy/n8D7v83zPed6Pwa6n7O6EWFOZjXc/ViFwInhekErgBXCcgJNHUtFjn8ZHywUmRtv5oI2zrWNiTVUu3i4FtonctgBP31QgJw0W+wd8flQdX+BM0yuxtioXbxZWJEKmRkCWhmDkJyMJncrVo8fuwWzvxfgCpxtfiqZz2fBvRLD0J4QKA0GWToHHMxFoE7XQH1Ci9+knfO27FF+g9PbzNuNhXfNRYypWQ5s4kRREJstidPYXQrKDcM/4MPf9t/mbvbYlbga0WXxzqM2Yldxclq9BEZuItLQ0vHa9h+OLHHPz/j1kOi9ZmZyc1MjlcqMgCDJCCGwTq9fK81VXjxXkSAf5/X48GZ1/VnSItFGcVjgc/tvQ0OCVBNxut0+hUBh4ngfHcVKlpKQgISEBW1tbkMvlWF5exsrKikSm+Pr6OoLB4HYYLperkuM4u8Fg0Op0OqytrWFzcxORSASiKEImk0GtVkOr1UKlUmFqagrT09NDDMP8X4fT6czmeX44IyPDSIc3NjZAHVEBWrQnCAKcTidPCGkxm833djKIJepwOLSiKPbl5eWdp1ZjDiielJSEwcHBVUJItdlsHtl3C/39/ddZlu2hGYTDYWmOnkztDwwMeNvb27N2X/89V5kCVqt1uLi4uEKhUCAajSIQCCA9PR1KpZI6gM/nK+js7JyN66Cjo0OjVqsDJpNJ4/F44PF4FgghLpZlLxcWFsLr9WJ8fLyxq6vrflyB7u7ucr1e/4KuaXFx8SHHcbdaW1tD9fX15YQQa0lJiWFsbMxlsVhK93NwhRByJxqN3mhqanLu/te6urpkhmEecBx33Gaz5cewf29TZ4yje7V9AAAAAElFTkSuQmCC" class="tooltip_new" title="Platinum Level Event">';
        }
        else
        {
          $sponsoredstar = ($crowdfund['ProjSponsored']&&$crowdfund['ProjDisableRibbon']==0?'<img style="padding-left: 5px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC10lEQVQ4T3WSbUiTURTH/8+2ZsqmpqWgokHLiSulKIXQkiC0As0PiVKEGlZK0IuESqlZBPXBkgIljQRlEWYoJqlkoWiGaZkvSZiYTc1tuq3ppnP3ee6NTSyFdeFyL+fc/++ce87hsG7F3+5kGYkKfPhpBOUZBIE6NxUoeJ4iZocvytWf8aniJLcm+3txGI7cfMcykkLxfsKwKuRXAYLjdAB2bkOFuh9fqlJdAw4XtrPMpFB0jc87BSq3XuyVtqNSe80JOhTqh3J1H4arT7kGxBW0sbSjCmgXbZjQWXDV/wYUnjoUfb0MkTwSfnIpqusHMFpzxjUgNu91iTLYu2iP0hfWRQ2yZachCUhDx8AcRmT56B2cxsjY7K1v6sxilzVwGKMvNZUoQ7yKMlVtiA5cgGjLQeg6LyJ37CmGv+s3iB3vnamwniB3wiRKUComBCjsST9/IbI1KzgiCxBJYdc8R22X+FXKrqESkNXYhCzOBaSYNE7ASof/NPMID2QMYIyBUeaMDI8QMGICI2YQwxCEJT0YOFBCYF+chN00v1oM60skUgK1ODxHJglMA7NrQe0mUGIEhCWA8QAnASeRg/IE5v5nMPSNNIm98a8d5looBB7N0rBkpTjoBJhdB8ZbwegSmLAMcGLwxl/QtrwQ7BYURz7Enb81WKuovg4yEY8a98i8ZM7NB5Q3gTkyoDZwIhmm6+8byQJSd5fizX+7YGyQZYv848s3+UeB2Q0AXQETLAAnx0xjmSasECHrx3/DKDschkaf5s3KK8c5Nw8I1hmQ31OQbt0OTuQBXedjWLTaiIgiDLvMYKouyN1NvGzwPFDpbv5YCkNPzzjj0C1TKtLlYXFYnv+B2e63Bfvu4a5LgL7OM4HzUrbYNHMwj05WcivIVZXDMpiPBCLgie/+2MCZrq7umEeIdQmYqcJZmx7XbfPIUT1A6/q/9p+Dl9mKspUlRB1rgGrN9wfR71VTy2FMbAAAAABJRU5ErkJggg==" class="tooltip_new" title="Gold Project">':'');
        }
        
        $icorank = '<div class="progress" style="position: relative;">
                      <div class="progress-bar" style="width: '.$crowdfund['ProjICORank'].'%">
                      </div>
                         <span class="rank">'.$crowdfund['ProjICORank'].'</span>
                    </div>';
                        
       
        
        $url='projects/'.$crowdfund['ProjID'].'/'.str_replace("+","-",urlencode(strtolower($crowdfund['EventName']))).'.html';
        
        echo '<tr'.($crowdfund['ProjSponsored']?' style="font-weight: bold;font-size: 1.1em;"':'').' onclick="window.location=\''.$url.'\'"><td><table class="link"><tr><td width="25" valign="top">'.($crowdfund['ProjImage']?'<img src="data:image/png;base64,'.$crowdfund['ProjImage'].'" height="16" width="16">':'').'</td><td><a href="'.$url.'" title="'.$crowdfund['ProjDesc'].'"'.($is_mobile?' style="font-weight:bold;"':' class="tooltip_new"').'>'.$crowdfund['EventName'].'</a>'.$sponsoredstar.'</td></tr>'.($is_mobile?'<tr><td></td><td>'.$start.'</td></tr></table></td>':'</table></td><td>'.$crowdfund['ProjCatName'].'</td><td>'.$start.'</td>').'<td>'.$date_left_from.'</td><td>'.$icorank.'</td></tr>';
      }
    ?></tbody>
    </table>
</div>