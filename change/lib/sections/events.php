<div class="container">
	<h2 style="padding-bottom: 10px;color: #34495e;"><b>Conferences & Events</b></h2>
      
     <div style="margin-bottom: 20px;">Nothing replaces meeting people in person. The events on this page have been selected because they represent the most important opportunities to meet the movers and shakers of the cryptocurrency and blockchain industry.</div>   
    <div style="padding-right: 17px;background-color: #669;">
    <table class="table" style="margin-bottom: 0px;border: 0px none;">
    <tr style="font-weight:bold;background-color: #669;color: #fff"><td style="border-top: 0px;border-right: 1px solid #ddd;width: 65%;<? echo $is_mobile?'border-right-color:#669;':''; ?>">Event</td><? if (!$is_mobile) { echo'<td style="border-top: 0px;border-right: 1px solid #ddd;width: 15%">Date</td><td style="border-top: 0px;border-right-color:#669;">Location</td>'; }?></tr>
    </table>
    </div>
    <div style="width:100%; max-height:<? echo $is_mobile?'290px':'395px'; ?>; overflow-y:scroll;">
    <table class="table table-bordered tbl_hoverrow">
    <tbody>
    <?
    
      $events = mysqli_query($db,"Select EventID,EventName,EventImage,EventTypeImage,EventStartDate,EventEndDate,EventWebsite,EventLocation 
                            From tbl_events E 
                            inner join tbl_eventtypes ET
                            On E.EventType = ET.EventTypeID
                            Where EventDisabled = 0 and EventDeleted = 0 and EventType <> 1 and EventType <> 4 and EventStartDate > UTC_TIMESTAMP
                            Order By EventStartDate
                            ");
                            
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
        
        echo '<tr onclick="window.open(\''.$event['EventWebsite'].'\')"><td style="width: 65%"><table><tr><td width="25" valign="top">'.($event['EventImage']?'<img src="data:image/png;base64,'.        
$event['EventImage'].'" height="16" width="16">':'').'</td><td><a href="'.$event['EventWebsite'].'" target="_blank" rel=”nofollow”>'.($is_mobile?'<b>'.$event['EventName'].'</b>':$event['EventName']).'</a>'.$sponsoredstar.'</td></tr>'.($is_mobile?'<tr><td></td><td>'.$date.' <br> '.$event['EventLocation'].'</td></tr></table>':'</table></td><td style="width: 15%">'.$date.'</td><td>'.$event['EventLocation'].'</td>').'</tr>';
      }
    ?></tbody>
    </table></div>
</div>