<?
  
  // View
  $live_icoview = $_GET['live_view']!=""?$_GET['live_view']:(isset($_COOKIE["live_icoview"])?$_COOKIE["live_icoview"]:3);
  setcookie("live_icoview",$live_icoview,time()+31556926 ,'/'); 
  $_COOKIE["live_icoview"] = $live_icoview;
  $live_icoview = $_COOKIE["live_icoview"];
  
  // Filter
  if ($_GET['fid']) 
  { 
    //
    $filter = mysqli_query($db,"Select Filter from tbl_filters where FilterID = ".$_GET['fid']);
    if (mysqli_num_rows($filter)==0)
    {
      header('Location: https://www.coinschedule.com');
      exit;
    }    
    else
    {
      $filter = mysqli_fetch_array($filter)['Filter'];      
    }
  
  
    $filters = explode(',', $filter);
  
    $platfilter = "";
    $newblockchain = 0;
  
    foreach ($filters as $value)
    {
      if (strpos($value,'plat_')!==false)
      {
        if ($value=='plat_0')
        {
          $newblockchain = 1;
        }
        else 
        {
          $platfilter.= "ProjPlatform = '".str_replace('plat_','',$value)."' or ";
        } 
      }
    }

  
    if ($platfilter) { $platfilter = ' and ('.($newblockchain?'ProjType = 1 or':'').'(ProjType = 2 and ('.substr($platfilter,0,-3).')))'; }
    else if($newblockchain) {$platfilter = ' and ProjType = 1'; }
    
    $catfilter = "";
  

    foreach ($filters as $value)
    {
      if (strpos($value,'cat_')!==false)
      {
        $catfilter.= 'P.ProjCatID = '.str_replace('cat_','',$value).' or ';
      }
    }

    if ($catfilter) { $catfilter = " and (".substr($catfilter,0,-3).")"; } 
  } 
  
  // Get ICOs from Database
  $icorank_threshold = mysqli_fetch_array(mysqli_query($db,"SELECT SettingValue FROM tbl_settings WHERE SettingID = 1"))['SettingValue'];
  
  $ico_query = $db->query("
    Select * from
    (
      Select 
      ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjDirectLink,ProjSponsored,ProjPlatinum,ProjDisableRibbon,
      EventID,EventName,ProjDesc,EventStartDate,EventEndDate,EventStartDateType,ProjCatName
      From tbl_events E 
      inner join tbl_projects P On E.EventProjID = P.ProjID
      left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
      left join tbl_submissions S ON S.SubProjID = P.ProjID  
      Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > $icorank_threshold and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
      (EventEndDate > UTC_TIMESTAMP or (EventStartDateType = 3 and DATE_ADD(ProjAddedOn, INTERVAL 3 MONTH) > UTC_TIMESTAMP)) 
      $platfilter
      $catfilter
    ) as E
  ");
  
  
  // Build Array
  $icos = $ico_query->fetch_all(MYSQLI_ASSOC);
  
  foreach ($icos as $key => &$ico)                  
  { 
    $enddate = strtotime($ico['EventEndDate']);
        
    // Remove if ICO is over
    if (time()>$enddate && $ico['EventStartDateType']==1)
    {
      unset($icos[$key]);
    } 
    else
    {
      // Live ICO
      $startdate = strtotime($ico['EventStartDate']);
      
      if (time()>=$startdate && $ico['EventStartDateType']==1)
      {
        // Percent Done
        $ico['Percent']=((time() - $startdate)/($enddate-$startdate))*100;
        $ico['Status'] = 1;
      }
      else
      {
        // Percent Done
        $ico['Percent']=0;
        $ico['Status'] = 2;
      }
    }
  }

  
  // Sort Upcoming ICOs
  $upcomingicos = $icos;
  array_multisort(
    array_column($upcomingicos, 'Status'),  SORT_DESC, 
    array_column($icos, 'EventStartDateType'), SORT_ASC, 
    array_column($upcomingicos, 'EventStartDate'), SORT_ASC, 
  $upcomingicos);

  
  // Sort LIVE ICOs
  array_multisort(
    array_column($icos, 'Status'),  SORT_ASC, 
    array_column($icos, 'ProjPackage'), SORT_DESC, 
    array_column($icos, 'Percent'), SORT_DESC, 
  $icos);
  
  
  // Blog Posts
  function get_words($sentence, $count = 70) {
    return implode(' ', array_slice(explode(' ', $sentence), 0, $count)).' ....';
  }                    
      
  $posts=json_decode(file_get_contents("../blogs.json"), true);
  
    
  // Events  
  $events = mysqli_query($db,"
    Select EventID,EventName,EventImage,EventTypeImage,EventStartDate,EventEndDate,EventWebsite,EventLocation 
    From tbl_events E 
    inner join tbl_eventtypes ET
    On E.EventType = ET.EventTypeID
    Where EventDisabled = 0 and EventDeleted = 0 and EventType <> 1 and EventType <> 4 and EventStartDate > UTC_TIMESTAMP
    Order By EventStartDate
  ");
  
  
  // Press Mentions
  $pressmentions = mysqli_query($db,"
    SELECT o.*
    FROM tbl_press o            
    LEFT JOIN tbl_press b           
    ON o.PressName = b.PressName AND o.PressDate < b.PressDate and b.PressImage <> ''
    WHERE b.PressDate is NULL and o.PressImage <> ''
    Order By PressDate DESC, PressID DESC LIMIT 24
  ");
  
  
?>