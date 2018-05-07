  <?php 
include  'database.php';
$id=$_GET['id'];
$sql = 'Delete from tbl_icohistory where ID='.$id;
$result = $conn->query($sql);
echo "Success";
?> 