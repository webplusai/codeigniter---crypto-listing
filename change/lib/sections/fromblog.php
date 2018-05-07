<div class="container">
	<h2 style="padding-bottom: 0px;color: #34495e;"><b>From the <? if (!$is_mobile) echo 'Coinschedule '; ?>Blog</b></h2>
      <?
    	function get_words($sentence, $count = 70) {
    	  return implode(' ', array_slice(explode(' ', $sentence), 0, $count)).' [...]';
    	}                    
      
      $posts=json_decode(file_get_contents("blogs.json"), true);
    	
      $counter = 1;
    	foreach ($posts as $post)
      {
        $image = str_replace("http:","https:",$post["_embedded"]["wp:featuredmedia"]["0"]["media_details"]["sizes"]["medium"]["source_url"]);
        
      ?>
      
      <div class="thumbnail" style="padding: 10px 10px 10px 10px;width: <? echo $is_mobile?'328px;':'360px;margin: 10px 10px 10px 10px;';?>height: 520px;float: left;">

        <table style="margin: 0 auto;"><tr><td width="<? echo $is_mobile?'280;':'300'; ?>" valign="top" align="center"><? if ($image) { echo ' <a href="'.$post['link'].'" target="_blank"><img style="padding: 10px 0px 10px 0px;margin:0px;" src="'.$image.'"></a>'; } ?></td></tr><tr>
        <td valign="top" style="color: #34495e;" width="300">
        <a href="<? echo $post['link'];?>" target="_blank">
          <h3 style="margin-top: 9px;color: #6a6c6f;"><b><? echo $post['title']['rendered'];?></b></h3>
        </a>
        <?
  	     $sentence=get_words($post['content']['rendered'],70);
  	     echo get_words($sentence,70-(strlen($post['title']['rendered'])+strlen($sentence)>665?15:0));
        ?></td></tr></table>
     </div>

      <? 
          if ($is_mobile && $counter==4) { break; }
          $counter++;
      	}
      ?>
</div>
