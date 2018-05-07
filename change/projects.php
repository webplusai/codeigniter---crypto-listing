<html>
<head><title>Coinschdule Admin - Projects</title></head>
<body>
<link href="public/css/list.css" rel="stylesheet">
<?php require_once('menu.php'); ?>
<div style="padding: 15px;">
<h1 style="display:inline;">Projects</h1>
<?php
session_start();
require "codebase/bd.php";

$projects = mysqli_query($db,"Select ProjID,ProjName,ProjSymbol,ProjImage,ProjTypeName from tbl_projects P inner join tbl_projecttypes PT on P.ProjType = PT.ProjTypeID Order By ProjName");

echo '<table class="responstable"><tr><th width="30"></th><th width="100">Symbol</th><th width="200">Name</th><th>Type</th></tr>';

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
      
  echo '<tr onclick="window.location=\'project.php?id='.$project['ProjID'].'\'"><td><img src="data:image/png;base64,'.$project['ProjImage'].'"></td><td>'.$project['ProjSymbol'].'</td><td>'.$project['ProjName'].'</td><td>'.$project['ProjTypeName'].'</td></tr>';
}

echo '</table>';

?>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>