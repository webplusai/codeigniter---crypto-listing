<?php

  function is_mobile()
  {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
  }

  function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
  {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
    
  }
  
   
  $is_mobile = is_mobile();
  
  function clean($string)
  {
    return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($string))))));
  }

  function cleanInput($string)
  {
      return htmlspecialchars(strip_tags($string, '<p>'));
  }

if (!function_exists('saveProjectLog')) {
  function saveProjectLog($connection, $data, $projectId = 0, $isCron = false)
  {
      if ($isCron) {
          $user = 'cron';
      } else {
          $user = 'admin';
          if (isset($_SESSION['user'])) {
              $user = $_SESSION['user'];
          }
      }
    if ($projectId) {
        $sql = "Select P.*, EventStartDate, EventEndDate, EventName, EventDesc, EventStartDateType
                from tbl_projects P
                inner join tbl_events E
                on P.ProjID = E.EventProjID
                where P.ProjID = :ProjID";

        $statement = $connection->prepare($sql);
        $statement->bind(['ProjID' => $projectId], ['ProjID' => 'integer']);
        $statement->execute();
        $project = $statement->fetch('assoc');
        if (strtotime($project['EventEndDate']) < 0) {
            $project['EventEndDate'] = '';
        }
        if (strtotime($project['EventStartDate']) < 0) {
            $project['EventStartDate'] = '';
        }

        $updateData = array_merge($project, $data);
        $changed = array_diff_assoc($data, $project);
        if (empty($changed)) {
            $updateData['ProjChanged'] = '';
        } else {
            $updateData['ProjChanged'] = json_encode($changed);
        }
        $updateData['ProjUpdatedType'] = 'update';
        $updateData['ProjUpdatedUser'] = $user;
        $connection->insert('tbl_projects_logs', $updateData);
    } else {
        $data['ProjChanged'] = json_encode($data);
        $data['ProjUpdatedType'] = 'insert';
        $data['ProjUpdatedUser'] = $user;
        $connection->insert('tbl_projects_logs', $data);
    }
  }
}

?>