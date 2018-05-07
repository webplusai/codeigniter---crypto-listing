<?

require "/home/coinschedule/public_html/lib/bd.php";

mysqli_query($db,"Delete from filterpanel");
  
mysqli_query($db,"
Insert IGNORE Into tbl_filterpanel (FilterSection,FilterName,FilterText,FilterImage)
Select DISTINCT 'Plat',CONCAT('plat_',Plat.ProjID), Plat.ProjName , Plat.ProjImage 
From tbl_events E 
inner join tbl_projects P On E.EventProjID = P.ProjID
left join tbl_submissions S ON S.SubProjID = P.ProjID  
left join tbl_projects Plat on Plat.ProjID = P.ProjPlatform 
Where ProjType = 2 and Plat.ProjName IS NOT NULL and P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16 
and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
EventEndDate > UTC_TIMESTAMP 
Order By ProjName");

mysqli_query($db,"
Insert IGNORE Into tbl_filterpanel (FilterSection,FilterName,FilterText,FilterImage)
Select DISTINCT 'Plat','plat_0', 'New Blockchain' , '' 
From tbl_events E 
inner join tbl_projects P On E.EventProjID = P.ProjID
left join tbl_submissions S ON S.SubProjID = P.ProjID  
Where ProjType = 1 and P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16 
and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
EventEndDate > UTC_TIMESTAMP 
Order By ProjName");


mysqli_query($db,"
Insert IGNORE Into tbl_filterpanel (FilterSection,FilterName,FilterText)
Select DISTINCT 'Cat',CONCAT('cat_',P.ProjCatID), PC.ProjCatName
From tbl_events E 
inner join tbl_projects P On E.EventProjID = P.ProjID
left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
left join tbl_submissions S ON S.SubProjID = P.ProjID  
Where P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16 
and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
EventEndDate > UTC_TIMESTAMP
Order By ProjCatName");

?>