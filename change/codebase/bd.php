<?php
//set math precision
//bcscale(8);
//test deploy

if (defined('ROOT_PATH')) {
	require_once(ROOT_PATH. 'change/config.php');
} else {
	require_once('config.php');
}



$db = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD) or die("Connection failed !");
mysqli_select_db($db,DB_DATABASE);
$faz1=mysqli_query($db,"SET NAMES 'utf8';");
$faz1=mysqli_query($db,"SET time_zone = '+0:00';");



function curl_download_new($Url,$port=7876,$ssl_version=1){
    // is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
	
	//make sure that https is set
	if (strpos($Url,'http')===false){
		$Url=str_replace('http://','',$Url);
		$Url='https://'.$Url;	
	}
	
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    curl_setopt($ch, CURLOPT_PORT, $port);
	if(strpos($Url,'https')!==false){
		curl_setopt($ch, CURLOPT_SSLVERSION, $ssl_version);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	}
	$output = curl_exec($ch);
	if(curl_errno($ch))
	{
		return 'Error! URL: '.$Url.' Error ID: '.curl_errno($ch);
	}
    curl_close($ch);
 
    return $output;
}

//This is the old CURL version, before using the forgers
//function curl_download_new($Url,$port=7876){
function curl_download_old($Url,$port=7876){
    $url_original=$Url;
	
	// is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
	
	//make sure that https is set
	if (strpos($Url,'http')===false){
		$Url=str_replace('http://','',$Url);
		$Url='https://'.$Url;	
	}
	
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_PORT, $port);
	if(strpos($Url,'https')!==false){
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	}
	$output = curl_exec($ch);
	
	if(curl_errno($ch))
	{
		//if error 7 happened (host unreachable) send warning and switch to
		//the server pool
		if((curl_errno($ch)==7) && ($url_original=='127.0.0.1')){
			mail('abuelau@gmail.com','Mynxt.info OFFLINE!','Local NRS is offline');
			$server = "localhost";
			$userName = "root";
			$pass = "fsfiodf7DJ&";
			$database = "mynxt";
			$db2 = mysqli_connect($server,$userName,$pass) or die("Connection failed !");
			mysqli_select_db($db2,$database);
			$faz1=mysqli_query($db2,"SET NAMES 'UTF8'");
			$sql="UPDATE tbl_servers SET nm_ok=0 WHERE tx_address='localhost'";
			$my_local_upt=mysqli_query($db2,$sql);
			mysql_close ($db2);
		}
		
		return 'Error: Tx '.curl_errno($ch);
		
	}
    curl_close($ch);
 	
    return $output;
}

function send_mailold($to,$subject,$html){
	$text=strip_tags($html);
	
	$mime_boundary=md5(time());
	$headers .= 'From: Coinschedule <moon@coinschedule.com>' . "\n";
	$headers .= 'MIME-Version: 1.0'. "\n";
	$headers .= "Content-Type: multipart/alternative; boundary=\"".$mime_boundary."\"". "\n"; 
	 
	$msg_to = "--".$mime_boundary. "\n";
	$msg_to .= "Content-Type: text/plain; charset=iso-8859-1". "\n";
	$msg_to .= "Content-Transfer-Encoding: 7bit". "\n\n";
	$msg_to .= $text . "\n\n";
	$msg_to .= "--".$mime_boundary. "\n";
	$msg_to .= "Content-Type: text/html; charset=iso-8859-1". "\n";
	$msg_to .= "Content-Transfer-Encoding: 7bit". "\n\n";
	$msg_to .= $html . "\n\n";
	$msg_to .= "--".$mime_boundary."--". "\n\n";
	
	mail($to,$subject,$msg_to,$headers);
	mail('abuelau@gmail.com',$subject.' [copy]',$msg_to,$headers);
									  
}


function select_server($db_connection){
	//************ SERVER SELECTION ********

	$sql="SELECT nm_ok FROM tbl_servers WHERE tx_address='localhost'";
	$my_local=mysqli_query($db_connection,$sql);
	$local_ok=false;
	if($row_local=mysqli_fetch_array($my_local)){
		if($row_local['nm_ok']==1)$local_ok=true;
	}
	//If local server is marked as OK, use it
	if($local_ok){
		$server="http://127.0.0.1";
	}else{
		//otherwise, choose from the poll
		$sql="SELECT * from (
		   SELECT * FROM tbl_servers 
		   WHERE tx_status='Online' 
		   ORDER BY nm_height DESC LIMIT 5
		) T ORDER BY RAND() 
		LIMIT 1";
		$my_svr=mysqli_query($db_connection,$sql);
		if($row_svr=mysqli_fetch_array($my_svr)){
			$server=$row_svr['tx_address'];
		}else{
			$server="node7.mynxt.info";
		}
	}
	
	return $server;
}


function format_nxt($amount){
	if(bcmul($amount,1)==0){
		return "0";
	}

	
	$amount_temp=bcmul($amount,100000000);
	$number_of_zeros=0;
	
	for($x=-1;$x>-10;$x--){
		$right_part=substr($amount_temp,$x);
		$number_of_zeros++;
		if((int)($right_part*1)!=0)break;
	}
	
	
	if($number_of_zeros>2){
		$precision=2;
	}else{
		$precision=9-$number_of_zeros;
	}
	
	$formatted_number=number_format($amount,$precision);
	return $formatted_number;
}

function SendMail($to,$subject,$html){
	$text=strip_tags($html);
	
	$mime_boundary=md5(time());
	$headers .= 'From: MyNxt.info <info@mynxt.info>' . "\n";
	$headers .= 'MIME-Version: 1.0'. "\n";
	$headers .= "Content-Type: multipart/alternative; boundary=\"".$mime_boundary."\"". "\n"; 
	 
	$msg_to = "--".$mime_boundary. "\n";
	$msg_to .= "Content-Type: text/plain; charset=iso-8859-1". "\n";
	$msg_to .= "Content-Transfer-Encoding: 7bit". "\n\n";
	$msg_to .= $text . "\n\n";
	$msg_to .= "--".$mime_boundary. "\n";
	$msg_to .= "Content-Type: text/html; charset=iso-8859-1". "\n";
	$msg_to .= "Content-Transfer-Encoding: 7bit". "\n\n";
	$msg_to .= $html . "\n\n";
	$msg_to .= "--".$mime_boundary."--". "\n\n";
	
	mail($to,$subject,$msg_to,$headers);
	mail('abuelau@gmail.com',$subject.' [copy]',$msg_to,$headers);
									  
}

function base64_url_encode($input) {
 return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
 return base64_decode(strtr($input, '-_,', '+/='));
}

function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function show_nice_msg2($msg,$type='success')
{
	echo '<div class="alert alert-'.$type.'" role="alert">'.$msg.'</div>';
}


function show_nice_msg($msg, $reduced_width=1,$color='#FFF1A8')
{
	echo '<div id="nice_msg" style="border:1px #FFF solid;background-color:'.$color.';color:#000;padding:10px;';
	if ($reduced_width==1) echo'width:90%;';
	echo 'margin-left:auto;margin-right:auto;margin-bottom:15px;margin-top:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;display:none">';
	echo $msg;
	echo '</div>';
	echo '<script language="javascript">';
	echo '$(document).ready( function() {';
	echo '$("#nice_msg").fadeIn(1000);';
    echo '$("#nice_msg").click(function() {$("#nice_msg").fadeOut(700);});';
	echo '});';
    echo '</script>';
}


function define_text_top($username,$areaname)
{
	$text_top='<div style="overflow:auto"><div style="float:left">';
	if ($areaname)	$text_top=$text_top.$areaname.'&nbsp;&nbsp;&#183;&nbsp;&nbsp;';
	if ($username)	$text_top=$text_top.$username;
	$text_top=$text_top.'</div><div style="float:right;margin-right:20px;"><a style="color:#fff;text-decoration:none" href="dashboard.php">dashboard</a>&nbsp;&nbsp;&#183;&nbsp;&nbsp<a style="color:#fff;text-decoration:none" href="timeline.php">my timeline</a>&nbsp;&nbsp;&#183;&nbsp;&nbsp<a style="color:#fff;text-decoration:none" href="settings.php">settings</a>&nbsp;&nbsp;&#183;&nbsp;&nbsp<a style="color:#fff;text-decoration:none" href="logout_all.php">logout</a></div></div>';
	return $text_top;
}

function get_facebook_cookie($app_id, $application_secret) {
    $args = array();
    parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
    ksort($args);
    $payload = '';
    foreach ($args as $key => $value) {
        if ($key != 'sig') {
            $payload .= $key . '=' . $value;
        }
    }
    if (md5($payload . $application_secret) != $args['sig']) {
      return null;
    }
    return $args;
}



function timeToAge($ts)
    {
        if ($ts>31536000) {
            $val = round($ts/31536000,0).' year';
        } else if ($ts>2419200) {
            $val = round($ts/2419200,0).' month';
        } else if ($ts>604800) {
            $val = round($ts/604800,0).' week';
        } else if ($ts>86400) {
            $val = round($ts/86400,0).' day';
        } else if ($ts>3600) {
            $val = round($ts/3600,0).' hour';
        } else if ($ts>60) {
            $val = round($ts/60,0).' min';
        }else {
            $val = $ts.' sec';
        }

        if($val>1) $val .= 's';
        return $val.' ago';
 }
 function timeToDate($ts)
    {
        $val = 0;
        
        if ($ts>31536000) {
            $val = round($ts/31536000,0).' year';
        } else if ($ts>2419200) {
            $val = round($ts/2419200,0).' month';
        } else if ($ts>950400) {
            $val = round($ts/604800,0).' week';
        } else if ($ts>86400) {
            $val = round($ts/86400,0).' day';
        } else if ($ts>3600) {
            $val = round($ts/3600,0).' hour';
        } else if ($ts>60) {
            $val = round($ts/60,0).' min';
        }

        if($val>1) $val .= 's';
        
		if(!trim($val)) $val .= 'now';
        return $val.'';
 }
 
	function getMinString($ts)
    {
        return sprintf( "%02.2d:%02.2d", floor( $ts / 60 ), $ts % 60 );
    }
	
    function getAgeString($dateTime)
    {
        $ts = time() - strtotime(str_replace("-","/",$dateTime));
        return timeToAge($ts);
    }

    function getAgeNxt($nxt_ts)
    {
		$dateTime2=$nxt_ts+strtotime('2013-11-24 12:00:00');
		
		/*
		echo $nxt_ts.'|';
		echo strtotime('2013-11-24 12:00:00').'|';
		echo $dateTime2.'<BR><BR>';
		*/
		
        $ts = time() - $dateTime2;
        return timeToAge($ts);
    }
    
	
	function getTSNxt($nxt_ts)
    {
		$dateTime2=$nxt_ts+strtotime('2013-11-24 12:00:00');
				
        return date("j-M-Y h:ia",$dateTime2);
    }


	function truncate_string($details,$max)
	{
		if(strlen($details)>$max)
		{
			$details = substr($details,0,$max);
			$i = strrpos($details," ");
			$details = substr($details,0,$i);
			$details = $details." ..... ";
		}
		return $details;
	}

//gera senha random
function geraSenha ($length = 8)
{
  // start with a blank password
  $password = "";
  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
  // set up a counter
  $i = 0; 
  // add random characters to $password until $length is reached
  while ($i < $length) { 
    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }
  }
  // done!
  return $password;
}




// Retorna timestamp para datas yyyy-mm-dd hh:mm:ss
	function viratimestamp($data) {
		$tmp1 = explode(" ",$data);
		$tmp2 = explode(":",$tmp1[1]);
		$seg = $tmp2[2];
		$min = $tmp2[1];
		$hor = $tmp2[0];
		$tmp3 = explode("-",$tmp1[0]);
		$dia = $tmp3[2];
		$mes = $tmp3[1];
		$ano = $tmp3[0];
		return mktime($hor,$min,$seg,$mes,$dia,$ano);
	}
	function timestamp($data) {
		$tmp1 = explode(" ",$data);
		$tmp2 = explode(":",$tmp1[1]);
		$seg = $tmp2[2];
		$min = $tmp2[1];
		$hor = $tmp2[0];
		$tmp3 = explode("-",$tmp1[0]);
		$dia = $tmp3[2];
		$mes = $tmp3[1];
		$ano = $tmp3[0];
		$ultimoacesso = mktime($hor,$min,$seg,$mes,$dia,$ano);
		$agora = time();
		$resultado = $agora - $ultimoacesso;
		return $resultado;
	}
?>
