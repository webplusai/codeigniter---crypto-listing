<?
  $plat = mysqli_query($db,"
                            Select ProjID,ProjImage,ProjSponsored,ProjDisableRibbon,EventName,ProjDesc,EventStartDate,EventEndDate,EventStartDateType 
                            From tbl_events E 
                            inner join tbl_projects P
                            On E.EventProjID = P.ProjID
                            Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjPlatinum = 1 and (EventEndDate > UTC_TIMESTAMP) 
                            Order By EventStartDateType,EventStartDate,ProjID,CASE WHEN EventEndDate > Now() THEN 0 ELSE 1 END,EventEndDate LIMIT 1");
                             
if (mysqli_num_rows($plat)>0)
{
    $plat = mysqli_fetch_array($plat);
    
    $url='projects/'.$plat['ProjID'].'/'.str_replace("+","-",urlencode(strtolower($plat['EventName']))).'.html';
    $percent_time=$plat['Percent'];
    if($percent_time>100)$percent_time=100;
	  $percent_time=number_format($percent_time,2);
    
    $startstamp = strtotime($plat['EventStartDate']);
    $startdate = date_create($plat['EventStartDate']);
    $start = date_format($startdate,"F jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':''); 
   
    
    $endstamp = strtotime($plat['EventEndDate']);
    $enddate = date_create($plat['EventEndDate']);
    $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':'');
     
    $date_number_from=date("U",strtotime($plat['EventEndDate']));
  	$seconds_left_from=$date_number_from-date("U");
  	$date_left_from=timeToDate($seconds_left_from);  
    
    $timerdate = (time()<$startstamp?$startdate:$enddate); 
    
    $timerdate = date_format($timerdate,'Y').",".(date_format($timerdate,'n')-1).",".date_format($timerdate,'d,H,i,s')
       
?>
<section>
<div class="container">
<a href="<? echo $url; ?>" style="display: block;">
<div style="padding: 5px;border: #C0C0C0 1px solid;font-weight:bold; font-size: 2em;color:#34495e;">
<table width="100%"><tr><td width="150"><img style="padding-left: 10px;" src="img/plat.png?1123"></td><? echo $is_mobile?'<td align="right"><img style="padding-left: 10px;" width="100" height="100" src="img/plat/'.$plat['ProjID'].'.png?2111"></td></tr><tr>':''; ?><td align="center" colspan="2"><table><? echo $is_mobile?'':'<tr><td width="105"><img style="padding-left: 10px;" width="100" height="100" src="img/plat/'.$plat['ProjID'].'.png"></td><td width="10"></td>'; ?><td align="center"><h4 style="font-size: 1.5em;margin: 0px;padding:0px;"><b><? echo $plat['EventName']; ?></b></h4>
<? echo $startstamp>time()?'Starts '.$start:'Ends '.$end; ?></td></tr></table>
<? echo $is_mobile?'</tr><tr><td colspan="3" align="center">':'<td></td><td width="240">'; ?>
<div id="<? echo $startstamp>time()?'startclock':'endclock'; ?>" style="margin-top: 22px;font-weight: bold;">
              <div>
                <span class="days" style="font-size: 1.6em;width: 55px;">- -</span>
                <div class="smalltext">Days</div>
              </div>
              <div>
                <span class="hours" style="font-size: 1.6em;width: 55px;">- -</span>
                <div class="smalltext">Hours</div>
              </div>
              <div>
                <span class="minutes" style="font-size: 1.6em;width: 55px;">- -</span>
                <div class="smalltext">Mins</div>
              </div>
              <div>
                <span class="seconds" style="font-size: 1.6em;width: 55px;">- -</span>
                <div class="smalltext">Secs</div>
              </div>
</div>
</td>
</tr></table>
</div>
</a>
</div>
</section>
<? if (time()<$endstamp) { ?>  
<script src="/js/serverdate.js?22"></script><script src="/js/countdown.js"></script> 
<script>                                           
var startdate = new Date(Date.UTC(<? echo $timerdate; ?>));
var enddate = new Date(new Date('<? echo date_format($enddate,'D M d Y H:i:s'); ?>Z'));
initializeClock('<? echo (time()<$startstamp?'startclock':'endclock'); ?>', startdate,enddate);          
</script>
<? } ?>
<? } ?>