<section>
<span id="events"></span>
<h2 class="section-heading">Conferences & Events</h2>
 <div style="margin-bottom: 20px;">Nothing replaces meeting people in person. The events on this page have been selected because they represent the most important opportunities to meet the movers and shakers of the cryptocurrency and blockchain industry.</div>   
    <div class="events list-table">
    <div class="list-container">
    <table>
    <thead>
    <tr>
      <th>Event<div>Event</div></th>
      <th>Date<div>Date</div></th>
      <th>Location<div>Location</div></th>
    </tr>
    </thead>
    <tbody>
    <?
      while ($event = mysqli_fetch_assoc($events)) 
      {
        $startdate = date_create($event['EventStartDate']);
           
        if ($event['EventEndDate'])
        {      
          $enddate = date_create($event['EventEndDate']); 
          
          if (date_format($startdate,"M")==date_format($enddate,"M"))
          {
            $date = date_format($startdate,"M jS")." - ".date_format($enddate,"jS")." ".date_format($startdate,"Y");
          }
        }
        else
        {
          $date = date_format($startdate,"M jS Y");
        }
         
        $sponsoredstar = ($event['ProjSponsored']?'<span class="glyphicon glyphicon-star tooltip_new label" style="color:black" title="Sponsored project"> </span>':'');
              
        //$url='events/'.$event['EventID'].'/'.str_replace("+","-",urlencode(str_replace("/","_",strtolower($event['EventName'])))).'.html';
        
        echo '
        <tr onclick="window.open(\''.$event['EventWebsite'].'\')">
          <td style="width: 65%">
            <table class="link">
            <tr>
              <td style="width:25px;">
                '.($event['EventImage']?'<img class="eventlogo" src="data:image/png;base64,'.$event['EventImage'].'" height="16" width="16" alt="'.str_replace('"','',$event['EventName']).' Logo">':'').'
              </td>
              <td>
                <a href="'.$event['EventWebsite'].'" target="_blank" rel="nofollow">'.$event['EventName'].'</a>
                '.$sponsoredstar.'
                <div class="details">'.$date.'<br>'.$event['EventLocation'].'</div>
              </td>
            </tr>
            </table>
          </td>
          <td style="width: 15%">
            '.$date.'
          </td>
          <td>
            '.$event['EventLocation'].'
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