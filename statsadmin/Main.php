<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/style.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="<?=$coinScheduleBaseUrl?>css/style.css"  media="screen,projection"/>
    <!--Bootstrap-->
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- <script type="text/javascript" src="js/dashboard.js"></script> -->
    <!--Bootstrap-->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
        <div class="form_body_signin">
            <div class="row">
                <div class="col s12">
                    <h4 class="form_header">Login</h4>
                    <form method="post" action="signup.php" id="signin_form">
                        <div class="row">
                            <div class="input-field col s12 form_input">
                                <input  type="email" name="email" class="validate" required>
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 form_input">
                                <input  type="password" name="password" class="validate" required>
                                <label for="password">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 from_input">
                                <button type="submit" class="waves-effect waves-light btn-large form_input" id="">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?=$coinScheduleBaseUrl?>js/materialize.min.js"></script>
<script type="text/javascript" src="<?=$coinScheduleBaseUrl?>js/dashboard.js"></script>
</body>
</html>
