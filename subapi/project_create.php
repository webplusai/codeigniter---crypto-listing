<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once('../rootpath.php');
  require_once(ROOT_PATH. 'change/config.php');
  require_once(ROOT_PATH . 'change/lib/codeigniter.php');
  require_once('../change/codebase/bd.php');
  
  $token = $_SERVER['HTTP_AUTHTOKEN'];
  if ($token!=API_KEY){exit;}

  function return_response($error,$msg)
  {
    $data = [ 'error' => $error, 'message' => $msg ];
    header('Content-type: application/json');
    echo json_encode( $data );
  }
  
  function clean_var($var)
  {
    global $db;
    return mysqli_real_escape_string($db,$var);
  }
  

  $name = clean_var($_POST['name']);
  $category = clean_var($_POST['category']);
  $img = clean_var($_POST['img']);
  $img_large = clean_var($_POST['img_large']);
  $description = clean_var($_POST['description']);
  $type = clean_var($_POST['type']);
  $platform = clean_var($_POST['platform']);
  $symbol = clean_var($_POST['symbol']);
  $referral = clean_var($_POST['referral']);
  $supply = clean_var($_POST['supply']);
  $location = clean_var($_POST['location']);
  $package = clean_var($_POST['package']);
    
  $links = json_decode($_POST['links'],true);
  //if ($error = json_last_error()) { }
  $people = json_decode($_POST['people'],true);
  $crowdfunds = json_decode($_POST['crowdfunds'],true);
  
  
  if ($name && $category && $description)
  {
  
    // SQL for Package so shows on the site correctly (will eventually be reduntant)
    
    // Plus Listing
    if ($package==2)
    {
      $package_fields = ",ProjHighlighted";
      $package_values = ",1";    
    }
    // Silver Listing
    else if ($package==3)
    {
      $package_fields = ",ProjPackage";
      $package_values = ",1";     
    }
    // Gold Listing
    else if ($package==4)
    {
      $package_fields = ",ProjPackage,ProjSponsored";
      $package_values = ",2,1";     
    }
    
    mysqli_query($db,"
      Insert into tbl_projects
      (
        ProjName,
        ProjCatID,
        ProjImage,
        ProjImageLarge,
        ProjDesc,
        ProjType,
        ProjPlatform,
        ProjSymbol,
        ProjHasReferal,
        ProjTotalSupp,
        ProjLocation,
        ProjPackageID,
        ProjICORank
        $package_fields
      )
      Values
      (
        '$name',
        $category,
        '$img',
        '$img_large',
        '$description',
        $type,
        '$platform',
        '$symbol',
        $referral,
        $supply,
        '$location',
        $package,
        20
        $package_values
      )
    ");
  
    $projid = mysqli_insert_id($db);
      
    // Error inserting Project?
    if ($projid==0){ return_response(1,'Project Insert Error'); exit; } 
    

      
    // Crowdfunds
    foreach ($crowdfunds as $crowdfund)
    {
      $crowdfund_type = clean_var($crowdfund['type']);
      $tba = clean_var($crowdfund['tba']);
      $startdate = clean_var($crowdfund['startdate']);
      $enddate = clean_var($crowdfund['enddate']); 
      
      $crowdfund_type = mysqli_query($db,"Select CFTypeName from tbl_crowdfundtypes where CFTypeID = $crowdfund_type");
      
      $crowdfund_type = mysqli_fetch_array($crowdfund_type)['CFTypeName'];
            
      mysqli_query($db,"
      Insert into tbl_events (EventProjID,EventType,EventName,EventStartDate,EventEndDate)  
      Values ($projid,1,'$name $crowdfund_type','$startdate','$enddate')
      ");
    }
        
    
    
    // Links
    foreach ($links as $link)
    {
      $link_type = clean_var($link['type']);
      $link = clean_var($link['link']);  
      
      mysqli_query($db,"Insert into tbl_links (LinkType,LinkParentType,LinkParentID,Link) Values ($link_type,1,$projid,'$link')");
    }
    
    
    // People
    $sort_order = 1;
    foreach ($people as $person)
    {
      $people_name = clean_var($person['name']);
      $position = clean_var($person['position']);  
      $people_links = $person['links'];
      
      mysqli_query($db,"Insert into tbl_people (PeopleName) Values ('$people_name')");
      
      $peopleid = mysqli_insert_id($db);
      
      // Link to Project
      mysqli_query($db,"
        Insert into tbl_people_projects 
        (PeopleProjPeopleID,PeopleProjProjID,PeopleProjPosition,PeopleProjSortOrder) 
        Values ($peopleid,$projid,'$position',$sort_order)
      ");
      
      
      // Links
      foreach ($people_links as $people_link)
      {
        $people_type = clean_var($people_link['type']);
        $people_link = clean_var($people_link['link']);  
        
        mysqli_query($db,"Insert into tbl_links (LinkType,LinkParentType,LinkParentID,Link) Values ($people_type,4,$peopleid,'$people_link')");
      }
      
      $sort_order++;
    }

    calcIcoRank($projid);
    flushAllCache();
    
    $data = [ 'error' => 0, 'message' => "OK", 'projid' => $projid];
    header('Content-type: application/json');
    echo json_encode( $data );
  }


?>