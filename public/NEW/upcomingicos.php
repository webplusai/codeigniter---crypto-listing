<?
  if ($widget)
  {
    $small_ico_icons=1;
    require_once('/home/coinschedule/public_html/lib/icoicons.php'); 
  }    
?>
<section>
<div class="container">
	<h2 style="<? echo $widget?'':'padding-bottom: 10px;'; ?>color: #34495e;">
    <b style="font-size: 28px;">Upcoming <? if (!$is_mobile) echo 'Token Sales & '; ?>ICOs</b>
    <? if ($widget) { ?>
     <span style="font-size: 0.5em;float: right;">
                <a href="https://www.coinschedule.com" target="_blank">  
            <div style="vertical-align:middle;display:table-cell;float: none;<? echo $is_mobile?'padding-left: 0px':'padding-bottom: 2px;'; ?>;padding-right: 10px;height: 45px;font-size: 1.9em;">
            <table><tr><td><b style="font-size: 0.9em;">Powered By&nbsp;</b></td><td><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAV/QAAFf0BzXBRYQAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAASUSURBVFiFvZddbBRVFMd/Z7qWAqWr2ZnZ3dI2JbSCPkkw+AF+BE1MTDQGvyAQhUADoX4QiJjQ2G6b7AOfCgYwAcSgvumDJj4pPJDw9cCDiUZAIyCwbXcbZLtAYypzfOjuZvbuTFFSOcl9uOee8///75mZM/eiqvhHoxtfb/omagRhV0wStrM8HrPzScfdPNHkScfdHI/Z+YTtLA8UkLCd1fGYraWRdNxd4wEuW7yE1qbm+tam5volr752O/JdfuyE7awurUkxYLmqHsQwy7I+zmQH3ynNG934XFV9QVUXADOAe4tL14DzInJMRL7NZAdP+3J2eZ73toktIiv6c9nPJGE776nqFjPAJ2I78J2q9qjqU2FxBvhREekFnvc8b8M4cRslHrPvA7qA0MD/ybYDaVFVABK2sxtYe5fI9wwM5ToBrJKn6NjzX1BEBBG5Y3KAcgVKlrCdHcA6IAz5koh8qaqHo9HoEEA+n7dFZKGqLgOaQ/IU+GhgKLe+YhOmgEY3Ps/zvBP4qlMOFtlaW1vbe/HK5RtBDNMm1U2ub2joUdX3A5Y9q6ZmfmZw4OS4ApKOe1xVHzOzLcvqyGQH94fsztzEKs/z9gVs4GR/LluBHTES5wSRi8i225Gn+tL1wDcisjeTHdyfdNw2sxKq+mijG5/r7xOWEfBKAPaVurq6Tf+C/DCwUFV37v5k36SBodwm4LIZq6ov++cRY3G+mSAiB85f+mM0gNQFFgM1wOvAPCAHPNe5puOvzjUdJB33gKr2GKkL/JNyBR5ov99irL2ain8IIG8CfgR2AjuAR4CLwJOp7q6ffOK/D8Brnd3WXlMloFAoTAWm+QKxLItEInHNBAFWAgnDty/V3XXG7xCRK4BnxE0rFApTqgSUcsxZSKOJBPhuBgWGWBm0LCAajd4ACuUIEbxbHplMpiEA4CAwbPgWpfrSU/0OVZ1O9SavN0Qbyn2kvPjz2TOeiFwwmSzLetb0pbq7fgceAvqAXuAIYy/XiVRf2vEJeCZA/IVfzp27VZqYpTwGPGHsYtWstvb02d9+/dsQcR7oAUj1pe8BjgIxxlouD86aXaOqK012ETlesUFj8esAxU3D+XxvgN8vZhRYCMxJdXcNAfx59Wov0BIg4KuKeUArPqWq8wIS3+jPZT8fT4gPY6mqfhGAcbo/l33Y76v64ViWtY5iGf2mqoeSjts3o7llUhjxjOaW2qTjpoLIARWRd01n1eekqovCCFT1g5GRkaVJxz0kIkdU9VLxM21W1adV9U1gZlg+8BJj71nZKh5B2AFyHCs1mapKhpl50C0LuAPyOza/CGlpnB4dHR3d5HnexrtB7hOxJRKJpGum1E1+y/O88T6zD0WkV0RmEn7cMu2UiHQw9nesOl/A2J9XREakeCtaAXwaEFdxgGx044+r6ovAfFVtBeqLS9dF5CJQupiUX7Sw07ZlWZ2Z7OAe/7F8DbA3jNy0WW3tkcLw8BRVJRqN3jxjdEq/mSIsy9qQyQ7uKJWiPOIxe0U8Zg/HY/a2ib6cxmP21njMvp503LWht+Ni4MaJJvddUntM3z+WF1yG8JaRGQAAAABJRU5ErkJggg==" alt="Coinschedule Logo"></td>
            <td style="width:2px"></td><td><b style="color: #000;"><span style="color: #77797D">Coin</span>Schedule</b></td></tr></table></div>
            </a>
    </span>
    <? } ?>
  </h2>
  <div style="padding-right: 17px;background-color: #5E4FA0;">
    <table class="table" style="margin-bottom: 0px;border: 0px none;">
      <tr style="font-weight:bold;background-color: #5E4FA0;color: #fff;cursor: default;">
        <th style="border-top: 0px;border-right: 1px solid #ddd;width: <? echo $is_mobile?'240px':'33%';?>"><? echo $is_mobile?'Details':'Name';?></th>
        <? if (!$is_mobile) { echo'<th  style="border-top: 0px;border-right: 1px solid #ddd;width: 28%;">Category</th><th  style="border-top: 0px;border-right: 1px solid #ddd;width: 28%;">Start Date</th>'; }?>
        <th style="border-top: 0px;">Starts In</th>
      </tr>
    </table>
  </div>
  <div style="width:100%; max-height:<? echo $is_mobile?'690px':'700px'; ?>; overflow-y:scroll;">
    <table class="table table-bordered tbl_hoverrow"><tbody>
<?
  require_once("/home/coinschedule/public_html/lib/bd.php");
  // Get Live ICOs from Database
  $icorank_threshold = mysqli_fetch_array(mysqli_query($db,"SELECT SettingValue FROM tbl_settings WHERE SettingID = 1"))['SettingValue'];
  
  $crowdfunds = mysqli_query($db,"
    Select 
    ProjID,ProjImage,ProjImageLarge,ProjPackage,ProjSponsored,ProjHighlighted,ProjPlatinum,ProjDisableRibbon,
    EventName,ProjDesc,EventID,EventStartDate,EventStartDateType,ProjCatName,ProjCatColor,ProjTopOfUpcoming
    From tbl_events E 
    inner join tbl_projects P On E.EventProjID = P.ProjID
    left join tbl_project_categories PC on P.ProjCatID = PC.ProjCatID
    left join tbl_submissions S ON S.SubProjID = P.ProjID
    Where ProjDeleted = 0 and EventDisabled = 0 and EventType = 1 and ProjICORank > $icorank_threshold and (S.SubStatus = 2 or S.SubStatus IS NULL) and 
    (EventStartDate > UTC_TIMESTAMP or (EventStartDateType = 3 and DATE_ADD(ProjAddedOn, INTERVAL 3 MONTH) > UTC_TIMESTAMP)) 
    $platfilter
    $catfilter
    Order By ProjTopOfUpcoming DESC, EventStartDateType,EventStartDate,ProjID
  ");
  
  
  // Loop through Upcoming ICOs
  while ($crowdfund = mysqli_fetch_assoc($crowdfunds)) 
  {
    
    // Dates
    if ($crowdfund['EventStartDateType']==1)
    {                                   
      $startdate = date_create($crowdfund['EventStartDate']);
      $start = date_format($startdate,"M jS Y").(date_format($startdate,"H:i")!="00:00"?' '.date_format($startdate,"H:i").' UTC':''); 
      $date_number_from=date("U",strtotime($crowdfund['EventStartDate']));
      $seconds_left_from=$date_number_from-date("U");
      $date_left_from=timeToDate($seconds_left_from);
    }
    else
    {
      $start = "TBA";
      $date_left_from = "TBA";         
    }
    

    // Sponsored Icon
    if ($crowdfund['ProjPlatinum'])
    {
      $sponsoredstar = '<img style="padding-left: 15px" src="'.$platbadge.'" class="tooltip_new" title="Platinum Level Event" alt="Platinum Level Event">';
    }
    else if($crowdfund['ProjPackage']==1)
    {
      $sponsoredstar = '<img style="padding-left: 5px" src="'.$silvbadge.'" class="tooltip_new" title="Silver Project" alt="Silver Project">';
    }
    else
    {
      $sponsoredstar = ($crowdfund['ProjSponsored']&&$crowdfund['ProjDisableRibbon']==0?'<img style="padding-left: 5px" src="'.$goldbadge.'" class="tooltip_new" title="Gold Project" alt="Gold Project">':'');
    }
    
    
    $url='https://www.coinschedule.com/icos/e'.$crowdfund['EventID'].'/'.str_replace("+","-",urlencode(strtolower($crowdfund['EventName']))).'.html';
    
    if (!$crowdfund['ProjTopOfUpcoming'] && $topofupcoming==1)
    {
      echo '<tr><td></td><td></td><td></td><td></td></tr>';
      $topofupcoming=0;
    }
    
    echo '
    <tr'.($crowdfund['ProjSponsored'] || $crowdfund['ProjHighlighted']?' style="font-weight: bold;font-size: '.($crowdfund['ProjPlatinum']?'1.3':($crowdfund['ProjHighlighted']?'1':'1.1')).'em;"':'').' '.($widget?'onclick="window.open(\''.$url.'\')"':'onclick="window.location=\''.$url.'\'"').' >
      <td style="width: '.($is_mobile?'240px':'33%').';">
        <table class="link">
          <tr>
            <td style="width:'.($crowdfund['ProjPlatinum']?'60':'25').'px;vertical-align: top;">
            '.($crowdfund['ProjPlatinum']?'<img src="data:image/png;base64,'.$crowdfund['ProjImageLarge'].'" height="48" width="48">':($crowdfund['ProjImage']?'<img src="data:image/png;base64,'.$crowdfund['ProjImage'].'" height="16" width="16" alt="'.$crowdfund['EventName'].' Logo">':'')).'
            </td>
            <td style="padding-top: 1px;">
              <a href="'.$url.'" '.($widget?'target="_blank"':'').' title="'.$crowdfund['ProjDesc'].'"'.($is_mobile||$widget&&$crowdfund['ProjSponsored']?' style="font-weight:bold;"':' class="tooltip_new"').'>
              '.$crowdfund['EventName'].'
              </a>
              '.$sponsoredstar.'
            </td>
          </tr>
          '.($is_mobile?'<tr><td></td><td>'.$start.'</td></tr></table></td>':'</table></td><td style="vertical-align: middle;width: 28%;">'.$crowdfund['ProjCatName'].'</td><td style="vertical-align: middle;width: 28%;">'.$start.'</td>').'<td style="vertical-align: middle;">'.$date_left_from.'</td></tr>';
          
          if ($crowdfund['ProjTopOfUpcoming'])
          {
            $topofupcoming = 1;
          }
          
  }
?>
    </tbody></table>
  </div>
</div>
</section>