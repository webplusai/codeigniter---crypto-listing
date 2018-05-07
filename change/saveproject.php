<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

session_start();
require "codebase/bd.php";
require_once "config.php";
require_once "lib/cake.php";
require_once "lib/functions.php";
require "vendor/predis/src/Autoloader.php";

function convertBase64ToImage($path, $base64)
{
  $ifp = fopen($path, 'wb');
  fwrite($ifp, base64_decode($base64));
  fclose($ifp);

  return $path;
}

$add = $_GET['add'];

ksort($_POST);
reset($_POST);          

$tablesallowed = array('tbl_projects','tbl_links','tbl_events','tbl_crowdfundbonus','tbl_projdistribution','tbl_people','tbl_people_projects');

$arrEventTracking = array('EventName', 'EventStartDate', 'EventEndDate', 'EventStartDateType', 'EventDesc');
$arrProjectField = array();
$arrSql = array();

for($i = 0; $i < count($_POST); ++$i) 
{
  // Current Field
  $value = current($_POST);
  $field = key($_POST);


  $dbtablefield = explode("+",$field);
  $dbPKfield = $dbtablefield[1];
  $dbPKvalue = $dbtablefield[2];
  $dbtable = $dbtablefield[3];
  $dbfield = $dbtablefield[4];
  if (is_array($value)) {
    $valuePlatform = implode(',', $value);
    $dbvalue = mysqli_real_escape_string($db,$valuePlatform);
  } else {
    $dbvalue = mysqli_real_escape_string($db,$value);
  }

  if ($dbtable == 'tbl_projects' || (in_array($dbfield, $arrEventTracking) && substr($dbPKvalue,0,3) != 'ins')) {
    $dbtablefield['value'] = $dbvalue;
    $arrProjectField[$dbfield] = $dbvalue;
  }

  // PeoplePicture
  if (strpos($field, 'PeoplePicture') !== false) {
    if(!empty($_FILES[$field]))
    {
      $pathTeam = '/home/website/live/public/uploads/team/';
      $peoplePicture = rand(111, 999) .'_'. time() . '.' . pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
      $pathTeam = $pathTeam . $peoplePicture;
      if(move_uploaded_file($_FILES[$field]['tmp_name'], $pathTeam)) {
        $dbvalue = $peoplePicture;
      }
    }
  }
  
  if (substr($dbPKvalue,0,3)=='ins' || $add) 
  {   
    // Error Check 
    if ($dbtable=='tbl_projects' && $dbfield=='ProjName' && $dbvalue==''){ $invalid = 1; }
    if ($dbtable=='tbl_links' && $dbfield=='Link' && $dbvalue==''){ $invalid = 1; }  
    if ($dbtable=='tbl_events' && $dbfield=='EventName' && $dbvalue==''){ $invalid = 1; } 
    if ($dbtable=='tbl_crowdfundbonus' && $dbfield=='CrowdBonusName' && $dbvalue==''){ $invalid = 1; }
    if ($dbtable=='tbl_projdistribution' && $dbfield=='DistroDesc' && $dbvalue==''){ $invalid = 1; } 
    if ($dbtable=='tbl_people' && $dbfield=='PeopleName' && $dbvalue==''){ $invalid = 1; } 
  
    $insert = 1;
  }
 
  // Delete 
  if ($dbtable=='tbl_links' && $dbfield=='Link' && $dbvalue==''){ $delete = 1; }  
  if ($dbtable=='tbl_events' && $dbfield=='EventName' && $dbvalue==''){ $delete = 1; }  
  if ($dbtable=='tbl_crowdfundbonus' && $dbfield=='CrowdBonusName' && $dbvalue==''){ $delete = 1; } 
  if ($dbtable=='tbl_projdistribution' && $dbfield=='DistroDesc' && $dbvalue==''){ $delete = 1; }
  if ($dbtable=='tbl_people' && $dbfield=='PeopleName' && $dbvalue==''){ $delete = 1; } 
  
  // Next Field
  $nextvalue = next($_POST);   
  $nextfield = key($_POST);
  
  $nextdbtablefield = explode("+",$nextfield);
  $nextdbPKvalue = $nextdbtablefield[2];
  $nextdbtable = $nextdbtablefield[3];

  if ($dbfield=='PeopleName') {
    $dbvalue = ucwords(strtolower($dbvalue));
  }

  // Build SQL
  if (strpos($dbfield,'Image') === false)
  {
    // Platform(s)
    if ($dbfield=='ProjPlatform')
    {
      $dbvalue = "";
      foreach ($value as $platform)
      {
        $dbvalue.=$platform.',';
      }
      $dbvalue = mysqli_real_escape_string($db,substr($dbvalue,0,-1));
    }
    
    if($add || $insert) {
      if ($dbtable == 'tbl_links' && $peopleid) {
        $insertfields.="$dbfield, LinkParentID,LinkType,LinkParentType, ";
        $insertvalues.="'$dbvalue','$peopleid', 10, 4, ";
      } else {
        $insertfields.="$dbfield, ";$insertvalues.="'$dbvalue', ";
      }
    } else {
      $sql.=$dbfield."='$dbvalue', ";
    }
  }
  else
  {
    // Images 
    if ($_FILES[$field]['tmp_name']!='' && strpos($field,'Image') !== false)
    {
      if ($dbfield == 'ProjHeaderImage') {

        $pathHeader = "/home/website/live/public/uploads/project_headers/";
        $headerPicture = rand(111, 999) .'_'. time() . '.' . pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $pathHeader = $pathHeader . $headerPicture;

        if(move_uploaded_file($_FILES[$field]['tmp_name'], $pathHeader)) {
          $dbvalue = $headerPicture;
        }

      } else {
        $dbvalue = base64_encode(file_get_contents($_FILES[$field]['tmp_name']));
        if (!empty($_POST['editprojectid'])) {
          $path = '/home/website/api/logos/';
          if (strpos($field, 'ProjImageLarge') !== FALSE) {
            $fileName = $path . $_POST['editprojectid'] . '_large.png';
          } else {
            $fileName = $path . $_POST['editprojectid'] . '.png';
          }

          convertBase64ToImage($fileName, $dbvalue);
        }
      }

      if($add || $insert) {
        $insertfields.="$dbfield, ";$insertvalues.="'$dbvalue', ";
      } else {
        $sql.=$dbfield."='$dbvalue', ";
      }
    }
  }


  if ($dbtable!=$nextdbtable || $dbPKvalue!=$nextdbPKvalue)
  {
    if ($add || $insert)
    {      
      // Foreign Keys
      if ($add && $dbtable == 'tbl_events') { $insertfields.= "EventProjID, "; $insertvalues.= "'$projid', ";}
      if ($add && $dbtable == 'tbl_links' && $peopleid) {
        $insertfields.= "LinkParentID, LinkType, LinkParentType, "; $insertvalues.= "'$peopleid', 10, 4, ";
      } else if ($add && $dbtable == 'tbl_links') {
        $insertfields.= "LinkParentID, "; $insertvalues.= "'$projid', ";
      }
      if ($add && $dbtable == 'tbl_projdistribution') { $insertfields.= "DistroProjID, "; $insertvalues.= "'$projid', ";}
      
      if ($add && $dbtable == 'tbl_crowdfundbonus') { $insertfields.= "CrowdBonusEventID, "; $insertvalues.= "'$eventid', ";}
    
      if ($add && $dbtable == 'tbl_people_projects') { $insertfields.= "PeopleProjPeopleID,PeopleProjProjID, "; $insertvalues.= "'$peopleid','$projid', ";}
      
      if ($insert && $dbtable == 'tbl_people_projects') { $insertfields.= "PeopleProjPeopleID, "; $insertvalues.= "'$peopleid', "; $invalid = $peopleid==0?1:0; $peopleid=0;}
    
      // Insert
      $sql = "Insert into $dbtable (".substr($insertfields,0,-2).") Values (".substr($insertvalues,0,-2).")";      
      
      $insertfields = "";
      $insertvalues = "";
    }
    elseif ($delete)
    {      
      // Delete
      if ($dbtable=='tbl_people')
      {
        $sql = "Delete p,pp from tbl_people p inner join tbl_people_projects pp on p.PeopleID = pp.PeopleProjPeopleID where $dbPKfield = '$dbPKvalue'";  
      }
      else
      {
        $sql = "Delete from $dbtable where $dbPKfield = '$dbPKvalue'";
      }
      
    }
    else
    {
      // Update
      if (strpos($dbPKvalue, 'upd') !== false && $dbtable == 'tbl_links') {
        $dbPKvalue = str_replace('upd', '', $dbPKvalue);
        $sql = "INSERT INTO `tbl_links` (`LinkID`, `LinkType`, `LinkParentType`, `LinkParentID`, `Link`) VALUES (NULL, '10', '4', $dbPKvalue, '$dbvalue');";
      } else {
        $sql = substr($sql,0,-2);
        $sql = "Update $dbtable SET ".$sql.=" Where $dbPKfield = '$dbPKvalue'";
      }
    }
     
    if ($invalid==0)
    {
      if (!in_array($dbtable,$tablesallowed))
      {
        exit;
      }

      $arrSql[] = $sql;
      mysqli_query($db,$sql);

      // IDs for Foreign Keys
      if ($dbtable == 'tbl_projects') { $projid = mysqli_insert_id($db); } 
      if ($dbtable == 'tbl_events') { $eventid = mysqli_insert_id($db); }
      if ($dbtable == 'tbl_people') { $peopleid = mysqli_insert_id($db); }    
      //echo $sql.'<BR>';
    }
    
    // Reset Vars
    $sql = "";    
    $invalid = 0;
    $insert = 0;
    $delete = 0;
  }
}

// flushall redis
Predis\Autoloader::register();
$options = [
    'parameters' => [
        'password' => REDIS_PASSWORD
    ]
];
$client = new Predis\Client([
    'scheme' => REDIS_SOCKET,
    'host'   => REDIS_HOST,
    'port'   => REDIS_PORT,
], $options);

$settings = $client->flushall();


if (!empty($arrSql)) {
  
  if (isset($_POST['editprojectid']) && !empty($_POST['editprojectid'])) {
    saveProjectLog($connection, $arrProjectField, $_POST['editprojectid']);
  } else {
    saveProjectLog($connection, $arrProjectField);
  }

  $connection->update('tbl_settings', array('SettingValue' => date('Y-m-d H:i:s')), array('SettingID' => 6));

}

//echo '<pre>'; print_r($arrSql); echo '</pre>';
header('Location: index.php');

?>