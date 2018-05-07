<?
    
  if (($_POST['plat'] || $_POST['cat']))
  {
    foreach ($_POST as $key => $value) 
    {
      if (strpos($key, 'plat') === 0) 
      {
        $filter.= "$key,";
      }
      
      if (strpos($key, 'cat') === 0) 
      {
        $filter.= "$key,";  
      }
    }
  
    
    if (!$filter || $filter=="plat," || $filter=="cat," || $filter=="plat,cat,")
    {
      header('Location: https://www.coinschedule.com/'); 
      exit; 
    }
    
    require('/home/coinschedule/public_html/lib/bd.php');
    
    $filterID = mysqli_query($db,"Select FilterID from tbl_filters where Filter = '$filter' LIMIT 1");
    
    if (mysqli_num_rows($filterID)>0)
    {
      $filterID = mysqli_fetch_array($filterID);
      $filterID = $filterID['FilterID'];
    }
    else
    {
      mysqli_query($db,"Insert Into tbl_filters (Filter) values ('$filter')"); 
      $filterID = $db->insert_id;
    } 
    
    header('Location: https://www.coinschedule.com/ico-list-'.$filterID.'.html');
    exit;
    
  }
  else
  {
    header('Location: https://www.coinschedule.com/');
    exit;
  }

?>