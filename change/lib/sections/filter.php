<script>
 $(document).ready(function(){

 $('#filtertoggle').click(function () {
  if(!$('#filter').is(":hidden"))
  {
    $("#filterimg").attr("src","img/filter_open.png");
    state = 0;
  }
  else
  {
    $("#filterimg").attr("src","img/filter_close.png");
    state=1;
  }
  
  $.cookie('cs_filter_state', state);
  $('#filter').slideToggle("slow");
});
});

function togglechk(check)
{

 $(check).click(); 
}

function applyfilter()
{
  var filterstring = '';
  $("#filter input:checkbox").each(function()
     {
      if ($(this).is(':checked'))
      {
        filterstring += $(this).attr('id') + ',';
      }      
  });
  $.cookie('cs_filter', filterstring);
  location.reload();
}

</script>
<? $filter = $_COOKIE['cs_filter']; ?>
 <center id="filtertoggle" style="padding: 5px 0px 5px 15px;border-bottom: 1px solid #e4e5e7;cursor: pointer;cursor: hand;font-size: 1.1em;"><b style=""><img width="16" height="16" id="filterimg" src="img/<? echo $_COOKIE['cs_filter_state']?'filter_close.png':'filter_open.png'; ?>"> Filter</b></center>
 <div id="filter" style="display: snone;font-size: 1.1em;padding: 0px 15px 0px 15px;<? echo $_COOKIE['cs_filter_state']?'':'display: none;'; ?>">
  <div style="display: inline-block;border: 1px solid #ccc;padding: 5px;margin-bottom: 10px;margin-top: 10px;width: 100%;">
    <table><tr><td width="16"><input id="plat" type="checkbox" style="margin-top: 5px;" <? echo strpos($filter,'plat,')!==false || !$_COOKIE['cs_filter']?'checked':''; ?>></td><td onClick="togglechk(plat);" style="cursor: pointer;cursor: hand;"><b>Filter By Platform</b></td><td width="45"></td><td><button style="font-size: 0.9em" onClick="applyfilter();">Apply</button></td></tr></table>
    <hr style="margin: 2px;">
  <?
  
  $filters = explode(',', $filter);

  $platfilter = "";
  
  if (strpos($filter,'plat,')!==false)
  {
    foreach ($filters as $value)
    {

      if (strpos($value,'plat_')!==false)
      {
        $platfilter.= "FIND_IN_SET('".str_replace('plat_','',$value)."', ProjPlatform) or ";
      }
      
    }
      
  }
  
  if ($platfilter) { $platfilter = ' and ('.substr($platfilter,0,-3).')'; }
  
  $catfilter = "";
  
  if (strpos($filter,'cat,')!==false)
  {
    foreach ($filters as $value)
    {
      
      if (strpos($value,'cat_')!==false)
      {
        $catfilter.= 'P.ProjCatID = '.str_replace('cat_','',$value).' or ';
      }
      
    }
      
  }
  
  if ($catfilter) { $catfilter = " and (".substr($catfilter,0,-3).")"; }

  // Platform
  $platforms = mysqli_query($db,"Select Plat.ProjID, Plat.ProjName as Platform, Plat.ProjImage as PlatformImage, ".($catfilter?'sum('.substr($catfilter,5).')':'count(*)')." as Total 
                                From tbl_events E 
                                inner join tbl_projects P On E.EventProjID = P.ProjID
                                left join tbl_submissions S ON S.SubProjID = P.ProjID  
                                left join tbl_projects Plat on Plat.ProjID IN(P.ProjPlatform) 
                                Where Plat.ProjName IS NOT NULL and P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16 
                                and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
                                EventEndDate > UTC_TIMESTAMP 
                                Group By Plat.ProjName
                                Order By Platform
                                "); 
                                
  
  while ($platform = mysqli_fetch_array($platforms))
  {
    echo 
    '<div style="display: inline-block;margin: 0px;vertical-align: middle;cursor: pointer;cursor: hand;">
    <table border=0><tr><td width="15"><input id="plat_'.$platform['ProjID'].'" type="checkbox" style="margin-top: 5px;" '.(strpos($filter,'plat_'.$platform['ProjID'].',')!==false?'checked':'').'></td><td width="18" onClick="togglechk(plat_'.$platform['ProjID'].');"><img style="padding-bottom: 2px;" src="data:image/png;base64,'.$platform['PlatformImage'].'"></td><td onClick="togglechk(plat_'.$platform['ProjID'].');">'.$platform['Platform'].' ('.$platform['Total'].')</td><td width="15"></td></tr></table>
    </div>';
  }
  
  ?>
  </div>
  <div style="display: inline-block;border: 1px solid #ccc;padding: 5px;margin-bottom: 10px;width: 100%;">
  <table><tr><td width="16"><input id="cat" type="checkbox" style="margin-top: 5px;" <? echo strpos($filter,'cat,')!==false || !$_COOKIE['cs_filter']?'checked':''; ?>></td><td onClick="togglechk(cat);" style="cursor: pointer;cursor: hand;"><b>Filter By Category</b></td><td width="45"></td><td><button style="font-size: 0.9em" onClick="applyfilter();">Apply</button></td></tr></table>
  <hr style="margin: 2px;">
  <?
  
   // Categories
  $cats = mysqli_query($db,"Select P.ProjCatID, PC.ProjCatName as Category,".($platfilter?'sum('.substr($platfilter,5).')':'count(*)')." as Total 
                                From tbl_events E 
                                inner join tbl_projects P On E.EventProjID = P.ProjID
                                left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
                                left join tbl_submissions S ON S.SubProjID = P.ProjID  
                                Where P.ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and P.ProjICORank > 16 
                                and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
                                EventEndDate > UTC_TIMESTAMP
                                Group By PC.ProjCatName
                                Order By Category
                                ");
  
  while ($cat = mysqli_fetch_array($cats))
  {
    echo 
    '<div style="display: inline-block;margin: 0px;vertical-align: middle;width: 260px;cursor: pointer;cursor: hand;">
    <table border=0><tr><td width="17"><input id="cat_'.$cat['ProjCatID'].'" type="checkbox" style="margin-top: 5px;" '.(strpos($filter,'cat_'.$cat['ProjCatID'].',')!==false?'checked':'').'></td><td onClick="togglechk(cat_'.$cat['ProjCatID'].');">'.$cat['Category'].' ('.$cat['Total'].')</td><td width="15"></td></tr></table>
    </div>';
  }
  
  ?>
  </div>   
  </div> 