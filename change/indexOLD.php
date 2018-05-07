<?php
// ini_set("display_startup_errors", 1); ini_set("display_errors", 1);
session_start();
require "../lib/bd.php";

//saves edits
if(isset($_POST['submission'])) { 	   
	if($_REQUEST['id']==""){
		echo "ERROR";exit;
	}else{
		$id=$_REQUEST['id'];
	}
	echo "UPDATING item...<BR><BR>";
	$sql="UPDATE tbl_data SET ";
	foreach($_POST as $name => $value){
		if(strpos($name,'_')>0){
			if(strpos($name,'tx_')!==false)$sql.="$name='$value', ";
			if(strpos($name,'dt_')!==false)$sql.="$name='$value', ";
			if(strpos($name,'nm_')!==false)$sql.="$name=$value, ";
		}
	}
	$sql=substr($sql,0,-2);
	$sql.=" WHERE id=".$id;
	echo 'SQL: '.$sql.'<BR><BR>';
	$addit=mysqli_query($db,$sql);
	$sql_time="UPDATE tbl_data SET dt_change=NOW() WHERE id=".$id;
	$addit2=mysqli_query($db,$sql_time);

	//add to the log
	$last_id=$id;
	$thechange="Updated ";
	$tx_comments=mysqli_real_escape_string($db,$_POST['comments']);
	if($_POST['tx_type']='crowdfund' || $_POST['tx_type']='milestone'){
		//coin stuff
		$thechange.=$_POST['tx_coin']." - ".$_POST['tx_name']." entry";
	}else{
		//event	
		$thechange.=$_POST['tx_name']." event";		
	}
	$sql="INSERT INTO tbl_log SET id_data=$last_id,tx_change='$thechange',tx_comments='$tx_comments'";
	$addlog=mysqli_query($db,$sql);
	echo 'SQL2: '.$sql.'<BR><BR>';

	//tweet
	echo '<BR>Tweeting...<BR>';
	// require codebird
	require_once('../lib/codebird/src/codebird.php');
	 
	\Codebird\Codebird::setConsumerKey("GDqsvAvEdwd8g8aGkocC5cReL", "X3MFmoclAiV89X4bC1ZUMeFPnZgR0f4nlRndhAtpnusRW8mA3O");
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken("764788651872096256-gBTfNLvYYA0EqZASm5ZaNNy1XhRikca", "XyVchkN9lxOSBy0xBnACJpA7su10BmjkpwBJD09xWVZ20");
	 
	$tweet=$thechange.' http://coinschedule.com/'.$last_id.'/'.strtolower(str_replace(" ",'-',$_POST['tx_name']));
	$params = array(
	  'status' => $tweet
	);
	$reply = $cb->statuses_update($params);
	//var_dump($reply);
	$status = $reply->httpstatus;
    if($status == 200) {
		echo 'Tweet sent<BR>';
	}else{
		echo 'Tweet FAILED!<BR>';
	}
	
	//end
	echo "Saved!";

	echo "<BR><BR><BR><a href='/change/index.php'>GO BACK</a>";
	exit;
}


//saves new item
if(isset($_POST['adding'])) { 	   
	echo "Saving new item...<BR><BR>";
	$sql="INSERT INTO tbl_data SET ";
	foreach($_POST as $name => $value){
		if(strpos($name,'_')>0){
			if(strpos($name,'tx_')!==false)$sql.="$name='$value', ";
			if(strpos($name,'dt_')!==false)$sql.="$name='$value', ";
			if(strpos($name,'nm_')!==false)$sql.="$name=$value, ";
		}
	}
	$sql=substr($sql,0,-2);
	echo 'SQL: '.$sql.'<BR><BR>';
	$addit=mysqli_query($db,$sql);

	//add to the log
	$last_id=mysqli_insert_id($db);
	$thechange="Added new ".($_POST['tx_type'])." entry ".$_POST['tx_name'];
	if($_POST['tx_type']='crowdfund' || $_POST['tx_type']='milestone')$thechange.=" for coin ".$_POST['tx_coin'];
	$sql="INSERT INTO tbl_log SET id_data=$last_id,tx_change='$thechange'";
	$addlog=mysqli_query($db,$sql);
	echo 'SQL2: '.$sql.'<BR><BR>';

	//tweet
	echo '<BR>Tweeting...<BR>';
	// require codebird
	require_once('../lib/codebird/src/codebird.php');
	 
	\Codebird\Codebird::setConsumerKey("GDqsvAvEdwd8g8aGkocC5cReL", "X3MFmoclAiV89X4bC1ZUMeFPnZgR0f4nlRndhAtpnusRW8mA3O");
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken("764788651872096256-gBTfNLvYYA0EqZASm5ZaNNy1XhRikca", "XyVchkN9lxOSBy0xBnACJpA7su10BmjkpwBJD09xWVZ20");
	 
	$tweet=$thechange.' http://coinschedule.com/'.$last_id.'/'.strtolower(str_replace(" ",'-',$_POST['tx_name']));
	$params = array(
	  'status' => $tweet
	);
	$reply = $cb->statuses_update($params);
	//var_dump($reply);
	$status = $reply->httpstatus;
    if($status == 200) {
		echo 'Tweet sent<BR>';
	}else{
		echo 'Tweet FAILED!<BR>';
	}
	
	//end
	echo "Saved!";

	echo "<BR><BR><BR><a href='/change/index.php'>GO BACK</a>";
	exit;
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Coinschedule - Admin</title>

<!-- Bootstrap -->
<link href="/css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<body>
<div class="container">
  <div class="row">
    <?php
if($msg) {
		$type="danger";
		if (strpos($msg,"success")>0)$type="success";
		show_nice_msg2($msg,$type);
	}

$sql_search = "SELECT * FROM tbl_data WHERE ";
$sql_search_fields = Array();
$sql = "SHOW COLUMNS FROM tbl_data";
$rs = mysqli_query($db,$sql);
	while($r = mysqli_fetch_array($rs)){
		$colum = $r[0];
		$columns[]=$colum;
		$sql_search_fields[] = $colum." like('%".mysqli_real_escape_string($db,$_POST['tx_search'])."%')";
	}

//searches for entry
if(isset($_POST['tx_search'])) { 	   
	$sql_search .= implode(" OR ", $sql_search_fields);
	$sql=$sql_search; 
	$listit=mysqli_query($db,$sql);
	
} else {
	$sql="SELECT * FROM tbl_data ORDER BY id DESC LIMIT 0,3"; 	
}
//if edit mode enabled, show that item
if(isset($_GET['edit'])) { 	   
	$sql="SELECT * FROM tbl_data WHERE id=".mysqli_real_escape_string($db,$_GET['edit']); 		
}

echo '<div style="background-color:#CCC;padding:7px;margin-bottom:20px">'.$sql.'</div>';
?>

    <form method="post" class="form-inline">
      <div class="form-group">
        <label for="tx_name">Search</label>
        <span style="color:#F00">*</span>
        <input type="text" class="form-control" id="tx_search" name="tx_search" placeholder="Search" value="<?php=$_REQUEST['tx_search']?>">
        <input type="submit" name="submit" id="submit" value="Submit">
        |
        <input type="button" name="button" id="button" value="Add new item" onclick="location.href='index.php?add=true'">
      </div>
    </form>
    <hr>

<?php


//lists the results OR latest 3 entries
$listit=mysqli_query($db,$sql);
echo "<h1>Results</h1>";
echo "<table  class='table'>"; 
while ($row=mysqli_fetch_array($listit)){
	echo "<tr>";
		for ($n=0;$n<mysqli_num_fields($listit);$n++){
			echo "<td>";
			if($n==0){echo '<a href="index.php?edit='.$row[$n].'">'.$row[$n].'</a>';}else{echo $row[$n];}
			echo '</td>';
		}
	echo "</tr>";
}	
echo "</table><hr>"; 


?>
    <?php if(isset($_GET['edit']) || isset($_GET['add'])) { ?>
    <h1  class="text-left">
      <?php if($_REQUEST['edit']){?>
      Edit
      <?php }else{?>
      Add
      <?php }?>
      entry</h1>
      <hr>
    <?php }?>
    <?php 
//*********************************
//EDIT ITEM
//*********************************
?>
    <?php
if(isset($_GET['edit'])) { 
mysqli_data_seek($listit,0);
if ($row=mysqli_fetch_array($listit)){
	echo '<h2 class="text-left">'.$row['tx_name'].'</h2>';
?>
    <div class="container">
      <div class="col-md-6">
        <form method="post" class="form-horizontal">
          <input type="hidden" name="submission" id="submission" value="1">
          <input type="hidden" name="id" id="id" value="<?php echo $_GET['edit']?>">
          <?php foreach ($columns as $thiscolumn){?>
          <div class="form-group">
            <label for="<?php echo $thiscolumn?>"  class="col-sm-3 control-label"><?php echo $thiscolumn?></label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="<?php echo $thiscolumn?>" name="<?php echo $thiscolumn?>" value="<?php echo $row[$thiscolumn]?>" <?php if($thiscolumn=='id' || $thiscolumn=='dt_change')echo ' disabled'?>>
            </div>
          </div>
          <?php }?>
          <div class="form-group">
            <label for="tx_comments" class="col-sm-3 control-label">Comments about this update</label>
            <div class="col-sm-9">
              <textarea name="comments" rows="5"  class="form-control" id="comments" value=""></textarea>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">SAVE</button>
        </form>
      </div>
    </div>
    <?php }}?>
    <?php 
//*********************************
//ADD NEW ITEM
//*********************************
?>
    <?php
if(isset($_GET['add'])) { 
?>
    </p>
    <div class="container">
      <div class="col-md-6">
        <form method="post" class="form-horizontal">
          <input type="hidden" name="adding" id="adding" value="1">
          <?php foreach ($columns as $thiscolumn){?>
          <div class="form-group">
            <label for="<?php echo $thiscolumn?>" class="col-sm-3 control-label"><?php echo $thiscolumn?></label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="<?php echo $thiscolumn?>" name="<?php echo $thiscolumn?>" value="<?php echo $row[$thiscolumn]?>"  <?php if($thiscolumn=='id' || $thiscolumn=='dt_change')echo ' disabled'?>>
            </div>
          </div>
          <?php }?>
          <button type="submit" class="btn btn-primary">SAVE</button>
        </form>
      </div>
    </div>
    <?php }?>
    
    <!-- Include all compiled plugins (below), or include individual files as needed --> 
    <script src="/js/bootstrap.min.js"></script> 
  </div>
</div>
</body>
</html>
