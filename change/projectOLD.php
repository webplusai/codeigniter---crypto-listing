<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="./js/jquery.js"></script>
<script src="./js/jquery.datetimepicker.full.min.js"></script>
<script src="./js/tab.js"></script>

<link href="css/edit.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./css/jquery.datetimepicker.css"/ >
<link href="css/tab.css?<?php echo time(); ?>" rel="stylesheet">
</head>
<body> 
<?php
require "../lib/bd.php";
require "./codebase/functions.php";

$id=$_REQUEST['id'];
$projid=mysqli_real_escape_string($db,$id);

if ($projid==''){$add = 1;}

echo '<h1 style="display:inline;">'.($add?'Add':'Edit').' Project</h1>';

if (!$add)
{
echo '<button onclick="if (confirm(\'Are you sure you want to delete this Project?\')){location.href=\'deleteproject.php?id='.$projid.'\';}" style="vertical-align: top;width: 100px;height: 30px;display:inline;margin: 4px 0px 0px 40px;float: right;">Delete</button>';
}

$projectinfo = mysqli_query($db,"
Select ProjID as 'Project ID',
ProjType as 'Type',
ProjPlatform as 'Platform',
ProjName as 'Name',
ProjSymbol as 'Symbol',
ProjDesc as 'Description',
ProjImage as 'Logo (16x16)',
ProjImageLarge as 'Logo (48x48)',
ProjSponsored as 'Sponsored',
ProjLocation as 'Location',
ProjTotalSupp as 'Total Supply',
ProjPreMined as 'Pre-mined',
ProjPreMinedNote as 'Pre-mined Note',
ProjAlgo as 'Algorithm',
EventID as 'ICO ID',
EventName as 'ICO Name',
EventStartDate as 'ICO Start',
EventEndDate as 'ICO End',
EventDesc as 'ICO Description' 
from tbl_projects P
inner join tbl_events E
on P.ProjID = E.EventProjID
where EventType = 1".($projid!=''?" and ProjID = $projid":'')." LIMIT 1");


if (mysqli_num_rows($projectinfo) > 0)
{

  echo '<form enctype="multipart/form-data" action="saveproject.php'.($add?'?add=1':'').'" method="post" style="display:inline;"><input type="submit" style="vertical-align: top;width: 100px;height: 30px;display:inline;margin: 4px 0px 10px 40px;" value="Save" /><table style="padding: 0px;margin: 0px;border-collapse: collapse;width: 100%;table-layout: fixed;"><tr><td valign="top">';
  
  echo '<div class="tab">
  <input type="button" class="tablinks" onclick="switchtab(event, \'Project\')" value="Project">
  <input type="button" class="tablinks" onclick="switchtab(event, \'Links\')" value="Links">
  <input type="button" class="tablinks" onclick="switchtab(event, \'Rates\')" value="Rates">
  <input type="button" class="tablinks" onclick="switchtab(event, \'Distribution\')" value="Distribution">
  <input type="button" class="tablinks" onclick="switchtab(event, \'Events\')" value="Events">
  <input type="button" class="tablinks" onclick="switchtab(event, \'Team\')" value="Team">
</div>';

  echo '<div id="Project" class="tabcontent defaulttab"><table class="tbl" style="table-layout: fixed;"><th width="150">Field</th><th>Value</th></tr>';

  $project = mysqli_fetch_array($projectinfo,MYSQLI_ASSOC);
  $projectfields = mysqli_fetch_fields($projectinfo);
  
  foreach($projectfields as $projectfield)
  {
    $fieldinfo[$projectfield->name]['name'] = $projectfield->orgname; 
    
    $fieldinfo[$projectfield->name]['table'] = $projectfield->orgtable;  
    
    $fieldinfo[$projectfield->name]['type'] = $projectfield->type;  
    
    if ($projectfield->flags & MYSQLI_PRI_KEY_FLAG) { 
        $fieldinfo[$projectfield->name]['type'] = 'PK';
    }  
  }
 
 
  // PROJECT + ICO
  foreach($project as $field => $value)
  {
    $value = $add==1?'':htmlentities($value);
    $fieldname = $fieldinfo[$field]['name'];
    $table = $fieldinfo[$field]['table']; 
    $type = $fieldinfo[$field]['type']; 
    
    $postname = ($table=='tbl_projects'?'1+ProjID+'.$project['Project ID']:'2+EventID+'.$project['ICO ID']).'+'.$table.'+'.$fieldname; 
    
    $table = $table=='tbl_events'?'ICO':$table; 
    
    $disablefield = 0;
        
    if (strpos($fieldname,'Image'))
    {
      $input = '<div style="vertical-align: middle;display:inline-block;"><img src="data:image/png;base64,'.$value.'"></div>
      <div style="display:inline-block;margin-left: 20px;"><input type="file" name="'.$postname.'"><input type="hidden" name="'.$postname.'"></div>';
    }
    else
    {
      if ($fieldname=='ProjType')
      {
        $input = cmb_projtypes($postname,$value);
        $projtype = $value;
      }
      elseif ($fieldname=='ProjPlatform')
      {      
        $input = chk_platforms($postname,$value,'platform');
      } 
      else
      {
        if($type == 'PK')
        {
          $input = $add?'[auto]':$value;
          if ($fieldname=='EventID'){$eventid = $value;}
        }
        elseif($type == 1)
        {
          $input = '<input type="hidden" value="0" name="'.$postname.'"><input style="width:20px;" type="checkbox" value="1" '.($value==1?' checked ':'').' name="'.$postname.'">';
        }
        elseif($type == 252)
        {
          $input = '<textarea name="'.$postname.'">'.$value.'</textarea>';  
        }
        elseif($type == 10 || $type == 12)
        {
          $input = '<input name="'.$postname.'" id="datetimepicker_'.$fieldname.'" type="text" value="'.($value=='' || $value=='0000-00-00 00:00:00'?'':date("Y-m-d H:i",strtotime($value))).'"><script>$(\'#datetimepicker_'.$fieldname.'\').datetimepicker({format:\'Y-m-d H:i\'});</script>'; 
        }
        else
        { 
          $input = '<input type="text" value="'.$value.'" name="'.$postname.'">'.($fieldname=='EventName' && $add?'<input type="hidden" name="2+EventID+ins+tbl_events+EventType" value="1">':'');
        }
      }
    }
    
    echo '<tr><td>'.$field.'</td><td>'.$input.'</td></tr>';
  }

   echo '</table></div>';
  
  
  // LINKS
  echo '<div id="Links" class="tabcontent"><table class="tbl" style="table-layout: fixed;"><th width="100">Link Type</th><th>Link <i style="float: right">(enter blank link to delete)</i></th></tr>';
  
  
  $links = mysqli_query($db,"
  Select LinkTypeID,LinkTypeName,LinkTypeImage,LinkID,Link 
  from tbl_linktypes LT
  left join tbl_links L
  on LT.LinkTypeID = L.LinkType and L.LinkParentType = 1 and LinkParentID = '$projid' 
  Order By LinkTypeSortOrder");
  
  while ($link = mysqli_fetch_array($links))
  {
    echo '<tr><td><div style="vertical-align: middle;display:inline-block;width: 25px;"><img src="data:image/png;base64,'.$link['LinkTypeImage'].'"></div>'.$link['LinkTypeName'].'</td><td><input type="text" value="'.$link['Link'].'" name="2+LinkID+'.($link['LinkID']?$link['LinkID']:'ins'.$link['LinkTypeID']).'+tbl_links+Link" />'.($link['LinkID']?'':'<input type="hidden" value="'.$link['LinkTypeID'].'" name="2+LinkID+ins'.$link['LinkTypeID'].'+tbl_links+LinkType"><input type="hidden" value="1" name="2+LinkID+ins'.$link['LinkTypeID'].'+tbl_links+LinkParentType">'.($add==1?'':'<input type="hidden" value="'.$projid.'" name="2+LinkID+ins'.$link['LinkTypeID'].'+tbl_links+LinkParentID">')).'</td></tr>';
  }
  
  echo '</table></div>';
  
  
  // RATES
  echo '<div id="Rates" class="tabcontent"><table class="tbl"><th width="270">Rate Name <i style="float: right">(enter blank name to delete)</i></th><th width="120">Start Date</th><th width="120">End Date</th><th>Date Note <i style="float: right">(will show instead of start date)</i></th><th width="100">Rate</th></tr>';
  
  if (!$add)
  {
    $rates = mysqli_query($db,"
    Select CrowdBonusID,CrowdBonusName,CrowdBonusStartDate,CrowdBonusEndDate,CrowdFundBonusDateNote,CrowdFundBonusRate
    from  tbl_crowdfundbonus CB
    Where CrowdBonusEventID = '$eventid' 
    Order By CrowdBonusStartDate");   
  
    $row = 1;                   
    while ($rate = mysqli_fetch_array($rates))
    {
      echo '<tr>
      <td><input type="text" value="'.htmlentities($rate['CrowdBonusName']).'" name="3+CrowdBonusID+'.$rate['CrowdBonusID'].'+tbl_crowdfundbonus+CrowdBonusName"/></td>
      <td><input name="3+CrowdBonusID+'.$rate['CrowdBonusID'].'+tbl_crowdfundbonus+CrowdBonusStartDate" id="datetimepicker_CrowdBonusStartDate'.$row.'" type="text" value="'.($rate['CrowdBonusStartDate']=='' || $rate['CrowdBonusStartDate']=='0000-00-00 00:00:00'?'':date("Y-m-d H:i",strtotime($rate['CrowdBonusStartDate']))).'"><script>$(\'#datetimepicker_CrowdBonusStartDate'.$row.'\').datetimepicker({format:\'Y-m-d H:i\'});</script></td>
      <td><input name="3+CrowdBonusID+'.$rate['CrowdBonusID'].'+tbl_crowdfundbonus+CrowdBonusEndDate" id="datetimepicker_CrowdBonusEndDate'.$row.'" type="text" value="'.($rate['CrowdBonusEndDate']=='' || $rate['CrowdBonusEndDate']=='0000-00-00 00:00:00'?'':date("Y-m-d H:i",strtotime($rate['CrowdBonusEndDate']))).'"><script>$(\'#datetimepicker_CrowdBonusEndDate'.$row.'\').datetimepicker({format:\'Y-m-d H:i\'});</script></td>
      <td><input type="text" value="'.htmlentities($rate['CrowdFundBonusDateNote']).'" name="2+CrowdBonusID+'.$rate['CrowdBonusID'].'+tbl_crowdfundbonus+CrowdFundBonusDateNote"/></td>
      <td><input type="text" value="'.$rate['CrowdFundBonusRate'].'" name="3+CrowdBonusID+'.$rate['CrowdBonusID'].'+tbl_crowdfundbonus+CrowdFundBonusRate"/></td>
      </tr>';
      $row++;
    }
  } 
  

  for($newrow = $row+1; $newrow < $row+($add?16:6); ++$newrow) 
  {
    echo '<tr>
      <td><input type="text" name="3+CrowdBonusID+ins'.$newrow.'+tbl_crowdfundbonus+CrowdBonusName"/></td>
      <td><input name="3+CrowdBonusID+ins'.$newrow.'+tbl_crowdfundbonus+CrowdBonusStartDate" id="datetimepicker_CrowdBonusStartDate'.$newrow.'" type="text"><script>$(\'#datetimepicker_CrowdBonusStartDate'.$newrow.'\').datetimepicker({format:\'Y-m-d H:i\'});</script></td>
      <td><input name="3+CrowdBonusID+ins'.$newrow.'+tbl_crowdfundbonus+CrowdBonusEndDate" id="datetimepicker_CrowdBonusEndDate'.$newrow.'" type="text"><script>$(\'#datetimepicker_CrowdBonusEndDate'.$newrow.'\').datetimepicker({format:\'Y-m-d H:i\'});</script></td>
      <td><input type="text" name="3+CrowdBonusID+ins'.$newrow.'+tbl_crowdfundbonus+CrowdFundBonusDateNote"/></td>
      <td><input type="text" name="3+CrowdBonusID+ins'.$newrow.'+tbl_crowdfundbonus+CrowdFundBonusRate"/>
      '.($add==1?'':'<input type="hidden" value="'.$eventid.'" name="3+CrowdBonusID+ins'.$newrow.'+tbl_crowdfundbonus+CrowdBonusEventID">').'</td>
      </tr>';  
  }
  
  echo '</table></div>';
  
  
   // DISTRIBUTION
  echo '<div id="Distribution" class="tabcontent"><table class="tbl"><th>Distribution Description <i style="float: right">(enter blank description to delete)</i></th><th width="120">Amount</th><th width="60">Percent</th><th>Note</th><th width="80">Sort Order</th></tr>';
  
  if (!$add)
  {
    $distros = mysqli_query($db,"
    Select DistroID,DistroDesc,	DistroAmount,DistroPercent,DistroNote,DistroSortOrder
    from tbl_projdistribution 
    Where DistroProjID = '$projid' 
    Order By DistroSortOrder");
  
  
    $row = 1;
    while ($distro = mysqli_fetch_array($distros))
    {
      echo '<tr>
      <td><input type="text" value="'.htmlentities($distro['DistroDesc']).'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroDesc"></td>
      <td><input type="text" value="'.$distro['DistroAmount'].'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroAmount"></td>
      <td><input type="text" value="'.$distro['DistroPercent'].'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroPercent"></td>
      <td><input type="text" value="'.htmlentities($distro['DistroNote']).'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroNote"></td>
      <td><input type="text" value="'.$distro['DistroSortOrder'].'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroSortOrder"></td>
      </tr>';
      $row++;
    }
  } 
  

  for($newrow = $row+1; $newrow < $row+($add?16:6); ++$newrow) 
  {
    echo '<tr>
    <td><input type="text" value="'.$distro['DistroDesc'].'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroDesc"></td>
    <td><input type="text" value="'.$distro['DistroAmount'].'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroAmount"></td>
    <td><input type="text" value="'.$distro['DistroPercent'].'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroPercent"></td>
    <td><input type="text" value="'.$distro['DistroNote'].'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroNote"></td>
    <td><input type="text" value="'.$distro['DistroSortOrder'].'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroSortOrder">
    '.($add==1?'':'<input type="hidden" value="'.$projid.'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroProjID">').'</td>
    </tr>';  
  }
  
  echo '</table></div>';
  
  
  // EVENTS
  echo '<div id="Events" class="tabcontent"><table class="tbl"><th width="100">Event Type</th><th>Event Name <i style="float: right">(enter blank name to delete)</i></th><th width="120">Start Date</th><th width="60" ><center>Quarter</center></th></tr>';
  
  if (!$add)
  {
    $events = mysqli_query($db,"
    Select EventID,EventType,EventName,EventStartDateType,EventStartDate
    from tbl_events LT
    Where EventType <> 1 and EventProjID = '$projid' 
    Order By EventStartDate");
  
  
    $row = 1;
    while ($event = mysqli_fetch_array($events))
    {
      echo '<tr>
      <td>'.cmb_eventtypes('2+EventID+'.$event['EventID'].'+tbl_events+EventType',$event['EventType']).'</td><td><input type="text" value="'.$event['EventName'].'" name="2+EventID+'.$event['EventID'].'+tbl_events+EventName"/></td>
      <td><input name="2+EventID+'.$event['EventID'].'+tbl_events+EventStartDate" id="datetimepicker_EventStartDateins'.$row.'" type="text" value="'.($event['EventStartDate']=='' || $event['EventStartDate']=='0000-00-00 00:00:00'?'':date("Y-m-d H:i",strtotime($event['EventStartDate']))).'"><script>$(\'#datetimepicker_EventStartDateins'.$row.'\').datetimepicker({format:\'Y-m-d H:i\'});</script></td>
      <td><input type="hidden" value="1" name="2+EventID+'.$event['EventID'].'+tbl_events+EventStartDateType">
          <input type="checkbox" value="2" '.($event['EventStartDateType']==2?'checked':'').' name="2+EventID+'.$event['EventID'].'+tbl_events+EventStartDateType"></td>
      </tr>';
      $row++;
    }
  }
  

  for($newrow = $row+1; $newrow < $row+($add?16:6); ++$newrow) 
  {
    echo '<tr>
      <td>'.cmb_eventtypes('2+EventID+ins'.$newrow.'+tbl_events+EventType',4).'</td><td><input type="text" name="2+EventID+ins'.$newrow.'+tbl_events+EventName"/></td>
      <td><input name="2+EventID+ins'.$newrow.'+tbl_events+EventStartDate" id="datetimepicker_EventStartDate'.$newrow.'" type="text"><script>$(\'#datetimepicker_EventStartDate'.$newrow.'\').datetimepicker({format:\'Y-m-d H:i\'});</script></td>
      <td><input type="hidden" value="1" name="2+EventID+ins'.$newrow.'+tbl_events+EventStartDateType">
          <input type="checkbox" value="2" name="2+EventID+ins'.$newrow.'+tbl_events+EventStartDateType">
      '.($add==1?'':'<input type="hidden" value="'.$projid.'" name="2+EventID+ins'.$newrow.'+tbl_events+EventProjID">').'</td>
      </tr>';  
  }
  
  echo '</table></div>';
  
  /*
  // TEAM
  echo '<div id="Team" class="tabcontent"><table class="tbl"><th>Name <i style="float: right">(enter blank name to delete)</i></th><th>Position</th><th width="80">Sort Order</th></tr>';
  
  if (!$add)
  {
    $peoples = mysqli_query($db,"
    Select PeopleProjID,PeopleID,PeopleName,PeopleProjPosition,PeopleProjSortOrder
    from tbl_people P
    inner join tbl_people_projects PP
    P.PeopleID = PP.PeopleProjPeopleID 
    Where PeopleProjProjID = '$projid' 
    Order By PeopleProjSortOrder,PeopleName");
  
  
    $row = 1;
    while ($people = mysqli_fetch_array($peoples))
    {
      echo '<tr>
      <td><input type="text" value="'.$people['PeopleName'].'" name="2+PeopleID+'.$distro['PeopleID'].'+tbl_people+PeopleName"></td>
      <td><input type="text" value="'.$people['PeopleProjPosition'].'" name="3+PeopleProjID+'.$distro['PeopleProjPosition'].'+tbl_people_projects+PeopleProjPosition"></td>
      <td><input type="text" value="'.$people['PeopleProjSortOrder'].'" name="3+PeopleProjID+'.$distro['PeopleProjSortOrder'].'+tbl_people_projects+PeopleProjSortOrder"></td>
      </tr>';
      $row++;
    }
  } 
  

  for($newrow = $row+1; $newrow < $row+($add?16:6); ++$newrow) 
  {
    echo '<tr>
      <td><input type="text" value="'.$people['PeopleName'].'" name="2+PeopleID+ins'.$newrow.'+tbl_people+PeopleName"></td>
      <td><input type="text" value="'.$people['PeopleProjPosition'].'" name="3+PeopleProjID+ins'.$newrow.'+tbl_people_projects+PeopleProjPosition"></td>
      <td><input type="text" value="'.$people['PeopleProjSortOrder'].'" name="3+PeopleProjID+ins'.$newrow.'+tbl_people_projects+PeopleProjSortOrder"></td>
    '.($add==1?'':'<input type="hidden" value="'.$projid.'" name="3+PeopleProjID+ins'.$newrow.'+tbl_projdistribution+PeopleProjProjID">').'</td>
    </tr>';  
  }
  
  echo '</table></div>';  */
  
   
  echo '</td></tr></table></form>';

?>

<?php } ?>

</body>
</html>
