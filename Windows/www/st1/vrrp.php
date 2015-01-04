<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP'];
$read = $_SESSION['read'];
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

<!DOCTYPE html>
<html>
<head>
    <style>
        td{
            vertical-align: top;
            text-align: left;
        }
        .mainWrapperNetwork{
            margin: auto;
            margin-top: 40px;

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
    <h2>ST1</h2>
</header>
<nav id="aymenu">
    <ul id="MenuList">
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
        <li class="Active"> <a href="vrrp.php">VRRP</a></li>
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
<div class="mainWrapperNetwork">
    <center>
        <table border="0">
            <tr>
                <td>VRRP Enabled</td>
                <td>
                    <select name="vrrpEnabled">
                        <option value="0" <?php if($vrrpEnabled == 0){echo "selected";}?>>No</option>
                        <option value="1" <?php if($vrrpEnabled == 1){echo "selected";}?>>Yes</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Virtual IP Address</td>
                <td><input type="text" name="vrrpIpAddress" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" value="<?php echo $vrrpIpAddress;?>"></td>
            </tr>
            <tr>
                <td>Virtual Netmask</td>
                <td><input type="text" name="vrrpIpNetmask" pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" value="<?php echo $vrrpIpNetmask;?>"></td>
            </tr>
            <tr>
                <td>Virtual Router ID</td>
                <td><input type="number" min="1" max="255" name="vrrpVrid" value="<?php echo $vrrpVrid;?>"></td>
            </tr>
            <tr>
                <td>Pioriry</td>
                <td><input type="number" min="0" max="255" name="vrrpPriority" value="<?php echo $vrrpPriority;?>"></td>
            </tr>
            <tr>
                <td>Advertisement Interval</td>
                <td><input type="number" min="1" max="4294967" name="vrrpAdvInterval" value="<?php echo $vrrpAdvInterval;?>"></td>
            </tr>
            </form>
        </table>
    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>