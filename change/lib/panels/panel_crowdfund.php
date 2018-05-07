<?

$crowdfunds = mysqli_query($db,"Select EventID,EventName,EventDesc,EventStartDate,EventEndDate,EventTotalRaised,P.ProjSymbol as EventTotalRaisedSymbol,P.ProjID as EventTotalRaisedProjID, EventParticipants from tbl_events E left join tbl_projects P on E.EventTotalRaisedProjID = P.ProjID where EventType = 1 and EventProjID = '$projid'"); 

$numofrows = mysqli_num_rows($crowdfunds);
  
if($numofrows>0)
{   
  $crowdfund = mysqli_fetch_assoc($crowdfunds);
  
  $crowdfundid = $crowdfund['EventID'];  
  $startstamp = strtotime($crowdfund['EventStartDate']);
  $startdate = date_create($crowdfund['EventStartDate']);
  $start = date_format($startdate,"F jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':''); 
  
  $endstamp = strtotime($crowdfund['EventEndDate']);
  $enddate = date_create($crowdfund['EventEndDate']);
  $end = date_format($enddate,"F jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':'');
  
  $desc = nl2br($crowdfund['EventDesc']);  

?>
<div class="col-md-8 col-md-offset-2" style="margin-top:20px;margin-bottom:30px">
<h3>Crowdfund</h3> 
<table class="table">
  <tbody>
    <tr>
      <th scope="row" style="width: <? echo $is_mobile?'130px':'230px';?>;">Start Date</th>
          <td>
          <? 
            echo $start;		  
			    ?>
          </td>
          <? 
            if ($is_mobile)
            {
              echo '</tr><tr><td colspan="2">';            
            }
            else
            {
              echo '<td align="right">';
            }
          ?>
            <div id="startclock">
              <div>
                <span class="days"><? echo (time()<$startstamp || time() > $endstamp ?'- -':'L'); ?></span>
                <div class="smalltext">Days</div>
              </div>
              <div>
                <span class="hours"><? echo (time()<$startstamp || time() > $endstamp?'- -':'I'); ?></span>
                <div class="smalltext">Hours</div>
              </div>
              <div>
                <span class="minutes"><? echo (time()<$startstamp || time() > $endstamp?'- -':'V'); ?></span>
                <div class="smalltext">Mins</div>
              </div>
              <div>
                <span class="seconds"><? echo (time()<$startstamp || time() > $endstamp?'- -':'E'); ?></span>
                <div class="smalltext">Secs</div>                             
              </div>
            </div>
          </td>
        </tr>
      <th scope="row">End Date</th>
          <td>
          <? 
            echo $end;		  
			    ?>
          </td>
          <? 
            if ($is_mobile)
            {
              echo '</tr><tr><td colspan="2">';            
            }
            else
            {
              echo '<td align="right">';
            }
          ?>
            <div id="endclock" style="margin-bottom: 25px;">
              <div>
                <span class="days">- -</span>
                <div class="smalltext">Days</div>
              </div>
              <div>
                <span class="hours">- -</span>
                <div class="smalltext">Hours</div>
              </div>
              <div>
                <span class="minutes">- -</span>
                <div class="smalltext">Mins</div>
              </div>
              <div>
                <span class="seconds">- -</span>
                <div class="smalltext">Secs</div>
              </div>
            </div>
          </td>
        </tr>
      <? 
        if ($proj_spons)
        {
        echo '<th scope="row" colspan="3">Details</th><tr><td colspan="3" style="padding-bottom: 25px">'.$desc.'</td></tr>'; 
        
        $currentprice = mysqli_query($db,"Select CrowdFundBonusRate,ProjSymbol from tbl_crowdfundbonus CF inner join tbl_events E on CF.CrowdBonusEventID = E.EventID inner join tbl_projects P on E.EventTotalRaisedProjID = P.ProjID Where CrowdBonusEventID = $crowdfundid and CrowdBonusStartDate <= '".date("Y-m-d H:i:s")."' and CrowdBonusEndDate > '".date("Y-m-d H:i:s")."'");
       
        if($currentprice = mysqli_fetch_assoc($currentprice))
        {
        
        $currentprice = $currentprice['CrowdFundBonusRate'].' '.$currentprice['ProjSymbol'];
      ?>  
      <th scope="row">Current Price</th>
      <td colspan="2">
      <? echo $currentprice; ?>
      </td>
      </tr>
      <? } ?>
      <? 
        $rates = mysqli_query($db,"Select CrowdBonusName,CrowdBonusStartDate,CrowdBonusEndDate,CrowdFundBonusDateNote from tbl_crowdfundbonus Where CrowdBonusEventID = $crowdfundid Order By CrowdBonusStartDate");
        
        $numofrows = mysqli_num_rows($rates);
  
        if($numofrows>0)
        {
      ?>
      <th scope="row" colspan="3">Rates</th>
      <tr><td colspan="3">
      <table style="margin-bottom: 25px;">
      <?
        while ($rate = mysqli_fetch_assoc($rates)) 
        {   
          $ratestartdate = date_create($rate['CrowdBonusStartDate']);
          $ratestart = $rate['CrowdFundBonusDateNote']!=''?$rate['CrowdFundBonusDateNote']:date_format($ratestartdate,"F jS Y").(date_format($ratestartdate,"H:i")!="00:00"?' '.date_format($ratestartdate,"H:i").' UTC':'');
          $rateenddate = date_create($rate['CrowdBonusEndDate']);
          $rateend = date_format($rateenddate,"F jS Y").(date_format($rateenddate,"H:i")!="00:00"?' '.date_format($rateenddate,"H:i").' UTC':'');
          echo '<tr><td>'.$ratestart.'</td><td width="20"></td><td>'.$rate['CrowdBonusName'].'</td></tr>';
        }
      ?>
      </table>
      </td>
      </tr>
      <? } ?>
      <? 
        $distros = mysqli_query($db,"Select DistroDesc,DistroAmount,DistroPercent,DistroNote,ProjSymbol from tbl_projdistribution PD inner join tbl_projects P on PD.DistroProjID = P.ProjID Where DistroProjID = $projid Order By DistroSortOrder");
        
        $numofrows = mysqli_num_rows($distros);
  
        if($numofrows>0)
        {
      ?>
      <th scope="row" colspan="3">Distribution</th>
      <tr><td colspan="3">
      <table>
      <?
        while ($distro = mysqli_fetch_assoc($distros)) 
        {   
          echo '<tr><td style="padding-bottom: 20px;">'.$distro['DistroDesc'].'</td><td width="20"></td><td width="130" valign="top">'.number_format(($distro['DistroAmount']+0)).' '.$distro['ProjSymbol'].'</td>'.(is_mobile()?'':'<td width="20"></td><td valign="top">'.$distro['DistroPercent'].'%</td><td width="20"></td><td><i>'.$distro['DistroNote'].'</i></td>').'</tr>';
        }
      ?>
      </table>
      </td>
      </tr>
      <? } } ?>
    </tbody>
  </table>
</div>

<? if (time()<$endstamp) { ?>  
<script src="/js/serverdate.js"></script><script src="/js/countdown.js?<? echo time(); ?>"></script> 
<script>                                           
var startdate = new Date(new Date('<? echo date_format((time()<$startstamp?$startdate:$enddate),'D M d Y H:i:s'); ?>Z'));
var enddate = new Date(new Date('<? echo date_format($enddate,'D M d Y H:i:s'); ?>Z'));
initializeClock('<? echo (time()<$startstamp?'startclock':'endclock'); ?>', startdate,enddate);          
</script>
<? } ?>
<? } ?>