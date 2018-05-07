<?
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

  $proj_links = mysqli_query($db,"Select LinkTypeID,Link,LinkTypeImage from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID where L.LinkParentType = $parenttype and L.LinkParentID = '$id' and LinkTypeID = 5 Order By LinkTypeSortOrder");
  $numofrows = mysqli_num_rows($proj_links);
  
  if ($numofrows>0)
  {
  $twitter = mysqli_fetch_assoc($proj_links);
  $twitteruser = parse_url($twitter['Link']);
  $twitteruser = explode("/", $twitteruser['path']);
  $twitteruser = end($twitteruser);
 
  $twitterXML = new SimpleXmlElement(grab_url("https://twitrss.me/twitter_user_to_rss/?user=".$twitteruser), LIBXML_NOCDATA);
 
  $linkimage = $twitter['LinkTypeImage'];

?>
<div class="col-md-8 col-md-offset-2" style="margin-bottom:40px">
<h3>Twitter Feed</h3>
<tr><td colspan = "3" class="panelmain">
<table class="table">
<?

  $cnt = count($twitterXML->channel->item);
  $cnt = $cnt>5?5:$cnt;
  
  for($i=0; $i<$cnt; $i++)
  {
	 $url = $twitterXML->channel->item[$i]->link;
	 $title = $twitterXML->channel->item[$i]->title;
	  
	 echo '<tr><td><img src="data:image/png;base64,'.$linkimage.'" height="16" width="16"></td><td><a href="'.$url.'" target="_blank" rel="nofollow">'.$title.'</a></td></tr>';
  } 

?>
</table>
</td></tr>
</table>
</div>
<?  } ?>