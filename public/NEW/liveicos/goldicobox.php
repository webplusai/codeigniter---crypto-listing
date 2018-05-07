<?
  if ($is_mobile)
  {
    echo '
    <div class="icobox goldbox '.($live_icoview==3?'goldplate':'').'"> 
      <a href="'.$url.'" '.($widget?'target="_blank"':'').' '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
      '.($live_icoview==3?'':($liveico['ProjDisableRibbon']==0?'<img src="https://www.coinschedule.com/img/gold.png" class="goldrib"><span class="ribbon" style="color:#fff;">Gold</span>':'')).'
      <table>
      <tr>
        <td rowspan="1" width="50" style="padding: 5px;" valign="top">
        <img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" />
        </td>
        <td>
          <h4 style="margin:0px;padding-bottom:2px;'.($liveico['ProjSponsored']?'font-weight: bold;':'').'">'.$liveico['EventName'].'</h4>
          <div style="font-size: 1.1em;'.($percent_time>90?'color:red;':'').($percent_time<=10?'color:green;':'').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>
        </td>
      </tr>
      <tr><td class="category" colspan="2">
          <div>'.strtoupper($liveico['ProjCatName']).'</div>
      </td></tr>            
      </table>
      </a>
    </div>';
  }
  else
  {
    echo '
    <div class="icobox goldbox '.($live_icoview==3?'goldplate':'').'"> 
      <a href="'.$url.'" '.($widget?'target="_blank"':'').' '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
      '.($live_icoview==3?'':($liveico['ProjDisableRibbon']==0?'<img src="https://www.coinschedule.com/img/gold.png" class="goldrib"><span class="ribbon" style="color:#fff;">Gold</span>':'')).'
      <table>
      <tr><td class="tooltippopup">
        <p class="tooltip_new " title="'.$liveico['ProjDesc'].'">
          '.($liveico['ProjImageLarge']?'<img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" alt="'.$liveico['EventName'].' Logo" />':'').'
        </p>
        <h4 style="'.($live_icoview==3?'color:#000;':'').'margin-bottom:0px;'.($liveico['ProjSponsored']?'font-weight: bold;':'').'">
          '.$liveico['EventName'].'
        </h4>
        <div class="tooltip_new" style="font-size: 1.1em;'.($percent_time<=10?'color:green;':($percent_time>90?'color:red;':'color:#000;')).'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>
      </td></tr>
      <tr><td class="category">
        <div>'.strtoupper($liveico['ProjCatName']).'</div>
      </td></tr>
      </table>
      </a>
    </div>';
  }
?> 