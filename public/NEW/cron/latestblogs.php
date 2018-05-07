<?php 
        $url="https://www.coinschedule.com/blog/wp-json/wp/v2/posts/?per_page=10&_embed&categories_exclude=50";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    	  $output = curl_exec($ch);    
        //file_put_contents("../blogs.json", $output);
        
        $fp = fopen("/home/coinschedule/public_html/blogs.json", 'w');
        fwrite($fp, $output);
        fclose($fp);

        curl_close($ch);
?>