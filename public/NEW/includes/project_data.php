<?
  //get details of this entry
  $projid = $_GET['id'];
  $eventid = $_GET['eventid'];
  
  $icorank_threshold = mysqli_fetch_array(mysqli_query($db,"SELECT SettingValue FROM tbl_settings WHERE SettingID = 1"))['SettingValue'];
  
  $projdet = mysqli_query($db,"
  Select P.ProjID,P.ProjName,P.ProjSymbol,P.ProjDesc,P.ProjType,P.ProjImageLarge,ProjTypeName,P.ProjPackage,P.ProjSponsored,P.ProjHighlighted,P.ProjPlatinum,P.ProjDisableRibbon,P.ProjLocation,P.ProjAlgo,P.ProjTotalSupp,P.ProjTotalSuppNote,P.ProjPreMined,P.ProjPreMinedNote,P.ProjAffilLinks,ProjCatName,ProjPageHtmlHeadCode, E.EventName,E.EventDeleted 
  from tbl_projects P 
  inner join tbl_projecttypes PT
  on P.ProjType = PT.ProjTypeID
  left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
  left join tbl_submissions S ON S.SubProjID = P.ProjID
  left join tbl_events E ON P.ProjID = E.EventProjID 
  where ProjDeleted = 0 and ProjICORank > $icorank_threshold and ".($eventid?"E.EventID = '$eventid'":"P.ProjID = '$projid'")." and (S.SubStatus = 2 or S.SubStatus IS NULL)");

  if($projdet = mysqli_fetch_array($projdet))
  {
  
    $projid = $projdet['ProjID'];
  	$id=$projid;
    $proj_name=$projdet['ProjName'];
    $event_name=$projdet['EventName'];
    $proj_title=$projdet['ProjName'].($projdet['ProjSymbol']==''?'':' ('.$projdet['ProjSymbol'].')');
  	$proj_type=$projdet['ProjTypeName'];
    $proj_img='data:image/png;base64,'.$projdet['ProjImageLarge']; 
    
    $proj_category = $projdet['ProjCatName'];
  
    
    $proj_plat = $projdet['ProjPlatinum'];
    $proj_spons = $projdet['ProjSponsored'];
    $proj_package = $projdet['ProjPackage'];
    $proj_hidespons = $projdet['ProjDisableRibbon'];
      
    $proj_location = $projdet['ProjLocation'];
    $proj_algo = $projdet['ProjAlgo'];
    
    $proj_totalsuppNote = $projdet['ProjTotalSuppNote']; 
    $proj_totalsupp = $proj_totalsuppNote?$proj_totalsuppNote:number_format($projdet['ProjTotalSupp'],0,'.',',');; 
    
    
    $proj_premined = $projdet['ProjPreMined'];
    $proj_preminednote = $projdet['ProjPreMinedNote']; 
    
    $whitepaper = mysqli_query($db,"Select LinkType,Link,LinkTypeImage from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID where LinkParentType = 1 and LinkParentID = '$projid' and LinkType = 14");
    if(mysqli_num_rows($whitepaper)>0){ $whitepaper= mysqli_fetch_assoc($whitepaper); $paperimg=$whitepaper['LinkTypeImage']; $whitepaper = $whitepaper['Link']; } else { $whitepaper = '';}
    
    $bitcointalk = mysqli_query($db,"Select LinkType,Link,LinkTypeImage from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID where LinkParentType = 1 and LinkParentID = '$projid' and LinkType = 4");
    if(mysqli_num_rows($bitcointalk)>0){ $bitcointalk= mysqli_fetch_assoc($bitcointalk); $bitcointalk=$bitcointalk['Link'];  } else { $bitcointalk = '';}
    
   
  	$proj_desc = $projdet['ProjDesc'];
    
    $page_title = $event_name && !$projdet['EventDeleted']?$event_name:$proj_title;
    
    if (!$proj_hidespons && $proj_spons)
    {
      if ($proj_plat)
      {
        $proj_sponsoredbadge='<img class="sponsbadge inline" src="'.$platbadge.'" title="Platinum Project" alt="Platinum Project" >';
      }
      else if ($proj_package==2)
      {
        $proj_sponsoredbadge='<img class="sponsbadge inline" src="'.$goldbadge_large.'" title="Gold Project" alt="Gold Project">';
      }
    }
    else if ($proj_package==1)
    {
      $proj_sponsoredbadge='<img class="sponsbadge inline" src="'.$silverbadge_large.'" title="Silver Project" alt="Silver Project">';  
    }     
    
    $proj_website = mysqli_query($db,"Select LinkID,LinkType,Link from tbl_links where LinkParentType = 1 and LinkParentID = '$projid' and LinkType = 1");
    if(mysqli_num_rows($proj_website)>0)
    { 
      if ($projdet['ProjAffilLinks'])
      {
        $proj_website= "https://www.coinschedule.com/link.php?l=".mysqli_fetch_assoc($proj_website)['LinkID'];
      }
      else
      {
        $proj_website= mysqli_fetch_assoc($proj_website)['Link'];
      }
    } 
    else
    { 
      $proj_website = '';
    }
    
   
   }
  else
  {
    header( 'Location: https://www.coinschedule.com' ) ;
  	die();
  }
  
  
$crowdfunds = mysqli_query($db,"Select EventID,EventName,EventDesc,EventStartDate,EventEndDate,EventTotalRaised,EventStartDateType,EventRatesNote,P.ProjSymbol as EventTotalRaisedSymbol,P.ProjID as EventTotalRaisedProjID, EventParticipants from tbl_events E left join tbl_projects P on E.EventTotalRaisedProjID = P.ProjID where EventType = 1 and ".($eventid?"EventID='$eventid'":"EventProjID = '$projid'")." and EventDisabled = 0 and EventDeleted = 0 Order By CASE WHEN EventEndDate > Now() THEN 0 ELSE 1 END,EventEndDate LIMIT 1"); 

$numofrows = mysqli_num_rows($crowdfunds);
  
if($numofrows>0)
{   
  $crowdfund = mysqli_fetch_assoc($crowdfunds);
  
  $crowdfundid = $crowdfund['EventID'];  
  
  if ($crowdfund['EventStartDateType']==1)
  {
    $startstamp = strtotime($crowdfund['EventStartDate']);
    $startdate = date_create($crowdfund['EventStartDate']);
    $start = date_format($startdate,"F jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':''); 
    
    $endstamp = strtotime($crowdfund['EventEndDate']);
    $enddate = date_create($crowdfund['EventEndDate']);
    $end = date_format($enddate,"F jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':'');
    
    $timerdate = (time()<$startstamp?$startdate:$enddate); 
    
    $timerdate = date_format($timerdate,'Y').",".(date_format($timerdate,'n')-1).",".date_format($timerdate,'d,H,i,s');
  }
  else
  {
    $start = "TBA";

    $end = "TBA"; 
  }
  
  $crowdfunddesc = nl2br($crowdfund['EventDesc']);  
}  


 $rates = mysqli_query($db,"Select CrowdBonusName,CrowdBonusStartDate,CrowdBonusEndDate,CrowdFundBonusDateNote from tbl_crowdfundbonus Where CrowdBonusEventID = $crowdfundid Order By CrowdBonusStartDate");
 
$distros = mysqli_query($db,"Select DistroDesc,DistroAmount,DistroPercent,DistroNote,ProjSymbol from tbl_projdistribution PD inner join tbl_projects P on PD.DistroProjID = P.ProjID Where DistroProjID = $projid Order By DistroSortOrder");
  

$proj_links = mysqli_query($db,"Select LinkTypeID,LinkTypeName,LinkID,Link,LinkTypeImage,ProjAffilLinks from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID inner join tbl_projects P on L.LinkParentID = P.ProjID where L.LinkParentType = 1 and L.LinkParentID = '$projid' Order By LinkTypeSortOrder");
  
  
           
  $peoples = mysqli_query($db,"
  Select PeopleID,PeopleName,PeopleProjPosition
	from tbl_people P
  Inner join tbl_people_projects PP
  on P.PeopleID = PP.PeopleProjPeopleID
  where PeopleProjProjID = '$projid'
  Order By PeopleProjSortOrder
  "); 
  
  $events = mysqli_query($db,"
  (
  Select EventID,EventProjID,ProjName,ProjImage,EventType,EventTypeName,EventName,EventDesc,EventStartDate as EventDate,EventStartDateType,EventLocation,EventEndDate,EventTypeImage
	from tbl_events E
  Inner join tbl_eventtypes ET
  on E.EventType = ET.EventTypeID
  Left Join tbl_projects P
  on E.EventProjID = P.ProjID
	where EventTypeID <> 1 and EventProjID = '$projid'
  )
  Order By EventID,EventName
  "); 
  


?>