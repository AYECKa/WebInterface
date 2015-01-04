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
    $serialNumber = substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);
//

    $egrDstEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrDstEthAddress']), 11);
    $egrSrcEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrSrcEthAddress']), 11);
    $egrVlanSupport = substr(snmp2_get($device_IP,"public",$oid['egrVlanSupport']), 9);
    $egrVlanPcp = substr(snmp2_get($device_IP,"public",$oid['egrVlanPcp']), 9);
    $egrVlanVid = substr(snmp2_get($device_IP,"public",$oid['egrVlanVid']), 9);
    $egrDscp = substr(snmp2_get($device_IP,"public",$oid['egrDscp']), 9);
    $egrSrcIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrSrcIpAddress']), 11);
    $egrDstIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrDstIpAddress']), 11);
    $egrDstUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrDstUdpPort']), 9);
    $egrTsoipNumber = substr(snmp2_get($device_IP,"public",$oid['egrTsoipNumber']), 9);
    $egrTsoipTimeoutAwarenessEnable = substr(snmp2_get($device_IP,"public",$oid['egrTsoipTimeoutAwarenessEnable']), 9);
    $egrTsoipMaxTimeout = substr(snmp2_get($device_IP,"public",$oid['egrTsoipMaxTimeout']), 9);
    $egrModOutputEnabled = substr(snmp2_get($device_IP,"public",$oid['egrModOutputEnabled']), 9);
    $egrNetOutputEnabled = substr(snmp2_get($device_IP,"public",$oid['egrNetOutputEnabled']), 9);


}

//write

if (isset($_POST['write']) AND $read == "read"){

    snmp2_set($device_IP, "private", $oid['egrDstEthAddress'], $type['egrDstEthAddress'], $_POST['egrDstEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrSrcEthAddress'], $type['egrSrcEthAddress'], $_POST['egrSrcEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrVlanSupport'], $type['egrVlanSupport'], $_POST['egrVlanSupport']);
    snmp2_set($device_IP, "private", $oid['egrVlanPcp'], $type['egrVlanPcp'], $_POST['egrVlanPcp']);
    snmp2_set($device_IP, "private", $oid['egrVlanVid'], $type['egrVlanVid'], $_POST['egrVlanVid']);
    snmp2_set($device_IP, "private", $oid['egrDscp'], $type['egrDscp'], $_POST['egrDscp']);
    snmp2_set($device_IP, "private", $oid['egrSrcIpAddress'], $type['egrSrcIpAddress'], $_POST['egrSrcIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrDstIpAddress'], $type['egrDstIpAddress'], $_POST['egrDstIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrDstUdpPort'], $type['egrDstUdpPort'], $_POST['egrDstUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrTsoipNumber'], $type['egrTsoipNumber'], $_POST['egrTsoipNumber']);
    snmp2_set($device_IP, "private", $oid['egrTsoipTimeoutAwarenessEnable'], $type['egrTsoipTimeoutAwarenessEnable'], $_POST['egrTsoipTimeoutAwarenessEnable']);
    snmp2_set($device_IP, "private", $oid['egrTsoipMaxTimeout'], $type['egrTsoipMaxTimeout'], $_POST['egrTsoipMaxTimeout']);
    snmp2_set($device_IP, "private", $oid['egrModOutputEnabled'], $type['egrModOutputEnabled'], $_POST['egrModOutputEnabled']);
    snmp2_set($device_IP, "private", $oid['egrNetOutputEnabled'], $type['egrNetOutputEnabled'], $_POST['egrNetOutputEnabled']);

    //read
    $egrDstEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrDstEthAddress']), 11);
    $egrSrcEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrSrcEthAddress']), 11);
    $egrVlanSupport = substr(snmp2_get($device_IP,"public",$oid['egrVlanSupport']), 9);
    $egrVlanPcp = substr(snmp2_get($device_IP,"public",$oid['egrVlanPcp']), 9);
    $egrVlanVid = substr(snmp2_get($device_IP,"public",$oid['egrVlanVid']), 9);
    $egrDscp = substr(snmp2_get($device_IP,"public",$oid['egrDscp']), 9);
    $egrSrcIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrSrcIpAddress']), 11);
    $egrDstIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrDstIpAddress']), 11);
    $egrDstUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrDstUdpPort']), 9);
    $egrTsoipNumber = substr(snmp2_get($device_IP,"public",$oid['egrTsoipNumber']), 9);
    $egrTsoipTimeoutAwarenessEnable = substr(snmp2_get($device_IP,"public",$oid['egrTsoipTimeoutAwarenessEnable']), 9);
    $egrTsoipMaxTimeout = substr(snmp2_get($device_IP,"public",$oid['egrTsoipMaxTimeout']), 9);
    $egrModOutputEnabled = substr(snmp2_get($device_IP,"public",$oid['egrModOutputEnabled']), 9);
    $egrNetOutputEnabled = substr(snmp2_get($device_IP,"public",$oid['egrNetOutputEnabled']), 9);





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
        <li class="Active"> <a href="egress.php">Egress Configuration</a></li>
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
                <td>Destination Ethernet Address</td>
                <td><input type="text" value="<?php echo $egrDstEthAddress;?>" name="egrDstEthAddress"></td>
            </tr>
            <tr>
                <td>Source Ethernet Address</td>
                <td><input type="text" value="<?php echo $egrSrcEthAddress;?>" name="egrSrcEthAddress"></td>
            </tr>
            <tr>
                <td>802.1Q VLAN Support</td>
                <td><select name="egrVlanSupport">
                        <option value="0" <?php if($egrVlanSupport == 0){echo "selected";}?>>No</option>
                        <option value="1" <?php if($egrVlanSupport == 1){echo "selected";}?>>Yes</option>
                    </select> </td>
            </tr>
            <tr>
                <td>Priority Code Point (PCP)</td>
                <td><input type="number" min="0" max="7" value="<?php echo $egrVlanPcp;?>" name="egrVlanPcp"></td>
            </tr>
            <tr>
                <td>VLAN Identifier (VID)</td>
                <td><input type="number" min="0" max="4094" value="<?php echo $egrVlanVid;?>" name="egrVlanVid"></td>
            </tr>
            <tr>
                <td>DSCP</td>
                <td><input type="number" min="0" max="63" value="<?php echo $egrDscp;?>" name="egrDscp"></td>
            </tr>
            <tr>
                <td>Source IP Address</td>
                <td><input type="text" value="<?php echo $egrSrcIpAddress;?>" name="egrSrcIpAddress"></td>
            </tr>
            <tr>
                <td>Destination IP Address</td>
                <td><input type="text" value="<?php echo $egrDstIpAddress;?>" name="egrDstIpAddress"></td>
            </tr>
            <tr>
                <td>Source UDP port</td>
                <td><input type="number" value="<?php echo $egrDstUdpPort;?>" name="egrDstUdpPort"></td>
            </tr>
            <tr>
                <td>Number of TS Packets in IP Packet</td>
                <td><input type="number" min="1" max="7" value="<?php echo $egrTsoipNumber;?>" name="egrTsoipNumber"></td>
            </tr>
            <tr>
                <td>PCR Awareness Enable</td>
                <td><select name="">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select> </td>
            </tr>
            <tr>
                <td>Timeout Awareness Enable</td>
                <td><select name="egrTsoipTimeoutAwarenessEnable">
                        <option value="0" <?php if($egrTsoipTimeoutAwarenessEnable == 0){echo "selected";}?>>No</option>
                        <option value="1" <?php if($egrTsoipTimeoutAwarenessEnable == 1){echo "selected";}?>>Yes</option>
                    </select> </td>
            </tr>
            <tr>
                <td>Maximum Timeout</td>
                <td><input type="number" value="<?php echo $egrTsoipMaxTimeout;?>" name="egrTsoipMaxTimeout"></td>
            </tr>
            <tr>
                <td>Modulator Output Enabled</td>
                <td><select name="egrModOutputEnabled">
                        <option value="0" <?php if($egrModOutputEnabled == 0){echo "selected";}?>>No</option>
                        <option value="1" <?php if($egrModOutputEnabled == 1){echo "selected";}?>>Yes</option>
                    </select> </td>
            </tr>
            <tr>
                <td>Network Output Enabled</td>
                <td><select name="egrNetOutputEnabled">
                        <option value="0" <?php if($egrNetOutputEnabled == 0){echo "selected";}?>>No</option>
                        <option value="1" <?php if($egrNetOutputEnabled == 1){echo "selected";}?>>Yes</option>
                    </select> </td>
            </tr>
        </table>
        </form>

    </center>
</div>
    <footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>