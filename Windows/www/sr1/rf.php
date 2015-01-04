<?php
session_start();
error_reporting(0);
$read = $_SESSION['read'];
$device_IP = $_SESSION['device_IP'];
$ver = $_SESSION['srVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];

require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
require_once('rf_functions.php');

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

//    RF1
    if($_GET['rf'] == "1"){
        rf1_conf1_read();
        rf1_conf2_read();
    }

    //    RF2
    if($_GET['rf'] == "2"){
        rf2_conf1_read();
        rf2_conf2_read();
    }


}

//    Write
if (isset($_POST['write']) AND $read == "read"){
//    RF1
    if($_GET['rf'] == "1"){

        snmp2_set($device_IP, "private", $oid['rx1CfgMode'], $type['rx1CfgMode'], $_POST['rx1CfgMode']);
        snmp2_set($device_IP, "private", $oid['rx1CfgSwitchPeriod'], $type['rx1CfgSwitchPeriod'], $_POST['rx1CfgSwitchPeriod']);


        snmp2_set($device_IP, "private", $oid['profileName1'], $type['profileName1'], $_POST['profileName1']);
        snmp2_set($device_IP, "private", $oid['frequency1'], $type['frequency1'], $_POST['frequency1']*1000);
        snmp2_set($device_IP, "private", $oid['acquisitionBandwidth1'], $type['acquisitionBandwidth1'], $_POST['acquisitionBandwidth1']*1000);
        snmp2_set($device_IP, "private", $oid['symbolRate1'], $type['symbolRate1'], $_POST['symbolRate1']*1000);
        snmp2_set($device_IP, "private", $oid['goldCode1'], $type['goldCode1'], $_POST['goldCode1']);
        snmp2_set($device_IP, "private", $oid['isi1'], $type['isi1'], $_POST['isi1']);
        snmp2_set($device_IP, "private", $oid['lnbPower1'], $type['lnbPower1'], $_POST['lnbPower1']);
        if($_POST['signal22KHz1'] == "1"){
            snmp2_set($device_IP, "private", $oid['signal22KHz1'], $type['signal22KHz1'], $_POST['signal22KHz1']);
        }else{
            snmp2_set($device_IP, "private", $oid['signal22KHz1'], $type['signal22KHz1'], "0");
        }
        if($_POST['compensation1'] == "1"){
            snmp2_set($device_IP, "private", $oid['compensation1'], $type['compensation1'], $_POST['compensation1']);
        }else{
            snmp2_set($device_IP, "private", $oid['compensation1'], $type['compensation1'], "0");
        }

        if($_POST['rx1CfgSetStatus'] == "1"){
            snmp2_set($device_IP, "private", $oid['rx1CfgSet1Status'], $type['rx1CfgSet1Status'], "0");
        } else {
            snmp2_set($device_IP, "private", $oid['rx1CfgSet1Status'], $type['rx1CfgSet1Status'], "1");
        }

        //conf2
        snmp2_set($device_IP, "private", $oid['profileName2'], $type['profileName2'], $_POST['profileName2']);
        snmp2_set($device_IP, "private", $oid['frequency2'], $type['frequency2'], $_POST['frequency2']*1000);
        snmp2_set($device_IP, "private", $oid['acquisitionBandwidth2'], $type['acquisitionBandwidth2'], $_POST['acquisitionBandwidth2']*1000);
        snmp2_set($device_IP, "private", $oid['symbolRate2'], $type['symbolRate2'], $_POST['symbolRate2']*1000);
        snmp2_set($device_IP, "private", $oid['goldCode2'], $type['goldCode2'], $_POST['goldCode2']);
        snmp2_set($device_IP, "private", $oid['isi2'], $type['isi2'], $_POST['isi2']);
        snmp2_set($device_IP, "private", $oid['lnbPower2'], $type['lnbPower2'], $_POST['lnbPower2']);
        if($_POST['signal22KHz2'] == "1"){
            snmp2_set($device_IP, "private", $oid['signal22KHz2'], $type['signal22KHz2'], $_POST['signal22KHz2']);
        }else{
            snmp2_set($device_IP, "private", $oid['signal22KHz2'], $type['signal22KHz2'], "0");
        }
        if($_POST['compensation2'] == "1"){
            snmp2_set($device_IP, "private", $oid['compensation2'], $type['compensation2'], $_POST['compensation2']);
        }else{
            snmp2_set($device_IP, "private", $oid['compensation2'], $type['compensation2'], "0");
        }
        rf1_conf2_read();
        rf1_conf1_read();
    }

    //rf2
    if($_GET['rf'] == "2"){

        snmp2_set($device_IP, "private", $oid['rx2CfgMode'], $type['rx2CfgMode'], $_POST['rx1CfgMode']);
        snmp2_set($device_IP, "private", $oid['rx2CfgSwitchPeriod'], $type['rx2CfgSwitchPeriod'], $_POST['rx1CfgSwitchPeriod']);

        snmp2_set($device_IP, "private", $oid['profileName3'], $type['profileName3'], $_POST['profileName1']);
        snmp2_set($device_IP, "private", $oid['frequency3'], $type['frequency3'], $_POST['frequency1']*1000);
        snmp2_set($device_IP, "private", $oid['acquisitionBandwidth3'], $type['acquisitionBandwidth3'], $_POST['acquisitionBandwidth1']*1000);
        snmp2_set($device_IP, "private", $oid['symbolRate3'], $type['symbolRate3'], $_POST['symbolRate1']*1000);
        snmp2_set($device_IP, "private", $oid['goldCode3'], $type['goldCode3'], $_POST['goldCode1']);
        snmp2_set($device_IP, "private", $oid['isi3'], $type['isi3'], $_POST['isi3']);

        snmp2_set($device_IP, "private", $oid['lnbPower3'], $type['lnbPower3'], $_POST['lnbPower1']);
        if($_POST['signal22KHz1'] == "1"){
            snmp2_set($device_IP, "private", $oid['signal22KHz3'], $type['signal22KHz3'], $_POST['signal22KHz1']);
        }else{
            snmp2_set($device_IP, "private", $oid['signal22KHz3'], $type['signal22KHz3'], "0");
        }
        if($_POST['compensation1'] == "1"){
            snmp2_set($device_IP, "private", $oid['compensation3'], $type['compensation3'], $_POST['compensation1']);
        }else{
            snmp2_set($device_IP, "private", $oid['compensation3'], $type['compensation3'], "0");
        }

        if($_POST['rx1CfgSetStatus'] == "1"){
            snmp2_set($device_IP, "private", $oid['rx2CfgSet1Status'], $type['rx2CfgSet1Status'], "0");
        } else {
            snmp2_set($device_IP, "private", $oid['rx2CfgSet1Status'], $type['rx2CfgSet1Status'], "1");
        }

        //rf2_conf2

        snmp2_set($device_IP, "private", $oid['profileName4'], $type['profileName4'], $_POST['profileName2']);
        snmp2_set($device_IP, "private", $oid['frequency4'], $type['frequency4'], $_POST['frequency2']*1000);
        snmp2_set($device_IP, "private", $oid['acquisitionBandwidth4'], $type['acquisitionBandwidth4'], $_POST['acquisitionBandwidth2']*1000);
        snmp2_set($device_IP, "private", $oid['symbolRate4'], $type['symbolRate4'], $_POST['symbolRate2']*1000);
        snmp2_set($device_IP, "private", $oid['goldCode4'], $type['goldCode4'], $_POST['goldCode2']);
        snmp2_set($device_IP, "private", $oid['isi4'], $type['isi4'], $_POST['isi2']);
        snmp2_set($device_IP, "private", $oid['lnbPower4'], $type['lnbPower4'], $_POST['lnbPower2']);
        if($_POST['signal22KHz2'] == "1"){
            snmp2_set($device_IP, "private", $oid['signal22KHz4'], $type['signal22KHz4'], $_POST['signal22KHz2']);
        }else{
            snmp2_set($device_IP, "private", $oid['signal22KHz4'], $type['signal22KHz4'], "0");
        }
        if($_POST['compensation2'] == "1"){
            snmp2_set($device_IP, "private", $oid['compensation4'], $type['compensation4'], $_POST['compensation2']);
        }else{
            snmp2_set($device_IP, "private", $oid['compensation4'], $type['compensation4'], "0");
        }

        rf2_conf1_read();
        rf2_conf2_read();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ayecka Device Manager</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" href="../style/style.css">
    <style>
        .rfTable td{
            padding-left: 5px;
        }
    </style>
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
        <li  <?php if($_GET['rf']=="1"){ echo "class='Active'";}?>> <a href="rf.php?rf=1">RF1</a></li>
        <li> | </li>
        <li  <?php if($_GET['rf']=="2"){ echo "class='Active'";}?>> <a href="rf.php?rf=2">RF2</a></li>
        <li> | </li>
        <li> <a href="rf_control.php">RF Control</a></li>
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
        <table border="0" class="rfTable">
            <tr><td>
                    <label>Switch Mode</label>
                    <select name="rx1CfgMode">
                        <option value="0"  <?php if($rx1CfgMode == "0"){echo "selected";}?>>Auto</option>
                        <option value="1"  <?php if($rx1CfgMode == "1"){echo "selected";}?>>Manual</option>
                        <option value="2"  <?php if($rx1CfgMode == "2"){echo "selected";}?>>Error</option>
                    </select>
                    <input type="number" name="period" min="3" max="30" size="2" value="<?php echo $rx1CfgSwitchPeriod; ?>">Sec
                </td></tr>
            <tr>
                <td><label>Configuration set active</label>
                    <label><input type="radio" value="1" name="rx1CfgSetStatus"  <?php  if($rx1CfgSetStatus == "1"){echo "checked";}?>>Configuration set 1</label>
                    <label><input type="radio" value="2" name="rx1CfgSetStatus"  <?php  if($rx1CfgSetStatus == "2"){echo "checked";}?>>Configuration set 2</label>
                </td>
            </tr>
            <tr>
                <td>
                    Configuration 1
                    <table border="0">
                        <tr>
                            <td>Profile Name</td>
                            <td><input type="text" value="<?php echo $profileName1;?>" name="profileName1"></td>
                        </tr>
                        <tr>
                            <td>Frequency</td>
                            <td><input type="text" value="<?php echo $frequency1?>" name="frequency1" maxlength="7"></td>
                            <td>MHZ</td>
                        </tr>
                        <tr>
                            <td>Acq. Range</td>
                            <td><input type="text" value="<?php echo $acquisitionBandwidth1;?>" name="acquisitionBandwidth1" maxlength="5"></td>
                            <td>Khz</td>
                        </tr>
                        <tr>
                            <td>Sym. Rate</td>
                            <td><input type="text" value="<?php echo $symbolRate1; ?>" name="symbolRate1"></td>
                            <td>Ksps</td>
                        </tr>
                        <tr>
                            <td>Gold Code</td>
                            <td><input type="text" value="<?php echo $goldCode1; ?>" name="goldCode1"></td>
                        </tr>
                        <tr>
                            <td>ISI</td>
                            <td><input type="text" value="<?php echo $isi1; ?>" name="isi1"></td>
                        </tr>
                        <tr>
                            <td>LNB Power</td>
                            <td><select name="lnbPower1">
                                    <option value="0"  <?php if($lnbPower1 == "0"){echo "selected";}?>>Disabled</option>
                                    <option value="1"  <?php if($lnbPower1 == "1"){echo "selected";}?>>13V</option>
                                    <option value="2"  <?php if($lnbPower1 == "2"){echo "selected";}?>>18V</option>
                                    <option value="3"  <?php if($lnbPower1 == "3"){echo "selected";}?>>Error</option>
                                </select> </td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" value="1" name="signal22KHz1"  <?php if($signal22KHz1 == "1"){echo "checked";}?>> 22 Khz</label></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" value="1" name="compensation1"  <?php if($compensation1 == "1"){echo "checked";}?>> Compensation</label></td>
                        </tr>
                    </table>

                </td>
                <td>
                    Configuration 2
                    <table border="0">
                        <tr>
                            <td>Profile Name</td>
                            <td><input type="text" value="<?php echo $profileName2;?>" name="profileName2"></td>
                        </tr>
                        <tr>
                            <td>Frequency</td>
                            <td><input type="text" value="<?php echo $frequency2?>" name="frequency2" maxlength="7"></td>
                            <td>MHZ</td>
                        </tr>
                        <tr>
                            <td>Acq. Range</td>
                            <td><input type="text" value="<?php echo $acquisitionBandwidth2;?>" name="acquisitionBandwidth2" maxlength="5"></td>
                            <td>Khz</td>
                        </tr>
                        <tr>
                            <td>Sym. Rate</td>
                            <td><input type="text" value="<?php echo $symbolRate2; ?>" name="symbolRate2"></td>
                            <td>Ksps</td>
                        </tr>
                        <tr>
                            <td>Gold Code</td>
                            <td><input type="text" value="<?php echo $goldCode2; ?>" name="goldCode2"></td>
                        </tr>
                        <tr>
                            <td>ISI</td>
                            <td><input type="text" value="<?php echo $isi2; ?>" name="isi2"></td>
                        </tr>
                        <tr>
                            <td>LNB Power</td>
                            <td><select name="lnbPower2">
                                    <option value="0"  <?php if($lnbPower2 == "0"){echo "selected";}?>>Disabled</option>
                                    <option value="1"  <?php if($lnbPower2 == "1"){echo "selected";}?>>13V</option>
                                    <option value="2"  <?php if($lnbPower2 == "2"){echo "selected";}?>>18V</option>
                                    <option value="3"  <?php if($lnbPower2 == "3"){echo "selected";}?>>Error</option>
                                </select> </td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" value="1" name="signal22KHz2"  <?php if($signal22KHz2 == "1"){echo "checked";}?>> 22 Khz</label></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" value="1" name="compensation2"  <?php if($compensation2 == "1"){echo "checked";}?>> Compensation</label></td>
                        </tr>
                    </table>

                </td>

            </tr>
        </table>
    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>