<? 
  require('includes/global_icons.php'); 
  require('includes/global_data.php'); 
  require('includes/icos_data.php');
?>
<!doctype html>
<html lang="en">
<head>
  <? require ('includes/global_meta.php'); ?>
  <title>Coinschedule - ICO Results - List of ICOs</title>
    <link rel="stylesheet" type="text/css" href="css/css_global.php" />
    <link rel="stylesheet" type="text/css" href="css/css_icos.php?5675" />
  <script async src="js/site.js"></script>
</head>
<body>
  <? require ('includes/global_navbar.php'); ?>
  <div class="container">
    <div class="header"></div>  
    <div class="main">
      <section>
      <h1>ICO Results</h1>
      This page shows the results of Cryptocurrency ICOs and is updated regularly. Note that the total raised information is provided by the icos themselves and not independently verified by Coinschedule.
      <table class="dataTable list-table" id="tbl_icos">
      <thead><tr><th style="width:600px;">Name</th><th style="width:250px;">Category</th><th style="width:250px;">Link</th><th style="min-width:75px;width:75px;">Ended On</th><th style="text-align:right;padding-right: 20px;width:250px;">Total Raised</th></tr></thead>
      <tbody>
      <?
         while ($ico = mysqli_fetch_array($results))
        {
          $link="";
          
          if ($ico['Link'])
          {
            $link = "onClick=\"window.open('".$ico['Link']."')\"";
          }
        
          echo '<tr '.$link.'><td>'.$ico['ICOName'].'</td><td>'.$ico['ProjCatName'].'</td><td>'.$ico['Link'].'</td><td>'.$ico['EndDate'].'</td><td>$'.number_format($ico['TotalUSD'],0,'.',',').'</td></tr>';
        }
      ?>
      </tbody>
      </table>
      <br>
      <i>Totals raised for ICOs are valued using BTC exchange rate at that time. Data correct on 16th October 2017 14:00 UTC</i>
      </section>
    </div>
    <? require ('includes/global_footer.php'); ?>   
  </div>
  <script type="text/javascript" src="js/datatables.min.js"></script>
  <script>
    
    var ico_table;
  
     ico_table = $('#tbl_icos').DataTable( {
        rowReorder: false,
        paging: false,
        info: false,
        searching: true,
        order: [[ <? echo $is_mobile?'1':'3'; ?>, "desc" ]],
        columnDefs: [ { orderable: true, targets: '_all' } ]
      } );
  
  </script>
</body>
</html>