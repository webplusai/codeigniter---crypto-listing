<?
  if($projid!='')
  {
    $parenttype = 1;
    $id = $projid;
  }
  elseif($exchangeid!='')
  {
    $parenttype = 2;
    $id = $exchangeid;
  }
  elseif($eventid!='')
  {
    $parenttype = 3;
    $id = $eventid;
  }
  elseif($peopleid!='')
  {
    $parenttype = 4;
    $id = $peopleid;
  }
  
  $proj_links = mysqli_query($db,"Select LinkTypeID,LinkTypeName,Link,LinkTypeImage from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID where L.LinkParentType = $parenttype and L.LinkParentID = '$id' Order By LinkTypeSortOrder");
  $numofrows = mysqli_num_rows($proj_links);
  
  if($numofrows>0)
  {
  
  $colheight = ceil($numofrows/2);
  $rowcount = 1;
?>
<div class="col-md-8 col-md-offset-2" style="margin-bottom:30px">
<h3>Links</h3>
<table class="table" style="width: 100%">
<tr><td valign="top" style="width: 50%">
<table class="tbl_links">
<?
  while ($link = mysqli_fetch_assoc($proj_links)) 
  {    
    echo '<tr><td class="list_img"><img src="data:image/png;base64,'.$link['LinkTypeImage'].'" height="16" width="16"></td><td width="'.($ismobile?'':'60').'"><a target="_blank" href="'.$link['Link'].'" rel="nofollow">'.$link['LinkTypeName'].'</a></td></tr>';
    
    if ($rowcount == $colheight)
    {
      echo '</table></td><td valign="top"><table class="tbl_links">';
    }
    
    $rowcount++;
  }
?>
</table>
</td></tr>
</table>
</div>
<? } ?>
