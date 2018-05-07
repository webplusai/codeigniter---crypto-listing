<? 
  require('includes/global_icons.php');
  require('includes/global_data.php'); 
  require('includes/project_icons.php');
  require('includes/project_data.php');
?>
<!doctype html>
<html lang="en">
<head>
  <? require ('includes/global_meta.php'); ?>
  <title><? echo $page_title; ?></title>
  <link rel="stylesheet" type="text/css" href="css/css_global.php" />
  <link rel="stylesheet" type="text/css" href="css/css_project.php" />
  <script async src="js/site.js"></script>
  <? if ($proj_package==0) { require_once('includes/global_banner_footer.php'); } ?>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>
      <? if ($proj_package==0) { require_once('includes/global_banner.php'); } ?>
      <div class="project-heading">
        
          <img class="project-logo inline" src="<? echo $proj_img; ?>" height="48" width="48" alt="<? echo $proj_name; ?> Logo"><h1 class="inline"><? echo $proj_title; ?><? echo $proj_sponsoredbadge; ?> 
        </h1>
      
        <div class="project-desc">
          <? echo $proj_desc; ?>
        </div>
      </div>
      
      <div class="projectinfo">
        <div class="infoitem">
          <div class="infolabel inline">Project Type</div>
          <div class="infovalue inline"><? echo $proj_type;?></div>
        </div>
        <div class="infoitem">
          <div class="infolabel inline">Platform</div>
          <div class="infovalue inline"><? echo $proj_type;?></div>
        </div>
        <div class="infoitem">
          <div class="infolabel inline">Website</div>
          <div class="infovalue inline"><a href="<? echo $proj_website;?>" target="_blank">Open <img src="<? echo $openwindow; ?>" class="inline" alt="Open Website"></a></div>
        </div>
        <div class="infoitem">
          <div class="infolabel inline">Category</div>
          <div class="infovalue inline"><? echo $proj_category;?></div>
        </div>
        <div class="infoitem">
          <div class="infolabel inline">Total Supply</div>
          <div class="infovalue inline"><? echo $proj_totalsupp;?></div>
        </div>
        <div class="infoitem">
          <div class="infolabel inline">Whitepaper</div>
          <div class="infovalue inline"><? echo $whitepaper?'<a href="'.$whitepaper.'" target="_blank">View <img src="'.$openwindow.'" class="inline" alt="View Whitepaper"></a>':'None Provided'; ?></div>
        </div>
        <div class="infoitem">
          <div class="infolabel inline">Bitcoin Talk</div>
          <div class="infovalue inline"><? echo $bitcointalk?'<a href="'.$bitcointalk.'" target="_blank">Open <img src="'.$openwindow.'" class="inline" alt="Open Bitcoin Talk"></a>':'None Provided'; ?></div>
        </div>        
        
        <div class="crowdfund">
          <h2><? echo $event_name; ?></h2>
  
          <div class="infoitem">
            <div class="infolabel inline">Start Date</div>
            <div class="infovalue date inline"><? echo $start;?></div>
             <div id="startclock" <? echo $is_mobile?'style="font-weight: bold;"':''; ?>>
             <div>
                <span class="days"><? echo (time()<$startstamp || time() > $endstamp ?'- -':'L'); ?></span>
                <div class="smalltext">Days</div>
              </div><div>
                <span class="hours"><? echo (time()<$startstamp || time() > $endstamp?'- -':'I'); ?></span>
                <div class="smalltext">Hours</div>
              </div><div>
                <span class="minutes"><? echo (time()<$startstamp || time() > $endstamp?'- -':'V'); ?></span>
                <div class="smalltext">Mins</div>
              </div><div>
                <span class="seconds"><? echo (time()<$startstamp || time() > $endstamp?'- -':'E'); ?></span>
                <div class="smalltext">Secs</div>                             
              </div>
            </div>
          </div>
          <div class="infoitem">
            <div class="infolabel inline">End Date</div>
            <div class="infovalue date inline"><? echo $end;?></div>
            <div id="endclock" style="margin-bottom: 25px;<? echo $is_mobile?'font-weight: bold;':''; ?>">
              <div>
                <span class="days">- -</span>
                <div class="smalltext">Days</div>
              </div><div>
                <span class="hours">- -</span>
                <div class="smalltext">Hours</div>
              </div><div>
                <span class="minutes">- -</span>
                <div class="smalltext">Mins</div>
              </div><div>
                <span class="seconds">- -</span>
                <div class="smalltext">Secs</div>
              </div>
            </div>
          </div>          
        </div>
        <? if ($proj_spons){ ?>
      
        <? if ($crowdfunddesc) { ?>
        <div class="crowdfund-details">
          <div class="infoitem">
            <div class="infolabel">Details</div>
          </div>
          <div class="infodetails"><? echo $crowdfunddesc; ?></div>
        </div>
        <? } ?>
        
        <? if (mysqli_num_rows($rates)>0) { ?>
        <div class="crowdfund-rates">
          <div class="infoitem">
            <div class="infolabel">Rates and Bonuses</div>
          </div>
          <div class="infodetails">
            <table class="ratestable"><thead>
            <tr>
              <th></th>
            </tr></thead>
            <tbody>
          <?
            while ($rate = mysqli_fetch_assoc($rates)) 
            {   
              echo '<tr><td>'.$rate['CrowdBonusName'].'</td></tr>';
            }
          ?>
            </tbody>
            </table>
          </div>
        </div>
        <? } ?>
        
        <? if (mysqli_num_rows($distros)>0) { ?>
        <div class="crowdfund-distro">
          <div class="infoitem">
            <div class="infolabel">Distribution</div>
          </div>
          <div class="infodetails">
            <table class="distrotable"><thead>
            <tr>
              <th style="width: 300px;"></th><th style="width: 200px;"></th><th></th><th></th>
            </tr></thead>
            <tbody>
          <?
            while ($distro = mysqli_fetch_assoc($distros)) 
            {   
              echo '<tr><td>'.$distro['DistroDesc'].'</td>'.($distro['DistroAmount']>0?'<td>'.number_format(($distro['DistroAmount']+0)).' '.$distro['ProjSymbol'].'</td>':'').'<td>'.($distro['DistroPercent']==0?'':$distro['DistroPercent'].'%').'</td><td><i>'.$distro['DistroNote'].'</i></td></tr>';
            }
          ?>
            </tbody>
            </table>
          </div>
        </div>
        <? } ?>
      
        <? if (mysqli_num_rows($proj_links)>0) { ?>
        <div class="project-links">
          <h2>Links</h2>
          <div class="infoitem">
            <? while ($link = mysqli_fetch_assoc($proj_links)) 
               {    
                if ($link['ProjAffilLinks'] && $link['LinkTypeID']==1)
                {
                  $link['Link']= "https://www.coinschedule.com/link.php?l=".$link['LinkID'];
                }
                
                echo '<div class="proj-link inline"><img class="inline" src="data:image/png;base64,'.$link['LinkTypeImage'].'" height="16" width="16" alt="'.$link['LinkTypeName'].' Link"><a target="_blank" href="'.$link['Link'].'" rel="nofollow">'.$link['LinkTypeName'].'</a></div>';
            ?>
            
            <? } ?>
          </div>
        </div>
        <? } ?>
        
        <? if (mysqli_num_rows($peoples)>0) { ?>
        <div class="project-team">
          <h2>Team</h2>
          <div class="infoitem">
            <table class="teamtable"><thead>
            <tr>
              <th style="width: 300px;">Name</th><th style="width: 400px;">Position</th><th>Links</th>
            </tr></thead>
            <tbody>
            <? while ($people = mysqli_fetch_assoc($peoples))  
               {    
                  $peoplelinks = mysqli_query($db,"Select Link,LinkTypeImage,LinkTypeName from tbl_links L inner join tbl_linktypes LT on L.LinkType = LT.LinkTypeID where LinkParentType = 4 and LinkParentID = ".$people['PeopleID']." Order By LinkTypeSortOrder");
                  
                  $links = "<div>";
                  while ($peoplelink = mysqli_fetch_array($peoplelinks))
                  {
                    $links.='<a rel="nofollow" target="_blank" href="'.$peoplelink['Link'].'"><img class="inline" src="data:image/png;base64,'.$peoplelink['LinkTypeImage'].'" alt="'.$people['PeopleName'].' '.$peoplelink['LinkTypeName'].' Link"></a>';
                  }
                   
                  $links.= "</div>"; 
                    
                 echo '<tr><td>'.$people['PeopleName'].'</td><td>'.$people['PeopleProjPosition'].'</td><td>'.$links.'</td></tr>';
               }
            ?>
            </tbody>
            </table>
          </div>
        </div>
        <? } ?>
        
         <? if (mysqli_num_rows($events)>0) { ?>
        <div class="project-events">
          <h2>Upcoming Events and Milestones</h2>
          <div class="infoitem">
            <table class="eventstable"><thead>
            <tr>
              <th style="width: 10px;"></th><th style="width: 100px;">Date</th><th>Event</th>
            </tr></thead>
            <tbody>
            <?
              while ($event = mysqli_fetch_assoc($events))  
              {    
                $eventdate = date_create($event['EventDate']);
      
                $eventname = $projid=='' && $event['EventProjID']!=0 && $event['Bonus']==0 && stripos($event['EventName'],$event['ProjName'])===false?$event['ProjName'].' - '.$event['EventName']:$event['EventName'];       
          
                $eventtitle = $eventname.($event['EventLocation']==''?'':' - <i>'.$event['EventLocation'].'</i>').(date_format($eventdate,"H:i")!="00:00" && $event['EventType']==1?' ('.date_format($eventdate,"H:i").' UTC)':'');
                //$eventtitle = $event['EventName'].(date_format($eventdate,"H:i")!="00:00"?' ('.date_format($eventdate,"H:i").' UTC)':'');
                
                if ($event['EventStartDateType']==2)
                {
                  $eventdate = 'Q'.ceil(date_format($eventdate,'n')/3).' '.date_format($eventdate,'Y');
                }
                elseif ($event['EventStartDateType']==3)
                {
                  $eventdate = $eventdate?date_format($eventdate,"M Y"):'';
                }
                else
                {
                  if ($event['EventDate']=="0000-00-00 00:00:00") { $eventdate = "TBA"; }else {
                      $eventdate = $eventdate?date_format($eventdate,$ismobile?"d/m/y":"M Y"):'';
                  }  
                }
                
                $eventicon = '<td width="20"><img class="inline" src="data:image/png;base64,'.$event['EventTypeImage'].'" height="16" width="16" alt="'.$event['EventTypeName'].'"></td>';
                    
                echo '<tr>'.$eventicon.'<td>'.$eventdate.'</td><td class="name">'.$eventtitle.'</td></tr>';
              }
            ?>
            </tbody>
            </table>
          </div>
        </div>
        <? } ?>
        <? } ?>
      </div>
      
      
      
      <div class="project-comments" id="disqus_thread"></div>
      <script>
        var disqus_config = function () {
            this.page.url = 'http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ); ?>'
            this.page.identifier = 'testNEW'
        };
        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = '//coinschedule.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();       
      </script>
      <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
      
      <? if (time()<$endstamp) { ?>  
      <script>                                           
        var startdate = new Date(Date.UTC(<? echo $timerdate; ?>));
        var enddate = new Date(new Date('<? echo date_format($enddate,'D M d Y H:i:s'); ?>Z'));
        initializeClock('<? echo (time()<$startstamp?'startclock':'endclock'); ?>', startdate,enddate);          
      </script>
      <? } ?>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
</body>
</html>