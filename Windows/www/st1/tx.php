<?php
session_start();
error_reporting(0);
$read = $_SESSION['read_st'];
$device_IP = $_SESSION['device_IP_st'];
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

    // system info
    include_once('info_function.php');
//

    $txFrequency = substr(snmp2_get($device_IP,"public",$oid['txFrequency']), 9)/1000;
    $txPowerLevel = substr(snmp2_get($device_IP,"public",$oid['txPowerLevel']), 9)/10;
    $txPowerState = substr(snmp2_get($device_IP,"public",$oid['txPowerState']), 9);
    $txPowerStateInitial = substr(snmp2_get($device_IP,"public",$oid['txPowerStateInitial']), 9);
}

//write

if (isset($_POST['write']) AND $read == "read"){

    snmp2_set($device_IP, "private", $oid['txFrequency'], $type['txFrequency'], $_POST['txFrequency']*1000);
    snmp2_set($device_IP, "private", $oid['txPowerLevel'], $type['txPowerLevel'], $_POST['txPowerLevel']*10);

    if($_POST['txPowerState'] == "1"){
        snmp2_set($device_IP, "private", $oid['txPowerState'], $type['txPowerState'], "1");
    } else {
        snmp2_set($device_IP, "private", $oid['txPowerState'], $type['txPowerState'], "0");
    }

    if($_POST['txPowerStateInitial'] == "1"){
        snmp2_set($device_IP, "private", $oid['txPowerStateInitial'], $type['txPowerStateInitial'], "1");
    } else {
        snmp2_set($device_IP, "private", $oid['txPowerStateInitial'], $type['txPowerStateInitial'], "0");
    }

    //read
    $txFrequency = substr(snmp2_get($device_IP,"public",$oid['txFrequency']), 9)/1000;
    $txPowerLevel = substr(snmp2_get($device_IP,"public",$oid['txPowerLevel']), 9)/10;
    $txPowerState = substr(snmp2_get($device_IP,"public",$oid['txPowerState']), 9);
    $txPowerStateInitial = substr(snmp2_get($device_IP,"public",$oid['txPowerStateInitial']), 9);
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
<?php
$active = "tx";
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
        <?php
        include_once('info.php');
        ?>

        <hr>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 text-left">
                <table class="table table-condensed table-hover">
                    <tr>
                        <td>Tx Frequency</td>
                        <td><input class="form-control input-sm" type="text" value="<?php echo $txFrequency;?>" name="txFrequency"></td>
                        <td>Mhz</td>
                    </tr>
                    <tr>
                        <td>Tx Power Level</td>
                        <td><input class="form-control input-sm" type="text" value="<?php echo $txPowerLevel; ?>" name="txPowerLevel"></td>
                        <td>dbm</td>
                    </tr>
                    <tr>
                        <td>TX Output Power</td>
                        <td>
                            <select class="form-control input-sm" name="txPowerState">
                                <option value="0" <?php if($txPowerState == "0"){echo "selected";}?>>Off</option>
                                <option value="1" <?php if($txPowerState == "1"){echo "selected";}?>>On</option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>TX Output Power On Powercycle</td>
                        <td>
                            <select class="form-control input-sm" name="txPowerStateInitial">
                                <option value="0" <?php if($txPowerStateInitial == "0"){echo "selected";}?>>Off</option>
                                <option value="1" <?php if($txPowerStateInitial == "1"){echo "selected";}?>>User-Defined</option>
                            </select>
                        </td>
                        <td></td>
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