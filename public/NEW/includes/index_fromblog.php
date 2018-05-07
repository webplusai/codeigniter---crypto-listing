<section>
<span id="blog"></span>
<h2 class="section-heading">From the Coinschedule Blog</h2>
<div class="fromblog">
      <?
      	foreach ($posts as $post)
        {
          $image = str_replace("http:","https:",$post["_embedded"]["wp:featuredmedia"]["0"]["media_details"]["sizes"]["medium"]["source_url"]);
          $date = date_create($post['date']);
          $date = date_format($date,"jS F Y");
      ?>
      <div class="blogpost inline">
        <table>
        <tr>
          <td>
            <? if ($image) { echo ' <a href="'.$post['link'].'" target="_blank"><img src="'.$image.'" alt="'.$post['title']['rendered'].'"></a>'; } ?>
          </td>
        </tr>
        <tr>
          <td>
            <a href="<? echo $post['link'];?>" target="_blank">
              <h2><? echo $post['title']['rendered'];?></h2>
            </a>
            <h3>Posted On <? echo $date; ?></h3>
          </td>
        </tr>
        <tr>
          <td class="details">
            <?
      	     $sentence=get_words($post['content']['rendered'],50 +(700/strlen($post['title']['rendered'])));
      	     echo $sentence;
            ?>
          </td>
        </tr>
        </table>
     </div>
      <? 
      	}
      ?>
</div>
</section>
<div class="divider"></div>