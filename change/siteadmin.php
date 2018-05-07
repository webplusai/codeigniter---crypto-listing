<html>

<head><title>Coinschdule Admin - Siteadmin</title></head>

<style>
	.alert-success {
	    background-color: #dff0d8;
	    border-color: #d0e9c6;
	    color: #3c763d;
	}

	.alert {
	    position: relative;
	    padding: .75rem 1.25rem;
	    margin-bottom: 1rem;
	    border: 1px solid transparent;
	    border-radius: .25rem;
	}
	button {
		vertical-align: top;width: 160px;height: 40px;display:inline;margin: 4px 0px 0px 40px;
	}
</style>
<body>

<link href="public/css/list.css" rel="stylesheet">

<?php require_once('menu.php'); ?>

<div style="padding: 15px;">

<h1 style="display:inline;">Siteadmin</h1>

<?php

session_start();

require_once "config.php";
require_once "lib/cake.php";




$msg = '';
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	switch ($mode) {
		case 1:
			$msg = 'Cleared cache (twig)';
			break;
		case 2:
			$msg = 'Flushed cache (redis)';
			break;
		case 3:
			$msg = 'Set team groups';
			break;
		case 4:
			$msg = 'Convert Base64';
			break;
		case 5:
			$msg = 'set PackageID';
			setPackageID($connection);
			break;
		default:
			break;
	}
}

function setPackageID($connection) {
	$connection->execute('Update `tbl_projects` Set ProjPackageID = CASE WHEN `ProjPlatinum`=1 THEN 5 WHEN `ProjPackage` = 2 THEN 4  WHEN `ProjPackage` = 1 THEN 3 WHEN `ProjHighlighted` = 1 THEN 2 ELSE 1 END');
}

?>

<hr>
<button onclick="location.href='<?=BASE_URL?>cron/setTeamGroup';">Set Team Groups</button>
<button onclick="location.href='<?=BASE_URL?>cron/adminBase64ToImage';">Convert Base64</button>
<button onclick="location.href='<?=BASE_URL?>cron/clear_twig';">Clear Cache (twig)</button>
<button onclick="location.href='<?=BASE_URL?>cron/clean_cache';">Flush All Cache (redis)</button>
<button onclick="location.href='<?=BASE_URL?>change/siteadmin.php?mode=5';">Set PackageID</button>

<?php if (!empty($msg)) : ?>
<div style="margin-top: 30px;">
	<div class="alert alert-success">
		<p><?php echo $msg; ?></p>
	</div>
</div>
<?php endif; ?>

</div>

<?php require_once('footer.php'); ?>

</body>

</html>