<?php
session_start();
error_reporting(0);
$read = $_SESSION['read'];
$device_IP = $_SESSION['device_IP'];
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

    $_SESSION['device_IP'] = $device_IP;
    $_SESSION['read'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);
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
        <li> <a href="index.php">Status</a></li>
        <li> | </li>
        <li class="Active"> <a href="tx.php">TX Configuration</a></li>
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
                <td>Tx Frequency</td>
                <td><input type="text" value="<?php echo $txFrequency;?>" name="txFrequency"></td>
                <td>Mhz</td>
            </tr><tr>
                <td>Tx Power Level</td>
                <td><input type="text" value="<?php echo $txPowerLevel; ?>" name="txPowerLevel"></td>
                <td>dBm</td>
            </tr><tr>
                <td>TX Output Power</td>
                <td><select name="txPowerState">
                        <option value="0" <?php if($txPowerState == "0"){echo "selected";}?>>Off</option>
                        <option value="1" <?php if($txPowerState == "1"){echo "selected";}?>>On</option>
                    </select></td>
            </tr><tr>
                <td>TX Output Power On Powercycle</td>
                <td><select name="txPowerStateInitial">
                        <option value="0" <?php if($txPowerStateInitial == "0"){echo "selected";}?>>Off</option>
                        <option value="1" <?php if($txPowerStateInitial == "1"){echo "selected";}?>>User-Defined</option>
                    </select></td>
            </tr>

        </table>


    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>