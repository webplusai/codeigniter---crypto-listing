<?
  echo '
  <div class="icobox standardbox" '.($liveico['ProjHighlighted']?' style="border: 2px solid #F7A61C;"':'').'> 
    <a href="'.$url.'" '.($widget?'target="_blank"':'').' '.($liveico['ProjDirectLink']?'target="_blank" rel="nofollow"':'').'>
      <table>
        <tr>
        <td style="font-size: 0.9em;padding: 0px;text-align:center;">    
          <h4 class="tooltip_new" title="'.$liveico['ProjDesc'].'"'.($liveico['ProjHighlighted']?' style="font-weight:bold;color:black;"':'').'>'.$liveico['EventName'].'</h4>
          <p class="tooltip_new" style="margin:0px;font-size: 0.9em;'.($percent_time>90?'color:red;':'').($percent_time<=10?'color:green;':'').'" title="This ICO has already gone through '.$percent_time.'% of its planned crowdfunding time"><b>'.$percent_time.'% done</b></p>
        </td>
        </tr>
        <tr><td class="category"'.($liveico['ProjHighlighted']?' style="background-color:#F7A61C;border-top:#F7A61C;"':'').'>'.($liveico['ProjCatName']).'</td></tr>
      </table>
    </a>
  </div>';
?> 