<?php
session_start();
error_reporting(0);
$read = $_SESSION['read'];
$device_IP = $_SESSION['device_IP'];
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

    $_SESSION['device_IP'] = $device_IP;
    $_SESSION['read'] = "read";
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
    <h2>SR1</h2>
</header>
<nav id="aymenu">
    <ul id="MenuList">
        <li> <a href="index.php">Status</a></li>
        <li> | </li>
        <li> <a href="rf.php?rf=1">RF1</a></li>
        <li> | </li>
        <li> <a href="rf.php?rf=2">RF2</a></li>
        <li> | </li>
        <li> <a href="rf_control.php">RF Control</a></li>
        <li> | </li>
        <li class="Active"> <a href="filter.php">RF PID Filter</a></li>
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
        <select name="filterTable">
            <option value="rf1Conf1Table"  <?php if($readTable == "rf1Conf1Table"){echo "selected";}?>>Rx1 - Conf1</option>
            <option value="rf1Conf2Table"  <?php if($readTable == "rf1Conf2Table"){echo "selected";}?>>Rx1 - Conf2</option>
            <option value="rf2Conf1Table"  <?php if($readTable == "rf2Conf1Table"){echo "selected";}?>>Rx2 - Conf1</option>
            <option value="rf2Conf2Table"  <?php if($readTable == "rf2Conf2Table"){echo "selected";}?>>Rx2 - Conf2</option>
        </select>
        <input type="submit" name="filterTableSubmit" value="Read Table">

        <table border="1">
            <tr><th>Slot</th><th>PID</th><th>Mac Address</th><th>Status</th><th>IP Multicast</th></tr>
             <?php
            for ($i=0; $i<=7; $i++){
                $slot = $i +1;
                ?>
                <tr>
                    <td> <?php echo $slot;?></td>
                    <td><input type="number" min="0" max="8191" name="pid <?php echo$i;?>" value="<?php echo $filterTable[$i][1];?>"></td>
                    <td><input type="text" name="mac <?php echo $i;?>" value="<?php echo $filterTable[$i][2];?>" readonly></td>
                    <td><select name="status <?php echo $i;?>">
                            <option value="1"  <?php  if($filterTable[$i][7] == 1){echo "selected";}?>>Enable</option>
                            <option value="0"  <?php  if($filterTable[$i][7] == 0){echo "selected";}?>>Disable</option>
                        </select></td>
                    <td><select name="multiCast <?php echo $i;?>">
                            <option value="1"  <?php  if($filterTable[$i][8] == 1){echo "selected";}?>>Passed</option>
                            <option value="0"  <?php  if($filterTable[$i][8] == 0){echo "selected";}?>>Blocked</option>
                        </select></td>
                </tr>

             <?php }?>
        </table>
        </form>

    </center>
</div>
<footer><span class="ver">  <?php echo "Version number: ".$ver;?></span></footer>
</body>
</html>