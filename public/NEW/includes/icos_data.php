<? 
  $results = mysqli_query($db,"
  Select ICOName,StartDate,EndDate,TotalUSD,ProjCatName,Link 
  from tbl_icohistory H 
  left join tbl_project_categories C on H.Category = C.ProjCatID 
  Where TotalUSD > 0 and EndDate <> '0000-00-00'
  Order By EndDate DESC");
?>