<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP'];
$read = $_SESSION['read'];
$ver = $_SESSION['srVer'];

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

    $_SESSION['device_IP'] = $device_IP;
    $_SESSION['read'] = "read";
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

<!DOCTYPE html>
<html>
<head>
    <style>
        td{
            padding-right: 15px;
        }
    </style>
    <title>Ayecka Device Manager</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../style/style.css">
</head>


<body>
<header>
    <a href="http://www.ayecka.com" target="_blank"><img src="../images/ayeckaLogo.png"></a>
    <img id="slogen" src="../images/slogen.png">
    <h2>SR1</h2>
</header>
<nav id="aymenu">
    <ul id="MenuList">
        <li> <a href="index.php">Status</a></li>
        <li> | </li>
        <li> <a href="rf.php?rf=1">RF1</a></li>
        <li> | </li>
        <li> <a href="rf.php?rf=2">RF2</a></li>
        <li> | </li>
        <li class="Active"> <a href="rf_control.php">RF Control</a></li>
        <li> | </li>
        <li> <a href="filter.php">RF PID Filter</a></li>
        <li> | </li>
        <li> <a href="network.php">Network</a></li>
        <li> | </li>
        <li> <a href="images.php">Images</a></li>
        <li> | </li>
        <li> <a href="system.php">System</a></li>
        <li> | </li>
        <li> <a href="http://www.ayecka.com/Files/SR1_UserManual_V1.8.pdf" target="_blank">SR1 User Manual</a></li>
    </ul>
</nav>
<nav>
    <form method="post">
        <label>Device IP: </label><input type="text" value="<?php echo $device_IP;?>" name="device_IP">
        <input type="submit" name="read" value="Read From Device">
        <input type="submit" name="write" value="Write To Device">
</nav>

<nav id="boardInfo">

    <label>FPGA</label> <input type="text" value="<?php echo $fpgaVersion; ?>" name="fpgaVersion" readonly>
    <label>SW</label> <input type="text" value="<?php echo $softwareVersion; ?>" name="softwareVersion" readonly>
    <label>HW</label> <input type="text" value="<?php echo $hardwareVersion; ?>" name="hardwareVersion" readonly>
    <label>SN</label> <input type="text" value="<?php echo $serialNumber; ?>" name="serialNumber" readonly>

</nav>
<div class="mainWrapper">
    <center>
        <table border="0">

            <tr>
                <td>Rx Switching Mode</td>
                <td><label><input type="radio" value="0" name="rxSwitchMode"  <?php if($rxSwitchMode == "0"){echo "checked";}?>>Auto</label></td>
                <td><label><input type="radio" value="1" name="rxSwitchMode"  <?php if($rxSwitchMode == "1"){echo "checked";}?>>Manual</</label></td>
                <td><label> Switch Period <input type="number" name="rxSwitchPeriod" max="120" min="0" step="1" value="<?php echo $rxSwitchPeriod;?>"> sec (20-120)</label></td>

            </tr>
            <tr>
                <td>Active Rx Channel</td>
                <td><label><input type="radio" value="1" name="rxActive"  <?php if($rxActive == "1"){echo "checked";}?>>Rx1</label></td>
                <td><label><input type="radio" value="2" name="rxActive"  <?php if($rxActive == "2"){echo "checked";}?>>Rx2</</label></td>
            </tr>
            <tr>
                <td>Rx Channel Operation Mode</td>
                <td><label><input type="radio" value="0" name="rxChannelOpMode"  <?php if($rxChannelOpMode == "0"){echo "checked";}?>>Single</label></td>
                <td><label><input type="radio" value="1" name="rxChannelOpMode"  <?php if($rxChannelOpMode == "1"){echo "checked";}?>>Dual</</label></td>
            </tr>

        </table>
    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>