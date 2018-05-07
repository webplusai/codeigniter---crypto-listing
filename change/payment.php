<?phpphp
session_start();
require_once "../config.php";
require_once BASEPATH . "/lib/cake.php";

if (isset($_GET['subID']) && $_GET['subID']) {
    $sql = "SELECT * FROM `tbl_submissions` WHERE (SubProjID IS NULL OR SubProjID = 0) AND `SubID` = :SubID";
    $statement = $connection->prepare($sql);
    $statement->bind(['SubID' => $_GET['subID']], ['SubID' => 'integer']);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $submission = $statement->fetch('assoc');
        if ($submission['SubCoinName']) {
            $sql = "SELECT * FROM `tbl_payments` WHERE `SubID` = :SubID AND `PayStatus` = '1'";
            $statement = $connection->prepare($sql);
            $statement->bind(['SubID' => $_GET['subID']], ['SubID' => 'integer']);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                $payment = $statement->fetch('assoc');
                $connection->update('tbl_payments', array('PayStatus' => '0'), array('PayID' => $payment['PayID']));
                $hash = $submission['SubHashCode'];
                $secret = $payment['PaySecret'];
                $query = "h=$hash&secret=$secret";
                header('Location: https://www.coinschedule.com/payment.php?'.$query);
            } else {
                $errMessage = 'Something went wrong with payment address';
            }
        } else {
            $errMessage = 'Submission Incomplete';
        }
    } else {
        $errMessage = 'Invalid Submission ID';
    }
}

?>
<html>
<head><title>Coinschdule Admin - Payment</title></head>
<body>
<link href="public/css/list.css" rel="stylesheet">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<?php require_once('menu.php'); ?>
<div style="padding: 15px;">
<h1 style="display:inline;">Payment</h1>

    <form style="margin-top: 30px;" class="form-inline" action="https://www.coinschedule.com/change/payment.php" method="GET">
        <?phpphp if (isset($errMessage) && $errMessage) : ?>
        <p style="color: red"><?php=$errMessage?></p>
        <?phpphp endif; ?>
        <div class="form-group">
            <label for="subID">Submission ID</label>
            <input type="text" class="form-control" name="subID" id="subID">
        </div>
        <button name="submit" value="1" type="submit" class="btn btn-default">Submit</button>
    </form>

</div>
<?php require_once('footer.php'); ?>
</body>
</html>