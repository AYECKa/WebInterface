<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP_sr'];
$read = $_SESSION['read_sr'];
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

    $_SESSION['device_IP_sr'] = $device_IP;
    $_SESSION['read_sr'] = "read";
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
            <br><h4><strong><a href="http://www.ayecka.com/SR1.html">SR1</a></strong> - Advanced DVB-S2 Receiver with GigE interface</h4>
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
                <li> <a href="index.php">Status</a></li>
                <li> | </li>
                <li> <a href="rf.php?rf=1">RF1</a></li>
                <li> | </li>
                <li> <a href="rf.php?rf=2">RF2</a></li>
                <li> | </li>
                <li> <a href="rf_control.php">RF Control</a></li>
                <li> | </li>
                <li> <a href="filter.php">RF PID Filter</a></li>
                <li> | </li>
                <li> <a href="network.php">Network</a></li>
                <li> | </li>
                <li> <a href="images.php">Images</a></li>
                <li> | </li>
                <li class="active"> <a href="system.php">System</a></li>
                <li> | </li>
                <li> <a href="http://www.ayecka.com/Files/SR1_UserManual_V1.8.pdf" target="_blank">SR1 User Manual</a></li>
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
<hr>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-left">
        <table class="table table-hover table-condensed">
            <tr>
                <td colspan="2">
                    <input class="btn btn-warning btn-sm" type="submit" value="Cold Reset" name="coldReset">
                    <input class="btn btn-danger btn-sm" type="submit" value="Warm Reset" name="warmReset">
                </td>
            </tr>
            <tr class="success">
                <th colspan="2">SNMP</th>
            </tr>
            <tr>
                <td>Write Community String</td>
                <td><input class="form-control input-sm" type="text" name="snmpWriteCommunity" value="<?php echo $snmpWriteCommunity;?>" readonly></td>
            </tr>
            <tr>
                <td>Read Community String</td>
                <td><input class="form-control input-sm" type="text" name="snmpReadCommunity" value="<?php echo $snmpReadCommunity;?>" readonly></td>
            </tr>
            <tr>
                <td>NTP Server IP Address</td>
                <td><input class="form-control input-sm" type="text" name="ntpServerIpAddress" value="<?php echo $ntpServerIpAddress;?>"></td>
            </tr>
            <tr>
                <td>SNMP Trap Server IP Address</td>
                <td><input class="form-control input-sm" type="text" name="snmpTrapServerIpAddress" value="<?php echo $snmpTrapServerIpAddress;?>"></td>
            </tr>
            <tr class="success">
                <th colspan="2">Telnet</th>
            </tr>
            <tr>
                <td>User Name</td>
                <td><input class="form-control input-sm" type="text" name="username" value="<?php echo $username; ?>"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input class="form-control input-sm" type="text" name="password"></td>
            </tr>
            <tr>
                <td>Timeout</td>
                <td><input class="form-control input-sm" type="number" name="telnetTimeout" value="<?php echo $telnetTimeout;?>" min="60" max="3600"></td>
            </tr>
        </table>
    </div>

</form>
<div class="col-md-4"></div>
</div>
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