<html>
<head><title>Coinschdule Admin - Crowdfunds & ICOs</title></head>
<body>
<link href="public/css/list.css" rel="stylesheet">
<?php require_once('menu.php'); ?>
<div style="padding: 15px;">
<h1 style="display:inline;">Crowdfunds & ICOs</h1>
<button onclick="location.href='project.php';" style="vertical-align: top;width: 100px;height: 30px;display:inline;margin: 4px 0px 0px 40px;">Add</button>
<?php
session_start();
require "codebase/bd.php";

$projects = mysqli_query($db,"Select ProjID,ProjName,ProjSymbol,ProjImage,ProjSponsored,EventStartDate,EventEndDate,ProjCatName from tbl_projects P inner join tbl_events E on P.ProjID = E.EventProjID left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID left join tbl_submissions S ON S.SubProjID = P.ProjID where ProjDeleted = 0 and EventType = 1 and (EventEndDate > Now() or EventEndDate ='0000-00-00 00:00:00' or EventStartDateType = 3) and (S.SubStatus = 2 or S.SubStatus IS NULL) and EventDeleted = 0 Order By ProjName");

echo '<table class="responstable"><tr><th width="20"></th><th width="80">Symbol</th><th width="80" style="text-align: center;">Sponsored</th><th width="300">Name</th><th width="200">Start</th><th width="200">End</th><th>Category</th></tr>';

while ($project = mysqli_fetch_array($projects))
{
  $start = "";
  if ($project['EventStartDate']!='0000-00-00 00:00:00')
  {
    $startdate = date_create($project['EventStartDate']);
    $start = date_format($startdate,"M jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':'');
  } 
  
  $end = "";
  if ($project['EventEndDate']!='0000-00-00 00:00:00')
  {
    $enddate = date_create($project['EventEndDate']);
    $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':''); 
  }
      
  echo '<tr onclick="window.location=\'project.php?id='.$project['ProjID'].'\'"><td><img src="data:image/png;base64,'.$project['ProjImage'].'"></td><td>'.$project['ProjSymbol'].'</td><td align="center">'.($project['ProjSponsored']?'YES':'').'</td><td>'.$project['ProjName'].'</td><td>'.$start.'</td><td>'.$end.'</td><td>'.$project['ProjCatName'].'</td></tr>';
}

echo '</table>';

?>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>