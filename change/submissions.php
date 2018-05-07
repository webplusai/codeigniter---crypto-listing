<html>
<head><title>Coinschdule Admin - Submissions</title></head>
<body>
<link href="public/css/list.css?12edf232ssda2232" rel="stylesheet">
<?php require_once('menu.php'); ?>
<div style="padding: 15px;">
<h1 style="display:inline;padding-right: 130px;">Submissions</h1>
<?php
session_start();
require "codebase/bd.php";

$view = $_GET['view']?$_GET['view']:1;

/* echo '<label>View:<select id="view" name="view" onChange="window.location = \'?view=\'+ this.options[this.selectedIndex].value;">';

$substatus =  mysqli_query($db,"Select SubStatusID,SubStatusName from tbl_substatus Order By SubStatusID"); 

while ($substat = mysqli_fetch_array($substatus))
{
  echo'<option value="'.$substat['SubStatusID'].'" '.($substat['SubStatusID']==$view?'selected':'').'>'.$substat['SubStatusName'].'</option>';
}

echo '</select></label>';
*/

$subs = mysqli_query($db,"Select S.SubID,SubStatus,SubCoinName,SubHashCode,PaySecret,SubProjID,".($view==1?'SubDate':'SubStatusUpdatedOn as SubDate').",SubName,SubEmail,SubType,SubEventName,SubInfo,SubLink,SubResponsible,SubWhitePaper from tbl_submissions S left join tbl_payments P on S.SubID = P.SubID Where (SubStatus = 1 or SubStatus = 2) and SubProjID IS NULL and SubCoinName <> '' ORDER BY SubDate DESC");

if ($view==1){$dateheading = "Submitted";}
elseif ($view==2) {$dateheading = "Approved";}
elseif ($view==3) {$dateheading = "Rejected";}
elseif ($view==4) {$dateheading = "Marked";}

echo '<table class="responstable nohover"><tr><th width="100">'.$dateheading.' On</th><th width="180">Name</th><th width="300">Email</th><th width="200">Type</th><th>EventName</th><th>Link</th><th>Info</th><th>Status</th><th width="60" style="text-align: center;">Approve</th><th width="60" style="text-align: center;">Reject</th><th width="60" style="text-align: center;">Spam</th>'.($view>1?'<th width="60"  style="text-align: center;">Reset</th>':'').'</tr>';
                                
while ($sub = mysqli_fetch_array($subs))
{

  $id = $sub['SubID'];
  
  $link = $sub['SubLink'];
  if (false === strpos($link, '://')) {
    $link = 'http://' . $link;
  }

  $status = '';
  if ($sub['SubCoinName'] == '') {
    $status = 'Incomplete';
  } else if ($sub['SubCoinName'] != '') {
    $status = 'Payment Required';
    $status .= ' <a href="http://coinschedule.com/cron/push_payment?h='.$sub['SubHashCode'].'" class="btn btn-primary">Push Payment</a>';
  }
        
  echo '<tr><td>'.date("d/m/y H:i",strtotime($sub['SubDate'])).'</td><td>'.$sub['SubName'].'</td><td><a href="mailto:'.$sub['SubEmail'].'">'.$sub['SubEmail'].'</a></td><td>'.$sub['SubType'].'</td><td>'.$sub['SubEventName'].'</td><td><a href="'.$link.'" target="_blank">'.$sub['SubLink'].'</a></td><td>'.(1==1?'':nl2br($sub['SubInfo'])).'</td>
  <td>'.$status.'</td>
  <td align="center">
<a href="updatesubstats.php?id='.$id.'&view='.$view.'&status=2" onclick="return confirm(\'Are you sure you want to Approve '.$sub['SubEventName'].'?\');">
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABPklEQVR4Xr2TTytEYRSHf5exms9gJzuraylhZkjKfAPGxpKykpqkibpLJeXemZUN2ZgNKSaEBW7+LsQH0KQYY2Hn+N1z6625C82lnHq6p/Oe55zebq8lIvjXGHQgxGxtiSP3L0E2ppdBNAfDiiOvTxWwU8mrMJJawMTKPBJx5N0Tym3h1k95bRSGHEhAVB6g/PQxI+4+ZI14B5Db+rgEdQC2abqvj5HwwMiLbH7PiXcEcQ8pHwdyzshms18bFe+MDaeQ87es9BVANNe6y3qR34tatlEmCXOfVuBLgOvHMvKTPQA01/9kkc6OXsyullGZQzcAn5iw05y699IlxStu83nXS0Vz1vQsFdmsRIdsV9uldEfxRmEe1tIR+cchm89JKT2AaN6sbLAzFLaqSSUTTzbYww6EGPk32M3Kf37O334OxYr4c1tIAAAAAElFTkSuQmCC"></a></td>
  <td align="center"><a href="updatesubstats.php?id='.$id.'&view='.$view.'&status=3">
  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAjhJREFUOI2Fk+9PUlEYx7/Hi9B2x8V7b8wyV5ltMfs5YzUUdQhUb1z2H7Qp/hX+I01b4NZ7XlSWrBFkupaLRmuVTFKqoSJcfoztcjmX04uE8av1bOfNeT7fz/OcFwc4rgAgr0/bk34L78I/akUQvO88t3aWAKmlEQDkTzNT2cKij/3wzdJukhVB8O4uPKCFRR+LzTgzdQkJAPK1e+PbF2zDDWs2c6RHgxHvw3I5DABPBeHu+P3J56IscXUm+SVxtBXavETWRy8nr9qvDLVPzGZyenRtw2vkmGnM43wmnhS5dia+9TlB/DzvmnDaQ3KTvSHJ5vQe0gNR6uvo5ZQ8fRPenCYA4Od518To9ZAktoGMgVU1oErBdB2o6QBjyBVLNPJ9xz2vqlFSZ/0873KOjIQkQeBYtQqoKpimAYy1OJVymYZ3d6cXKH0LAKS56TeZXI5TgyGJM3SsDAC5iqqv/95zzwGR+p2hGSCUGqFqqPWSzjSAWrXWcdcglznuzph14IWl19R1euMJWkV/f/CzsQUBgCWO8zqsZ1bN/wnXq6Cp+odjCXnMcZ6b1sGXZmNnuKipOgAIxhNdex/3U26yahYTw5L1YjtQ0lQaS6fcBCA3Tp99be4iSSiH33p+lZTbqVI+QwlB/ShahcbSKfc8EJ0DIlvplFvRKnozs1dUDg6LeQeAv59pTe7PxIdsbGPgXHUZmGyf9giGqY2B8zQ+ZGOvpP79J0BfCxAA5KBF3O4WbpYELeLX5vAfETvzqIeEC14AAAAASUVORK5CYII="></a></td>
   <td align="center"><a href="updatesubstats.php?id='.$id.'&view='.$view.'&status=4">
   <img src="data:image/gif;base64,R0lGODlhEAAQAOZcAHF6hf///664w5KeqfHz9JqksMTL1Nbc4rjE0eTr8bnEzyw3QvD1/Ovx+O/0+2BqdFlha/X4+e71+rG7xig0P1xlbys2QeDo8Sk0QNXd5pGdqsHN2bjEz7/K1Fhha56lrLjE0LrF0JGdp5qiqXF7hoeRm6Krtio1QH+Klaayvtrb3VZfabLAzvDz9JymsnF8h15mb3J+ioyZpcfR2cHM14GMl9zk62pzfGlyfmx2gmVueCYyPrC7x1xkbXuGkejv9cfP146Xn5iirpSgq5uotPj9/6OnrbnF0MLK011lbm53grnE0EZOWOPq8GFqc0FLVc/U2rjDztDa5M/X4JWcpbC9ynaBi7vH1NXb4tnf5i04Q2RueP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAFwALAAAAAAQABAAAAeggFyCgwCFAIOIiAAMDQ0Mh4lchpOUhAGXmJkBUJAADjYZUzMdCjwpRBoynTEqRh8jVEElNSgmPpBcLwFAET8uWVdDEwpWiDkBBgQJBQcIAwJRJIg4yMrMzgIcSohb1cvNzwo3iA/e1+E6iBXm4AJLToge7NhHMIgrAUgtTUJYICICQvRABOFSEQkXpGxgUYVGkkRPdlDAcMLCAi0LmAwKBAA7"></a></td>'.($view>1?'<td align="center"><a href="updatesubstats.php?id='.$id.'&view='.$view.'&status=1">
   <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAlpJREFUOI2lUktIVGEYPf9//7n3pvmYplQUR0fGjJmgbBG4yxbVyooyqQiaWoSLaOGqB5jrqEVQO83AEKaNZNTGRUK4KDNlsHxMI9kDaqa58/B6x3vv/98WMYPaw6Kz+uCc73C+wwf8J0h+aAw9PlkdK344OtpqA0CgPSwvq6xFZqqf2yJBmD4WvdcRX2/A8kOtt/5Oukw7hVGnLdg50llVU3ujsqKqSJYVCCGgJeJOyaXng8nU/Pn390O5tQnaw9KB5j2m272VxqIz0fqGRr+iqDCMZaQzKdNFicvtqSSEAHOz06+LXhp780kpANRKciVzqRRUQsP2oJ8yGdHY/Oep8bGWkat+dWL6XUlkcvxWVtdR17CjOREQ1/IJKABQiXslJsOyBSxbwLQ4jKXsTLR+4gVAnC8DB/XI3dauhbm3vcIByt3butDdTQsGkqT4IDFYnMPiHLYQqPE17d8Z39e3ujA9k7yS0pIoLtuy2Te/O1goUVJcTWktCdPKcW7baW7acW6bn0xhZwGHAMQBgNjAsa+ey1NZj6e6xCHEByDCAIBy42Zs8dXtj70ntLz4t3BATduCAOeFBLN9R7I/2I4/7npPDwUYk4uXsxpA2Js1f7AR/BefKIpVFCaEYCmV/LA4cHihUOJGqDsz1CxZSmRTaVnQ0DPQde1CnvtlAn9o+Dih9BAI80pE2uVSlQqJubCS02FkUtdj/W1P81qyfjnQHpZ5eWmOudQC5zgCKytmQgjj3EL/0eHV+p8MAMB39tEgddAiHPGNgE7a1HmwWDfxDD094m9O/id8ByHt9+xEfzuQAAAAAElFTkSuQmCC"></a></td>':'').'

  </td></tr>';
}

echo '</table>';

?>                                
</div>
<?php require_once('footer.php'); ?>
</body>
</html>