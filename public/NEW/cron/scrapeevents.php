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
  

  
  $scrapes = mysqli_query($db,"SELECT * FROM tbl_eventsites Order By EventSiteID,EventSiteURL");
  
  while($scrape = mysqli_fetch_assoc($scrapes))
  {
    $siteURL = $scrape['EventSiteURL'];
    $siteURL = substr($siteURL,-1) == '/'?substr($siteURL,0,strlen($siteURL)-1):$siteURL;
  
    $eventDOM = getUrlasDOMXPath($siteURL);
    
  
    if(!empty($eventDOM))
    { 
      $event = $eventDOM->query($scrape['EventSiteScrapeDomQuery']);
       
      if($event->length > 0)
      {   
        foreach($event as $article)
        {      
          
          // Name 
          $eventname = $eventDOM->query($scrape['EventSiteScrapeNameDomQuery'],$article);
              
                  
          
          if($eventname->length > 0) 
          { 
            if ($scrape['EventSiteScrapeNameDomQueryItemAttrib']=='nodeValue')
            {
              $name = $eventname->item($scrape['EventSiteScrapeNameDomQueryItem'])->nodeValue;
            }
            else
            {
              $name = $eventname->item($scrape['EventSiteScrapeNameDomQueryItem'])->getAttribute($scrape['EventSiteScrapeNameDomQueryItemAttrib']);
            }
            
            $name = mysqli_real_escape_string($db,$name); 
          } 
          
          $name = trim($name);
          
          
          // Link
          $eventlink = $eventDOM->query($scrape['EventSiteScrapeLinkDomQuery'],$article);
                                                
                                                                                               
          if($eventlink->length > 0) 
          { 
            if ($scrape['EventSiteScrapeLinkDomQueryItemAttrib']=='nodeValue')
            {
              $link = $eventlink->item($scrape['EventSiteScrapeLinkDomQueryItem'])->nodeValue;
            }
            else
            {
              $link = $eventlink->item($scrape['EventSiteScrapeLinkDomQueryItem'])->getAttribute($scrape['EventSiteScrapeLinkDomQueryItemAttrib']);
            }
          } 
          
          $link = trim($link);
          
          if(substr($link,0,1)=='/')
          {
            $link = trim($siteURL.$link);
          }
          
          
          
          if(mysqli_fetch_array(mysqli_query($db,"Select Count(*) as Total from tbl_events where EventWebsite='".$link."' or EventName = '".$name."'"))['Total']==0 && $name!='' && $link!='')
          {
          
          // Image
          $image = "";
          $imagefile = grab_url("http://www.google.com/s2/favicons?domain_url=$link");
          $image = base64_encode($imagefile); 
                 
          
          // Date
          $enddate = "";
          $eventdate = $eventDOM->query($scrape['EventSiteScrapeDateDomQuery'],$article);
                  
          if($eventdate->length > 0) 
          { 
            if ($scrape['EventSiteScrapeDateDomQueryItemAttrib']=='nodeValue')
            {
              $date = $eventdate->item($scrape['EventSiteDateScrapeDomQueryItem'])->nodeValue;
            }
            else
            {
              $date = $eventdate->item($scrape['EventSiteScrapeDateDomQueryItem'])->getAttribute($scrape['EventSiteScrapeDomDateQueryItemAttrib']);
            }
                        
            if(strpos($date,"-")!==false)
            {
              $enddate = $date;
              $arr = explode(' ',trim($enddate));
              $enddate = $arr[0].' '.substr($enddate,strpos($enddate,"-")+1);
              $enddate = date("Y-m-d",strtotime($enddate));
              
              $startdate = substr($date,0,strpos($date,"-")).' '.$arr[2];
              $date = date("Y-m-d",strtotime($startdate)); 
            }
            else
            {
              $date = date("Y-m-d",strtotime($date));
            }
            
            
             
          } 
          
           
          // Location
          $eventlocation = $eventDOM->query($scrape['EventSiteScrapeLocationDomQuery'],$article);
                  
          if($eventlocation->length > 0) 
          { 
            if ($scrape['EventSiteScrapeLocationDomQueryItemAttrib']=='nodeValue')
            {
              $location = $eventlocation->item($scrape['EventSiteScrapeLocationDomQueryItem'])->nodeValue;
            }
            else
            {
              $location = $eventlocation->item($scrape['EventSiteScrapeLocationDomQueryItem'])->getAttribute($scrape['EventSiteScrapeDomLocationQueryItemAttrib']);
            }
          } 
         
         $location = trim($location);
         
          
            mysqli_query($db,"Insert into tbl_events (EventName,EventStartDate,".($enddate?'EventEndDate,':'')."EventWebsite,EventLocation,EventImage,EventType) values('$name','$date',".($enddate?"'$enddate',":'')."'$link','$location','$image',2)");
            
            //echo "Insert into tbl_events (EventName,EventStartDate,".($enddate?'EventEndDate,':'')."EventLink,EventLocation) values('$name','$date',".($enddate?"'$enddate',":'')."'$link','$location','$image')<BR>";
          }
        }
      }
    }
  }  
?>