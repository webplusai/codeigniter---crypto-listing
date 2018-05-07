<h2 class="section-heading" style="<? echo $widget?'':'padding-bottom: 10px;'; ?>color: #34495e;">
  <b style="font-size: 28px;">Live <? if (!$is_mobile) echo 'Token Sales & '; ?>ICOs</b>
</h2>
 <? 
        while ($liveico = mysqli_fetch_assoc($liveicos)) { 
        
           // Percentage Time
          $percent_time=$liveico['Percent'];
        	if($percent_time>100)$percent_time=100;
        	$percent_time=number_format($percent_time,2);
          
          $enddate = date_create($liveico['EventEndDate']);
          $end = date_format($enddate,"M jS Y").(date_format($enddate,"H:i")!="00:00"?' '.date_format($enddate,"H:i").' UTC':''); 
        
          if ($liveico['ProjSponsored']){ 
      ?>
      <div class="icobox gold icotip" title="<? echo "<b>".$liveico['EventName']."</b><br>".$liveico['ProjDesc']."<br><br>Ends $end"; ?>">
        <div class="icobox-text">
          <img src="data:image/png;base64,<? echo $liveico['ProjImageLarge']; ?>" /> 
          <h4><? echo $liveico['EventName']; ?></h4>
          <div class="<? echo ($percent_time>90?'red':'').($percent_time<=10?'green':''); ?> done" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b><? echo $percent_time; ?>% done</b></div>
        </div>
        <div class="category"><? echo strtoupper($liveico['ProjCatName']); ?></div>                                                      
      </div>  
      <? } else if ($liveico['ProjPackage']==1) { 
        if (!$lastgoldreached){ $lastgoldreached = 1; echo '<div style="height: 5px;"></div>'; }
      ?> 
      <div class="icobox silver icotip" title="<? echo "<b>".$liveico['EventName']."</b><br>".$liveico['ProjDesc']."<br><br>Ends $end"; ?>">
        <img src="data:image/png;base64,<? echo $liveico['ProjImageLarge']; ?>" />
        <div class="icobox-text">
          <h4><? echo $liveico['EventName']; ?></h4>
          <div class="<? echo ($percent_time>90?'red':'').($percent_time<=10?'green':''); ?> done" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b><? echo $percent_time; ?>% done</b></div>
        </div>
        <div class="category"><? echo strtoupper($liveico['ProjCatName']); ?></div>                                                       
      </div>       
      <? } else { 
        if (!$lastssilvreached){ $lastssilvreached = 1; echo '<div style="height: 10px;"></div>'; }
      ?> 
      <div class="icobox standard icotip" title="<? echo "<b>".$liveico['EventName']."</b><br>".$liveico['ProjDesc']."<br><br>Ends $end"; ?>">
        <div class="icobox-text">
          <h4><? echo $liveico['EventName']; ?></h4>
          <div class="<? echo ($percent_time>90?'red':'').($percent_time<=10?'green':''); ?> done" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b><? echo $percent_time; ?>% done</b></div>
        </div>
        <div class="category"><? echo $liveico['ProjCatName']; ?></div> 
      </div>
      <? } } ?>