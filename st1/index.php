<?php
session_start();
error_reporting(0);
$read = $_SESSION['read_st'];
$device_IP = $_SESSION['device_IP_st'];
$ver = $_SESSION['stVer'];
$refreshTime = $_SESSION['refreshTime'];

$oid = $_SESSION['oid'];
$modcod = $_SESSION['modcod'];
$rolloff = $_SESSION['rolloff'];
require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
modcodarray();
rolloff();

if(isset($_POST['update'])){
    $_SESSION['refreshTime'] = $_POST['refreshTime'];
    $refreshTime = $_SESSION['refreshTime'];
}

if($refreshTime == ""){
    $refreshTime = 60;
}

if($_GET['admin']=="1"){}else{
    $url=$_SERVER['REQUEST_URI'];
    header("Refresh: $refreshTime; URL=$url");
}

$power = array('<span class="label label-danger">Off</span>','<span class="label label-success">On</span>');
if (isset($_POST['read']) OR $read == "read"){

    if(isset($_POST['read'])){
        $device_IP = $_POST['device_IP'];
    }

    $_SESSION['device_IP_st'] = $device_IP;
    $_SESSION['read_st'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);
// system info
    include_once('info_function.php');
    //
    $txPowerStateStatus = substr(snmp2_get($device_IP,"public",$oid['txPowerStateStatus']), 9);
    $txFrequencyStatus = substr(snmp2_get($device_IP,"public",$oid['txFrequencyStatus']), 9)/1000;
    $modInputBitrateStatus = substr(snmp2_get($device_IP,"public",$oid['modInputBitrateStatus']), 9)/1000;
    $modOutputBitrateStatus = substr(snmp2_get($device_IP,"public",$oid['modOutputBitrateStatus']), 9)/1000;
    $modModcodStatus = substr(snmp2_get($device_IP,"public",$oid['modModcodStatus']), 9);
    $modRolloffStatus = substr(snmp2_get($device_IP,"public",$oid['modRolloffStatus']), 9);

}

?>
<!doctype html>

<html>

<head>
    <title>Ayecka Web Interface</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            padding-top: 0;
            padding-bottom: 20px;
        }
    </style>
</head>

<body>

<?php
$active = "index";
include_once('header.php');
?>

<!--PageBody-->
<!--end Page Body-->
<form method="post" class="form-inline">
    <div class="well well-sm text-center">

        <div class="form-group">
            <input type="text" class="form-control input-sm" value="<?php echo $device_IP;?>" name="device_IP" placeholder="Device IP Address">
        </div>
        <button type="submit" class="btn btn-success btn-sm" name="read">Read From Device</button>
        <button type="submit" class="btn btn-success btn-sm" disabled name="write">Write To Device</button>
    </div>
    <div class="well well-sm text-center">
        <div class="form-group">FPGA<input type="text" class="form-control input-sm" value="<?php echo $fpgaVersion; ?>" name="fpgaVersion" readonly>
        </div>
        <div class="form-group">SW<input type="text" class="form-control input-sm" value="<?php echo $softwareVersion; ?>" name="softwareVersion" readonly>
        </div>
        <div class="form-group">HW<input type="text" class="form-control input-sm" value="<?php echo $hardwareVersion; ?>" name="hardwareVersion" readonly>
        </div>
        <div class="form-group">SN<input type="text" class="form-control input-sm" value="<?php echo $serialNumber; ?>" name="serialNumber" readonly>
        </div>
        <?php
        include_once('info.php');
        ?>

        <hr>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 text-left">
                <table class="table table-condensed table-hover">
                    <tr>
                        <td>Power State</td>
                        <td><?php echo $power[$txPowerStateStatus]; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Center Frequency</td>
                        <td><input class="form-control input-sm" type="text" value="<?php  echo $txFrequencyStatus;?>" readonly></td>
                        <td>Mhz</td>
                    </tr>
                    <tr>
                        <td>Input Bitrate</td>
                        <td><input class="form-control input-sm" type="text" value="<?php  echo $modInputBitrateStatus;?>" readonly></td>
                        <td>Kbps</td>
                    </tr>
                    <tr>
                        <td>Output Bitrate</td>
                        <td><input class="form-control input-sm" type="text" value="<?php  echo $modOutputBitrateStatus;?>" readonly></td>
                        <td>Kbps</td>
                    </tr>
                    <tr>
                        <td>MODCOD</td>
                        <td><input class="form-control input-sm" type="text" value="<?php echo $modcod[$modModcodStatus]; ?>" readonly></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Rolloff</td>
                        <td><input class="form-control input-sm" type="text" value="<?php echo $rolloff[$modRolloffStatus];?>" readonly>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
</form>
<div class="col-md-4"></div>
<form method="post">
    This page automatically refreshes every
    <div class="form-group">
        <input class="form-control input-sm" type="number" min="10" max="360" name="refreshTime" value="<?php echo $refreshTime;?>">
    </div> Sec
    <button type="submit" class="btn btn-success btn-xs" name="update">Update</button>
    </div>
    <div class="container text-left">
        <hr>
        <footer>
            <span class="pull-right">  <?php echo "Version number: ".$ver;?></span>
            &copy; <a href="http://www.ayecka.com">Ayecka</a> Comunnication System</footer>
    </div>
</form>
<!-- /container -->
</body>

</html>