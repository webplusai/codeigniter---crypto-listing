<style>
html,body {margin:0px;padding: 0px;}

.topnav {
  overflow: hidden;
  background-color: #f2f2f2;
  border-bottom: 1px solid #D9E4E6;  
}

.topnav a {
  float: left;
  display: block;
  color: #000;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  border-right: 1px solid #D9E4E6;  
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav .icon {
  display: none;
}


@media screen and (max-width: 800px) {
  .topnav a:not(:first-child) {display: none;}
  .topnav a.icon {
    float: right;
    display: block;
  }
}

@media screen and (max-width: 800px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }

}
</style>
<div style="vertical-align:middle;display:table-cell;float: left;border-bottom: 1px solid #D9E4E6;border-right: 1px solid #D9E4E6;font-size: 1.5em;height: 48px;"><img src="public/images/logo48.png" style="float: left;padding-left: 10px;"><div style="vertical-align:middle;display:table-cell;float: none;padding-left: 5px;padding-right: 30px;height: 48px;"><b>Coinschedule Admin</b></div></div>
<div class="topnav" id="myTopnav">
  <a href="submissions.php">Submissions</a>
  <a href="index.php">Crowdfunds & ICOs</a>
  <a href="marketing.php">Marketing</a>
  <a href="results.php">Results</a>
  <a href="projects.php">Projects</a>
  <a href="events.php">Events</a>
  <a href="users.php">Users</a>
  <a href="holdings.php">Holdings</a>
  <a href="siteadmin.php">Siteadmin</a>
  <a href="apikeyrequests.php">Apikeys</a>
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div>

<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>