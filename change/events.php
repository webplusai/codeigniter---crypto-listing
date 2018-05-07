<html>
<head><title>Coinschdule Admin - Events</title></head>
<body>
<link href="public/css/list.css" rel="stylesheet">
<?php require_once('menu.php'); ?>
<div style="padding: 15px;">
<h1 style="display:inline;">Events</h1>
<?php
session_start();
require "codebase/bd.php";

$events = mysqli_query($db,"Select EventID,EventName,EventStartDate,EventEndDate,EventLocation,EventTypeName from tbl_events E inner join tbl_eventtypes ET on E.EventType = ET.EventTypeID where EventDeleted = 0 and EventType <> 1 and EventType <> 4 and (EventEndDate > Now() or EventEndDate ='0000-00-00 00:00:00') Order By EventName");

echo '<table class="responstable"><tr><th width="80">Type</th><th width="400">Name</th><th width="200">Location</th><th width="200">Start</th><th>End</th></tr>';

while ($event = mysqli_fetch_array($events))
{
  $start = "";
  if ($event['EventStartDate']!='0000-00-00 00:00:00')
  {
    $startdate = date_create($event['EventStartDate']);
    $start = date_format($startdate,"M jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':'');
  } 
  
  $end = "";
  if ($event['EventEndDate']!='0000-00-00 00:00:00')
  {
    $enddate = date_create($event['EventEndDate']);
    $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':''); 
  }
      
  echo '<tr onclick="window.location=\'event.php?id='.$event['EventID'].'\'"><td>'.$event['EventTypeName'].'</td><td>'.$event['EventName'].'</td><td>'.$event['EventLocation'].'</td><td>'.$start.'</td><td>'.$end.'</td></tr>';
}

echo '</table>';

?>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>