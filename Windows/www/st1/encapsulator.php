<?php

session_start();
error_reporting(0);
$read = $_SESSION['read_st'];
$device_IP = $_SESSION['device_IP_st'];
$ver = $_SESSION['stVer'];

$oid = $_SESSION['oid'];
$type = $_SESSION['type'];


require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();


$from1 = 1;
$to2 = 256;

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

//

}

if(isset($_POST['readtable'])){

//    snmp_get_by_rows
    for($i=1; $i<=256; $i++){
//        entry #
        for($c=1; $c<=6; $c++){
//            column
            $oid = "1.3.6.1.4.1.27928.102.1.3.1.1.1.".$c.".".$i;
            $snmpget[] = snmp2_get($device_IP,"public",$oid);
        }
// save to database
        $id = substr($snmpget[0], 9);
        $ip = substr($snmpget[1], 11);
        $mask = substr($snmpget[2], 11);
        $mac = substr($snmpget[3], 11);
        $pid = substr($snmpget[4], 9);
        $enabled = substr($snmpget[5], 9);

        mysql_query("UPDATE st_ipFwdTable SET `ipfwdEntryIndex` = '$id', `ipfwdIpAddress` = '$ip', `ipfwdIpNetmask` = '$mask', `ipfwdMacAddress` = '$mac', `ipfwdPid` = '$pid', `ipfwdEnabled` = '$enabled', `time` = CURRENT_TIMESTAMP WHERE Instance = $i");

//    unset get araay
        unset($get);
    }
}



/*
 ******************************
 *          Write!            *
 ******************************
 */

if (isset($_POST['write']) AND $read == "read"){
    foreach($_POST['checkbox'] as $i){

        $ip = "ipfwdIpAddress".$i;
        $netmask = "ipfwdIpNetmask".$i;
        $macaddress = "ipfwdMacAddress".$i;
        $pid = "ipfwdPid".$i;
        $enabled = "ipfwdEnabled".$i;

        mysql_query("UPDATE st_ipFwdTable SET
        ipfwdIpAddress = '$_POST[$ip]',
        ipfwdIpNetmask = '$_POST[$netmask]',
        ipfwdMacAddress = '$_POST[$macaddress]',
        ipfwdPid = '$_POST[$pid]',
        ipfwdEnabled = '$_POST[$enabled]',
        time=CURRENT_TIMESTAMP
        WHERE Instance = $i");

        $column = array("","","ipfwdIpAddress","ipfwdIpNetmask","ipfwdMacAddress","ipfwdPid","ipfwdEnabled");
        $columntype = array("","","a","a","macAddress","i","i");

        for($n = 2; $n <= 6; $n++){
            $q = mysql_query("SELECT $column[$n] FROM st_ipFwdTable WHERE Instance = $i");
            $r = mysql_fetch_array($q);

            $oid = "1.3.6.1.4.1.27928.102.1.3.1.1.1.".$n.".".$i;

            snmp2_set($device_IP, "private", $oid, $columntype[$n], $r[$column[$n]]);

        }
    }
    echo '<script type="text/javascript">alert("Entry';
    foreach($_POST['checkbox'] as $i){
        echo " ".$i.",";
    }
    echo ' was updated to the device")</script>';
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
            padding-top: 0;
            padding-bottom: 20px;
        }
    </style>
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $("td input,td select").change(function(){
                $(this).parent().parent().find("td .checkme").attr("checked",'checked');
            });
        });
    </script>
</head>

<body>

<?php
$active = "encapsulator";
include_once('header.php');
?>

<!--PageBody-->
<!--end Page Body-->
<form method="post" class="form-inline">
    <div class="well well-sm text-center">

        <div class="form-group">
            <input type="text" class="form-control input-sm" value="<?php echo $device_IP;?>" name="device_IP" placeholder="Device IP Address">
        </div>
        <button type="submit" class="btn btn-success btn-sm" name="read">Show Last Import</button>
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

                <?php
                $time = mysql_query("SELECT MAX(time) as max FROM st_ipFwdTable");
                $timeresult = mysql_fetch_array($time);
                echo "This is the last update from: <b>".$timeresult['max'];
                echo "</b><br>".'<input class="btn btn-sm btn-danger" type="submit" name="readtable" value="To update table from device Click Here">';
                ?>
                <br>*The updating process may take a few minutes

                <br><br>
                Show row:
                <div class="form-group">
                    <input class="form-control input-sm" type="number" min="0" max="256" name="from" value="<?php echo $from1;?>">
                </div>
                to:
                <div class="form-group">
                    <input class="form-control input-sm" type="number" min="0" max="256" name="to" value="<?php echo $to2;?>">
                </div>
                Order By:
                <div class="form-group">
                    <select name="order" class="form-control input-sm">
                        <option value="ipfwdEntryIndex">Number</option>
                        <option value="ipfwdIpAddress">IP Address</option>
                        <option value="ipfwdIpNetmask">IP Netmask</option>
                        <option value="ipfwdMacAddress">Ethernet Address</option>
                        <option value="ipfwdPid">PID</option>
                        <option value="ipfwdEnabled">Enabled</option>
                    </select>
                </div>
                <input class="btn btn-xs btn-info" type="submit" name="table" value="Update Table">
            </div>
            <br><br>
            <table class="table table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>IP Address</th>
                    <th>IP Netmask</th>
                    <th>Ethernet Address</th>
                    <th>PID</th>
                    <th>Enabled</th>
                    <th>Save</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $sqlquery="SELECT * FROM st_ipFwdTable";
                $theresult=mysql_query($sqlquery);

                if(isset($_POST['table'])){
                    $from1 = $_POST['from'];
                    $to2 = $_POST['to'];
                    $order = $_POST['order'];
                    $orderby = "DESC";
                    if($order == "ipfwdEntryIndex"){
                        $orderby = "ASC";
                    }

                    $sqlquery="SELECT * FROM st_ipFwdTable WHERE Instance BETWEEN $from1 AND $to2 ORDER BY $order $orderby";
                    $theresult=mysql_query($sqlquery);
                }

                while ($r=mysql_fetch_array($theresult)){
                    $i = $r['Instance'];
                    $enable = $disable = '';
                    $selected = ' selected="selected"';
                    empty($r['ipfwdEnabled']) ? $disable = $selected : $enable = $selected;

                    echo <<<ROW
    <tr>
        <td>
            {$r['ipfwdEntryIndex']}
        </td>
        <td>
            <input class="form-control input-sm" type="text" name="ipfwdIpAddress{$i}" value="{$r['ipfwdIpAddress']}" />
        </td>
        <td>
            <input class="form-control input-sm" type="text" name="ipfwdIpNetmask{$i}" value="{$r['ipfwdIpNetmask']}" />
        </td>
        <td>
            <input class="form-control input-sm" type="text" name="ipfwdMacAddress{$i}" value="{$r['ipfwdMacAddress']}" />
        </td>
        <td>
            <input class="form-control input-sm" type="number" min="0" max="8191" name="ipfwdPid{$i}" value="{$r['ipfwdPid']}" />
        </td>
        <td>
            <select name="ipfwdEnabled{$i}" class="form-control input-sm">
                <option value="1"{$enable}>Enabled</option>
                <option value="0"{$disable}>Disabled</option>
            </select>
        </td>
        <td>
            <input class="checkme" type="checkbox" name="checkbox[]" value="{$i}" />
        </td>
    </tr>
ROW;
                }
                ?>
                </tbody>
            </table>
            <br><br>
            <a href="#Top" class="btn btn-info">Back to Top</a>
</form>

</div>
<div class="col-md-3"></div>

<div class="container text-left">
    <hr>
    <footer>
        <span class="pull-right">  <?php echo "Version number: ".$ver;?></span>
        &copy; <a href="http://www.ayecka.com">Ayecka</a> Comunnication System</footer>
</div>

<!-- /container -->
</body>
</html>