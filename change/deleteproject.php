<?php
require "codebase/bd.php";

$projid = $_GET['id'];

if ($projid)
{
  mysqli_query($db,"Update tbl_projects Set ProjDeleted = 1 Where ProjID = '$projid'");
}

header('Location: index.php');

?>