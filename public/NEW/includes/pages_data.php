<?
  $page = mysqli_query($db,"Select PageTitle,PageContents from tbl_pages where PageName = '$page'");
  $page = mysqli_fetch_array($page);
  
  $pagetitle = $page['PageTitle'];
  $pagecontents = $page['PageContents'];
?>