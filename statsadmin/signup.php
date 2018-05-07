<?php
$email=$_POST['email'];
$password=$_POST['password'];
if($email=='admin@admin.com' && $password=='root')
{
    echo "Success";
}
else
{
    echo "Failed";
}
?>