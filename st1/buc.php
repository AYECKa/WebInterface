<?php
session_start();
error_reporting(0);
$read = $_SESSION['read_st'];
$device_IP = $_SESSION['device_IP_st'];
$ver = $_SESSION['stVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
$modcod = $_SESSION['modcod'];

require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
modcodarray();


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

    $bucPowerState = substr(snmp2_get($device_IP,"public",$oid['bucPowerState']), 9);
    $buc10MHzOutput = substr(snmp2_get($device_IP,"public",$oid['buc10MHzOutput']), 9);

}

//write

if (isset($_POST['write']) AND $read == "read"){

    snmp2_set($device_IP, "private", $oid['bucPowerState'], $type['bucPowerState'], $_POST['bucPowerState']);
    snmp2_set($device_IP, "private", $oid['buc10MHzOutput'], $type['buc10MHzOutput'], $_POST['buc10MHzOutput']);


    //Read
    $bucPowerState = substr(snmp2_get($device_IP,"public",$oid['bucPowerState']), 9);
    $buc10MHzOutput = substr(snmp2_get($device_IP,"public",$oid['buc10MHzOutput']), 9);
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
$active = "buc";
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
                        <td>Power</td>
                        <td>
                            <select class="form-control input-sm" name="bucPowerState">
                                <option value="0" <?php if($bucPowerState == "0"){echo "selected";}?>>Off</option>
                                <option value="1" <?php if($bucPowerState == "1"){echo "selected";}?>>On</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>10Mhz Output</td>
                        <td>
                            <select class="form-control input-sm" name="buc10MHzOutput">
                                <option value="0" <?php if($buc10MHzOutput == "0"){echo "selected";}?>>Off</option>
                                <option value="1" <?php if($buc10MHzOutput == "1"){echo "selected";}?>>On</option>
                            </select>
                        </td>
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