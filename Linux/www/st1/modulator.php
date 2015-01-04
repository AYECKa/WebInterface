<?php
session_start();
error_reporting(0);
$read = $_SESSION['read'];
$device_IP = $_SESSION['device_IP'];
$ver = $_SESSION['stVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
$modcod = $_SESSION['modcod'];

require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
modcodarray();


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

    $modSymbolRate = substr(snmp2_get($device_IP,"public",$oid['modSymbolRate']), 9);
    $modModcod = substr(snmp2_get($device_IP,"public",$oid['modModcod']), 9);
    $modPilots = substr(snmp2_get($device_IP,"public",$oid['modPilots']), 9);
    $modFrameType = substr(snmp2_get($device_IP,"public",$oid['modFrameType']), 9);
    $modRollOff = substr(snmp2_get($device_IP,"public",$oid['modRollOff']), 9);
    $modDvbs2Profile = substr(snmp2_get($device_IP,"public",$oid['modDvbs2Profile']), 9);
    $modScramblerSeed = substr(snmp2_get($device_IP,"public",$oid['modScramblerSeed']), 9);
    $modPowerState = substr(snmp2_get($device_IP,"public",$oid['modPowerState']), 9);
    $modSpectrumInversion = substr(snmp2_get($device_IP,"public",$oid['modSpectrumInversion']), 9);
    $modCarrierAtOutput = substr(snmp2_get($device_IP,"public",$oid['modCarrierAtOutput']), 9);
}

//write

if (isset($_POST['write']) AND $read == "read"){

    snmp2_set($device_IP, "private", $oid['modSymbolRate'], $type['modSymbolRate'], $_POST['modSymbolRate']);
    snmp2_set($device_IP, "private", $oid['modModcod'], $type['modModcod'], $_POST['modModcod']);
    snmp2_set($device_IP, "private", $oid['modPilots'], $type['modPilots'], $_POST['modPilots']);
    snmp2_set($device_IP, "private", $oid['modFrameType'], $type['modFrameType'], $_POST['modFrameType']);
    snmp2_set($device_IP, "private", $oid['modRollOff'], $type['modRollOff'], $_POST['modRollOff']);
    snmp2_set($device_IP, "private", $oid['modDvbs2Profile'], $type['modDvbs2Profile'], $_POST['modDvbs2Profile']);
    snmp2_set($device_IP, "private", $oid['modScramblerSeed'], $type['modScramblerSeed'], $_POST['modScramblerSeed']);
    snmp2_set($device_IP, "private", $oid['modPowerState'], $type['modPowerState'], $_POST['modPowerState']);
    snmp2_set($device_IP, "private", $oid['modSpectrumInversion'], $type['modSpectrumInversion'], $_POST['modSpectrumInversion']);
    snmp2_set($device_IP, "private", $oid['modCarrierAtOutput'], $type['modCarrierAtOutput'], $_POST['modCarrierAtOutput']);

    //Read
    $modSymbolRate = substr(snmp2_get($device_IP,"public",$oid['modSymbolRate']), 9);
    $modModcod = substr(snmp2_get($device_IP,"public",$oid['modModcod']), 9);
    $modPilots = substr(snmp2_get($device_IP,"public",$oid['modPilots']), 9);
    $modFrameType = substr(snmp2_get($device_IP,"public",$oid['modFrameType']), 9);
    $modRollOff = substr(snmp2_get($device_IP,"public",$oid['modRollOff']), 9);
    $modDvbs2Profile = substr(snmp2_get($device_IP,"public",$oid['modDvbs2Profile']), 9);
    $modScramblerSeed = substr(snmp2_get($device_IP,"public",$oid['modScramblerSeed']), 9);
    $modPowerState = substr(snmp2_get($device_IP,"public",$oid['modPowerState']), 9);
    $modSpectrumInversion = substr(snmp2_get($device_IP,"public",$oid['modSpectrumInversion']), 9);
    $modCarrierAtOutput = substr(snmp2_get($device_IP,"public",$oid['modCarrierAtOutput']), 9);
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
        <li> <a href="tx.php">TX Configuration</a></li>
        <li> | </li>
        <li class="Active"> <a href="modulator.php">Modulator Configuration</a></li>
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
                <td>Symbol Rate</td>
                <td><input type="text" value="<?php echo $modSymbolRate;?>" name="modSymbolRate"></td>
                <td>sym/sec</td>
            </tr><tr>
                <td>MODCOD</td>
                <td><select name="modModcod">
                        <?php

                        for($i = 0; $i < 29; $i++){
                            echo '<option value="'.$i.'"';
                            if($modModcod==$i){
                                echo "selected";
                            }
                            echo '>'.$modcod[$i]."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr><tr>
                <td>Pilots Insertion</td>
                <td><select name="modPilots">
                        <option value="1" <?php if($modPilots == "1"){echo "selected";}?>>Off</option>
                        <option value="0" <?php if($modPilots == "0"){echo "selected";}?>>On</option>
                    </select></td>
            </tr><tr>
                <td>Frame Size</td>
                <td><select name="modFrameType">
                        <option value="0" <?php if($modFrameType == "0"){echo "selected";}?>>Normal</option>
                        <option value="1" <?php if($modFrameType == "1"){echo "selected";}?>>Short</option>
                    </select></td>
            </tr><tr>
                <td>Roll Off</td>
                <td><select name="modRollOff">
                        <option value="0" <?php if($modRollOff == "0"){echo "selected";}?>>20%</option>
                        <option value="1" <?php if($modRollOff == "1"){echo "selected";}?>>25%</option>
                        <option value="2" <?php if($modRollOff == "2"){echo "selected";}?>>35%</option>
                        <option value="3" <?php if($modRollOff == "3"){echo "selected";}?>>5%</option>
                        <option value="4" <?php if($modRollOff == "4"){echo "selected";}?>>10%</option>
                        <option value="5" <?php if($modRollOff == "5"){echo "selected";}?>>15%</option>
                    </select></td>
            </tr><tr>
                <td>DVB-S2 Profile</td>
                <td><select name="modDvbs2Profile">
                        <option value="0" <?php if($modDvbs2Profile == "0"){echo "selected";}?>>CCM</option>
                        <option value="1" <?php if($modDvbs2Profile == "1"){echo "selected";}?>>VCM</option>
                        <option value="2" <?php if($modDvbs2Profile == "2"){echo "selected";}?>>ACM</option>
                    </select></td>
            </tr><tr>
                <td>Scrambler Seed</td>
                <td><input type="number" min="1" max="262143" value="<?php echo $modScramblerSeed;?>" name="modScramblerSeed"></td>
            </tr><tr>
                <td>Status</td>
                <td><select name="modPowerState">
                        <option value="0" <?php if($modPowerState == "0"){echo "selected";}?>>Enabled</option>
                        <option value="1" <?php if($modPowerState == "1"){echo "selected";}?>>Disabled</option>
                    </select></td>
            </tr><tr>
                <td>Spectral Inversion</td>
                <td><select name="modSpectrumInversion">
                        <option value="1" <?php if($modSpectrumInversion == "1"){echo "selected";}?>>On</option>
                        <option value="2" <?php if($modSpectrumInversion == "2"){echo "selected";}?>>Off</option>
                    </select></td>
            </tr><tr>
                <td>Carrier At Output</td>
                <td><select name="modCarrierAtOutput">
                        <option value="0" <?php if($modCarrierAtOutput == "0"){echo "selected";}?>>Not Forced</option>
                        <option value="1" <?php if($modCarrierAtOutput == "1"){echo "selected";}?>>Forced</option>
                    </select></td>
            </tr>

        </table>
        </form>

    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>