<?
if ($argv) 
{
	foreach ($argv as $k=>$v)
  {
		if ($k==0) continue;
		$it = explode("=", $v);
		if (isset($it[1])) 
    {
			$_GET[$it[0]] = $it[1];
		}
	}
} 

if ($_GET['plat'])
{ 
  $plat = $_GET['plat']; 
  if($plat=='waves')
  { 
    $where = "and CONCAT(',',ProjPlatform,',') LIKE '%,11,%'"; 
  } 
}

require "/home/coinschedule/public_html/lib/bd.php";
$liveicos = mysqli_query($db,"Select count(*) as Total
                              From tbl_events E 
                              inner join tbl_projects P
                              On E.EventProjID = P.ProjID
                              Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP $where"); 
                              
if($liveicos = mysqli_fetch_array($liveicos)){$liveicostotal = $liveicos['Total'];}       

$ucicos = mysqli_query($db,"Select count(*) as Total
                            From tbl_events E 
                            inner join tbl_projects P
                            On E.EventProjID = P.ProjID
                            Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3) $where"); 
                              
if($ucicos = mysqli_fetch_array($ucicos)){$ucicostotal = $ucicos['Total'];} 
                
$totals['upcoming'] = $ucicostotal;
$totals['live'] = $liveicostotal;

$totals = json_encode($totals);

//file_put_contents("../widget/icototals$plat.json", $totals);

$fp = fopen("/home/coinschedule/public_html/widget/icototals$plat.json", 'w');
fwrite($fp, $totals);
fclose($fp)

?>