<?php

$id = $_GET['id'];

if ($id)
{
require "codebase/bd.php";
  
  $res = mysqli_fetch_array(mysqli_query($db,"Select * from tbl_results where ResID = $id"));

  $name = $res['ResName'];
  $start = $res['ResStart'];
  $end = $res['ResEnd'];
  $total = $res['ResTotalRaised'];

  mysqli_query($db,"
  Insert into tbl_icohistory
  (ICOName,StartDate,EndDate,TotalUSD)
  values
  ('$name','$start','$end',$total)
  ");  

}

header('Location: https://www.coinschedule.com/change/newresults.php');

?>