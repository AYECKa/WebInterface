<?php
session_start();
error_reporting(0);
$read = $_SESSION['read'];
$device_IP = $_SESSION['device_IP'];
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

$power = array("Off","On");
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

   //
    $txPowerStateStatus = substr(snmp2_get($device_IP,"public",$oid['txPowerStateStatus']), 9);
    $txFrequencyStatus = substr(snmp2_get($device_IP,"public",$oid['txFrequencyStatus']), 9)/1000;
    $modInputBitrateStatus = substr(snmp2_get($device_IP,"public",$oid['modInputBitrateStatus']), 9)/1000;
    $modOutputBitrateStatus = substr(snmp2_get($device_IP,"public",$oid['modOutputBitrateStatus']), 9)/1000;
    $modModcodStatus = substr(snmp2_get($device_IP,"public",$oid['modModcodStatus']), 9);
    $modRolloffStatus = substr(snmp2_get($device_IP,"public",$oid['modRolloffStatus']), 9);

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Ayecka Device Manager</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" href="../style/style.css">
</head>


<body>
<header>
    <a href="http://www.ayecka.com" target="_blank"><img src="../images/ayeckaLogo.png"></a>
    <img id="slogen" src="../images/slogen.png">
    <h2>ST1</h2>
</header>
<nav id="aymenu">
    <ul id="MenuList">
        <li class="Active"> <a href="index.php">Status</a></li>
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
        <li> <a href="vrrp.php">VRRP</a></li>
        <li> | </li>
        <li> <a href="system.php">System</a></li>
        <li> | </li>
        <li> <a href="images.php">Images</a></li>
        <li> | </li>
        <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">ST1 User Manual</a></li>
    </ul>
</nav>
<nav>
    <form method="post">
        <label>Device IP: </label><input type="text" value="<?php echo $device_IP;?>" name="device_IP">
        <input type="submit" name="read" value="Read From Device">
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
                <td>Power State</td>
                <td><input type="text" value="<?php echo $power[$txPowerStateStatus]; ?>" readonly></td>
            </tr><tr>
                <td>Center Frequency</td>
                <td><input type="text" value="<?php  echo $txFrequencyStatus;?>" readonly></td>
                <td>Mhz</td>
            </tr><tr>
                <td>Input Bitrate</td>
                <td><input type="text" value="<?php  echo $modInputBitrateStatus;?>" readonly></td>
                <td>Kbps</td>
            </tr><tr>
                <td>Output Bitrate</td>
                <td><input type="text" value="<?php  echo $modOutputBitrateStatus;?>" readonly></td>
                <td>Kbps</td>
            </tr><tr>
                <td>MODCOD</td>
                <td><input type="text" value="<?php echo $modcod[$modModcodStatus]; ?>" readonly></td>
            </tr><tr>
                <td>Rolloff</td>
                <td><input type="text" value="<?php echo $rolloff[$modRolloffStatus];?>" readonly></td>
            </tr>

        </table>
        <br>
        <form method="post">This page automatically refreshes every <input type="number" min="10" max="360" name="refreshTime" value="<?php echo $refreshTime;?>"> seconds <input type="submit" name="update" value="Update"> </form></td>


    </center>

</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>