<?php
require "codebase/bd.php";
  
  if($_POST)
  {
    foreach($_POST as $post => $value)
    {
      if (strpos($post,'Category') !== false)
      {
        $id = str_replace('Category','',$post);
        
        $link = $_POST['Link'.$id];
        
        mysqli_query($db,"Update tbl_icohistory Set Category=$value,Link='$link' where ID=$id");
      }
    }
    echo 'OK';
  }

?>