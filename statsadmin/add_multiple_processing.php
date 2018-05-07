<?php
include  'database.php';
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
date_default_timezone_set('UTC');
$date=date('Y-m-d');
$count==0;
$fp = fopen($_FILES['csv']['tmp_name'],"r");
while (($array = fgetcsv($fp)) !== FALSE)
{
    if($count==0)
    {
        if($array[0]!='IcoName' && $array[1]!='StartDate' && $array[2]!='EndDate' && $array[3]!='TotalUSD'  && $array[4]!='Category' && $array[5]!='Link' && $array[6]!='Comments' && $array[7]!='Investors')
        {
        	echo "Failed";
        	die();
        }
    }
    if($count>0)
    {
        if(empty($array[3]))
        {
            $array[3]=0.0;
        }
        if(empty($array[4]))
        {
            $array[4]=0;
        }
        if(empty($array[7]))
        {
            $array[7]=0;
        }
        if(empty($array[1]))
        {
            $array[1]=$date;
        }
        if(empty($array[2]))
        {
            $array[2]=$date;
        }
    	$array[1]=str_replace('/','-',$array[1]);
    	$array[2]=str_replace('/','-',$array[2]);
    	$sql="INSERT INTO tbl_icohistory (ICOName, StartDate, EndDate,TotalUSD,Category,Link,Comments,Investors,PercentRaised)
  			  VALUES ('".$array[0]."','".$array[1]."','".$array[2]."',".$array[3].",".$array[4].",'".$array[5]."','".$array[6]."',".$array[7].",".$array[8].")";
		$result=$conn->query($sql);
    }
    $count++;
}//end while
echo "Success";
?>