<div class="container">
	<h2 style="padding-bottom: 10px;color: #34495e;"><b>From the <? if (!$is_mobile) echo 'Coinschedule '; ?>Blog</b></h2>
      <?
    	function get_words($sentence, $count = 100) {
    	  return implode(' ', array_slice(explode(' ', $sentence), 0, $count))." [...]";
    	}                    
      
      $posts=json_decode(file_get_contents("blogs.json"), true);
    	
    	foreach ($posts as $post)
      {
        $image = str_replace("http","https",$post["_embedded"]["wp:featuredmedia"]["0"]["media_details"]["sizes"]["medium"]["source_url"]);
    
      ?>
      
      <div class="thumbnail" style="padding: 0px 10px 10px 10px;">
        <div>
        <table><tr><td width="300" valign="top"><? if ($image) { echo ' <a href="'.$post['link'].'" target="_blank"><img style="padding: 10px 10px 10px 0px;float:left;" src="'.$image.'"></a>'; } ?></td>
        <? if($is_mobile) { echo '</tr><tr>'; } ?><td valign="top">
        <a href="<? echo $post['link'];?>" target="_blank">
          <h3 style="margin-top: 9px;"><? echo $post['title']['rendered'];?></h3>
        </a>
        <?
  	     $sentence=$post['content']['rendered'];
  	     echo get_words($sentence);
        ?></strong></td></tr></table>
        <div style="clear: both"></div>     
        </div>
      </div>

      <? 
      	}
      ?>
</div>
