<?php
session_start();
require "codebase/bd.php";

$add = $_GET['add'];

ksort($_POST);
reset($_POST);

$tablesallowed = array('tbl_projects','tbl_links','tbl_events','tbl_crowdfundbonus','tbl_projdistribution','tbl_people','tbl_people_projects');

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
  $dbvalue = mysqli_real_escape_string($db,$value);
  
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
    
    if($add || $insert){$insertfields.="$dbfield, ";$insertvalues.="'$dbvalue', ";} else {$sql.=$dbfield."='$dbvalue', ";}
  }
  else
  {
    // Images 
    if ($_FILES[$field]['tmp_name']!='')
    {
      $dbvalue = base64_encode(file_get_contents($_FILES[$field]['tmp_name']));
      if($add || $insert){$insertfields.="$dbfield, ";$insertvalues.="'$dbvalue', ";} else {$sql.=$dbfield."='$dbvalue', ";}
    }
  }
  
  if ($dbtable!=$nextdbtable || $dbPKvalue!=$nextdbPKvalue)
  {
    if ($add || $insert)
    {      
      // Foreign Keys
      if ($add && $dbtable == 'tbl_events') { $insertfields.= "EventProjID, "; $insertvalues.= "'$projid', ";}
      if ($add && $dbtable == 'tbl_links') { $insertfields.= "LinkParentID, "; $insertvalues.= "'$projid', ";}
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
      $sql = substr($sql,0,-2);
      $sql = "Update $dbtable SET ".$sql.=" Where $dbPKfield = '$dbPKvalue'";
      
      echo $sql;
    }
     
    if ($invalid==0)
    {
      if (!in_array($dbtable,$tablesallowed))
      {
        exit;
      }
       
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

header('Location: index.php');

?>