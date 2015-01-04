<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP_tc'];
$read = $_SESSION['read_tc'];
$ver = $_SESSION['tcVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];

require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();


if (isset($_POST['read']) OR $read == "read"){
    if(isset($_POST['read'])){
        $device_IP = $_POST['device_IP'];
    }

    $_SESSION['device_IP_tc'] = $device_IP;
    $_SESSION['read_tc'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);

//  Read
    $rxChannelOpMode = substr(snmp2_get($device_IP,"public",$oid['rxChannelOpMode']), 9);
    $rxSwitchMode =  substr(snmp2_get($device_IP,"public",$oid['rxSwitchMode']), 9);
    $rxSwitchPeriod =  substr(snmp2_get($device_IP,"public",$oid['rxSwitchPeriod']), 9);
    $rxActive = substr(snmp2_get($device_IP,"public",$oid['rxActive']), 9);


}

//    Write
if (isset($_POST['write']) AND $read == "read"){
    snmp2_set($device_IP, "private", $oid['rxChannelOpMode'], $type['rxChannelOpMode'], $_POST['rxChannelOpMode']);
    snmp2_set($device_IP, "private", $oid['rxSwitchMode'], $type['rxSwitchMode'], $_POST['rxSwitchMode']);
    snmp2_set($device_IP, "private", $oid['rxSwitchPeriod'], $type['rxSwitchPeriod'], $_POST['rxSwitchPeriod']);
    snmp2_set($device_IP, "private", $oid['rxActive'], $type['rxActive'], $_POST['rxActive']);



    // read
    $rxChannelOpMode = substr(snmp2_get($device_IP,"public",$oid['rxChannelOpMode']), 9);
    $rxSwitchMode =  substr(snmp2_get($device_IP,"public",$oid['rxSwitchMode']), 9);
    $rxSwitchPeriod =  substr(snmp2_get($device_IP,"public",$oid['rxSwitchPeriod']), 9);
    $rxActive = substr(snmp2_get($device_IP,"public",$oid['rxActive']), 9);


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
            padding-top: 0px;
            padding-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="well well-sm" style="margin-bottom: 0px;">
    <div class="container">
        <div class="col-lg-1"><img src="../images/ayeckaLogo.png" class="pull-left"></div>
        <div class="col-lg-10 text-center">
            <br><h4><strong><a href="http://www.ayecka.com/TC1.html">TC1</a></strong> - Transport stream Media converter</h4>
        </div>
        <div class="col-lg-1"><img src="../images/slogen2.png" class="pull-right"></div>
    </div>
</div>

<div class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li> <a href="index.php">Status</a></li>
                <li> | </li>
                <li> <a href="rf.php?rf=1">RF1</a></li>
                <li> | </li>
                <li> <a href="rf.php?rf=2">RF2</a></li>
                <li> | </li>
                <li class="active"> <a href="rf_control.php">RF Control</a></li>
                <li> | </li>
                <li> <a href="egress.php">Egress</a></li>
                <li> | </li>
                <li> <a href="network.php">Network</a></li>
                <li> | </li>
                <li> <a href="images.php">Images</a></li>
                <li> | </li>
                <li> <a href="system.php">System</a></li>
                <li> | </li>
                <li> <a href="http://www.ayecka.com/Files/TC1_UserManual_V1.0.pdf" target="_blank">TC1 User Manual</a></li>
            </ul>
        </div>
    </div>
</div>
<!--PageBody-->
<!--end Page Body-->
<form method="post" class="form-inline">
    <div class="well well-sm text-center">

        <div class="form-group">
            <input type="text" class="form-control input-sm" value="<?php echo $device_IP;?>" name="device_IP" placeholder="Device IP Address">
        </div>
        <button type="submit" class="btn btn-success btn-sm" name="read">Read From Device</button>
        <button type="submit" class="btn btn-success btn-sm" name="write">Write To Device</button>
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
        <hr>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-left">
                <table class="table table-hover table-condensed">
                    <tbody>
                    <tr>
                        <td>Rx Switching Mode</td>
                        <td>
                            <label>
                                <input type="radio" value="0" name="rxSwitchMode"  <?php if($rxSwitchMode == "0"){echo "checked";}?>>
                                Auto
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="radio" value="1" name="rxSwitchMode"  <?php if($rxSwitchMode == "1"){echo "checked";}?>>
                                Manual
                            </label>
                        </td>
                        <td>
                            Switch Period
                            <div class="form-group">
                                <input class="form-control input-sm" type="number" name="rxSwitchPeriod" max="120" min="0" step="1" value="<?php echo $rxSwitchPeriod;?>">
                            </div>sec
                        </td>
                    </tr>
                    <tr>
                        <td>Active Rx Channel</td>
                        <td>
                            <label>
                                <input type="radio" value="1" name="rxActive"  <?php if($rxActive == "1"){echo "checked";}?>>
                                Rx1
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="radio" value="2" name="rxActive"  <?php if($rxActive == "2"){echo "checked";}?>>
                                Rx2
                            </label>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Rx Channel Operation Mode</td>
                        <td>
                            <label>
                                <input type="radio" value="0" name="rxChannelOpMode"  <?php if($rxChannelOpMode == "0"){echo "checked";}?>>
                                Single
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="radio" value="1" name="rxChannelOpMode"  <?php if($rxChannelOpMode == "1"){echo "checked";}?>>
                                Dual
                            </label>
                        </td>
                        <td></td>
                    </tr>


                    </tbody>
                </table>
            </div>
</form>
<div class="col-md-3"></div>
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