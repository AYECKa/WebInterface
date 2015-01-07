<?php

/********************************************************
 * Reference:
 * 1.3.6.1.4.1.27928.101.1.rx.conf.4.3.1.column.entry  *
 ********************************************************
 */


session_start();
error_reporting(0);
$read = $_SESSION['read_sr'];
$device_IP = $_SESSION['device_IP_sr'];
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

    //system info
    include_once('info_function.php');

//  Choose Read Table
    if(isset($_POST['selectTableSubmit'])){
//        Choose table
        $selectTable = $_POST['selectTable'];

        for($i=1; $i<=8; $i++){
//            row number ($i)
            for($c=1; $c<=10; $c++){
//                column number ($c)
                $oid = "1.3.6.1.4.1.27928.101.1.".$selectTable.".4.3.1.".$c.".$i";
                $get[$i][$c] = substr(snmp2_get($device_IP,"public",$oid),9);

//                clean the mac address result
                if($c==3){
                    $get[$i][$c] = substr($get[$i][$c],2);
                }
            }
        }
//        loop closed
    }
}

/********************************
 *          Write!              *
 ********************************
 */
if (isset($_POST['write']) AND $read == "read"){
    for($i=1; $i<=8; $i++){
        $pidName = "pid".$i;
        $statusName = "status".$i;
        $multicastName = "multicast".$i;

        $pid = $_POST[$pidName];
        $status = $_POST[$statusName];
        $multicast = $_POST[$multicastName];
        $selectTable = $_POST['selectTable'];

//        snmp2_set($device_IP,"private",oid,type,value);

        $c = 2;
        $oid = "1.3.6.1.4.1.27928.101.1.".$selectTable.".4.3.1.".$c.".$i";
        snmp2_set($device_IP,"private",$oid,"i",$pid);

        $c = 8;
        $oid = "1.3.6.1.4.1.27928.101.1.".$selectTable.".4.3.1.".$c.".$i";
        snmp2_set($device_IP,"private",$oid,"i",$status);

        $c = 10;
        $oid = "1.3.6.1.4.1.27928.101.1.".$selectTable.".4.3.1.".$c.".$i";
        snmp2_set($device_IP,"private",$oid,"i",$multicast);
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

<?php
$active = "filter";
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
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <select name="selectTable" class="form-control input-sm">
                        <option value="1.1" <?php if($selectTable == "1.1"){echo "selected";}?>>Rx1 - Conf1</option>
                        <option value="1.2" <?php if($selectTable == "1.2"){echo "selected";}?>>Rx1 - Conf2</option>
                        <option value="2.1" <?php if($selectTable == "2.1"){echo "selected";}?>>Rx2 - Conf1</option>
                        <option value="2.2" <?php if($selectTable == "2.2"){echo "selected";}?>>Rx2 - Conf2</option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control input-sm btn-info" type="submit" name="selectTableSubmit" value="Read Table">
                </div>

                <br><br>
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
                    for($i=1; $i<=8; $i++){

                        $enable = $disable = '';
                        $selected = ' selected="selected"';
                        $get[$i][8] == 0 ? $disable = $selected : $enable = $selected;

                        $passed = $blocked = '';
                        $selected = ' selected="selected"';
                        $get[$i][10] == 0 ? $blocked = $selected : $passed = $selected;


                        echo <<<ROW
                             <tr>
                                <td>{$get[$i][1]}</td>
                                <td><input class="form-control input-sm" type="number" min="0" max="8191" name="pid{$i}" value="{$get[$i][2]}"></td>
                                <td><input class="form-control input-sm" type="text" name="mac{$i}" value="{$get[$i][3]}" readonly></td>
                                <td>
                                    <select name="status{$i}" class="form-control input-sm">
                                        <option value="1"{$enable}>Enable</option>
                                        <option value="0"{$disable}>Disable</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="multicast{$i}" class="form-control input-sm">
                                        <option value="1"{$passed}>Passed</option>
                                        <option value="0"{$blocked}>Blocked</option>
                                    </select>
                                </td>
                             </tr>
ROW;

                    }
                    ?>
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