<?

  $step = $_GET['step'];
  
  if ($step>0 && $step < 6)
  { 
    $step++;
  }
  
  header("Location: submit_entry?step=$step");
?>