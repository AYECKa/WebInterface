<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP_st'];
$read = $_SESSION['read_st'];
$ver = $_SESSION['stVer'];

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

    $_SESSION['device_IP_st'] = $device_IP;
    $_SESSION['read_st'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);
//

    $vrrpEnabled =  substr(snmp2_get($device_IP,"public",$oid['vrrpEnabled']), 9);
    $vrrpIpAddress =  substr(snmp2_get($device_IP,"public",$oid['vrrpIpAddress']), 11);
    $vrrpIpNetmask =  substr(snmp2_get($device_IP,"public",$oid['vrrpIpNetmask']), 11);
    $vrrpVrid =  substr(snmp2_get($device_IP,"public",$oid['vrrpVrid']), 9);
    $vrrpPriority =  substr(snmp2_get($device_IP,"public",$oid['vrrpPriority']), 9);
    $vrrpAdvInterval =  substr(snmp2_get($device_IP,"public",$oid['vrrpAdvInterval']), 9);


}

//    Write
if (isset($_POST['write']) AND $read == "read"){
    snmp2_set($device_IP, "private", $oid['vrrpEnabled'], $type['vrrpEnabled'], $_POST['vrrpEnabled']);
    snmp2_set($device_IP, "private", $oid['vrrpIpAddress'], $type['vrrpIpAddress'], $_POST['vrrpIpAddress']);
    snmp2_set($device_IP, "private", $oid['vrrpIpNetmask'], $type['vrrpIpNetmask'], $_POST['vrrpIpNetmask']);
    snmp2_set($device_IP, "private", $oid['vrrpVrid'], $type['vrrpVrid'], $_POST['vrrpVrid']);
    snmp2_set($device_IP, "private", $oid['vrrpPriority'], $type['vrrpPriority'], $_POST['vrrpPriority']);
    snmp2_set($device_IP, "private", $oid['vrrpAdvInterval'], $type['vrrpAdvInterval'], $_POST['vrrpAdvInterval']);

    //read
    $vrrpEnabled =  substr(snmp2_get($device_IP,"public",$oid['vrrpEnabled']), 9);
    $vrrpIpAddress =  substr(snmp2_get($device_IP,"public",$oid['vrrpIpAddress']), 11);
    $vrrpIpNetmask =  substr(snmp2_get($device_IP,"public",$oid['vrrpIpNetmask']), 11);
    $vrrpVrid =  substr(snmp2_get($device_IP,"public",$oid['vrrpVrid']), 9);
    $vrrpPriority =  substr(snmp2_get($device_IP,"public",$oid['vrrpPriority']), 9);
    $vrrpAdvInterval =  substr(snmp2_get($device_IP,"public",$oid['vrrpAdvInterval']), 9);

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
            <br><h4><strong><a href="http://www.ayecka.com/ST1.html">ST1</a></strong> - Satellite Transmitter, IP over DVB-S2</h4>
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
                <li> <a href="tx.php">TX Configuration</a></li>
                <li> | </li>
                <li> <a href="modulator.php">Modulator Configuration</a></li>
                <li> | </li>
                <li> <a href="encapsulator.php">IP Encapsulator</a></li>
                <li> | </li>
                <li> <a href="buc.php">BUC Control</a></li>
                <li> | </li>
                <li> <a href="egress.php">Egress Configuration</a></li>
                <li> | </li>
                <li> <a href="network.php">Network</a></li>
                <li> | </li>
                <li class="active"> <a href="vrrp.php">VRRP</a></li>
                <li> | </li>
                <li> <a href="system.php">System</a></li>
                <li> | </li>
                <li> <a href="images.php">Images</a></li>
                <li> | </li>
                <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">ST1 User Manual</a></li>
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
            <div class="col-md-4"></div>
            <div class="col-md-4 text-left">
                <table class="table table-condensed table-hover">
                    <tr>
                        <td>VRRP Enabled</td>
                        <td>
                            <select class="form-control input-sm" name="vrrpEnabled">
                                <option value="0" <?php if($vrrpEnabled == 0){echo "selected";}?>>No</option>
                                <option value="1" <?php if($vrrpEnabled == 1){echo "selected";}?>>Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Virtual IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="vrrpIpAddress" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" value="<?php echo $vrrpIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Virtual Netmask</td>
                        <td><input class="form-control input-sm" type="text" name="vrrpIpNetmask" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" value="<?php echo $vrrpIpNetmask;?>"></td>
                    </tr>
                    <tr>
                        <td>Virtual Router ID</td>
                        <td><input class="form-control input-sm" type="number" min="1" max="255" name="vrrpVrid" value="<?php echo $vrrpVrid;?>"></td>
                    </tr>
                    <tr>
                        <td>Pioriry</td>
                        <td><input class="form-control input-sm" type="number" min="0" max="255" name="vrrpPriority" value="<?php echo $vrrpPriority;?>"></td>
                    </tr>
                    <tr>
                        <td>Advertisement Interval</td>
                        <td><input class="form-control input-sm" type="number" min="1" max="4294967" name="vrrpAdvInterval" value="<?php echo $vrrpAdvInterval;?>"></td>
                    </tr>
                </table>
            </div>
</form>
</div>
<div class="col-md-4"></div>

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