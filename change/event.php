<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="./public/js/jquery.js"></script>
<script src="./public/js/jquery.datetimepicker.full.min.js"></script>
<script src="./public/js/tab.js"></script>

<link href="./public/css/edit.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./public/css/jquery.datetimepicker.css"/ >
<link href="./public/css/tab.css?<?php echo time(); ?>" rel="stylesheet">
<title>Coinschedule - Edit Event</title>
</head>
<body> 
<?php
require_once('menu.php');
require "codebase/bd.php";
require "./codebase/functions.php";

$id=$_REQUEST['id'];
$eventid=mysqli_real_escape_string($db,$id);

if ($eventid==''){$add = 1;}

echo '<div style="padding: 15px;"><h1 style="display:inline;">'.($add?'Add':'Edit').' Event</h1>';


?>


</div>
<?php require_once('footer.php'); ?>
</body>
</html>