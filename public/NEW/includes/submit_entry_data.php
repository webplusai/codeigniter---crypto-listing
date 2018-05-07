<?
  $steps = mysqli_query($db,"Select SubStepNumber, SubStepName, SubStepHeading from tbl_submitsteps Order by SubStepNumber");
  
  $current_step = $_GET['step']?$_GET['step']:1;

  if ($step > 6) { $step = 6; }
?>