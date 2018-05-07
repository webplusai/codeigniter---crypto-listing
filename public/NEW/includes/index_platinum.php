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
<a href="<? echo $url; ?>">
<div class="platinum">

    <div class="inline plateventimage" style="width:150px;">
      <img src="https://www.coinschedule.com/img/plat.png?1123" alt="Platinum Level Event">
    </div>&nbsp;<div class="inline"><table style="margin:auto;"><tr><td><img class="plateventlogo" src="https://www.coinschedule.com/img/plat/<? echo $plat['ProjID']; ?>.png" alt="Platinum Project <? echo $plat['EventName']; ?> Logo"></td><td style="text-align:center;"><h4><b><? echo $plat['EventName']; ?></b></h4><? echo $startstamp>time()?'Starts '.$start:'Ends '.$end; ?></td></tr></table></div>&nbsp;<div id="<? echo $startstamp>time()?'startclock':'endclock'; ?>" class="clock inline"><div><span class="days">- -</span><div class="smalltext">Days</div></div><div>
        <span class="hours">- -</span>
        <div class="smalltext">Hours</div>
      </div><div>
        <span class="minutes">- -</span>
        <div class="smalltext">Mins</div>
      </div><div>
        <span class="seconds">- -</span>
        <div class="smalltext">Secs</div>
      </div>
    </div>
    <div class="stretch"></div>
    
</div>
</a>
</section>
<? if (time()<$endstamp) { ?>  
<script>                                           
  var startdate = new Date(Date.UTC(<? echo $timerdate; ?>));
  var enddate = new Date(new Date('<? echo date_format($enddate,'D M d Y H:i:s'); ?>Z'));
  initializeClock('<? echo (time()<$startstamp?'startclock':'endclock'); ?>', startdate,enddate);          
</script>
<? } ?>
<? } ?>