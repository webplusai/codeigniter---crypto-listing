<? 
  require('includes/global_icons.php');
  require('includes/global_data.php');
  require('includes/stats_data.php');
?>
<!doctype html>
<html lang="en">
<head>
  <? require ('includes/global_meta.php'); ?>
  <title>Coinschedule - Cryptocurrency ICO Statistics</title>
  <link rel="stylesheet" type="text/css" href="css/css_global.php" />
  <link rel="stylesheet" type="text/css" href="css/css_stats.php" />
  <script src="js/site.js"></script>
  <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="https://www.coinschedule.com/lib/jqplot/excanvas.js"></script><![endif]-->
  <script language="javascript" type="text/javascript" src="js/stats.js?12a"></script>
  <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var dataArray = [
          <?
            $array = "";
            while ($icocat = mysqli_fetch_array($icocats))
            {
              $array.="\n['".$icocat['ProjCatName']."',".$icocat['Total']."],";
            }
            echo substr($array, 0, -1);
          ?>
          ]; 
          
        var total = getTotal(dataArray);    
                                
        // Adding tooltip column  
      	for (var i = 0; i < dataArray.length; i++) {
          dataArray[i].push(customTooltip(dataArray[i][0], dataArray[i][1], total));
        }            
        
              
        // Changing legend  
      	for (var i = 0; i < dataArray.length; i++) {
          dataArray[i][0] = dataArray[i][0] + " " + ((dataArray[i][1] / total) * 100).toFixed(1) + "%" + " ($" + 
              		addCommas(dataArray[i][1]) +')'; 
        }                    
       
         // Column names
        dataArray.unshift(['Category', 'Total', 'Tooltip']);
        
        var data = google.visualization.arrayToDataTable(dataArray);    
        
        // Setting role tooltip
        data.setColumnProperty(2, 'role', 'tooltip');
        data.setColumnProperty(2, 'html', true);                
       
        var formatter = new google.visualization.NumberFormat({ prefix: '$',fractionDigits:0});
        formatter.format(data, 1);

        var options = {
          is3D: false,
          pieHole: 0.4,
          pieStartAngle: 180,         
          sliceVisibilityThreshold: 0,     
          chartArea:{left:0,top:10,width:'75%',height:'90%'},
          pieSliceTextStyle:{fontName:'Open Sans',fontSize:13},
          legend:{<? echo $is_mobile?"position: 'top',maxLines:10,":""; ?>textStyle: {fontName:'Open Sans',fontSize:13}},
          tooltip:{isHtml: true,textStyle: {fontName:'Open Sans',fontSize:13}}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options); 
        
                       
      }  
      
      function customTooltip(name, value, total) {
    return '<div style="padding: 5px">' + name + '<br><b>' + ((value/total) * 100).toFixed(1) + '%</b>&nbsp;($' + addCommas(value) + ')</div>';
      }
      
      function getTotal(dataArray) {
          var total = 0;
         	for (var i = 0; i < dataArray.length; i++) {
            total += dataArray[i][1];
          }
          return total;
      }            
      
      function addCommas(nStr) {
          nStr += '';
          var x = nStr.split('.');
          var x1 = x[0];
          var x2 = x.length > 1 ? '.' + x[1] : '';
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(x1)) {
              x1 = x1.replace(rgx, '$1' + ',' + '$2');
          }
          return x1 + x2;
      }
      
    
  </script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>   
      <h1>Cryptocurrency ICO Stats <? echo $year; ?><div style="font-size: 0.5em;float: right;margin-top: 10px;">Year: <select onChange="location='stats.php?year=' + this.options[this.selectedIndex].value;"><option value="2017">2017</option><option value="2016" <? echo $year==2016?'selected':''; ?>>2016</option></select></div></h1>
      
      <div class="raisedchart inline top">
        <div id="chart1" class="barchart"></div>
        <div style="padding: 10px 10px 0px 0px;font-size: 0.8em;"><i><? if ($year==2016) { ?><b>Note: Figures do not include "The DAO" that raised $168M but was refunded after the smart contract was hacked</b><br><? } ?>Totals raised are grouped by the ICO closing date and are valued using BTC exchange rate at that time. Data correct on 16th October 2017 14:00 UTC</i></div>
      </div>
      <div class="inline">
        <table><tr style="font-weight: bold;font-size: 2em;"><td>Total:</td><td width="5"></td><td>$<? echo number_format($yeartotal,0,'.',','); ?></td></tr><tr style="font-weight: bold;font-size: 1em;"><td colspan="3">Total Number of ICOs: <? echo $numofico; ?></td></tr></table>
        
        
        <h3>Top Ten ICOs of <? echo $year; ?></h3>
        <table><thead><tr><th>Position</th><th width="10"></th><th width="150">Project</th><th align="right">Total Raised</th></tr></thead>
        <?
         $pos = 1;
         while ($top = mysqli_fetch_assoc($topten)) 
              {
                echo '<tr><td>'.$pos.'</td><td></td><td>'.($top['Link']?'<a href="'.$top['Link'].'" target="_blank" nofollow>'.$top['ICOName'].'</a>':$top['ICOName']).'</td><td align="right">$'.number_format($top['Total'],0,'.',',').'</td></tr>';
                $pos++;
              }
        ?>
        </table>
      </div>
      <hr>
      <h2 style="font-weight: bold;padding-bottom: 10px;">ICOs by Category <? echo $year; ?></h2>
      <div id="piechart_3d" style="width: 100%; height: 450px;"></div>
      <script>
          $(document).ready(function(){
              $.jqplot.config.enablePlugins = true;
              var s1 = <? echo "[$monthfig[1], $monthfig[2], $monthfig[3], $monthfig[4], $monthfig[5], $monthfig[6], $monthfig[7], $monthfig[8], $monthfig[9], $monthfig[10], $monthfig[11], $monthfig[12]]"; ?>;
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
                        <? echo $is_mobile?'':"label:'Total Raised',"; ?>
                        labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                        tickOptions: { <? echo $is_mobile?"formatter: function(format, value) { return '$'+(value/1000000)+'M'; }":'formatString:"$%\'d "'; ?>}
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
        </script>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
  <script>
    $.ajax({
      url: "//apps.cointraffic.io/js/?wkey=cNhyIu",
      dataType: "script"
    });
</script>
</body>
</html>