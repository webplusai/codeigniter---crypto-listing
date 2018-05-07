<?php
include  'database.php';
date_default_timezone_set('UTC');
$date=date('Y-m-d');
$data=array(
	'ID'=>$_POST['ID'],
	'ICOName'=>$_POST['ICOName'],
	'StartDate'=>$_POST['StartDate'],
	'EndDate'=>$_POST['EndDate'],
	'TotalUSD'=>$_POST['TotalUSD'],
	'ProjCatName'=>$_POST['ProjCatName'],
	'Category'=>$_POST['cat'],
	'Link'=>$_POST['Link'],
	'Comments'=>$_POST['Comments'],
	'Investors'=>$_POST['Investors']
);
if(empty($data['TotalUSD']))
{
    $data['TotalUSD']=0.0;
}
if(empty($data['Category']))
{
    $data['Category']=0;
}
if(empty($data['Investors']))
{
    $data['Investors']=0;
}
if(empty($data['StartDate']))
{
    $data['StartDate']=$date;
}
if(empty($data['EndDate']))
{
    $data['EndDate']=$date;
}

$PercentRaised = (int) $_POST['Percent'];
  $sql ="UPDATE tbl_icohistory
		SET ICOName='".$data['ICOName']."', StartDate='".$data['StartDate']."',EndDate='".$data['EndDate']."',TotalUSD=".$data['TotalUSD'].",Category=".$data['Category'].",Link='".$data['Link']."',Comments='".$data['Comments']."',Investors=".$data['Investors']."
		,PercentRaised=".$PercentRaised."
		WHERE ID=".$data['ID'];
$result = $conn->query($sql);
echo "Success";
?>