<?php
session_start();
require "codebase/bd.php";

$id = $_GET['id'];
$view = $_GET['view'];
$status = $_GET['status'];

mysqli_query($db,"Update tbl_submissions Set SubStatus = $status,SubStatusUpdatedOn = '".date("Y-m-d H:i:s")."' where SubID = $id");

if ($status == 2)
{
  header('Location: project.php?subid='.$id);
}
else
{
  header('Location: submissions.php?view='.$view);
}


?>