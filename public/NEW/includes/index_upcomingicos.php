<section>
<h2 class="section-heading">Upcoming Token Sales & ICOs</h2>
  <div class="upcoming list-table">
    <div class="list-container">
      <table>
      <thead>
      <tr>
        <th>Name<div>Name</div></th>
        <th>Category<div>Category</div></th>
        <th>Start Date<div>Start Date</div></th> 
        <th>Starts In<div>Starts In</div></th>
      </tr>
      </thead>
      <tbody>
<?

  
  
  // Loop through Upcoming ICOs
  foreach ($upcomingicos as $upcomingico) 
  {
    if ($upcomingico['Status']==1) { break;} 
    
    // Dates
    if ($upcomingico['EventStartDateType']==1)
    {                                   
      $startdate = date_create($upcomingico['EventStartDate']);
      $start = date_format($startdate,"M jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':''); 
      $date_number_from=date("U",strtotime($upcomingico['EventStartDate']));
      $seconds_left_from=$date_number_from-date("U");
      $date_left_from=timeToDate($seconds_left_from);
    }
    else
    {
      $start = "TBA";
      $date_left_from = "TBA";         
    }
    

    // Sponsored Icon
    if ($upcomingico['ProjPlatinum'])
    {
      //$sponsoredstar = '<img src="'.$platbadge.'" class="inline tooltip" title="Platinum Level Event" alt="Platinum Level Event">';
      $sponsoredstar='';
    }
    else if($upcomingico['ProjPackage']==1)
    {
      $sponsoredstar = '<img src="'.$silverbadge.'" class="inline tooltip" title="Silver Project" alt="Silver Project">';
    }
    else
    {
      $sponsoredstar = ($upcomingico['ProjSponsored']&&$upcomingico['ProjDisableRibbon']==0?'<img src="'.$goldbadge.'" class="inline tooltip" title="Gold Project" alt="Gold Project">':'');
    }
    
    
    $url='https://www.coinschedule.com/icos/e'.$upcomingico['EventID'].'/'.str_replace("+","-",urlencode(strtolower($upcomingico['EventName']))).'.html';
    
    echo '
    <tr '.($upcomingico['ProjSponsored']?' style="font-weight: bold;font-size: '.($upcomingico['ProjPlatinum']?'1.3':'1.1').'em;"':'').' '.($widget?'onclick="window.open(\''.$url.'\')"':'onclick="window.location=\''.$url.'\'"').' >
      <td style="vertical-align: middle;width: 450px;" class="tooltip" title="<b>'.$upcomingico['EventName'].'</b><br>'.htmlentities($upcomingico['ProjDesc']).'">
        <table class="link">
        <tr>
          <td style="width:'.($upcomingico['ProjPlatinum']?'60':'25').'px;">
            '.($upcomingico['ProjPlatinum']?'<img src="data:image/png;base64,'.$upcomingico['ProjImageLarge'].'" height="48" width="48">':($upcomingico['ProjImage']?'<img class="projlogo" src="data:image/png;base64,'.$upcomingico['ProjImage'].'" height="16" width="16" alt="'.$upcomingico['EventName'].' Logo">':'')).'
          </td>
          <td>
            <a href="'.$url.'">'.$upcomingico['EventName'].'</a>
            '.$sponsoredstar.'
            <div class="details">'.$start.'</div>
          </td>
        </tr>
        </table>
      </td>
      <td style="vertical-align: middle;width: 28%;">
        '.$upcomingico['ProjCatName'].'
      </td>
      <td style="vertical-align: middle;">
        '.$start.'
      </td>
      <td style="vertical-align: middle;width: 150px;">
        '.$date_left_from.'
      </td>
    </tr>';  
  }
?>
      </tbody>
      </table>
    </div>
  </div>
</section>
<div class="divider"></div>