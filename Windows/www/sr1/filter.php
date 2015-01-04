<?php
session_start();
error_reporting(0);
$read = $_SESSION['read_sr'];
$device_IP = $_SESSION['device_IP_sr'];
$ver = $_SESSION['srVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
$readTable = "";

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

//  Choose Read Table
    if(isset($_POST['filterTableSubmit'])){
        //table chosen
        $readTable = $_POST['filterTable'];

        //Value
        $walk = snmpwalk($device_IP, "public", $oid[$readTable]);

        for($i=0; $i<=64; $i += 8){
            $filter1[] = substr($walk[$i],9);
        }
        for($i=1; $i<=65; $i += 8){
            $filter2[] = substr($walk[$i],9);
        }
        for($i=2; $i<=66; $i += 8){
            $filter3[] = substr($walk[$i],9);
        }
        for($i=3; $i<=67; $i += 8){
            $filter4[] = substr($walk[$i],9);
        }
        for($i=4; $i<=68; $i += 8){
            $filter5[] = substr($walk[$i],9);
        }
        for($i=5; $i<=69; $i += 8){
            $filter6[] = substr($walk[$i],9);
        }
        for($i=6; $i<=70; $i += 8){
            $filter7[] = substr($walk[$i],9);
        }
        for($i=7; $i<=71; $i += 8){
            $filter8[] = substr($walk[$i],9);
        }
        $filterTable = array($filter1,$filter2,$filter3,$filter4,$filter5,$filter6,$filter7,$filter8);

//Clean Mac Address
        for ($i=0; $i<=8; $i++){
            $filterTable[$i][2] = substr($filterTable[$i][2],2);
        }

//!!!DONE VALUE ARRAY! [filterTable]!!!



    }

}

if (isset($_POST['write']) AND $read == "read"){
//Write
    for($i=0; $i<=7; $i++){
        $pid = "pid$i";
        $mac = "mac$i";
        $status = "status$i";
        $multiCast = "multiCast$i";
        $arrayName = "row$i";
        $$arrayName = array($i,$_POST[$pid],$_POST[$mac],$_POST[$status],$_POST[$multiCast]);
    }
    $tableRow = array($row0,$row1,$row2,$row3,$row4,$row5,$row6,$row7);


    //START OID ARRAY
    $readTable = $_POST['filterTable'];
    $walkoid1 = snmpwalkoid($device_IP, "public", $oid[$readTable]);

    $i = "0";
    foreach ($walkoid1 as $key => $value) {
        $newKey = substr($key,3);
        $walkoid[$i] = "1".$newKey;
        $i++;
    }

    for($i=0; $i<=64; $i += 8){
        $tableoid1[] = $walkoid[$i];
    }
    for($i=1; $i<=65; $i += 8){
        $tableoid2[] = $walkoid[$i];
    }
    for($i=2; $i<=66; $i += 8){
        $tableoid3[] = $walkoid[$i];
    }
    for($i=3; $i<=67; $i += 8){
        $tableoid4[] = $walkoid[$i];
    }
    for($i=4; $i<=68; $i += 8){
        $tableoid5[] = $walkoid[$i];
    }
    for($i=5; $i<=69; $i += 8){
        $tableoid6[] = $walkoid[$i];
    }
    for($i=6; $i<=70; $i += 8){
        $tableoid7[] = $walkoid[$i];
    }
    for($i=7; $i<=71; $i += 8){
        $tableoid8[] = $walkoid[$i];
    }
    $filterOid = array($tableoid1,$tableoid2,$tableoid3,$tableoid4,$tableoid5,$tableoid6,$tableoid7,$tableoid8);

//!!! DONE OID ARRAY! [filterOid]!

    for($i=0; $i<=7; $i++){
        $arrayName = "row$i";
        snmp2_set($device_IP, "private", $filterOid[$i][1], "int", $tableRow[$i][1]);
        snmp2_set($device_IP, "private", $filterOid[$i][7], "int", $tableRow[$i][3]);
        snmp2_set($device_IP, "private", $filterOid[$i][8], "int", $tableRow[$i][4]);
    }


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
                <li class="active"> <a href="filter.php">RF PID Filter</a></li>
                <li> | </li>
                <li> <a href="network.php">Network</a></li>
                <li> | </li>
                <li> <a href="images.php">Images</a></li>
                <li> | </li>
                <li> <a href="system.php">System</a></li>
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
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <select name="filterTable" class="form-control input-sm">
                        <option value="rf1Conf1Table"  <?php if($readTable == "rf1Conf1Table"){echo "selected";}?>>Rx1 - Conf1</option>
                        <option value="rf1Conf2Table"  <?php if($readTable == "rf1Conf2Table"){echo "selected";}?>>Rx1 - Conf2</option>
                        <option value="rf2Conf1Table"  <?php if($readTable == "rf2Conf1Table"){echo "selected";}?>>Rx2 - Conf1</option>
                        <option value="rf2Conf2Table"  <?php if($readTable == "rf2Conf2Table"){echo "selected";}?>>Rx2 - Conf2</option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control input-sm btn-info" type="submit" name="filterTableSubmit" value="Read Table">
                </div>


            <table class="table table-condensed table-hover">
                <thead>
                <tr>
                    <th>Slot</th>
                    <th>PID</th>
                    <th>Mac Address</th>
                    <th>Status</th>
                    <th>IP Multicast</th>
                </tr>
                </thead>
                <tbody>

                <?php
                for ($i=0; $i<=7; $i++){
                    $slot = $i +1;
                    ?>
                    <tr>
                        <td> <?php echo $slot;?></td>
                        <td><input class="form-control input-sm" type="number" min="0" max="8191" name="pid <?php echo$i;?>" value="<?php echo $filterTable[$i][1];?>"></td>
                        <td><input class="form-control input-sm" type="text" name="mac <?php echo $i;?>" value="<?php echo $filterTable[$i][2];?>" readonly></td>
                        <td><select name="status <?php echo $i;?>" class="form-control input-sm">
                                <option value="1"  <?php  if($filterTable[$i][7] == 1){echo "selected";}?>>Enable</option>
                                <option value="0"  <?php  if($filterTable[$i][7] == 0){echo "selected";}?>>Disable</option>
                            </select></td>
                        <td><select name="multiCast <?php echo $i;?>" class="form-control input-sm">
                                <option value="1"  <?php  if($filterTable[$i][8] == 1){echo "selected";}?>>Passed</option>
                                <option value="0"  <?php  if($filterTable[$i][8] == 0){echo "selected";}?>>Blocked</option>
                            </select></td>
                    </tr>

                <?php }?>


                </tbody>
            </table>

</form>
</div>
<div class="col-md-3"></div>
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