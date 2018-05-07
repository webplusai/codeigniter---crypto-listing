<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/style.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/dataTables.bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/dataTables.jqueryui.min.css">
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/dataTables.material.min.css">
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="<?=$coinScheduleBaseUrl?>js/dashboard.js"></script>
    <script type="text/javascript" src="<?=$coinScheduleBaseUrl?>js/materialize.min.js"></script>
    <script type="text/javascript" src="<?=$coinScheduleBaseUrl?>js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?=$coinScheduleBaseUrl?>js/jquery.dataTables.js"></script>
<!--    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"> </script>-->
<!--    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.material.min.js"></script>-->
</head>
<body class="dashboard_body">
    <div class="navbar_div">
        <div class="side_navbar">
            <ul class="collapsible collapsible-accordion">
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal main_heading single_row" id="dashboard_heading">Single Row</a>
                </li>
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal main_heading multiple_row" id="dashboard_heading">Mutiple With Csv</a>
                </li>
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal main_heading all_results" id="dashboard_heading">All Results</a>
                </li>
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal main_heading years_result" id="2018">2018 Results</a>
                </li>
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal main_heading years_result" id="2017">2017 Results</a>
                </li>
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal main_heading years_result" id="2016">2016 Results</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="content_div" >
        <div class="content_header">
            <h5 id="content_heading">Ico Stats</h5>
        </div>
        <div class="result_content" >

        </div>
    </div>
</body>
</html>