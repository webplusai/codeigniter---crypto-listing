<?
//********************************************************************
// ICORANK PROTOTYPE
//
// This is ICORank algoriothm v0.1
// July 2017 - Alex Buelau
//
// This is a prototype intended to show how ICOrank works and to help
// calibrate the initial algorithm
//
//  Current algorithm (0 - 100 points):
//
// Having a website = 7 points
// Having a white paper in PDF format = 10 points
// Social media: Twitter = 2 points / Reddit = 2 points / Slack = 5 points
// Bitcointalk ANN thread = 5 points
// MAXIMUM SO FAR = 31 points
//
// Team members: 1 team member = 5 points / 2 or more = 9 points
// Linkedin for team members: 1 team member with linkedin = 3 points / 2 or more = 5 points
// ID uploaded: 1 team member = 12 points / 2 or more = 18 points
// MAXIMUM SO FAR = 63 points
//
// Editorial Grade = 0 to 20 points (quality of website, of whitepaper, of team)
// MAXIMUM SO FAR = 83 points
// 
// Paid slots: Silver = 5 points / Gold = 11 points / Platinum = 17 points
// (Silver / Gold / Platinum requires at leats 1 project member with ID uploaded)
// MAXIMUM SO FAR = 100 points
//
//
//********************************************************************
 
require_once("/home/coinschedule/public_html/lib/bd.php");
echo "<table><tr><td>ID</td><td>Project</td><td>ICOrank</td></tr>";

//get a list of all projects that are active and either upcoming or on-going	
$sql="Select *
	From tbl_events E 
    inner join tbl_projects P
    On E.EventProjID = P.ProjID
    Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and (EventStartDate > UTC_TIMESTAMP or EventStartDateType = 3) 
	UNION
	Select * from
    	(
        Select *
        From tbl_events E 
        inner join tbl_projects P
        On E.EventProjID = P.ProjID
        Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and EventStartDateType <> 3 and EventStartDate <= UTC_TIMESTAMP and EventEndDate > UTC_TIMESTAMP
        ) as E";						
$projects = mysqli_query($db,$sql);
  
while ($project = mysqli_fetch_array($projects))
{
  $ICOrank=0; //initialise variable
  
  //get all links of this project
  $links_st = mysqli_query($db,"Select * from tbl_links L WHERE L.LinkParentType=1 AND L.LinkParentID=".$project['ProjID']);
	while ($links = mysqli_fetch_array($links_st))
	{
		//if it's a website link give 7 points (type 1)
		if ($links['LinkType']==1)$ICOrank=$ICOrank+7;
		
		//if it's a PDF white paper give 10 points (type 14)
		if ($links['LinkType']==14)$ICOrank=$ICOrank+10;		
		
		//if it's Twitter give 2 points (type 5)
		if ($links['LinkType']==5)$ICOrank=$ICOrank+2;

		//if it's reddit give 2 points (type 6)
		if ($links['LinkType']==6)$ICOrank=$ICOrank+2;
		
		//if it's slack give 5 points (type 9)
		if ($links['LinkType']==9)$ICOrank=$ICOrank+6;
		
		//if it's bitcointalk give 5 points (type 4)
		if ($links['LinkType']==4)$ICOrank=$ICOrank+5;
	}
	

  //get all team members of this project
  $team_st = mysqli_query($db,"Select * from tbl_people_projects P WHERE P.PeopleProjProjID=".$project['ProjID']);
	if ($team = mysqli_fetch_array($team_st))
	{
		//if it's 1 team member give 4 points if 2 or more give 10 points
		if(mysqli_num_rows($team_st)==1)$ICOrank=$ICOrank+5;
		if(mysqli_num_rows($team_st)>=1)$ICOrank=$ICOrank+9;
				
		//if it's 1 linkedin 2 points if 2 or more give 5 points
		$links2_st = mysqli_query($db,"Select * from tbl_links L WHERE L.LinkParentType=4 AND L.LinkType=10 AND L.LinkParentID=".$project['ProjID']);
		if ($links2 = mysqli_fetch_array($links2_st)){
			if(mysqli_num_rows($links2_st)==1)$ICOrank=$ICOrank+3;
			if(mysqli_num_rows($links2_st)>=1)$ICOrank=$ICOrank+5;
		}
		
		//if it's 1 uploaded ID give 12 points if more give 18 points
		//*** THIS IS NOT IMPLEMENTED YET ***
		
	}
  	

	//get editorial grade of this project and assign to ICOrank
	$ICOrank=$ICOrank+$project['ProjEditorialGrade'];
	
	//get paid level of this project
	if ($project['ProjSponsored']==1){
		//if project is SILVER, give 6 points
		//**** SILVER DOES NOT EXIST IN COINSCHEDULE YET
		
		//if project is GOLD, give 14 points
		//AT the moment, non platinum means is gold
		if ($project['ProjPlatinum']==0)$ICOrank=$ICOrank+11; 
		
		//if project is PLATINUM give 22 points
		if ($project['ProjPlatinum']==1)$ICOrank=$ICOrank+17; 

	}
	
	
  echo "<tr>";
  echo "<td>".$project['ProjID']."</td>";
  echo "<td>".$project['ProjName']."</td>";
  echo "<td>";
  if($ICOrank<10)$color="red";
  if($ICOrank<20)$color="orange";
  if($ICOrank>=20)$color="green";
  echo "<span style=color:$color>".$ICOrank."</span>";
  echo "</td>";
  echo "</tr>";  
  
  //record in DB
  $record_st = mysqli_query($db,"UPDATE tbl_projects SET ProjICORank=$ICOrank WHERE ProjID=".$project['ProjID']);
}

echo "</table>";


//	echo "<tr><td colspan=3>"."Select * from tbl_links L WHERE L.LinkParentID=".$project['ProjID']."</td></tr>";

?>