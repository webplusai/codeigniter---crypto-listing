<?
  global $dbconn,$imagepath,$eventtypeimagepath,$projimagepath;
  
  $events = mysqli_query($db,"
  (
  Select EventID,EventProjID,ProjName,ProjImage,EventType,EventName,EventDesc,EventStartDate as EventDate,EventStartDateType,EventLocation,EventEndDate,EventTypeImage
	from tbl_events E
  Inner join tbl_eventtypes ET
  on E.EventType = ET.EventTypeID
  Left Join tbl_projects P
  on E.EventProjID = P.ProjID
	where EventTypeID <> 1 and EventProjID = '$projid'
  )
  Order By EventID,EventName
  "); 
  
  $numofrows = mysqli_num_rows($events);
  if($numofrows>0)
  {
  
?>
<div class="col-md-8 col-md-offset-2" style="margin-bottom:40px">
<h3>Upcoming Events and Milestones</h3>
<tr><td colspan = "3" class="panelmain">
<? 
  
    echo '<table class="table"><tbody class="list"><tr class="tbl_list_head" style="font-weight:bold"><td width="20"></td><td width="130">Date</td>'.(($indexpage==1 || $mainpage==1) && $ismobile==0?'<td width="20"></td>':'').'<td>Event</td></tr>';
    while ($event = mysqli_fetch_assoc($events)) 
    {   
      $eventdate = date_create($event['EventDate']);
      
      $eventname = $projid=='' && $event['EventProjID']!=0 && $event['Bonus']==0 && stripos($event['EventName'],$event['ProjName'])===false?$event['ProjName'].' - '.$event['EventName']:$event['EventName'];       

      $eventtitle = $eventname.($event['EventLocation']==''?'':' - <i>'.$event['EventLocation'].'</i>').(date_format($eventdate,"H:i")!="00:00" && $event['EventType']==1?' ('.date_format($eventdate,"H:i").' UTC)':'');
      //$eventtitle = $event['EventName'].(date_format($eventdate,"H:i")!="00:00"?' ('.date_format($eventdate,"H:i").' UTC)':'');
      
      if ($event['EventStartDateType']==2)
      {
        $eventdate = 'Q'.ceil(date_format($eventdate,'n')/3).' '.date_format($eventdate,'Y');
      }
      else        
      {
        if ($eventdate=="")
        $eventdate = date_format($eventdate,$ismobile?"d/m/y":"M jS Y");  
      }
      
      $eventicon = ($ismobile?'':'<td valign="top"><img src="data:image/png;base64,'.$event['EventTypeImage'].'" height="16" width="16"></td>');
               
      if ($indexpage==1 || $mainpage==1)
      {
             
        $link = ($event['EventType']==1 || $event['EventType']==4?'index.php?page=project&id='.$event['EventProjID']:'index.php?page=event&id='.$event['EventID']); 
         
        echo '<tr onclick="window.location=\''.$link.'\'"><td style="display:none;" class="date">'.$event['EventDate'].'</td><td width="5"><img src="'.($event['EventProjID']==0?$eventtypeimagepath.$event['EventType'].'.png':'data:image/png;base64,'.$event['ProjImage']).'" height="16" width="16"></td><td valign="top">'.$eventdate.'</td>'.$eventicon.'<td class="name">'.$eventtitle.'</a></td></tr>';
      }
      else
      {
              echo '<tr>'.$eventicon.'<td valign="top">'.$eventdate.'</td><td class="name">'.$eventtitle.'</td></tr>';
      }  

       
    }
    
    echo '</tbody></table>';

  
?>

</td></tr>
</table>
</div>


<?  } ?>
