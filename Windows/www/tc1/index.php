<?php
session_start();
error_reporting(0);
$read = $_SESSION['read'];
$device_IP = $_SESSION['device_IP'];
$ver = $_SESSION['tcVer'];
$refreshTime = $_SESSION['refreshTime'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
$modcod = $_SESSION['modcod'];
$pilot = $_SESSION['pilot'];
$tunerStatus = $_SESSION['tunerStatus'];
$rolloff = $_SESSION['rolloff'];
require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
modcodarray();
pilotarray();
tunerStatus();
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
    $tunerStatus1 = substr(snmp2_get($device_IP,"public",$oid['tunerStatus1']), 9);

    if($tunerStatus1 == "0" OR $_GET['admin']=="1"){
        $frequency1 = substr(snmp2_get($device_IP,"public",$oid['frequency1']), 9)/1000;
        $esno1 = substr(snmp2_get($device_IP,"public",$oid['esno1']), 9)/10;
        $powerLevel1 = substr(snmp2_get($device_IP,"public",$oid['powerLevel1']), 9)/10;
        $ber1 = substr(snmp2_get($device_IP,"public",$oid['ber1']), 9);
        $frequencyOffset1 = substr(snmp2_get($device_IP,"public",$oid['frequencyOffset1']), 9)/1000;
        $modcodStatus1 = substr(snmp2_get($device_IP,"public",$oid['modcodStatus1']), 9);
        $rollOffStatus1 = substr(snmp2_get($device_IP,"public",$oid['rollOffStatus1']), 9);
        $pilotsStatus1 = substr(snmp2_get($device_IP,"public",$oid['pilotsStatus1']), 9);

        $rx1CfgSet1Status = substr(snmp2_get($device_IP,"public",$oid['rx1CfgSet1Status']), 9);
        $rx1CfgSet2Status = substr(snmp2_get($device_IP,"public",$oid['rx1CfgSet2Status']), 9);

        if($rx1CfgSet1Status == 0){
            $rx1ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName1']), 9);
        }
        if($rx1CfgSet2Status == 0){
            $rx1ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName2']), 9);
        }

    }

//    RF2
    $tunerStatus2 = substr(snmp2_get($device_IP,"public",$oid['tunerStatus2']), 9);
    if($tunerStatus2 == "0" OR $_GET['admin']=="1"){
        $frequency3 = substr(snmp2_get($device_IP,"public",$oid['frequency3']), 9)/1000;
        $esno2 = substr(snmp2_get($device_IP,"public",$oid['esno2']), 9)/10;
        $powerLevel2 = substr(snmp2_get($device_IP,"public",$oid['powerLevel2']), 9)/10;
        $ber2 = substr(snmp2_get($device_IP,"public",$oid['ber2']), 9);
        $frequencyOffset2 = substr(snmp2_get($device_IP,"public",$oid['frequencyOffset2']), 9)/1000;
        $modcodStatus2 = substr(snmp2_get($device_IP,"public",$oid['modcodStatus2']), 9);
        $rollOffStatus2 = substr(snmp2_get($device_IP,"public",$oid['rollOffStatus2']), 9);
        $pilotsStatus2 = substr(snmp2_get($device_IP,"public",$oid['pilotsStatus2']), 9);

        $rx2CfgSet1Status = substr(snmp2_get($device_IP,"public",$oid['rx2CfgSet1Status']), 9);
        $rx2CfgSet2Status = substr(snmp2_get($device_IP,"public",$oid['rx2CfgSet2Status']), 9);

        if($rx2CfgSet1Status == 0){
            $rx2ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName3']), 9);
        }
        if($rx2CfgSet2Status == 0){
            $rx2ActiveProfile = '"'.substr(snmp2_get($device_IP,"public",$oid['profileName4']), 9);
        }
    }

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
    <h2>TC1</h2>
</header>
<nav id="aymenu">
    <ul id="MenuList">
        <li class="Active"> <a href="index.php ">Status</a></li>
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
<div class="mainWrapper">
    <center>
        <table border="0">
            <tr><td>
                    RX1 <?php echo $rx1ActiveProfile; ?>
                    <table border="0">
                        <tr>
                            <td>Lock</td>
                            <td><?php echo $tunerStatus[$tunerStatus1]; ?></td>
                        </tr><tr>
                            <td>Frequency</td>
                            <td><input type="text" value="<?php echo $frequency1; ?>" name="frequency1" readonly></td>
                            <td>MHZ</td>
                        </tr><tr>
                            <td>EsNo</td>
                            <td><input type="text" value="<?php echo $esno1; ?>" name="esno1" readonly></td>
                            <td>db</td>
                        </tr><tr>
                            <td>Power</td>
                            <td><input type="text" value="<?php echo $powerLevel1; ?>" name="powerLevel1" readonly></td>
                            <td>dbm</td>
                        </tr><tr>
                            <td>Demod BER</td>
                            <td><input type="text" value="<?php echo $ber1; ?>" name="ber1" readonly></td>
                            <td>10e7</td>
                        </tr><tr>
                            <td>Offset</td>
                            <td><input type="text" value="<?php echo $frequencyOffset1; ?>" name="frequencyOffset1" readonly></td>
                            <td>Khz</td>
                        </tr><tr>
                            <td>MODCOD</td>
                            <td><input type="text" value="<?php echo $modcod[$modcodStatus1]; ?>" name="modcodStatus1" readonly></td>
                        </tr><tr>
                            <td>Roll Off</td>
                            <td><input type="text" value="<?php echo $rolloff[$rollOffStatus1]; ?>" name="rollOffStatus1" readonly></td>
                            <td>%</td>
                        </tr><tr>
                            <td>Pilot</td>
                            <td><input type="text" value="<?php echo $pilot[$pilotsStatus1]; ?>" name="pilotsStatus1" readonly></td>
                        </tr>
                    </table>
                </td><td>


                    RX2 <?php echo $rx2ActiveProfile; ?>

                    <table>
                        <tr>
                            <td>Lock</td>
                            <td><?php echo $tunerStatus[$tunerStatus2]; ?></td>
                        </tr><tr>
                            <td>Frequency</td>
                            <td><input type="text" value="<?php echo $frequency3; ?>" name="frequency3" readonly></td>
                            <td>MHZ</td>
                        </tr><tr>
                            <td>EsNo</td>
                            <td><input type="text" value="<?php echo $esno2; ?>" name="esno2" readonly></td>
                            <td>db</td>
                        </tr><tr>
                            <td>Power</td>
                            <td><input type="text" value="<?php echo $powerLevel2; ?>" name="powerLevel2" readonly></td>
                            <td>dbm</td>
                        </tr><tr>
                            <td>Demod BER</td>
                            <td><input type="text" value="<?php echo $ber2; ?>" name="ber2" readonly></td>
                            <td>10e7</td>
                        </tr><tr>
                            <td>Offset</td>
                            <td><input type="text" value="<?php echo $frequencyOffset2; ?>" name="frequencyOffset2" readonly></td>
                            <td>Khz</td>
                        </tr><tr>
                            <td>MODCOD</td>
                            <td><input type="text" value="<?php echo $modcod[$modcodStatus2]; ?>" name="modcodStatus2" readonly></td>
                        </tr><tr>
                            <td>Roll Off</td>
                            <td><input type="text" value="<?php echo $rolloff[$rollOffStatus2]; ?>" name="rollOffStatus2" readonly></td>
                            <td>%</td>
                        </tr><tr>
                            <td>Pilot</td>
                            <td><input type="text" value="<?php echo $pilot[$pilotsStatus2]; ?>" name="pilotsStatus2" readonly></td>
                        </tr>

                    </table>
                    </form>
                </td></tr>
            <tr>
                <td><form method="post">This page automatically refreshes every <input type="number" min="10" max="360" name="refreshTime" value="<?php  echo $refreshTime;?>"> seconds <input type="submit" name="update" value="Update"> </form></td>
            </tr>
        </table>
    </center>
</div>
<footer><span class="ver"><?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>