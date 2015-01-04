<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP_tc'];
$read = $_SESSION['read_tc'];
$ver = $_SESSION['tcVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];

require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
require_once('egress_functions.php');


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
// System info
    include_once('info_function.php');

    //RX1
    rx1_read();
    //RX2
    rx2_read();
    //ASI
    asi_read();


}

//    Write
if (isset($_POST['write']) AND $read == "read"){

    //RX1
    snmp2_set($device_IP, "private", $oid['egrRx1Enabled'], $type['egrRx1Enabled'], $_POST['egrRx1Enabled']);
    snmp2_set($device_IP, "private", $oid['egrRx1DstIpAddress'], $type['egrRx1DstIpAddress'], $_POST['egrRx1DstIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx1DstEthAddress'], $type['egrRx1DstEthAddress'], $_POST['egrRx1DstEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx1DstUdpPort'], $type['egrRx1DstUdpPort'], $_POST['egrRx1DstUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrRx1Dscp'], $type['egrRx1Dscp'], $_POST['egrRx1Dscp']);
    snmp2_set($device_IP, "private", $oid['egrRx1SrcIpAddress'], $type['egrRx1SrcIpAddress'], $_POST['egrRx1SrcIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx1SrcEthAddress'], $type['egrRx1SrcEthAddress'], $_POST['egrRx1SrcEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx1SrcUdpPort'], $type['egrRx1SrcUdpPort'], $_POST['egrRx1SrcUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrRx1TSoIPContainerSize'], $type['egrRx1TSoIPContainerSize'], $_POST['egrRx1TSoIPContainerSize']);
    snmp2_set($device_IP, "private", $oid['egrRx1PcrAwareness'], $type['egrRx1PcrAwareness'], $_POST['egrRx1PcrAwareness']);
    snmp2_set($device_IP, "private", $oid['egrRx1MaxTimeout'], $type['egrRx1MaxTimeout'], $_POST['egrRx1MaxTimeout']);
    rx1_read();

    //RX2
    snmp2_set($device_IP, "private", $oid['egrRx2Enabled'], $type['egrRx2Enabled'], $_POST['egrRx2Enabled']);
    snmp2_set($device_IP, "private", $oid['egrRx2DstIpAddress'], $type['egrRx2DstIpAddress'], $_POST['egrRx2DstIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx2DstEthAddress'], $type['egrRx2DstEthAddress'], $_POST['egrRx2DstEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx2DstUdpPort'], $type['egrRx2DstUdpPort'], $_POST['egrRx2DstUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrRx2Dscp'], $type['egrRx2Dscp'], $_POST['egrRx2Dscp']);
    snmp2_set($device_IP, "private", $oid['egrRx2SrcIpAddress'], $type['egrRx2SrcIpAddress'], $_POST['egrRx2SrcIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx2SrcEthAddress'], $type['egrRx2SrcEthAddress'], $_POST['egrRx2SrcEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrRx2SrcUdpPort'], $type['egrRx2SrcUdpPort'], $_POST['egrRx2SrcUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrRx2TSoIPContainerSize'], $type['egrRx2TSoIPContainerSize'], $_POST['egrRx2TSoIPContainerSize']);
    snmp2_set($device_IP, "private", $oid['egrRx2PcrAwareness'], $type['egrRx2PcrAwareness'], $_POST['egrRx2PcrAwareness']);
    snmp2_set($device_IP, "private", $oid['egrRx2MaxTimeout'], $type['egrRx2MaxTimeout'], $_POST['egrRx2MaxTimeout']);
    rx2_read();

    //ASI
    snmp2_set($device_IP, "private", $oid['egrAsiEnabled'], $type['egrAsiEnabled'], $_POST['egrAsiEnabled']);
    snmp2_set($device_IP, "private", $oid['egrAsiDstIpAddress'], $type['egrAsiDstIpAddress'], $_POST['egrAsiDstIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrAsiDstEthAddress'], $type['egrAsiDstEthAddress'], $_POST['egrAsiDstEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrAsiDstUdpPort'], $type['egrAsiDstUdpPort'], $_POST['egrAsiDstUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrAsiDscp'], $type['egrAsiDscp'], $_POST['egrAsiDscp']);
    snmp2_set($device_IP, "private", $oid['egrAsiSrcIpAddress'], $type['egrAsiSrcIpAddress'], $_POST['egrAsiSrcIpAddress']);
    snmp2_set($device_IP, "private", $oid['egrAsiSrcEthAddress'], $type['egrAsiSrcEthAddress'], $_POST['egrAsiSrcEthAddress']);
    snmp2_set($device_IP, "private", $oid['egrAsiSrcUdpPort'], $type['egrAsiSrcUdpPort'], $_POST['egrAsiSrcUdpPort']);
    snmp2_set($device_IP, "private", $oid['egrAsiTSoIPContainerSize'], $type['egrAsiTSoIPContainerSize'], $_POST['egrAsiTSoIPContainerSize']);
    snmp2_set($device_IP, "private", $oid['egrAsiPcrAwareness'], $type['egrAsiPcrAwareness'], $_POST['egrAsiPcrAwareness']);
    snmp2_set($device_IP, "private", $oid['egrAsiMaxTimeout'], $type['egrAsiMaxTimeout'], $_POST['egrAsiMaxTimeout']);
    asi_read();

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
<?php
$active = "egress";
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
                <strong>Rx1</strong>
                <table class="table table-hover">
                    <tr>
                        <td></td>
                        <td><select name="egrRx1Enabled" class="form-control input-sm">
                                <option value="1" <?php if($egrRx1Enabled == 1){echo "selected";}?>>Enable</option>
                                <option value="0" <?php if($egrRx1Enabled == 0){echo "selected";}?>>Disable</option>
                            </select> </td>
                    </tr>
                    <tr>
                        <td>Destination IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx1DstIpAddress" value="<?php echo $egrRx1DstIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Destination Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx1DstEthAddress" value="<?php echo $egrRx1DstEthAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Destination UDP port</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx1DstUdpPort" value="<?php echo $egrRx1DstUdpPort;?>"></td>
                    </tr>
                    <tr>
                        <td>DSCP</td>
                        <td><input class="form-control input-sm" type="number" min="0" max="63" name="egrRx1Dscp" value="<?php echo $egrRx1Dscp;?>"></td>
                    </tr>
                    <tr>
                        <td>Source IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx1SrcIpAddress" value="<?php echo $egrRx1SrcIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Source Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx1SrcEthAddress" value="<?php echo $egrRx1SrcEthAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Source UDP port</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx1SrcUdpPort" value="<?php echo $egrRx1SrcUdpPort;?>"></td>
                    </tr>
                    <tr>
                        <td>Number of TS Packets in IP Packet</td>
                        <td><input class="form-control input-sm" type="number" min="1" max="7" name="egrRx1TSoIPContainerSize" value="<?php echo $egrRx1TSoIPContainerSize;?>"></td>
                    </tr>
                    <tr>
                        <td>PCR Awareness Enable</td>
                        <td>
                            <select name="egrRx1PcrAwareness" class="form-control input-sm">
                                <option value="1" <?php if($egrRx1PcrAwareness == 1){echo "selected";}?>>Yes</option>
                                <option value="0" <?php if($egrRx1PcrAwareness == 0){echo "selected";}?>>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Timeout Awareness</td>
                        <td><input class="form-control input-sm" type="number" name="egrRx1MaxTimeout" value="<?php echo $egrRx1MaxTimeout;?>"></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 text-left">
                <strong>Rx2</strong>
                <table class="table table-hover">
                    <tr>
                        <td></td>
                        <td><select name="egrRx2Enabled" class="form-control input-sm">
                                <option value="1" <?php if($egrRx2Enabled == 1){echo "selected";}?>>Enable</option>
                                <option value="0" <?php if($egrRx2Enabled == 0){echo "selected";}?>>Disable</option>
                            </select> </td>
                    </tr>
                    <tr>
                        <td>Destination IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx2DstIpAddress" value="<?php echo $egrRx2DstIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Destination Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx2DstEthAddress" value="<?php echo $egrRx2DstEthAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Destination UDP port</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx2DstUdpPort" value="<?php echo $egrRx2DstUdpPort;?>"></td>
                    </tr>
                    <tr>
                        <td>DSCP</td>
                        <td><input class="form-control input-sm" type="number" min="0" max="63" name="egrRx2Dscp" value="<?php echo $egrRx2Dscp;?>"></td>
                    </tr>
                    <tr>
                        <td>Source IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx2SrcIpAddress" value="<?php echo $egrRx2SrcIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Source Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx2SrcEthAddress" value="<?php echo $egrRx2SrcEthAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Source UDP port</td>
                        <td><input class="form-control input-sm" type="text" name="egrRx2SrcUdpPort" value="<?php echo $egrRx2SrcUdpPort;?>"></td>
                    </tr>
                    <tr>
                        <td>Number of TS Packets in IP Packet</td>
                        <td><input class="form-control input-sm" type="number" min="1" max="7" name="egrRx2TSoIPContainerSize" value="<?php echo $egrRx2TSoIPContainerSize;?>"></td>
                    </tr>
                    <tr>
                        <td>PCR Awareness Enable</td>
                        <td>
                            <select name="egrRx2PcrAwareness" class="form-control input-sm">
                                <option value="1" <?php if($egrRx2PcrAwareness == 1){echo "selected";}?>>Yes</option>
                                <option value="0" <?php if($egrRx2PcrAwareness == 0){echo "selected";}?>>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Timeout Awareness</td>
                        <td><input class="form-control input-sm" type="number" name="egrRx2MaxTimeout" value="<?php echo $egrRx2MaxTimeout;?>"></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 text-left">
                <strong>ASI</strong>
                <table class="table table-hover">
                    <tr>
                        <td></td>
                        <td><select name="egrAsiEnabled" class="form-control input-sm">
                                <option value="1" <?php if($egrAsiEnabled == 1){echo "selected";}?>>Enable</option>
                                <option value="0" <?php if($egrAsiEnabled == 0){echo "selected";}?>>Disable</option>
                            </select> </td>
                    </tr>
                    <tr>
                        <td>Destination IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrAsiDstIpAddress" value="<?php echo $egrAsiDstIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Destination Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrAsiDstEthAddress" value="<?php echo $egrAsiDstEthAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Destination UDP port</td>
                        <td><input class="form-control input-sm" type="text" name="egrAsiDstUdpPort" value="<?php echo $egrAsiDstUdpPort;?>"></td>
                    </tr>
                    <tr>
                        <td>DSCP</td>
                        <td><input class="form-control input-sm" type="number" min="0" max="63" name="egrAsiDscp" value="<?php echo $egrAsiDscp;?>"></td>
                    </tr>
                    <tr>
                        <td>Source IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrAsiSrcIpAddress" value="<?php echo $egrAsiSrcIpAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Source Ethernet Address</td>
                        <td><input class="form-control input-sm" type="text" name="egrAsiSrcEthAddress" value="<?php echo $egrAsiSrcEthAddress;?>"></td>
                    </tr>
                    <tr>
                        <td>Source UDP port</td>
                        <td><input class="form-control input-sm" type="text" name="egrAsiSrcUdpPort" value="<?php echo $egrAsiSrcUdpPort;?>"></td>
                    </tr>
                    <tr>
                        <td>Number of TS Packets in IP Packet</td>
                        <td><input class="form-control input-sm" type="number" min="1" max="7" name="egrAsiTSoIPContainerSize" value="<?php echo $egrAsiTSoIPContainerSize;?>"></td>
                    </tr>
                    <tr>
                        <td>PCR Awareness Enable</td>
                        <td>
                            <select name="egrAsiPcrAwareness" class="form-control input-sm">
                                <option value="1" <?php if($egrAsiPcrAwareness == 1){echo "selected";}?>>Yes</option>
                                <option value="0" <?php if($egrAsiPcrAwareness == 0){echo "selected";}?>>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Timeout Awareness</td>
                        <td><input class="form-control input-sm" type="number" name="egrAsiMaxTimeout" value="<?php echo $egrAsiMaxTimeout;?>"></td>
                    </tr>
                </table>
            </div>
</form>

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