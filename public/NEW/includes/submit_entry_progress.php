        <ul class="progress-indicator custom-complex">
          <? 
            while ($step = mysqli_fetch_array($steps)) 
            {
              $stepnum = $step['SubStepNumber'];
              $stepname = $step['SubStepName'];
              $stepclass = '';

              if ($stepnum == $current_step) 
              { 
                $stepheading = $step['SubStepHeading']; 
                
                echo '
                  <li class="current">
                    <a href="submit_entry.php?step='.$stepnum.'">
                      <span class="bubble"></span>
                      '.$stepnum.' '.$stepname.'
                    </a>
                  </li>';
              }
              else if ($stepnum < $current_step)
              {
                echo '
                  <li class="completed">
                    <a href="submit_entry.php?step='.$stepnum.'">
                      <span class="bubble"></span>
                      <div class="checkmark inline"></div>
                      '.$stepnum.' '.$stepname.'
                    </a>
                  </li>'; 
              }
              else
              {
                echo '
                  <li>
                      <span class="bubble"></span>
                      '.$stepnum.' '.$stepname.'
                  </li>'; 
              }
         
            } 
          ?>
        </ul> 