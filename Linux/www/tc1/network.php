<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP'];
$read = $_SESSION['read'];
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

    $_SESSION['device_IP'] = $device_IP;
    $_SESSION['read'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);

//  Management
    $managementIpAddress = substr(snmp2_get($device_IP,"public",$oid['managementIpAddress']), 11);
    $managementSubnetMask = substr(snmp2_get($device_IP,"public",$oid['managementSubnetMask']), 11);
    $managementMacAddress = substr(snmp2_get($device_IP,"public",$oid['managementMacAddress']), 11);
    $managementMulticastMode = substr(snmp2_get($device_IP,"public",$oid['managementMulticastMode']), 9);
    $managementDefaultGateway = substr(snmp2_get($device_IP,"public",$oid['managementDefaultGateway']), 11);
    $managementVlanId = substr(snmp2_get($device_IP,"public",$oid['managementVlanId']), 9);
    $managementDscp = substr(snmp2_get($device_IP,"public",$oid['managementDscp']), 9);
    $managementDhcpClient = substr(snmp2_get($device_IP,"public",$oid['managementDhcpClient']), 9);
    $isolateNets = substr(snmp2_get($device_IP,"public",$oid['isolateNets']), 9);
//  Traffic

    $lanIpAddress = substr(snmp2_get($device_IP,"public",$oid['lanIpAddress']), 11);
    $lanSubnetMask = substr(snmp2_get($device_IP,"public",$oid['lanSubnetMask']), 11);
    $lanMacAddress = substr(snmp2_get($device_IP,"public",$oid['lanMacAddress']), 11);
    $lanMulticastMode = substr(snmp2_get($device_IP,"public",$oid['lanMulticastMode']), 9);
    $routerIpAddress = substr(snmp2_get($device_IP,"public",$oid['routerIpAddress']), 11);
    $lanDhcpClient = substr(snmp2_get($device_IP,"public",$oid['lanDhcpClient']), 9);


}

//    Write
if (isset($_POST['write']) AND $read == "read"){
    //Traffic
    if($_POST['lanDhcpClient'] == "1"){
        //selected
        snmp2_set($device_IP, "private", $oid['lanDhcpClient'], $type['lanDhcpClient'], '1');
    }else{
        //not selected
        snmp2_set($device_IP, "private", $oid['lanDhcpClient'], $type['lanDhcpClient'], '0');
    }

    snmp2_set($device_IP, "private", $oid['lanIpAddress'], $type['lanIpAddress'], $_POST['lanIpAddress']);
    snmp2_set($device_IP, "private", $oid['lanSubnetMask'], $type['lanSubnetMask'], $_POST['lanSubnetMask']);
    snmp2_set($device_IP, "private", $oid['routerIpAddress'], $type['routerIpAddress'], $_POST['routerIpAddress']);

    if($_POST['lanMulticastMode'] == "0"){
        //selected
        snmp2_set($device_IP, "private", $oid['lanMulticastMode'], $type['lanMulticastMode'], '0');
    }else{
        //not selected
        snmp2_set($device_IP, "private", $oid['lanMulticastMode'], $type['lanMulticastMode'], '1');
    }

    //read
    $lanIpAddress = substr(snmp2_get($device_IP,"public",$oid['lanIpAddress']), 11);
    $lanSubnetMask = substr(snmp2_get($device_IP,"public",$oid['lanSubnetMask']), 11);
    $lanMacAddress = substr(snmp2_get($device_IP,"public",$oid['lanMacAddress']), 11);
    $lanMulticastMode = substr(snmp2_get($device_IP,"public",$oid['lanMulticastMode']), 9);
    $routerIpAddress = substr(snmp2_get($device_IP,"public",$oid['routerIpAddress']), 11);
    $lanDhcpClient = substr(snmp2_get($device_IP,"public",$oid['lanDhcpClient']), 9);


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
    <h2>TC1</h2>
</header>
<nav id="aymenu">
    <ul id="MenuList">
        <li> <a href="index.php ">Status</a></li>
        <li> | </li>
        <li> <a href="rf.php ?rf=1">RF1</a></li>
        <li> | </li>
        <li> <a href="rf.php ?rf=2">RF2</a></li>
        <li> | </li>
        <li> <a href="rf_control.php ">RF Control</a></li>
        <li> | </li>
        <li> <a href="egress.php ">Egress</a></li>
        <li> | </li>
        <li class="Active"> <a href="network.php ">Network</a></li>
        <li> | </li>
        <li> <a href="images.php ">Images</a></li>
        <li> | </li>
        <li> <a href="system.php ">System</a></li>
        <li> | </li>
        <li> <a href="http://www.ayecka.com/Files/TC1_UserManual_V1.0.pdf" target="_blank">TC1 User Manual</a></li>
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
                <td>
                    <b>Management</b>
                    <table>
                        <tr>
                            <td>IP Address</td>
                            <td><input type="text" name="managementIpAddress" value="<?php echo $managementIpAddress; ?>" readonly></td>
                        </tr>
                        <tr>
                            <td>IP Mask</td>
                            <td><input type="text" name="managementSubnetMask" value="<?php echo $managementSubnetMask; ?>" readonly></td>
                        </tr>
                        <tr>
                            <td>MAC Address</td>
                            <td><input type="text" name="managementMacAddress" value="<?php echo $managementMacAddress;?>" readonly></td>
                        </tr>
                        <tr>
                            <td>Multicast Enable</td>
                            <td><input type="checkbox" value="0" name="managementMulticastMode" <?php if($managementMulticastMode=="0"){echo "checked";}?> disabled></td>
                        </tr>
                        <tr>
                            <td>Default Gateway</td>
                            <td><input type="text" name="managementDefaultGateway" value="<?php echo $managementDefaultGateway;?>" readonly></td>
                        </tr>
                        <tr>
                            <td>VLAN ID</td>
                            <td><input type="number" min="0" max="4094" name="managementVlanId" value="<?php echo $managementVlanId;?>" readonly></td>
                        </tr>
                        <tr>
                            <td>DSCP</td>
                            <td><input type="number" min="0" max="63" name="managementDscp" value="<?php echo $managementDscp; ?>" readonly></td>
                        </tr>
                        <tr>
                            <td>DHCP Client</td>
                            <td><input type="checkbox" name="managementDhcpClient" <?php if($managementDhcpClient == "1"){echo "checked";}?> disabled></td>
                        </tr>
                        <tr>
                            <td>Isolate Network</td>
                            <td><select name="isolateNets" disabled>
                                    <option value="1" <?php if($isolateNets == 1){echo "selected";}?>>Isolated</option>
                                    <option value="0" <?php if($isolateNets == 0){echo "selected";}?>>Connected</option>
                                </select></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <b>Traffic</b>
                    <table>
                        <tr>
                            <td>IP Address</td>
                            <td><input type="text" name="lanIpAddress" value="<?php echo $lanIpAddress; ?>"></td>
                        </tr>
                        <tr>
                            <td>IP Mask</td>
                            <td><input type="text" name="lanSubnetMask" value="<?php echo $lanSubnetMask; ?>"></td>
                        </tr>
                        <tr>
                            <td>MAC Address</td>
                            <td><input type="text" name="lanMacAddress" value="<?php echo $lanMacAddress; ?>" readonly></td>
                        </tr>
                        <tr>
                            <td>Multicast Enable</td>
                            <td><input type="checkbox" value="0" name="lanMulticastMode" <?php if($lanMulticastMode =="0"){echo "checked";}?>></td>
                        </tr>
                        <tr>
                            <td>Router IP</td>
                            <td><input type="text" name="routerIpAddress" value="<?php echo $routerIpAddress;?>"></td>
                        </tr>
                        <tr>
                            <td>DHCP Client</td>
                            <td><input type="checkbox" name="lanDhcpClient" value="1" <?php if($lanDhcpClient == "1"){echo "checked";}?>></td>
                        </tr>
                    </table>
                </td>

            </tr>

            </form>
        </table>
    </center>
</div>
<footer><span class="ver">  <?php  echo "Version number: ".$ver;?></span></footer>
</body>
</html>