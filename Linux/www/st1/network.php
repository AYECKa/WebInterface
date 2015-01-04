<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP_st'];
$read = $_SESSION['read_st'];
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

    $_SESSION['device_IP_st'] = $device_IP;
    $_SESSION['read_st'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);
// system info
    include_once('info_function.php');

//  Management
    $managementIpAddress = substr(snmp2_get($device_IP,"public",$oid['managementIpAddress']), 11);
    $managementSubnetMask = substr(snmp2_get($device_IP,"public",$oid['managementSubnetMask']), 11);
    $managementMacAddress = substr(snmp2_get($device_IP,"public",$oid['managementMacAddress']), 11);
    $managementMulticastMode = substr(snmp2_get($device_IP,"public",$oid['managementMulticastMode']), 9);
    $managementDefaultGateway = substr(snmp2_get($device_IP,"public",$oid['managementDefaultGateway']), 11);
    $managementVlanId = substr(snmp2_get($device_IP,"public",$oid['managementVlanId']), 9);
    $managementDscp = substr(snmp2_get($device_IP,"public",$oid['managementDscp']), 9);
    $managementDhcpClient = substr(snmp2_get($device_IP,"public",$oid['managementDhcpClient']), 9);
    $managementPortState = substr(snmp2_get($device_IP,"public",$oid['managementPortState']), 9);
    $isolateNetworks = substr(snmp2_get($device_IP,"public",$oid['isolateNetworks']), 9);
//  Traffic

    $lanIpAddress = substr(snmp2_get($device_IP,"public",$oid['lanIpAddress']), 11);
    $lanSubnetMask = substr(snmp2_get($device_IP,"public",$oid['lanSubnetMask']), 11);
    $lanMacAddress = substr(snmp2_get($device_IP,"public",$oid['lanMacAddress']), 11);
    $lanMulticastMode = substr(snmp2_get($device_IP,"public",$oid['lanMulticastMode']), 9);
    $routerIpAddress = substr(snmp2_get($device_IP,"public",$oid['routerIpAddress']), 11);
    $lanDhcpClient = substr(snmp2_get($device_IP,"public",$oid['lanDhcpClient']), 9);


    //ARP

    $routerArpMode = substr(snmp2_get($device_IP,"public",$oid['routerArpMode']), 9);
    $routerArpPeriod = substr(snmp2_get($device_IP,"public",$oid['routerArpPeriod']), 9)/1000;
    $routerArpTimeout = substr(snmp2_get($device_IP,"public",$oid['routerArpTimeout']), 9)/1000;
    $routerMacAddress = substr(snmp2_get($device_IP,"public",$oid['routerMacAddress']), 11);



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

    //ARP
    snmp2_set($device_IP, "private", $oid['routerArpMode'], $type['routerArpMode'], $_POST['routerArpMode']);
    snmp2_set($device_IP, "private", $oid['routerArpPeriod'], $type['routerArpPeriod'], $_POST['routerArpPeriod']*1000);
    snmp2_set($device_IP, "private", $oid['routerArpTimeout'], $type['routerArpTimeout'], $_POST['routerArpTimeout']*1000);
    snmp2_set($device_IP, "private", $oid['routerMacAddress'], $type['routerMacAddress'], $_POST['routerMacAddress']);

    //read
    $routerArpMode = substr(snmp2_get($device_IP,"public",$oid['routerArpMode']), 9);
    $routerArpPeriod = substr(snmp2_get($device_IP,"public",$oid['routerArpPeriod']), 9)/1000;
    $routerArpTimeout = substr(snmp2_get($device_IP,"public",$oid['routerArpTimeout']), 9)/1000;
    $routerMacAddress = substr(snmp2_get($device_IP,"public",$oid['routerMacAddress']), 11);



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

<?php
$active = "network";
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

            <div class="col-md-4 text-left">
                <strong>Management</strong>
                <table class="table table-hover table-condensed">
                    <tr>
                        <td>IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="managementIpAddress" value="<?php echo $managementIpAddress; ?>" readonly></td>
                    </tr>
                    <tr>
                        <td>IP Mask</td>
                        <td><input class="form-control input-sm" type="text" name="managementSubnetMask" value="<?php echo $managementSubnetMask; ?>" readonly></td>
                    </tr>
                    <tr>
                        <td>MAC Address</td>
                        <td><input class="form-control input-sm" type="text" name="managementMacAddress" value="<?php echo $managementMacAddress;?>" readonly></td>
                    </tr>
                    <tr>
                        <td>Multicast Enable</td>
                        <td>
                            <?php if($managementMulticastMode == 0){
                                echo '<span class="label label-success">Enabled</span>';
                            }else{
                                echo '<span class="label label-danger">Disabled</span>';
                            }?>
                        </td>
                    </tr>
                    <tr>
                        <td>Default Gateway</td>
                        <td><input class="form-control input-sm" type="text" name="managementDefaultGateway" value="<?php echo $managementDefaultGateway;?>" readonly></td>
                    </tr>
                    <tr>
                        <td>VLAN ID</td>
                        <td><input class="form-control input-sm" type="number" min="0" max="4094" name="managementVlanId" value="<?php echo $managementVlanId;?>" readonly></td>
                    </tr>
                    <tr>
                        <td>DSCP</td>
                        <td><input class="form-control input-sm" type="number" min="0" max="63" name="managementDscp" value="<?php echo $managementDscp; ?>" readonly></td>
                    </tr>
                    <tr>
                        <td>DHCP Client</td>
                        <td>
                            <?php if($managementDhcpClient == 1){
                                echo '<span class="label label-success">Yes</span>';
                            }else{
                                echo '<span class="label label-danger">No</span>';
                            }?>
                        </td>
                    </tr>
                    <tr>
                        <td>Isolate Network</td>
                        <td>
                            <?php if($isolateNetworks == 1){
                                echo '<span class="label label-default">Isolated</span>';
                            }else{
                                echo '<span class="label label-primary">Connected</span>';
                            }?>

                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-4 text-left">
                <strong>Traffic</strong>
                <table class="table table-hover table-condensed">
                    <tr>
                        <td>IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="lanIpAddress" value="<?php echo $lanIpAddress; ?>"></td>
                    </tr>
                    <tr>
                        <td>IP Mask</td>
                        <td><input class="form-control input-sm" type="text" name="lanSubnetMask" value="<?php echo $lanSubnetMask; ?>"></td>
                    </tr>
                    <tr>
                        <td>MAC Address</td>
                        <td><input class="form-control input-sm" type="text" name="lanMacAddress" value="<?php echo $lanMacAddress; ?>" readonly></td>
                    </tr>
                    <tr>
                        <td>Multicast Enable</td>
                        <td><input type="checkbox" value="0" name="lanMulticastMode"  <?php if($lanMulticastMode =="0"){echo "checked";}?>></td>
                    </tr>
                    <tr>
                        <td>Router IP</td>
                        <td><input class="form-control input-sm" type="text" name="routerIpAddress" value="<?php echo $routerIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>DHCP Client</td>
                        <td><input type="checkbox" name="lanDhcpClient" value="1"  <?php if($lanDhcpClient == "1"){echo "checked";}?>></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-4 text-left">
                <strong>ARP</strong>
                <table class="table table-hover table-condensed">
                    <tr>
                        <td>Management Mode</td>
                        <td>
                            <select name="routerArpMode" class="form-control input-sm">
                                <option value="0"  <?php if($routerArpMode == "0"){echo "selected";}?>>Manual</option>
                                <option value="1"  <?php if($routerArpMode == "1"){echo "selected";}?>>Automatic</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Period</td>
                        <td>
                            <div class="input-group">
                                <input class="form-control input-sm" type="number" name="routerArpPeriod" min="0" max="600" value="<?php echo $routerArpPeriod; ?>">
                                <span class="input-group-addon">Sec</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Timeout</td>
                        <td>
                            <div class="input-group">
                                <input class="form-control input-sm" type="number" name="routerArpTimeout" min="0" max="60" value="<?php echo $routerArpTimeout; ?>">
                                <span class="input-group-addon">Sec</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Router Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" value="<?php echo $routerMacAddress; ?>" name="routerMacAddress"></td>
                    </tr>
                </table>
            </div>

        </div>

</form>
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