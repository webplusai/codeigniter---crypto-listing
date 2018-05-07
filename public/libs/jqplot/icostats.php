<?

$dategrouping = 'StartDate';

require_once("../lib/bd.php");

$months = mysqli_query($db,"SELECT MONTH($dategrouping) as Month,SUM(TotalUSD) as Total FROM `tbl_icohistory` WHERE YEAR($dategrouping) = 2016 and ID <> 112 GROUP BY MONTH($dategrouping) Order BY MONTH($dategrouping)");

 $year1 = array_fill(1, 12, "''");
 while ($month = mysqli_fetch_assoc($months)) 
      {
        $year1[$month['Month']]= $month['Total'];
        $year1total = $year1total + $month['Total'];
      }
      
      
$numofico2016 = mysqli_query($db,"SELECT COUNT(*) as Total FROM `tbl_icohistory` WHERE YEAR($dategrouping) = 2016 and ID <> 112");
$numofico2016 = mysqli_fetch_assoc($numofico2016);
$numofico2016 = $numofico2016['Total'];
      
$topten2016 = mysqli_query($db,"SELECT ICOName, TotalUSD as Total, Link  from `tbl_icohistory` WHERE YEAR($dategrouping) = 2016 and ID <> 112 Order by TotalUSD DESC LIMIT 10");



$months = mysqli_query($db,"SELECT MONTH($dategrouping) as Month,SUM(TotalUSD) as Total FROM `tbl_icohistory` WHERE YEAR($dategrouping) = 2017 and ID <> 112 GROUP BY MONTH($dategrouping) Order BY MONTH($dategrouping)");


 $year2 = array_fill(1, 12, "''");
 while ($month = mysqli_fetch_assoc($months)) 
      {
        $year2[$month['Month']]= $month['Total'];
        $year2total = $year2total + $month['Total'];
      }

$numofico2017 = mysqli_query($db,"SELECT COUNT(*) as Total FROM `tbl_icohistory` WHERE YEAR($dategrouping) = 2017 and ID <> 112");
$numofico2017 = mysqli_fetch_assoc($numofico2017);
$numofico2017 = $numofico2017['Total'];

$topten2017 = mysqli_query($db,"SELECT ICOName, TotalUSD as Total, Link  from `tbl_icohistory` WHERE YEAR($dategrouping) = 2017 and ID <> 112 Order by TotalUSD DESC LIMIT 10");

      
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">  
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="/homer/vendor/jquery/dist/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery.jqplot.min.js"></script>
<script type="text/javascript" src="plugins/jqplot.barRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.categoryAxisRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.pointLabels.js"></script>
<script type="text/javascript" src="download.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />
<title>Coinschedule - ICO Stats</title>
<style>
body {
font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
margin: 20px;
}
table {
border-collapse: collapse;
}
</style>
</head>
<body>



<table><tr><td><h2 style="font-weight: bold;font-size: 2em;margin-bottom: 20px;">ICO Stats for 2016</h2></td><td width="10"></td>
<td valign="top" rowspan="3">
<button style="margin: 10px 0px 10px 0px;" id="dwnl-chart-1" onClick="$.fn.jqplotSaveImage('chart1', 'Coinschedule ICO Stats 2016')">Download Chart</button>
<table>
<tr style="font-weight: bold;font-size: 2em;"><td>Total Raised:</td><td>$<? echo number_format($year1total,0,'.',','); ?></td></tr>
<tr style="font-weight: bold;font-size: 1em;"><td colspan="2">Total Number of ICOs: <? echo $numofico2016; ?></td></tr>
</table>
<h3 style="margin-bottom: 10px;margin-top:30px;">Top Ten ICOs of 2016</h3>
<table><tr style="font-weight: bold;"><td>Position</td><td width="10"></td><td>Project</td><td width="10"><td align="right">Total Raised</td></tr>
<?
 $pos = 1;
 while ($topten = mysqli_fetch_assoc($topten2016)) 
      {
        echo '<tr><td>'.$pos.'</td><td></td><td>'.($topten['Link']?'<a href="'.$topten['Link'].'" target="_blank" nofollow>'.$topten['ICOName'].'</a>':$topten['ICOName']).'</td><td></td><td align="right">$'.number_format($topten['Total'],0,'.',',').'</td></tr>';
        $pos++;
      }
?>
</table>
</td></tr>
<tr><td valign="top" height="200">                       
<div id="chart1" style="height:300px;width:900px;"></div>
</td>
<td width="20"></td>

</tr>
<tr><td align="right" valign="top" style="padding: 10px 10px 0px 0px;font-size: 0.8em;"><i><b>Note: Figures do not include "The DAO" that raised $168M but was refunded after the smart contract was hacked</b><br>Totals raised are grouped by Month/Year of start date but are valued using exchange rate at ICO end date. Data correct on 18th June 2017 14:00 UTC</i></td></tr>
</table>
<br><br>
<hr>

<table><tr><td><h2 style="font-weight: bold;font-size: 2em;margin-bottom: 20px;">ICO Stats for 2017</h2></td><td width="10"></td>
<td valign="top" rowspan="3">
<button style="margin: 10px 0px 10px 0px;" id="dwnl-chart-2" onClick="$.fn.jqplotSaveImage('chart2', 'Coinschedule ICO Stats 2017')">Download Chart</button>
<table>
<tr style="font-weight: bold;font-size: 2em;"><td>Total Raised:</td><td>$<? echo number_format($year2total,0,'.',','); ?></td></tr>
<tr style="font-weight: bold;font-size: 1em;"><td colspan="2">Total Number of ICOs: <? echo $numofico2017; ?></td></tr>
</table>
<h3 style="margin-bottom: 10px;margin-top:30px;">Top Ten ICOs of 2017</h3>
<table><tr style="font-weight: bold;"><td>Position</td><td width="10"></td><td>Project</td><td width="10"><td align="right">Total Raised</td></tr>
<?
 $pos = 1;
 while ($topten = mysqli_fetch_assoc($topten2017)) 
      {
        echo '<tr><td>'.$pos.'</td><td></td><td>'.($topten['Link']?'<a href="'.$topten['Link'].'" target="_blank" nofollow>'.$topten['ICOName'].'</a>':$topten['ICOName']).'</td><td></td><td align="right">$'.number_format($topten['Total'],0,'.',',').'</td></tr>';
        $pos++;
      }
?>
</table>
</td>
<tr><td valign="top" height="200">                       
<div id="chart2" style="height:300px;width:900px;"></div>
</td>
<td width="20"></td>

</tr>
<tr><td align="right" valign="top" style="padding: 10px 10px 0px 0px;font-size: 0.8em;"><i>Totals raised are grouped by Month/Year of start date but are valued using exchange rate at ICO end date. Data correct on 18th June 2017 14:00 UTC</i></td></tr>
</table>



<script>
$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        var s1 = <? echo "[$year1[1], $year1[2], $year1[3], $year1[4], $year1[5], $year1[6], $year1[7], $year1[8], $year1[9], $year1[10], $year1[11], $year1[12]]"; ?>;
        var ticks = ['Jan', 'Feb', 'Mar', 'Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
         
        plot1 = $.jqplot('chart1', [s1], {
            // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
           
            animate: !$.jqplot.use_excanvas,
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: false }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                },
                yaxis:{
                  label:'Total Raised',
                  labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                  tickOptions: { formatString:"$%'d "}
                }
            },
            highlighter: { show: false }
        });
     
        $('#chart1').bind('jqplotDataClick', 
            function (ev, seriesIndex, pointIndex, data) {
                $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        ); 

    
    
    $.jqplot.config.enablePlugins = true;
        var s1 = <? echo "[$year2[1], $year2[2], $year2[3], $year2[4], $year2[5], $year2[6], $year2[7], $year2[8], $year2[9], $year2[10], $year2[11], $year2[12]]"; ?>;
        var ticks = ['Jan', 'Feb', 'Mar', 'Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
         
        plot1 = $.jqplot('chart2', [s1], {
            // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
            
            animate: !$.jqplot.use_excanvas,
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: false }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                },
                yaxis:{
                  label:'Total Raised',
                  labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                  tickOptions: { formatString:"$%'d "}
                }
            },
            highlighter: { show: false }
        });
     
        $('#chart1').bind('jqplotDataClick', 
            function (ev, seriesIndex, pointIndex, data) {
                $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        ); 
    }); 
    
    $.fn.jqplotSaveImage = function(id, filename) {

        var imgData = $('#'+id).jqplotToImageStr({});
        if (imgData) {
            download(imgData, filename+'.png', "image/png");
        }
    }; 
</script>
</body>
</html>


