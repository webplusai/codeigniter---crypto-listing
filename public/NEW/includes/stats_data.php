<? 
  $year = $_GET['year']?$_GET['year']:date("Y");
   
  $dategrouping = 'EndDate';
  
  
  $months = mysqli_query($db,"SELECT MONTH($dategrouping) as Month,SUM(TotalUSD) as Total FROM `tbl_icohistory` WHERE YEAR($dategrouping) = $year and ID <> 112 GROUP BY MONTH($dategrouping) Order BY MONTH($dategrouping)");
  
  
  $monthfig = array_fill(1, 12, "''");
   while ($month = mysqli_fetch_assoc($months)) 
        {
          $monthfig[$month['Month']]= $month['Total'];
          $yeartotal = $yeartotal + $month['Total'];
        }
  
  $numofico = mysqli_query($db,"SELECT COUNT(*) as Total FROM `tbl_icohistory` WHERE YEAR($dategrouping) = $year and ID <> 112");
  $numofico = mysqli_fetch_assoc($numofico);
  $numofico = $numofico['Total'];
  
  $topten = mysqli_query($db,"SELECT ICOName, TotalUSD as Total, Link  from `tbl_icohistory` WHERE YEAR($dategrouping) = $year and ID <> 112 Order by TotalUSD DESC LIMIT 10");
  
  
  $icocats = mysqli_query($db,"Select ProjCatName,SUM(TotalUSD) as Total from tbl_icohistory H inner join tbl_project_categories C on H.Category = C.ProjCatID WHERE YEAR($dategrouping) = $year and ID <> 112 and TotalUSD <> 0 GROUP BY Category Order By Total DESC");

?>