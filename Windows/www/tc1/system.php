<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP'];
$read = $_SESSION['read'];
$ver = $_SESSION['ver'];

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
    $snmpWriteCommunity = substr(snmp2_get($device_IP,"public",$oid['snmpWriteCommunity']), 9);
    $snmpReadCommunity = substr(snmp2_get($device_IP,"public",$oid['snmpReadCommunity']), 9);
    $ntpServerIpAddress = substr(snmp2_get($device_IP,"public",$oid['ntpServerIpAddress']), 11);
    $snmpTrapServerIpAddress = substr(snmp2_get($device_IP,"public",$oid['snmpTrapServerIpAddress']), 11);
    //telnet
    $username = substr(snmp2_get($device_IP,"public",$oid['username']), 9);
    $telnetTimeout = substr(snmp2_get($device_IP,"public",$oid['telnetTimeout']), 9);

}

//    Write
if (isset($_POST['write']) AND $read == "read"){
    snmp2_set($device_IP, "private", $oid['snmpWriteCommunity'], $type['snmpWriteCommunity'], $_POST['snmpWriteCommunity']);
    snmp2_set($device_IP, "private", $oid['snmpReadCommunity'], $type['snmpReadCommunity'], $_POST['snmpReadCommunity']);
    snmp2_set($device_IP, "private", $oid['ntpServerIpAddress'], $type['ntpServerIpAddress'], $_POST['ntpServerIpAddress']);
    snmp2_set($device_IP, "private", $oid['snmpTrapServerIpAddress'], $type['snmpTrapServerIpAddress'], $_POST['snmpTrapServerIpAddress']);
    snmp2_set($device_IP, "private", $oid['username'], $type['username'], $_POST['username']);
    snmp2_set($device_IP, "private", $oid['telnetTimeout'], $type['telnetTimeout'], $_POST['telnetTimeout']);
    if($_POST['password'] != ""){
        snmp2_set($device_IP, "private", $oid['password'], $type['password'], $_POST['password']);
    }
    //read
    $snmpWriteCommunity = substr(snmp2_get($device_IP,"public",$oid['snmpWriteCommunity']), 9);
    $snmpReadCommunity = substr(snmp2_get($device_IP,"public",$oid['snmpReadCommunity']), 9);
    $ntpServerIpAddress = substr(snmp2_get($device_IP,"public",$oid['ntpServerIpAddress']), 11);
    $snmpTrapServerIpAddress = substr(snmp2_get($device_IP,"public",$oid['snmpTrapServerIpAddress']), 11);
    //telnet
    $username = substr(snmp2_get($device_IP,"public",$oid['username']), 9);
    $telnetTimeout = substr(snmp2_get($device_IP,"public",$oid['telnetTimeout']), 9);


}
//Reset
if(isset($_POST['coldReset'])){
    echo '<script type="text/javascript">alert("Please wait 1 minute and read from device again.")</script>';
    snmp2_set($device_IP, "private", $oid['coldReset'] , $type['coldReset'], "1");
}
if(isset($_POST['warmReset'])){
    echo '<script type="text/javascript">alert("Please wait 1 minute and read from device again.")</script>';
    snmp2_set($device_IP, "private", $oid['warmReset'], $type['warmReset'], '1');

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Ayecka Device Manager</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../style/style.css">
    <style>
        td{
            vertical-align: top;
        }
        .mainWrapperImages{
            margin-top: 40px;
        }

    </style>

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
        <li> <a href="network.php ">Network</a></li>
        <li> | </li>
        <li> <a href="images.php ">Images</a></li>
        <li> | </li>
        <li class="Active"> <a href="system.php ">System</a></li>
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

<div class="mainWrapperImages">
    <center>
        <table border="0">
            <tr>
                <td>
                    <input type="submit" value="Cold Reset" name="coldReset">
                    <input type="submit" value="Warm Reset" name="warmReset">
                </td>
            </tr>
            <tr>
                <td>Write Community String</td>
                <td><input type="text" name="snmpWriteCommunity" value="<?php echo $snmpWriteCommunity;?>" readonly></td>
            </tr>
            <tr>
                <td>Read Community String</td>
                <td><input type="text" name="snmpReadCommunity" value="<?php echo $snmpReadCommunity;?>" readonly></td>
            </tr>
            <tr>
                <td>NTP Server IP Address</td>
                <td><input type="text" name="ntpServerIpAddress" value="<?php echo $ntpServerIpAddress;?>"></td>
            </tr>
            <tr>
                <td>SNMP Trap Server IP Address</td>
                <td><input type="text" name="snmpTrapServerIpAddress" value="<?php echo $snmpTrapServerIpAddress;?>"></td>
            </tr>
            <tr>
                <td><b>Telnet</b></td>
            </tr>
            <tr>
                <td>User Name</td>
                <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="text" name="password"></td>
            </tr>
            <tr>
                <td>Time Out</td>
                <td><input type="number" name="telnetTimeout" value="<?php echo $telnetTimeout;?>" min="60" max="3600"></td>
            </tr>
        </table>

    </center>
</div>
<footer><span class="ver">  <?php  echo "Version number: ".$ver;?></span></footer>
</body>
</html>