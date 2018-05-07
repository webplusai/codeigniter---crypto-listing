<?
  require "/home/coinschedule/public_html/lib/bd.php";
  
  function getUrlasDOMXPath($url,$html='')
  {
    if($html==''){ $html = grab_url($url); }
    
    //$html = file_get_contents($url); 
    
    $doc = new DOMDocument();      
    libxml_use_internal_errors(TRUE); 
      if(!empty($html))
      { 
        $doc->loadHTML($html);
        libxml_clear_errors();
      
    
        $xpath = new DOMXPath($doc);
        return $xpath; 
      }
      else
      {
        return false;
      } 
  }
  
  function grab_url($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // times out after 4s
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0"); 
    $cookie_file = "cookie1.txt";
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
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
  
  $pressDOM = getUrlasDOMXPath("https://www.google.co.uk/search?biw=1920&bih=1109&tbm=nws&q=coinschedule&oq=coinschedule");
  
  if(!empty($pressDOM))
  {
    $press = $pressDOM->query("//div[@class='g']"); 
    
    if($press->length > 0)
    {   
      foreach($press as $mention)
      {    
        $mention = $pressDOM->query("div",$mention);
        
        $name = $mention->item(0)->getElementsByTagName('span')->item(0)->nodeValue;
        
        $date = $mention->item(0)->getElementsByTagName('span')->item(2)->nodeValue;
        $date = date_format(date_create($date),"Y-m-d");
        $headline = mysqli_real_escape_string($db,$mention->item(0)->getElementsByTagName('a')->item(1)->nodeValue);
        $link = $mention->item(0)->getElementsByTagName('a')->item(0)->getAttribute('href');


        if ($name && $date && $link)
        {
          if (mysqli_num_rows(mysqli_query($db,"Select * from tbl_press where PressLink = '$link'"))==0)
          {
            mysqli_query($db,"Insert into tbl_press (PressName,PressDate,PressHeadline,PressLink) values ('$name','$date','$headline','$link')");            
            print_r($name." ".$date." ".$headline." ".$link);
            echo '<br>';
          }
        }
      }
    }
  }  
?>