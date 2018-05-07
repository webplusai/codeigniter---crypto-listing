<?
  global $dbconn,$imagepath,$eventtypeimagepath,$projimagepath;
  
  $peoples = mysqli_query($db,"
  Select PeopleName,PeopleProjPosition
	from tbl_people P
  Inner join tbl_people_projects PP
  on P.PeopleID = PP.PeopleProjPeopleID
  where PeopleProjProjID = '$projid'
  Order By PeopleProjSortOrder
  "); 
  
  $numofrows = mysqli_num_rows($peoples);
  if($numofrows>0)
  {
  
?>
<div class="col-md-8 col-md-offset-2" style="margin-bottom:40px">
<h3>Team</h3>
<tr><td colspan = "3" class="panelmain">
<? 
  
    echo '<table class="table"><tbody class="list"><tr class="tbl_list_head" style="font-weight:bold"><td>Name</td><td>Position</td></tr>';
    while ($people = mysqli_fetch_assoc($peoples)) 
    {   
      echo '<tr><td>'.$people['PeopleName'].'</td><td>'.$people['PeopleProjPosition'].'</td></tr>';
    }
    
    echo '</tbody></table>';

  
?>

</td></tr>
</table>
</div>


<?  } ?>
