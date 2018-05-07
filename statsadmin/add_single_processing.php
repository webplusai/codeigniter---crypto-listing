<?php
include  'database.php';
date_default_timezone_set('UTC');
$date=date('Y-m-d');
$data=array(
    'ICOName'=>$_POST['ICOName'],
    'StartDate'=>$_POST['StartDate'],
    'EndDate'=>$_POST['EndDate'],
    'TotalUSD'=>$_POST['TotalUSD'],
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
$sql="INSERT INTO tbl_icohistory (ICOName, StartDate, EndDate,TotalUSD,Category,Link,Comments,Investors,PercentRaised)
      VALUES ('".$data['ICOName']."','".$data['StartDate']."','".$data['EndDate']."',".$data['TotalUSD'].",".$data['Category'].",'".$data['Link']."','".$data['Comments']."','".$data['Investors']."', $PercentRaised)";

$result=$conn->query($sql);
echo "Success";
?>