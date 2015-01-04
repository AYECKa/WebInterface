<?php
session_start();
error_reporting(0);
$device_IP = $_SESSION['device_IP_st'];
$read = $_SESSION['read_st'];
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

    $_SESSION['device_IP_st'] = $device_IP;
    $_SESSION['read_st'] = "read";
//    Board Information
    $hardwareVersion = substr(snmp2_get($device_IP,"public",$oid['hardwareVersion']), 9);
    $fpgaVersion = substr(snmp2_get($device_IP,"public",$oid['fpgaVersion']), 9);
    $softwareVersion = substr(snmp2_get($device_IP,"public",$oid['softwareVersion']), 9);
    $serialNumber =  substr(snmp2_get($device_IP,"public",$oid['serialNumber']), 9);
// system info
    include_once('info_function.php');

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
            <br><h4><strong><a href="http://www.ayecka.com/ST1.html">ST1</a></strong> - Satellite Transmitter, IP over DVB-S2</h4>
        </div>
        <div class="col-lg-1"><img src="../images/slogen2.png" class="pull-right"></div>
    </div>
</div>

<?php
$active = "images";
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
            <div class="col-md-1"></div>
            <div class="col-md-5 text-left">
                <h4><span class="label label-success">SW Image Table</span></h4>
                <table class="table table-hover table-condensed table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Version</th>
                        <th>Size</th>
                        <th>Valid</th>
                        <th>Active</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Image #1</th>
                        <td><input class="form-control input-sm" type="text" name="softwareVersionValue1" value="<?php echo $softwareVersionValue1; ?>" readonly></td>
                        <td><input class="form-control input-sm" type="text" name="softwareVersionSize1" value="<?php echo $softwareVersionSize1; ?>" readonly></td>
                        <td>
                            <?php if($softwareVersionValid1 == 1){
                                echo '<span class="label label-success">Valid</span>';
                            }else{
                                echo '<span class="label label-danger">Not Valid</span>';
                            }?>
                        </td>
                        <td><input type="radio" name="softwareVersionActive" value="1" <?php if($softwareVersionActive1 == 1){echo "checked";}?>></td>
                    </tr>
                    <tr>
                        <th>Image #2</th>
                        <td><input class="form-control input-sm" type="text" name="softwareVersionValue2" value="<?php echo $softwareVersionValue2; ?>" readonly></td>
                        <td><input class="form-control input-sm" type="text" name="softwareVersionSize2" value="<?php echo $softwareVersionSize2; ?>" readonly></td>
                        <td>
                            <?php if($softwareVersionValid2 == 1){
                                echo '<span class="label label-success">Valid</span>';
                            }else{
                                echo '<span class="label label-danger">Not Valid</span>';
                            }?>
                        </td>
                        <td><input type="radio" name="softwareVersionActive" value="2" <?php if($softwareVersionActive2 == 1){echo "checked";}?>></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <h4><span class="label label-success">FPGA Image Table</span></h4>
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Version</th>
                        <th>Size</th>
                        <th>Valid</th>
                        <th>Active</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Image #1</th>
                        <td><input class="form-control input-sm" type="text" name="fpgaVersionValue1" value="<?php echo $fpgaVersionValue1; ?>" readonly></td>
                        <td><input class="form-control input-sm" type="text" name="fpgaVersionSize1" value="<?php echo $fpgaVersionSize1; ?>" readonly></td>
                        <td>
                            <?php if($fpgaVersionValid1 == 1){
                                echo '<span class="label label-success">Valid</span>';
                            }else{
                                echo '<span class="label label-danger">Not Valid</span>';
                            }?>
                        </td>
                        <td><input type="radio" name="fpgaVersionActive" value="1"  <?php if($fpgaVersionActive1 == 1){echo "checked";}?>></td>
                    </tr>
                    <tr>
                        <th>Image #2</th>
                        <td><input class="form-control input-sm" type="text" name="fpgaVersionValue2" value="<?php echo $fpgaVersionValue2; ?>" readonly></td>
                        <td><input class="form-control input-sm" type="text" name="softwareVersionSize2" value="<?php echo $fpgaVersionSize2; ?>" readonly></td>
                        <td>
                            <?php if($fpgaVersionValid2 == 1){
                                echo '<span class="label label-success">Valid</span>';
                            }else{
                                echo '<span class="label label-danger">Not Valid</span>';
                            }?>
                        </td>
                        <td><input type="radio" name="fpgaVersionActive" value="2"  <?php if($fpgaVersionActive2 == 1){echo "checked";}?>></td>
                    </tr>

                    </tbody>
                </table>
                <br>
                <h4><span class="label label-success">BL Image Table</span></h4>
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Version</th>
                        <th>Size</th>
                        <th>Valid</th>
                        <th>Active</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Image #1</th>
                        <td><input class="form-control input-sm" type="text" name="blVersionValue1" value="<?php echo $blVersionValue1; ?>" readonly></td>
                        <td><input class="form-control input-sm" type="text" name="blVersionSize1" value="<?php echo $blVersionSize1; ?>" readonly></td>
                        <td>
                            <?php if($blVersionValid1 == 1){
                                echo '<span class="label label-success">Valid</span>';
                            }else{
                                echo '<span class="label label-danger">Not Valid</span>';
                            }?>
                        </td>
                        <td><input type="radio" name="blVersionActive" value="1"  <?php if($blVersionActive1 == 1){echo "checked";}?>></td>
                    </tr>

                    </tbody>
                </table>


            </div>

            <div class="col-md-5 text-left">
                <table class="table table-hover table-condensed">
                    <tbody>
                    <tr>
                        <td>TFTP Server IP Address</td>
                        <td><input class="form-control input-sm" type="text" name="tftpServerIp" value="<?php echo $tftpServerIp;?>"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>SW File Name</td>
                        <td><input class="form-control input-sm" type="text" name="softwareImageFilename" value="<?php echo $softwareImageFilename;?>"></td>
                        <td><input class="btn btn-xs btn-info" type="submit" value="Start Download" name="startSoftwareUpgrade"></td>
                    </tr>
                    <tr>
                        <td>FW File Name</td>
                        <td><input class="form-control input-sm" type="text" name="fpgaImageFilename" value="<?php echo $fpgaImageFilename; ?>"></td>
                        <td><input class="btn btn-xs btn-info" type="submit" value="Start Download" name="startFpgaUpgrade"></td>
                    </tr>
                    <tr>
                        <td>BL File Name</td>
                        <td><input class="form-control input-sm" type="text" name="bootloaderImageFilename" value="<?php echo $bootloaderImageFilename;?>"></td>
                        <td><input class="btn btn-xs btn-info" type="submit" value="Start Download" name="startBootloaderDownload">
                            <input class="btn btn-xs btn-warning" style="margin-top: 10px;" type="submit" value="Start BL Upgrade" name="startBootloaderUpgrade">
                        </td>
                    </tr>

                    </tbody>

                </table>
            </div>
</form>
<div class="col-md-1"></div>
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