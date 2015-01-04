<?php
session_start();
error_reporting(0);
$read = $_SESSION['read_tc'];
$device_IP = $_SESSION['device_IP_tc'];
$ver = $_SESSION['tcVer'];
$refreshTime = $_SESSION['refreshTime'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
$modcod = $_SESSION['modcod'];
$pilot = $_SESSION['pilot'];
$tunerStatus = $_SESSION['tunerStatus'];
$rolloff = $_SESSION['rolloff'];
require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
modcodarray();
pilotarray();
tunerStatus();
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

//    RF1
    $tunerStatus1 = substr(snmp2_get($device_IP,"public",$oid['tunerStatus1']), 9);

    if($tunerStatus1 == "0" OR $_GET['admin']=="1"){
        $frequency1 = substr(snmp2_get($device_IP,"public",$oid['frequency1']), 9)/1000;
        $esno1 = substr(snmp2_get($device_IP,"public",$oid['esno1']), 9)/10;
        $powerLevel1 = substr(snmp2_get($device_IP,"public",$oid['powerLevel1']), 9)/10;
        $ber1 = substr(snmp2_get($device_IP,"public",$oid['ber1']), 9);
        $frequencyOffset1 = substr(snmp2_get($device_IP,"public",$oid['frequencyOffset1']), 9)/1000;
        $modcodStatus1 = substr(snmp2_get($device_IP,"public",$oid['modcodStatus1']), 9);
        $rollOffStatus1 = substr(snmp2_get($device_IP,"public",$oid['rollOffStatus1']), 9);
        $pilotsStatus1 = substr(snmp2_get($device_IP,"public",$oid['pilotsStatus1']), 9);

        $rx1CfgSet1Status = substr(snmp2_get($device_IP,"public",$oid['rx1CfgSet1Status']), 9);
        $rx1CfgSet2Status = substr(snmp2_get($device_IP,"public",$oid['rx1CfgSet2Status']), 9);

        if($rx1CfgSet1Status == 0){
            $rx1ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName1']), 9);
        }
        if($rx1CfgSet2Status == 0){
            $rx1ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName2']), 9);
        }

    }

//    RF2
    $tunerStatus2 = substr(snmp2_get($device_IP,"public",$oid['tunerStatus2']), 9);
    if($tunerStatus2 == "0" OR $_GET['admin']=="1"){
        $frequency3 = substr(snmp2_get($device_IP,"public",$oid['frequency3']), 9)/1000;
        $esno2 = substr(snmp2_get($device_IP,"public",$oid['esno2']), 9)/10;
        $powerLevel2 = substr(snmp2_get($device_IP,"public",$oid['powerLevel2']), 9)/10;
        $ber2 = substr(snmp2_get($device_IP,"public",$oid['ber2']), 9);
        $frequencyOffset2 = substr(snmp2_get($device_IP,"public",$oid['frequencyOffset2']), 9)/1000;
        $modcodStatus2 = substr(snmp2_get($device_IP,"public",$oid['modcodStatus2']), 9);
        $rollOffStatus2 = substr(snmp2_get($device_IP,"public",$oid['rollOffStatus2']), 9);
        $pilotsStatus2 = substr(snmp2_get($device_IP,"public",$oid['pilotsStatus2']), 9);

        $rx2CfgSet1Status = substr(snmp2_get($device_IP,"public",$oid['rx2CfgSet1Status']), 9);
        $rx2CfgSet2Status = substr(snmp2_get($device_IP,"public",$oid['rx2CfgSet2Status']), 9);

        if($rx2CfgSet1Status == 0){
            $rx2ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName3']), 9);
        }
        if($rx2CfgSet2Status == 0){
            $rx2ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName4']), 9);
        }
    }

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
                <li class="active"> <a href="index.php">Status</a></li>
                <li> | </li>
                <li> <a href="rf.php?rf=1">RF1</a></li>
                <li> | </li>
                <li> <a href="rf.php?rf=2">RF2</a></li>
                <li> | </li>
                <li> <a href="rf_control.php">RF Control</a></li>
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
        <hr>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3 text-left">
                <strong>RF1</strong> <?php echo $rx1ActiveProfile; ?>
                <table class="table table-hover table-condensed">
                    <tbody>
                    <tr>
                        <td>Lock</td>
                        <td><?php echo $tunerStatus[$tunerStatus1]; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Frequency</td>
                        <td>
                            <input type="text" class="form-control input-sm" value="<?php echo $frequency1; ?>" name="frequency1" readonly>
                        </td>
                        <td>Mhz</td>
                    </tr>
                    <tr>
                        <td>EsNo</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $esno1; ?>" name="esno1" readonly>
                        </td>
                        <td>db</td>
                    </tr>
                    <tr>
                        <td>Power</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $powerLevel1; ?>" name="powerLevel1" readonly>
                        </td>
                        <td>dbm</td>
                    </tr>
                    <tr>
                        <td>Demod BER</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $ber1; ?>" name="ber1" readonly>
                        </td>
                        <td>10e7</td>
                    </tr>
                    <tr>
                        <td>Offset</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $frequencyOffset1; ?>" name="frequencyOffset1" readonly>
                        </td>
                        <td>Khz</td>
                    </tr>
                    <tr>
                        <td>MODCOD</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $modcod[$modcodStatus1]; ?>" name="modcodStatus1" readonly>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Roll Off</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $rolloff[$rollOffStatus1]; ?>" name="rollOffStatus1" readonly>
                        </td>
                        <td>%</td>
                    </tr>
                    <tr>
                        <td>Pilot</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $pilot[$pilotsStatus1]; ?>" name="pilotsStatus1" readonly>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3 text-left">
                <strong>RF2</strong> <?php echo $rx2ActiveProfile; ?>
                <table class="table table-hover table-condensed">
                    <tbody>
                    <tr>
                        <td>Lock</td>
                        <td><?php echo $tunerStatus[$tunerStatus2]; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Frequency</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $frequency3; ?>" name="frequency3" readonly>
                        </td>
                        <td>Mhz</td>
                    </tr>
                    <tr>
                        <td>EsNo</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $esno2; ?>" name="esno2" readonly>
                        </td>
                        <td>db</td>
                    </tr>
                    <tr>
                        <td>Power</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $powerLevel2; ?>" name="powerLevel2" readonly>
                        </td>
                        <td>dbm</td>
                    </tr>
                    <tr>
                        <td>Demod BER</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $ber2; ?>" name="ber2" readonly>
                        </td>
                        <td>10e7</td>
                    </tr>
                    <tr>
                        <td>Offset</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $frequencyOffset2; ?>" name="frequencyOffset2" readonly>
                        </td>
                        <td>Khz</td>
                    </tr>
                    <tr>
                        <td>MODCOD</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $modcod[$modcodStatus2]; ?>" name="modcodStatus2" readonly>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Roll Off</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $rolloff[$rollOffStatus2]; ?>" name="rollOffStatus2" readonly>
                        </td>
                        <td>%</td>
                    </tr>
                    <tr>
                        <td>Pilot</td>
                        <td>
                            <input class="form-control input-sm" type="text" value="<?php echo $pilot[$pilotsStatus2]; ?>" name="pilotsStatus2" readonly>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
</form>
<div class="col-md-3"></div>
<form method="post">
    </div>This page automatically refreshes every
    <div class="form-group">
        <input class="form-control input-sm" type="number" min="10" max="360" name="refreshTime" value="<?php echo $refreshTime;?>">
    </div> Sec
    <button type="submit" class="btn btn-success btn-xs" name="update">Update</button>
    </div>
    <div class="container text-left">
        <hr>
        <footer>
            <span class="pull-right"><?php echo "Version number: ".$ver;?></span>
            &copy; <a href="http://www.ayecka.com">Ayecka</a> Comunnication System</footer>
    </div>
</form>
<!-- /container -->
</body>

</html>