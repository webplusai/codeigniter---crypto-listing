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
<link href="./public/css/edit.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./public/css/jquery.datetimepicker.css"/ >
<link href="./public/css/tab.css?<?php echo time(); ?>" rel="stylesheet">
<link rel="stylesheet" href="./public/css/style.css">
<title>Coinschedule - Edit Project</title>
  <style>
    .input-error {
      border: 1px solid #F00!important;
    }
    .a-btn {
      padding: 2px 10px;
      margin-left: 5px;
    }
    .team-img { margin-left: 2px; margin-top: 5px; float: left }
    .logo-link {
      width: 50%;
    }
    /* Absolute Center Spinner */
    .loading {
      display: none;
      position: fixed;
      z-index: 999;
      height: 2em;
      width: 2em;
      overflow: show;
      margin: auto;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
    }

    /* Transparent Overlay */
    .loading:before {
      content: '';
      display: block;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.3);
    }

    /* :not(:required) hides these rules from IE9 and below */
    .loading:not(:required) {
      /* hide "loading..." text */
      font: 0/0 a;
      color: transparent;
      text-shadow: none;
      background-color: transparent;
      border: 0;
    }

    .loading:not(:required):after {
      content: '';
      display: block;
      font-size: 10px;
      width: 1em;
      height: 1em;
      margin-top: -0.5em;
      -webkit-animation: spinner 1500ms infinite linear;
      -moz-animation: spinner 1500ms infinite linear;
      -ms-animation: spinner 1500ms infinite linear;
      -o-animation: spinner 1500ms infinite linear;
      animation: spinner 1500ms infinite linear;
      border-radius: 0.5em;
      -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
      box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
    }

    /* Animation */
    @-webkit-keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @-moz-keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @-o-keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    .input-error {
      border: 1px solid #F00 !important;
    }
  </style>
</head>
<body>
<div class="loading" id="loadItem">Loading&#8230;</div>
<?php
require_once('menu.php');
require "codebase/bd.php";
require_once "config.php";
require_once "lib/cake.php";
require "codebase/functions.php";
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


// start get people group
$statement = $connection->execute('SELECT * FROM tbl_peoplegroups ORDER BY PeopleGroupSort ASC');
$peopleGroup = $statement->fetchAll('assoc');

function renderPeopleGroup($peopleGroup, $name, $value = '') {
  $peopleGroupHtml = '';
  if (!empty($peopleGroup)) {
    $peopleGroupHtml .= '<select name="'.$name.'" class="">';
    foreach ($peopleGroup as $group) {
      if ($group['PeopleGroupID'] == $value) {
        $peopleGroupHtml .= '<option selected value="'.$group['PeopleGroupID'].'">'.$group['PeopleGroupName'].'</option>';
      } else {
        $peopleGroupHtml .= '<option value="'.$group['PeopleGroupID'].'">'.$group['PeopleGroupName'].'</option>';
      }
    }
    $peopleGroupHtml .= '</select>';
  }
  return $peopleGroupHtml;
}
// end get people group


$id=$_REQUEST['id'];
$projid=mysqli_real_escape_string($db,$id);

if ($projid==''){$add = 1;}

echo '<div style="padding: 15px;"><h1 style="display:inline;">'.($add?'Add':'Edit').' Project</h1>';

if (!$add)
{
echo '<button onclick="if (confirm(\'Are you sure you want to delete this Project?\')){location.href=\'deleteproject.php?id='.$projid.'\';}" style="vertical-align: top;width: 100px;height: 30px;display:inline;margin: 4px 0px 0px 40px;float: right;">Delete</button>';
}
else        
{
  if ($_GET['subid'])
  {
    $subid = $_GET['subid'];
    $submission = mysqli_query($db,"Select SubEventName,SubInfo,SubLink from tbl_submissions where SubID = $subid");
    if (mysqli_num_rows($submission) > 0)
    {
      $sub = mysqli_fetch_array($submission,MYSQLI_ASSOC);
    }     
  } 
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
ProjHeaderImage as 'Project Header Image',
ProjCatID as 'Category',
ProjDirectLink as 'DirectLink',
ProjPackage as 'SponPackage',
ProjSponsored as 'Sponsored',
ProjHighlighted as 'Highlighted',
ProjPlatinum as 'Platinum',
ProjDisableRibbon as 'Disable Ribbon',
ProjTopOfUpcoming as 'Top of Upcoming',
ProjICORank as 'ICORank',
ProjEditorialGrade as 'Editorial Grade',
ProjLocation as 'Location',
ProjTotalSupp as 'Total Supply',
ProjTotalSuppNote as 'Total Supply Note',
ProjPreMined as 'Pre-mined',
ProjPreMinedNote as 'Pre-mined Note',
ProjAlgo as 'Algorithm',
ProjAffilLinks as 'Links are Affilate',
EventID as 'ICO ID',
EventName as 'ICO Name',
EventStartDate as 'ICO Start',
EventEndDate as 'ICO End',
EventStartDateType as 'Start Date Type',
EventDesc as 'ICO Description'
from tbl_projects P
inner join tbl_events E
on P.ProjID = E.EventProjID
where EventType = 1".($projid!=''?" and ProjID = $projid":'')." and (EventEndDate > Now() or EventEndDate = '0000-00-00 00:00:00' or EventStartDateType = 3) LIMIT 1");


if (mysqli_num_rows($projectinfo) > 0)
{

  echo '<form enctype="multipart/form-data" action="saveproject.php'.($add?'?add=1':'').'" method="post" style="display:inline;">
  <input type="submit" style="vertical-align: top;width: 100px;height: 30px;display:inline;margin: 4px 0px 10px 40px;" value="Save" />
  <input class="calcRank" type="button" style="vertical-align: top;width: 100px;height: 30px;display:inline;margin: 4px 0px 10px 40px;" value="Calc ICORank">
  <table style="padding: 0px;margin: 0px;border-collapse: collapse;width: 100%;table-layout: fixed;"><tr><td valign="top">';

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
    
    if ($subid!='')
    {
      if ($fieldname=='ProjName' || $fieldname=='EventName'){$value = $sub['SubEventName'];}
      if ($fieldname=='ProjDesc'){$value = $sub['SubInfo'];}
    }
  
    
    $postname = ($table=='tbl_projects'?'1+ProjID+'.$project['Project ID']:'2+EventID+'.$project['ICO ID']).'+'.$table.'+'.$fieldname; 
    
    $table = $table=='tbl_events'?'ICO':$table; 
    
    $disablefield = 0;

    if ($fieldname == 'ProjID') {
      echo '<input type="hidden" id="scrapeProjID" value="'.$value.'" />';
    }

    if (strpos($fieldname,'Header')) {
      $input = '';
      if ($value != '') {
        $input .= '<div style="vertical-align: middle;display:inline-block;margin-right: 20px;">
                  <img style="height: 100px" id="img'.$fieldname.'" src="https://www.coinschedule.com/public/uploads/project_headers/'.$value.'">
                  </div>';
      }
      $input .= '<div style="display:inline-block;">
                  <input type="file" name="'.$postname.'">
                  <input type="hidden" name="'.$postname.'">
                </div>';

    }
    else if (strpos($fieldname,'Image'))
    {
      $input = '<div style="vertical-align: middle;display:inline-block;"><img id="img'.$fieldname.'" src="data:image/png;base64,'.$value.'"></div>
      <div style="display:inline-block;margin-left: 20px;"><input type="file" name="'.$postname.'"><input type="hidden" name="'.$postname.'"></div>';
      $input .= '<div><input class="logo-link" type="text" id="'.$fieldname.'" name="'.$fieldname.'"><a class="a-btn btn btn-primary scrape-logo" id="'.$fieldname.'">Scrape</a></div>';
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
        $value = str_replace("'", "", $value);
        $input = chk_platforms($postname,$value,'platform');
      } 
      elseif ($fieldname=='ProjCatID')
      {      
        $input = cmb_projcats($postname,$value);
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
          $input = '<input id="id_'.$fieldname.'" type="text" value="'.$value.'" name="'.$postname.'">'.($fieldname=='EventName' && $add?'<input type="hidden" name="2+EventID+1+tbl_events+EventType" value="1">':'');
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

  $lastLinkID = 0;
  while ($link = mysqli_fetch_array($links))
  {
    $lastLinkID ++;
    $mandatory = '';
    $style = '';
    $scrape_link = '';
    $id = 'id="Links'.$link['LinkTypeID'].'"';
    if ($link['LinkTypeID'] == 1) {
      $mandatory = '<span style="color:#F00">*</span>';
      $style = 'style="width: 89%; margin-right: 20px;"';
      $scrape_link = '<a style="padding: 2px 10px;margin-top: -4px;text-decoration: none;" href="javascript:void();" class="btn btn-primary" id="scrape_link">Scrape Links</a>';
    }
    echo '<tr><td><div style="vertical-align: middle;display:inline-block;width: 25px;"><img src="data:image/png;base64,'.$link['LinkTypeImage'].'"></div>'.$link['LinkTypeName'].$mandatory.'</td><td><input '.$id.' '.$style.' type="text" value="'.($subid!=''&&$link['LinkTypeID']==1?$sub['SubLink']:$link['Link']).'" name="2+LinkID+'.($link['LinkID']?$link['LinkID']:'ins'.$link['LinkTypeID']).'+tbl_links+Link" />'.($link['LinkID']?'':'<input type="hidden" value="'.$link['LinkTypeID'].'" name="2+LinkID+ins'.$link['LinkTypeID'].'+tbl_links+LinkType"><input type="hidden" value="1" name="2+LinkID+ins'.$link['LinkTypeID'].'+tbl_links+LinkParentType">'.($add==1?'':'<input type="hidden" value="'.$projid.'" name="2+LinkID+ins'.$link['LinkTypeID'].'+tbl_links+LinkParentID">')).$scrape_link.'</td></tr>';
  }
  
  echo '</table></div>';
  echo '<input type="hidden" id="scrape-lastid" value="'.$lastLinkID.'">';
  
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
  echo '<div id="Distribution" class="tabcontent">
        <input class="calcDistAmount" type="button" style="width: 100px;margin-top: 10px;" value="Calc Amounts">
        <table class="tbl"><th>Distribution Description <i style="float: right">(enter blank description to delete)</i></th><th width="120">Amount</th><th width="60">Percent</th><th>Note</th><th width="80">Sort Order</th></tr>';
  
  $sortorder = 0;
  
  if (!$add)
  {
    $distros = mysqli_query($db,"
    Select DistroID,DistroDesc,	DistroAmount,DistroPercent,DistroNote,DistroSortOrder
    from tbl_projdistribution 
    Where DistroProjID = '$projid' 
    Order By DistroSortOrder");
  
    $countTotalRow = 0;
    $row = 1;
    while ($distro = mysqli_fetch_array($distros))
    {
      $countTotalRow ++;
      echo '<tr>
      <td><input type="text" value="'.htmlentities($distro['DistroDesc']).'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroDesc"></td>
      <td><input class="calDistroAmount'.$countTotalRow.'" type="text" value="'.$distro['DistroAmount'].'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroAmount"></td>
      <td><input class="calDistroPercent'.$countTotalRow.'" type="text" value="'.$distro['DistroPercent'].'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroPercent"></td>
      <td><input type="text" value="'.htmlentities($distro['DistroNote']).'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroNote"></td>
      <td><input type="text" value="'.$distro['DistroSortOrder'].'" name="2+DistroID+'.$distro['DistroID'].'+tbl_projdistribution+DistroSortOrder"></td>
      </tr>';
      $row++;
      $sortorder = $people['DistroSortOrder'];
    }
  } 
  

  for($newrow = $row+1; $newrow < $row+($add?16:6); ++$newrow) 
  {
    $countTotalRow ++;
    $sortorder++;
    echo '<tr>
    <td><input type="text" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroDesc"></td>
    <td><input class="calDistroAmount'.$countTotalRow.'" type="text" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroAmount"></td>
    <td><input class="calDistroPercent'.$countTotalRow.'" type="text" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroPercent"></td>
    <td><input type="text" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroNote"></td>
    <td><input type="text" value="'.$sortorder.'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroSortOrder">
    '.($add==1?'':'<input type="hidden" value="'.$projid.'" name="2+DistroID+ins'.$newrow.'+tbl_projdistribution+DistroProjID">').'</td>
    </tr>';  
  }

  echo '<input type="hidden" id="totalCalDistro" value="'.$countTotalRow.'" />';
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
  
  
  // TEAM
  echo '<div id="Team" class="tabcontent">
    <table class="tbl">
    <th width="15%">Name <i style="float: right">(enter blank name to delete)</i></th>
    <th width="20%">Description</th>
    <th width="15%">Position</th>
    <th width="15%">LinkedIn</th>
    <th width="10%">Group</th>
    <th width="20%">Picture</th>
    <th width="5%">Sort Order</th></tr>';
  $sortorder = 0;
  
  if (!$add)
  {
    $peoples = mysqli_query($db,"
    Select PeopleProjID,PeopleID,PeopleName,PeopleProjPosition,PeopleProjSortOrder,PeoplePicture,PeopleProjGroupID,L.Link,LinkID,PeopleDesc
    from tbl_people P
    inner join tbl_people_projects PP On P.PeopleID = PP.PeopleProjPeopleID
    LEFT JOIN tbl_peoplegroups PG ON PP.PeopleProjGroupID = PG.PeopleGroupID
    LEFT JOIN tbl_links L ON P.PeopleID = L.LinkParentID AND L.LinkType = 10 AND L.LinkParentType = 4
    Where PeopleProjProjID = '$projid' 
    Order By PeopleProjSortOrder,PeopleName");
  
  
    $row = 1;
    while ($people = mysqli_fetch_array($peoples))
    {
      $src = 'https://www.coinschedule.com/public/uploads/team/' . $people['PeoplePicture'];;
      $postname = '2+PeopleID+'.$people['PeopleID'].'+tbl_people+PeoplePicture';

      $input = '';
      if ($people['PeoplePicture'] != '') {
        $input .= '<div id="teamImg'.$people['PeopleID'].'" style="vertical-align: middle;display:inline-block;margin-right: 20px;">
                    <a target="_blank" href="'.$src.'">Open</a>
                </div>';
      }
      $input .= '
                <div style="display:inline-block; position: relative">
                    <input type="file" name="'.$postname.'" value="'.$people['PeoplePicture'].'">
                    <input type="hidden" name="'.$postname.'" value="'.$people['PeoplePicture'].'">
                    <a style="position: absolute; top: -7px; left: 190px;" class="a-btn btn btn-primary team-img" data-id="'.$people['PeopleID'].'">Remove</a>
                </div>';

      $groupName = '3+PeopleProjID+'.$people['PeopleProjID'].'+tbl_people_projects+PeopleProjGroupID';
      $renderGroup = renderPeopleGroup($peopleGroup, $groupName, $people['PeopleProjGroupID']);

      if ($people['LinkID']) {
        $nameLink = '4+LinkID+'.$people['LinkID'].'+tbl_links+Link';
      } else {
        $nameLink = '4+LinkID+upd'.$people['PeopleID'].'+tbl_links+Link';
      }

      echo '<tr>
      <td><input type="text" value="'.$people['PeopleName'].'" name="2+PeopleID+'.$people['PeopleID'].'+tbl_people+PeopleName"></td>
      <td><textarea name="2+PeopleID+'.$people['PeopleID'].'+tbl_people+PeopleDesc">'.$people['PeopleDesc'].'</textarea></td>
      <td><input type="text" value="'.$people['PeopleProjPosition'].'" name="3+PeopleProjID+'.$people['PeopleProjID'].'+tbl_people_projects+PeopleProjPosition"></td>
      <td><input type="text" value="'.$people['Link'].'" name="'.$nameLink.'" /></td>
      <td>'.$renderGroup.'</td>
      <td>'.$input.'</td>
      <td><input type="text" value="'.$people['PeopleProjSortOrder'].'" name="3+PeopleProjID+'.$people['PeopleProjID'].'+tbl_people_projects+PeopleProjSortOrder"></td>
      </tr>';
      $row++;
      $sortorder = $people['PeopleProjSortOrder'];
    }
  } 
  

  for($newrow = $row+1; $newrow < $row+($add?16:21); ++$newrow) 
  { 
    $sortorder++;
    $postname = '3'.$newrow.'+PeopleID+ins'.$newrow.'+tbl_people+PeoplePicture';
    $imgInput = '<td><input type="file" name="'.$postname.'"><input type="hidden" name="'.$postname.'"></td>';

    $groupName = '3'.$newrow.'a+PeopleProjID+ins'.$newrow.'+tbl_people_projects+PeopleProjGroupID';
    $renderGroup = renderPeopleGroup($peopleGroup, $groupName);

    echo '<tr>
      <td><input type="text" name="3'.$newrow.'+PeopleID+ins'.$newrow.'+tbl_people+PeopleName"></td>
      <td><textarea name="3'.$newrow.'+PeopleID+ins'.$newrow.'+tbl_people+PeopleDesc"></textarea></td>
      <td><input type="text" name="3'.$newrow.'a+PeopleProjID+ins'.$newrow.'+tbl_people_projects+PeopleProjPosition"></td>
      <td><input type="text" name="3'.$newrow.'a+LinkID+ins'.$newrow.'+tbl_links+Link"></td>
      <td>'.$renderGroup.'</td>
      '.$imgInput.'
      <td><input type="text" value="'.$sortorder.'" name="3'.$newrow.'a+PeopleProjID+ins'.$newrow.'+tbl_people_projects+PeopleProjSortOrder"></td>
    '.($add==1?'':'<input type="hidden" value="'.$projid.'" name="3'.$newrow.'a+PeopleProjID+ins'.$newrow.'+tbl_people_projects+PeopleProjProjID">').'</td>
    </tr>';  
  }
  
  echo '</table></div>';

  if (!empty($projid)) {
    echo '<input type="hidden" name="editprojectid" value="'.$projid.'">';
  }

  echo '</td></tr></table></form>';

?>

<?php } ?>
</div>
<script>
  $( "#scrape_link" ).click(function() {
    var lastID = $("#scrape-lastid").val();
    var website = $("#Links1").val();
    $("#Links1").removeClass('input-error');
    if (!website) {
      $("#Links1").addClass('input-error');
      return false;
    }

    var formData = {
      action: 'scrape_website',
      website: website
    }
    $("#loadItem").show();
    $.ajax({
      url: 'https://www.coinschedule.com/ajax/scrapeWebsite',
      data: formData,
      dataType: 'json',
      type: 'POST',
      success: function(jsonData) {
        var data = jsonData.data;
        for (var i = 0; i <= lastID; i ++) {
          if (data[i]) {
            var selector = i + 1;
            $("#Links"+selector).val(data[i]);
          }
        }
        $("#loadItem").hide();
      }
    });
  });

  $( ".scrape-logo" ).click(function() {
    var eleID = $(this).attr('id');
    var website = $("#"+eleID).val();
    var formData = {
      action: 'scrape_logo',
      website: website,
      eleID: eleID,
      projectID: $("#scrapeProjID").val()
    }

    $("#loadItem").show();
    $.ajax({
      url: 'https://www.coinschedule.com/ajax/scrapeLogo',
      data: formData,
      dataType: 'json',
      type: 'POST',
      success: function(jsonData) {
        if (jsonData.status == 1) {
          $('#img'+eleID).attr("src", 'data:image/png;base64,'+jsonData.data);
        } else {
          alert(jsonData.message);
        }

        $("#loadItem").hide();
      }
    });

  });

  $( ".calcRank" ).click(function() {
    var projID = $('#scrapeProjID').val();
    var formData = {
      projID: projID
    }

    $("#loadItem").show();
    $.ajax({
      url: 'https://www.coinschedule.com/ajax/calcIcoRank',
      data: formData,
      dataType: 'json',
      type: 'POST',
      success: function(jsonData) {
        if (jsonData.status == 1) {
          $('#id_ProjICORank').val(jsonData.data);
        } else {
          alert(jsonData.message);
        }
        $("#loadItem").hide();
      }
    });

  });

  $( ".team-img" ).click(function() {
    var people_id = $(this).attr('data-id');
    var formData = {
      people_id: people_id,
      action: 'delete_people_picture'
    }

    $("#loadItem").show();
    $.ajax({
      url: '/change/ajax.php',
      data: formData,
      dataType: 'json',
      type: 'POST',
      success: function(jsonData) {
        if (jsonData.code == 1) {
          $("#teamImg"+people_id).remove();
        } else {

        }
        $("#loadItem").hide();
      }
    });

  });

  $( ".calcDistAmount" ).click(function() {
    var totalCalDistro = parseInt($("#totalCalDistro").val());
    var projTotalSupp = parseInt($("#id_ProjTotalSupp").val());
    var percent = 0;
    var amount = 0;
    for (var row = 1; row <= totalCalDistro; row ++) {
      percent = parseInt($(".calDistroPercent"+row).val());
      if (percent > 0) {
        amount = projTotalSupp * (percent / 100);
        $(".calDistroAmount"+row).val(amount);
      }
    }

  });

</script>

<?php require_once('footer.php'); ?>
</body>
</html>
