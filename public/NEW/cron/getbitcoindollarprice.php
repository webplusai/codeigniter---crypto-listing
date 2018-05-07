<?

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


  $btcprice = json_decode(grab_url("https://api.coinmarketcap.com/v1/ticker/bitcoin/"),true);

  if ($btcprice[0]['price_usd'])
  {
    file_put_contents("/home/coinschedule/cron/btcusd.php",'<? $btcusdprice='.$btcprice[0]['price_usd']."; ?>");
  }
  
?>
