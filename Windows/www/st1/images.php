<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP'];
$read = $_SESSION['read'];
$ver = $_SESSION['stVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
$swRow1 = $_SESSION['swRow1'];
$swRow2 = $_SESSION['swRow2'];

$fwRow1 = $_SESSION['fwRow1'];
$fwRow2 = $_SESSION['fwRow2'];

$bl = $_SESSION['bl'];


require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();
tableArray();


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
    //SWTable
    $softwareVersionValue1 =  substr(snmp2_get($device_IP,"public",$swRow1['softwareVersionValue']), 9);
    $softwareVersionSize1 = substr(snmp2_get($device_IP,"public",$swRow1['softwareVersionSize']), 9);
    $softwareVersionValid1 = substr(snmp2_get($device_IP,"public",$swRow1['softwareVersionValid']), 9);
    $softwareVersionActive1 = substr(snmp2_get($device_IP,"public",$swRow1['softwareVersionActive']), 9);

    $softwareVersionValue2 =  substr(snmp2_get($device_IP,"public",$swRow2['softwareVersionValue']), 9);
    $softwareVersionSize2 = substr(snmp2_get($device_IP,"public",$swRow2['softwareVersionSize']), 9);
    $softwareVersionValid2 = substr(snmp2_get($device_IP,"public",$swRow2['softwareVersionValid']), 9);
    $softwareVersionActive2 = substr(snmp2_get($device_IP,"public",$swRow2['softwareVersionActive']), 9);

    //FWTable
    $fpgaVersionValue1 =  substr(snmp2_get($device_IP,"public",$fwRow1['fpgaVersionValue']), 9);
    $fpgaVersionSize1 = substr(snmp2_get($device_IP,"public",$fwRow1['fpgaVersionSize']), 9);
    $fpgaVersionValid1 = substr(snmp2_get($device_IP,"public",$fwRow1['fpgaVersionValid']), 9);
    $fpgaVersionActive1 = substr(snmp2_get($device_IP,"public",$fwRow1['fpgaVersionActive']), 9);

    $fpgaVersionValue2 =  substr(snmp2_get($device_IP,"public",$fwRow2['fpgaVersionValue']), 9);
    $fpgaVersionSize2 = substr(snmp2_get($device_IP,"public",$fwRow2['fpgaVersionSize']), 9);
    $fpgaVersionValid2 = substr(snmp2_get($device_IP,"public",$fwRow2['fpgaVersionValid']), 9);
    $fpgaVersionActive2 = substr(snmp2_get($device_IP,"public",$fwRow2['fpgaVersionActive']), 9);

    //BL
    $blVersionValue1 =  substr(snmp2_get($device_IP,"public",$bl['blVersionValue']), 9);
    $blVersionSize1 = substr(snmp2_get($device_IP,"public",$bl['blVersionSize']), 9);
    $blVersionValid1 = substr(snmp2_get($device_IP,"public",$bl['blVersionValid']), 9);
    $blVersionActive1 = substr(snmp2_get($device_IP,"public",$bl['blVersionActive']), 9);


    //upgrade
    $tftpServerIp =  substr(snmp2_get($device_IP,"public",$oid['tftpServerIp']), 11);
    $softwareImageFilename =  substr(snmp2_get($device_IP,"public",$oid['softwareImageFilename']), 9);
    $fpgaImageFilename =  substr(snmp2_get($device_IP,"public",$oid['fpgaImageFilename']), 9);
    $bootloaderImageFilename =  substr(snmp2_get($device_IP,"public",$oid['bootloaderImageFilename']), 9);
}

//    Write
if (isset($_POST['write']) AND $read == "read"){
    if($_POST['softwareVersionActive'] == 1){
        snmp2_set($device_IP, "private", $swRow1['softwareVersionActive'], "i", "1");
        snmp2_set($device_IP, "private", $swRow2['softwareVersionActive'], "i", "0");
    } else {
        snmp2_set($device_IP, "private", $swRow1['softwareVersionActive'], "i", "0");
        snmp2_set($device_IP, "private", $swRow2['softwareVersionActive'], "i", "1");
    }

    if($_POST['fpgaVersionActive'] == 1){
        snmp2_set($device_IP, "private", $fwRow1['fpgaVersionActive'], "i", "1");
        snmp2_set($device_IP, "private", $fwRow2['fpgaVersionActive'], "i", "0");
    } else {
        snmp2_set($device_IP, "private", $fwRow1['fpgaVersionActive'], "i", "0");
        snmp2_set($device_IP, "private", $fwRow2['fpgaVersionActive'], "i", "1");
    }

    //UpgradeInfo
    snmp2_set($device_IP, "private", $oid['tftpServerIp'], $type['tftpServerIp'], $_POST['tftpServerIp']);
    snmp2_set($device_IP, "private", $oid['softwareImageFilename'], $type['softwareImageFilename'], $_POST['softwareImageFilename']);
    snmp2_set($device_IP, "private", $oid['fpgaImageFilename'], $type['fpgaImageFilename'], $_POST['fpgaImageFilename']);
    snmp2_set($device_IP, "private", $oid['bootloaderImageFilename'], $type['bootloaderImageFilename'], $_POST['bootloaderImageFilename']);

    // read
    $softwareVersionActive2 = substr(snmp2_get($device_IP,"public",$swRow2['softwareVersionActive']), 9);
    $softwareVersionActive1 = substr(snmp2_get($device_IP,"public",$swRow1['softwareVersionActive']), 9);
    $fpgaVersionActive2 = substr(snmp2_get($device_IP,"public",$fwRow2['fpgaVersionActive']), 9);
    $fpgaVersionActive1 = substr(snmp2_get($device_IP,"public",$fwRow1['fpgaVersionActive']), 9);

    $tftpServerIp =  substr(snmp2_get($device_IP,"public",$oid['tftpServerIp']), 11);
    $softwareImageFilename =  substr(snmp2_get($device_IP,"public",$oid['softwareImageFilename']), 9);
    $fpgaImageFilename =  substr(snmp2_get($device_IP,"public",$oid['fpgaImageFilename']), 9);
    $bootloaderImageFilename =  substr(snmp2_get($device_IP,"public",$oid['bootloaderImageFilename']), 9);

}
if(isset($_POST['startSoftwareUpgrade'])){
    snmp2_set($device_IP, "private", $oid['tftpServerIp'], $type['tftpServerIp'], $_POST['tftpServerIp']);
    snmp2_set($device_IP, "private", $oid['softwareImageFilename'], $type['softwareImageFilename'], $_POST['softwareImageFilename']);
    snmp2_set($device_IP, "private", $oid['startSoftwareUpgrade'], $type['startSoftwareUpgrade'], '1');
    echo '<script type="text/javascript">alert("Please wait 1 minute and read from device again.")</script>';

}

if(isset($_POST['startFpgaUpgrade'])){
    snmp2_set($device_IP, "private", $oid['tftpServerIp'], $type['tftpServerIp'], $_POST['tftpServerIp']);
    snmp2_set($device_IP, "private", $oid['fpgaImageFilename'], $type['fpgaImageFilename'], $_POST['fpgaImageFilename']);
    snmp2_set($device_IP, "private", $oid['startFpgaUpgrade'], $type['startFpgaUpgrade'], '1');
    echo '<script type="text/javascript">alert("Please wait 1 minute and read from device again.")</script>';

}

if(isset($_POST['startBootloaderDownload'])){
    snmp2_set($device_IP, "private", $oid['tftpServerIp'], $type['tftpServerIp'], $_POST['tftpServerIp']);
    snmp2_set($device_IP, "private", $oid['bootloaderImageFilename'], $type['bootloaderImageFilename'], $_POST['bootloaderImageFilename']);
    snmp2_set($device_IP, "private", $oid['startBootloaderDownload'], $type['startBootloaderDownload'], '1');
    echo '<script type="text/javascript">alert("Please wait 1 minute and read from device again.")</script>';

}

if(isset($_POST['startBootloaderUpgrade'])){
    snmp2_set($device_IP, "private", $oid['startBootloaderUpgrade'], $type['startBootloaderUpgrade'], '1');
    echo '<script type="text/javascript">alert("Please wait 1 minute and read from device again.")</script>';
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
        <li> <a href="vrrp.php">VRRP</a></li>
        <li> | </li>
        <li> <a href="system.php">System</a></li>
        <li> | </li>
        <li class="Active"> <a href="images.php">Images</a></li>
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

<div class="mainWrapperImages">
    <center>
        <table border="0">
            <tr>
                <td>
                    <b>SW Images Table</b>
                    <table>
                        <tr>
                            <td></td>
                            <td>Version</td>
                            <td>Size</td>
                            <td>Valid</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>SW Image #1</td>
                            <td><input type="text" name="softwareVersionValue1" value="<?php echo $softwareVersionValue1; ?>" readonly></td>
                            <td><input type="text" name="softwareVersionSize1" value="<?php echo $softwareVersionSize1; ?>" readonly></td>
                            <td><input type="checkbox" value="1" name="softwareVersionValid1" <?php if($softwareVersionValid1 == 1){echo "checked";}?> disabled></td>
                            <td><input type="radio" name="softwareVersionActive" value="1" <?php if($softwareVersionActive1 == 1){echo "checked";}?>></td>
                        </tr>
                        <tr>
                            <td>SW Image #2</td>
                            <td><input type="text" name="softwareVersionValue2" value="<?php echo $softwareVersionValue2; ?>" readonly></td>
                            <td><input type="text" name="softwareVersionSize2" value="<?php echo $softwareVersionSize2; ?>" readonly></td>
                            <td><input type="checkbox" value="2" name="softwareVersionValid1" <?php if($softwareVersionValid2 == 1){echo "checked";}?> disabled></td>
                            <td><input type="radio" name="softwareVersionActive" value="2" <?php if($softwareVersionActive2 == 1){echo "checked";}?>></td>
                        </tr>
                    </table>
                    <br><br>
                    <b>FPGA Images Table</b>
                    <table>
                        <tr>
                            <td></td>
                            <td>Version</td>
                            <td>Size</td>
                            <td>Valid</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>FW Image #1</td>
                            <td><input type="text" name="fpgaVersionValue1" value="<?php echo $fpgaVersionValue1; ?>" readonly></td>
                            <td><input type="text" name="fpgaVersionSize1" value="<?php echo $fpgaVersionSize1; ?>" readonly></td>
                            <td><input type="checkbox" value="1" name="fpgaVersionValid1" <?php if($fpgaVersionValid1 == 1){echo "checked";}?> disabled></td>
                            <td><input type="radio" name="fpgaVersionActive" value="1" <?php if($fpgaVersionActive1 == 1){echo "checked";}?>></td>
                        </tr>
                        <tr>
                            <td>FW Image #2</td>
                            <td><input type="text" name="fpgaVersionValue2" value="<?php echo $fpgaVersionValue2; ?>" readonly></td>
                            <td><input type="text" name="softwareVersionSize2" value="<?php echo $fpgaVersionSize2; ?>" readonly></td>
                            <td><input type="checkbox" value="2" name="fpgaVersionValid1" <?php if($fpgaVersionValid2 == 1){echo "checked";}?> disabled></td>
                            <td><input type="radio" name="fpgaVersionActive" value="2" <?php if($fpgaVersionActive2 == 1){echo "checked";}?>></td>
                        </tr>
                    </table>
                    <br><br>
                    <b>BL Image Table</b>
                    <table>
                        <tr>
                            <td></td>
                            <td>Version</td>
                            <td>Size</td>
                            <td>Valid</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>BL Image #1</td>
                            <td><input type="text" name="blVersionValue1" value="<?php echo $blVersionValue1; ?>" readonly></td>
                            <td><input type="text" name="blVersionSize1" value="<?php echo $blVersionSize1; ?>" readonly></td>
                            <td><input type="checkbox" value="1" name="blVersionValid1" <?php if($blVersionValid1 == 1){echo "checked";}?> disabled></td>
                            <td><input type="radio" name="blVersionActive" value="1" <?php if($blVersionActive1 == 1){echo "checked";}?>></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table border="0">
                        <tr>
                            <td colspan="3">TFTP Server IP Address <input type="text" name="tftpServerIp" value="<?php echo $tftpServerIp;?>"></td>
                        </tr>
                        <tr>
                            <td>SW File Name</td>
                            <td><input type="text" name="softwareImageFilename" value="<?php echo $softwareImageFilename;?>"></td>
                            <td><input type="submit" value="Start Download" name="startSoftwareUpgrade"></td>
                        </tr>
                        <tr>
                            <td>FW File Name</td>
                            <td><input type="text" name="fpgaImageFilename" value="<?php echo $fpgaImageFilename; ?>"></td>
                            <td><input type="submit" value="Start Download" name="startFpgaUpgrade"></td>
                        </tr>
                        <tr>
                            <td>BL File Name</td>
                            <td><input type="text" name="bootloaderImageFilename" value="<?php echo $bootloaderImageFilename;?>"></td>
                            <td><input type="submit" value="Start Download" name="startBootloaderDownload"></td>
                            <td><input type="submit" value="Start BL Upgrade" name="startBootloaderUpgrade"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            </form>
        </table>
    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>