<section>
 <div id="filtertoggle" class="filtertoggle">
  <h4><img class="inline" width="16" height="16" id="filterimg" src="<? echo $_COOKIE['cs_filter_state']?$closefilter:$openfilter; ?>" alt="Filter Toggle Icon"> Filter</h4>
 </div>
 <div id="filter" class="filter" <? echo $_COOKIE['cs_filter_state']?'style="display: block;"':''; ?>>
  <form action="/applyfilter.php" method="post">
  <div class="filterbox filterplatform">
    <table>
    <tr>
      <td>
        <b>Filter By Platform</b>
      </td>
    </tr>
    </table>
    <hr style="margin: 2px;">
  <?
 
  // Platform
  $filterpanels = mysqli_query($db,"Select FilterSection,FilterName,FilterText,FilterImage from tbl_filterpanel Order By FilterSection DESC, CASE WHEN FilterName = 'Plat_0' THEN 0 ELSE FilterText END");
  $row = 0;
  
  while ($filterpanel = mysqli_fetch_array($filterpanels))
  {
    if ($filterpanel['FilterSection']=='Cat') { break; }
        
    $filtername = $filterpanel['FilterName'];
    $filterimg = $filterpanel['FilterImage'];
    $filtertext = $filterpanel['FilterText'];
    
    echo 
    '<div class="filteritem">
    <table>
    <tr>
      <td>
        <input name="'.$filtername.'" id="'.$filtername.'" type="checkbox" '.(strpos($filter,$filtername.',')!==false?'checked':'').'>
      </td>
      '.($filterimg?'<td onClick="togglechk(\''.$filtername.'\');">
        <img class="inline" src="data:image/png;base64,'.$filterimg.'" alt="'.$filtertext.' Logo"></td>':'').'
      <td onClick="togglechk(\''.$filtername.'\');" class="filteritemtext">
        '.$filtertext.'
      </td>
    </tr>
    </table>
    </div>';
    $row++;
  }
  
  ?>
  </div>
  <div class="filterbox filtercategory">
    <table>
    <tr>
      <td>
        <b>Filter By Category</b>
      </td>
    </tr>
    </table>
    <hr style="margin: 2px;">
  <?

  
  while ($filterpanel = mysqli_fetch_array($filterpanels))
  {
    $filtername = $filterpanel['FilterName'];
    $filtertext = $filterpanel['FilterText'];
    
    echo 
    '<div class="filteritem">
    <table>
    <tr>
      <td>
        <input name="'.$filtername.'" id="'.$filtername.'" type="checkbox" '.(strpos($filter,$filtername.',')!==false?'checked':'').'>
      </td>
      <td onClick="togglechk(\''.$filtername.'\');" class="filteritemtext">
        '.$filtertext.'
      </td>
    </tr>
    </table>
    </div>';
  }
  
  ?>
  </div> 
  <input type="submit" class="apply" value="Apply Filter">
  </form>  
  </div>
</section> 