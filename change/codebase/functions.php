<?php

function cmb_projtypes($name,$value,$class='',$onchange='',$id='', $disabled = '')
{
  global $db;
  
  $projtypes = mysqli_query($db,"Select ProjTypeID,ProjTypeName from tbl_projecttypes");
  
  $return = '<select '.$disabled.' id="'.$id.'" name="'.$name.'" class="'.$class.'" onChange="'.$onchange.'">';
   
  while ($projtype = mysqli_fetch_array($projtypes))
  {
    $return.='<option value="'.$projtype['ProjTypeID'].'" '.($value==$projtype['ProjTypeID']?' selected':'').'>'.$projtype['ProjTypeName'].'</option>';  
  }
  
  $return.= '</select>';

  return $return;
}


function chk_platforms($name,$value,$class)
{
  global $db;
  
  $values = explode(",",$value);
  
  $platforms = mysqli_query($db,"Select ProjID,ProjName from tbl_projects where ProjAllowsTokens = 1 Order By ProjName");
   
  while ($platform = mysqli_fetch_array($platforms))
  {
    $return.='<label style="position: relative;vertical-align: middle;bottom: 3px;right: 3px;margin-right: 10px;"><input style="width:20px;margin-top: 4px;" name="'.$name.'[]" class="'.$class.'" type="checkbox" value="'.$platform['ProjID'].'" '.(in_array($platform['ProjID'],$values)?' checked':'').'>'.$platform['ProjName'].'</label>';  
  }

  return $return;
}

function sel_algorithm($name,$class,$disabled='')
{
  $return='<select '.$disabled.' style="width: 300px;" id="'.$name.'" name="'.$name.'[]" class="'.$class.'" multiple="multiple">';
  $return.='<option value="1">SHA-256</option>';
  $return.='<option value="2">Scrypt</option>';
  $return.='<option value="3">Ethash</option>';
  $return.='<option value="4">SHA-3</option>';
  $return.='<option value="5">POS</option>';
  $return.='</select>';

  return $return;
}

function sel_platforms($name,$value,$class,$fdname=false,$disabled='')
{
  global $db;
  
  $values = explode(",",$value);
  
  $platforms = mysqli_query($db,"Select ProjID,ProjName from tbl_projects where ProjAllowsTokens = 1 Order By ProjName");

  $return = '';
  if ($fdname) {
    $return.='<select '.$disabled.' style="width: 300px;" id="'.$name.'" name="'.$name.'[]" class="'.$class.'" multiple="multiple">';
  } else {
    $return.='<select '.$disabled.' style="width: 300px;" id="'.$name.'" name="'.$name.'" class="'.$class.'" multiple="multiple">';
  }

   
  while ($platform = mysqli_fetch_array($platforms))
  {
    $return.='<option value="'.$platform['ProjID'].'">'.$platform['ProjName'].'</option>';  
  }

  $return.='</select>';
  
  return $return;
}


function cmb_eventtypes($name,$value,$class='',$onchange='')
{
  global $db;
  
  $eventtypes = mysqli_query($db,"Select EventTypeID,EventTypeName from tbl_eventtypes where EventTypeID <> 1");
  
  $return = '<select name="'.$name.'" class="'.$class.'" onChange="'.$onchange.'">';
   
  while ($eventtype = mysqli_fetch_array($eventtypes))
  {
    $return.='<option value="'.$eventtype['EventTypeID'].'" '.($value==$eventtype['EventTypeID']?' selected':'').'>'.$eventtype['EventTypeName'].'</option>';  
  }
  
  $return.= '</select>';

  return $return;
}

function cmb_projcats($name,$value)
{
  global $db;
  
  $cats = mysqli_query($db,"Select ProjCatID,ProjCatName from tbl_project_categories Order By ProjCatName");
  
  $return = '<select name="'.$name.'" class="'.$class.'"><option value="0">-- No Category --</option>';
   
  while ($cat = mysqli_fetch_array($cats))
  {
    $return.='<option value="'.$cat['ProjCatID'].'" '.($value==$cat['ProjCatID']?' selected':'').'>'.$cat['ProjCatName'].'</option>';  
  }
  
  $return.= '</select>';

  return $return;
}

function display_date($date)
{
  if ($date=="0000-00-00 00:00:00")
  {
    return "";
  }
  else
  {
    return $date;
  }
}


  function grab_url($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    return curl_exec($ch);
  }
  
  function grab_file($url,$saveto){
      $ch = curl_init ($url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
      $raw=curl_exec($ch);
      curl_close ($ch);
      if(file_exists($saveto)){
          unlink($saveto);
      }
      $fp = fopen($saveto,'x');
      fwrite($fp, $raw);
      fclose($fp);
  }

?>                        