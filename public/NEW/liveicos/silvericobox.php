<?
    echo '
      <div class="icobox silverbox '.($live_icoview==3?'silverplate':'').'"> 
      <a href="'.$url.'" '.($widget?'target="_blank"':'').' '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
      '.($live_icoview==3?'':($liveico['ProjDisableRibbon']==0?'<img src="https://www.coinschedule.com/img/silver.png?1" class="silvrib"><span class="ribbon silver" style="color:#fff;">Silver</span>':'')).'
      <table>
        <tr>
          <td style="width:60px;">
            <p class="tooltip_new" title="'.$liveico['ProjDesc'].'"><img src="data:image/png;base64,'.$liveico['ProjImageLarge'].'" height="48" width="48" alt="'.$liveico['EventName'].' Logo" /></p>
          </td>
          <td>    
            <h4'.($live_icoview==1?' style="width:100px;"':'').'>'.$liveico['EventName'].'</h4>
            <div class="tooltip_new" style="font-size: 1em;'.($percent_time<=10?'color:green;':($percent_time>90?'color:red;':'color:#000;')).'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></div>
          </td>
        </tr>
        <tr>
          <td class="category" colspan="2">
            <div>'.strtoupper($liveico['ProjCatName']).'</div>
          </td>
        </tr>
      </table>
      </a>
      </div>';   
?>
