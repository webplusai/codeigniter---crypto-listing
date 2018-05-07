<section>
<h2 class="section-heading">Live Token Sales & ICOs<div class="liveview">View: <select onchange="location='index.php?live_view=' + this.options[this.selectedIndex].value;"><option value="1">Plates</option><option value="2" <? echo $live_icoview==2?'selected':''; ?>>List</option></select></div></h2>

<?
if ($live_icoview==2)
{
 echo '
 <div>
 <div class="live list-table">
    <div class="list-container">
      <table>
      <thead>
      <tr>
        <th style="width: 700px;">Name<div>Name</div></th>
        <th>Category<div>Category</div></th>
        <th style="width: 200px;">End Date<div>End Date</div></th> 
        <th>Ends In<div>Ends In</div></th>
      </tr>
      </thead>
      <tbody>
 '; 
  foreach ($icos as $liveico) 
  {
    if ($liveico['Status']==2) { break; } 
    
    // End Date
    $enddate = date_create($liveico['EventEndDate']);
    $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':''); 
    $date_number_from=date("U",strtotime($liveico['EventEndDate']));
  	$seconds_left_from=$date_number_from-date("U");
  	$date_left_from=timeToDate($seconds_left_from);
          
    // Tooltip
    $tooltip = "<center><b>".$liveico['EventName']."</b><br>".htmlentities($liveico['ProjDesc'])."<br><br><B>Ends $end</b></center>";
    
    // Url
    //$url='https://www.coinschedule.com/icos/e'.$liveico['EventID'].'/'.str_replace("+","-",urlencode(strtolower($liveico['EventName']))).'.html';
    $url='https://www.coinschedule.com/NEW/project.php?eventid='.$liveico['EventID'];
    
    // Sponsored Icon
    if ($liveico['ProjPlatinum'])
    {
      //$sponsoredstar = '<img src="'.$platbadge.'" class="inline tooltip" title="Platinum Level Event" alt="Platinum Level Event">';
      $sponsoredstar='';
    }
    else if($liveico['ProjPackage']==1)
    {
     $sponsoredstar = '<img style="padding-left: 5px" src="'.$silverbadge.'" title="Silver Project" alt="Silver Project">';
    }
    else
    {
      $sponsoredstar = ($liveico['ProjSponsored']&&$liveico['ProjDisableRibbon']==0?'<img style="padding-left: 5px" src="'.$goldbadge.'" class="tooltip" title="Gold Project" alt="Gold Project">':'');
    }
    
    echo '
    <tr '.($liveico['ProjSponsored']?' style="font-weight: bold;font-size: '.($liveico['ProjPlatinum']?'1.3':'1.1').'em;"':'').' onclick="window.location=\''.$url.'\'">
      <td  class="tooltip" title="'.$tooltip.'">
        <table class="link">
        <tr>
          <td style="width:'.($liveico['ProjPlatinum']?'60':'25').'px;">
            '.($liveico['ProjPlatinum']?'<img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48">':($liveico['ProjImage']?'<img class="projlogo" src="data:image/png;base64,'.$liveico['ProjImage'].'" height="16" width="16" alt="'.$liveico['EventName'].' Logo">':'')).'
          </td>
          <td>
            <a href="'.$url.'">'.$liveico['EventName'].'</a>
            '.$sponsoredstar.'
          </td>
        </tr>
        </table>
      </td>
      <td style="vertical-align: middle;width: 28%;">
        '.$liveico['ProjCatName'].'
      </td>
      <td style="vertical-align: middle;">
        '.$end.'
      </td>
      <td style="vertical-align: middle;width: 150px;">
        '.$date_left_from.'
      </td>
    </tr>'; 
  } 
 
 echo '
      </tbody>
      </table>
    </div>
    </div>';   
    
}
else
{
  echo '<div class="liveicos">';
  
  foreach ($icos as $liveico) 
  { 
    if ($liveico['Status']==2) { break; } 
        
    // Percentage Time
    $percent_time=$liveico['Percent'];
    if($percent_time>100)$percent_time=100;
    $percent_time=number_format($percent_time,2);
          
    // End Date
    $enddate = date_create($liveico['EventEndDate']);
    $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':''); 
          
    // Tooltip
    $tooltip = "<center><b>".$liveico['EventName']."</b><br>".htmlentities($liveico['ProjDesc'])."<br><br><B>Ends $end</b></center>";
    
    // Url
    //$url='https://www.coinschedule.com/icos/e'.$liveico['EventID'].'/'.str_replace("+","-",urlencode(strtolower($liveico['EventName']))).'.html';
    $url='https://www.coinschedule.com/NEW/project.php?eventid='.$liveico['EventID'];
        
    // GOLD
    if ($liveico['ProjSponsored']){ ?><div class="icobox gold tooltip" title="<? echo $tooltip; ?>">
        <a href="<? echo $url; ?>">
        <table>
        <tr>
          <td class="icobox-text">
            <img src="data:image/png;base64,<? echo $liveico['ProjImageLarge']; ?>" alt="<? echo $liveico['EventName']; ?> Logo"/>
            <h4><? echo $liveico['EventName']; ?></h4>
            <div class="<? echo ($percent_time>90?'red':'').($percent_time<=10?'green':''); ?> done">
              <b><? echo $percent_time; ?>% done</b>
            </div>
          </td>
        </tr>
        <tr>
          <td class="category">
            <? echo strtoupper($liveico['ProjCatName']); ?>
          </td>
        </tr>         
        </table>
        </a>
      </div><? } 
    // SILVER
    else if ($liveico['ProjPackage']==1) { 
      if (!$lastgoldreached){ $lastgoldreached = 1; echo '<div style="height: 5px;"></div>'; } ?><div class="icobox silver tooltip" title="<? echo $tooltip; ?>">
        <a href="<? echo $url; ?>">
        <table>
        <tr>
          <td>
            <img src="data:image/png;base64,<? echo $liveico['ProjImageLarge']; ?>" alt="<? echo $liveico['EventName']; ?> Logo"/>
          </td>
          <td class="icobox-text">
            <h4><? echo $liveico['EventName']; ?></h4>
            <div class="<? echo ($percent_time>90?'red':'').($percent_time<=10?'green':''); ?> done"><b><? echo $percent_time; ?>% done</b></div>
          </td>
        </tr>
        <tr>
          <td class="category" colspan="2">
            <? echo strtoupper($liveico['ProjCatName']); ?>
          </td>
        </tr> 
        </table>
        </a>                                                      
      </div><? } 
    // STANDARD
    else { 
      if (!$lastssilvreached){ $lastssilvreached = 1; echo '<div style="height: 10px;"></div>'; } ?><div class="icobox standard tooltip" title="<? echo $tooltip; ?>">
        <a href="<? echo $url; ?>">
        <table>
        <tr>
          <td class="icobox-text">
            <h4><? echo $liveico['EventName']; ?></h4>
            <div class="<? echo ($percent_time>90?'red':'').($percent_time<=10?'green':''); ?> done"><b><? echo $percent_time; ?>% done</b></div>
          </td>
        </tr>
        <tr>
          <td class="category">
            <? echo $liveico['ProjCatName']; ?>
          </td>
        </tr> 
        </table>
        </a>
      </div><?  } 
  } 
}
?> 
<div class="disclaimer">Note: This is not investment advice. By using Coinschedule you agree to our Disclaimer. <a href="https://www.coinschedule.com/disclaimer.php" target="_blank">FULL DISCLAIMER</a></div>
</div>
</section>
<div class="divider"></div>
   