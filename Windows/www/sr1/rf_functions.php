<?php
session_start();
error_reporting(0);
$oid = $_SESSION['oid'];
$type = $_SESSION['type'];


require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();


function rf1_conf1_read(){
    global $frequency1, $acquisitionBandwidth1, $symbolRate1, $goldCode1, $isi1, $rx1CfgMode, $rx1CfgSwitchPeriod, $lnbPower1, $signal22KHz1, $compensation1, $profileName1, $rx1CfgSetStatus;
    global $oid, $device_IP;

    $rx1CfgMode = substr(snmp2_get($device_IP, "public",$oid['rx1CfgMode']), 9);
    $rx1CfgSwitchPeriod = substr(snmp2_get($device_IP, "public",$oid['rx1CfgSwitchPeriod']), 9);
    $rx1CfgSetStatus = substr(snmp2_get($device_IP, "public",$oid['rx1CfgSet1Status']), 9);
    if ($rx1CfgSetStatus == "1"){
        //set1 inactive; set 2 active
        $rx1CfgSetStatus = "2";
    } else {
        //set1 active; set 2 inactive
        $rx1CfgSetStatus = "1";
    }

    $profileName1 = substr(snmp2_get($device_IP, "public",$oid['profileName1']), 9);
    $frequency1 = substr(snmp2_get($device_IP, "public",$oid['frequency1']), 9)/1000;
    $acquisitionBandwidth1 = substr(snmp2_get($device_IP, "public",$oid['acquisitionBandwidth1']), 9)/1000;
    $symbolRate1 = substr(snmp2_get($device_IP, "public",$oid['symbolRate1']), 9)/1000;
    $goldCode1 = substr(snmp2_get($device_IP, "public",$oid['goldCode1']), 9);
    $isi1 = substr(snmp2_get($device_IP, "public",$oid['isi1']), 9);
    $lnbPower1 = substr(snmp2_get($device_IP, "public",$oid['lnbPower1']), 9);
    $signal22KHz1 = substr(snmp2_get($device_IP, "public",$oid['signal22KHz1']), 9);
    $compensation1 = substr(snmp2_get($device_IP, "public",$oid['compensation1']), 9);
}




function rf1_conf2_read(){
    global $frequency2, $acquisitionBandwidth2, $symbolRate2, $goldCode2, $isi2, $lnbPower2, $signal22KHz2, $compensation2, $profileName2;
    global $oid, $device_IP;
    $profileName2 = substr(snmp2_get($device_IP, "public",$oid['profileName2']), 9);
    $frequency2 = substr(snmp2_get($device_IP, "public",$oid['frequency2']), 9)/1000;
    $acquisitionBandwidth2 = substr(snmp2_get($device_IP, "public",$oid['acquisitionBandwidth2']), 9)/1000;
    $symbolRate2 = substr(snmp2_get($device_IP, "public",$oid['symbolRate2']), 9)/1000;
    $goldCode2 = substr(snmp2_get($device_IP, "public",$oid['goldCode2']), 9);
    $isi2 = substr(snmp2_get($device_IP, "public",$oid['isi2']), 9);
    $lnbPower2 = substr(snmp2_get($device_IP, "public",$oid['lnbPower2']), 9);
    $signal22KHz2 = substr(snmp2_get($device_IP, "public",$oid['signal22KHz2']), 9);
    $compensation2 = substr(snmp2_get($device_IP, "public",$oid['compensation2']), 9);
}



// rf2

function rf2_conf1_read(){
    global $frequency1, $acquisitionBandwidth1, $symbolRate1, $goldCode1, $isi1, $rx1CfgMode, $rx1CfgSwitchPeriod, $lnbPower1, $signal22KHz1, $compensation1, $profileName1, $rx1CfgSetStatus;
    global $oid, $device_IP;

    $profileName1 = substr(snmp2_get($device_IP, "public",$oid['profileName3']), 9);
    $frequency1 = substr(snmp2_get($device_IP, "public",$oid['frequency3']), 9)/1000;
    $acquisitionBandwidth1 = substr(snmp2_get($device_IP, "public",$oid['acquisitionBandwidth3']), 9)/1000;
    $symbolRate1 = substr(snmp2_get($device_IP, "public",$oid['symbolRate3']), 9)/1000;
    $goldCode1 = substr(snmp2_get($device_IP, "public",$oid['goldCode3']), 9);
    $isi1 = substr(snmp2_get($device_IP, "public",$oid['isi3']), 9);
    $lnbPower1 = substr(snmp2_get($device_IP, "public",$oid['lnbPower3']), 9);
    $signal22KHz1 = substr(snmp2_get($device_IP, "public",$oid['signal22KHz3']), 9);
    $compensation1 = substr(snmp2_get($device_IP, "public",$oid['compensation3']), 9);

    $rx1CfgMode = substr(snmp2_get($device_IP, "public",$oid['rx2CfgMode']), 9);
    $rx1CfgSwitchPeriod = substr(snmp2_get($device_IP, "public",$oid['rx2CfgSwitchPeriod']), 9);

    $rx1CfgSetStatus = substr(snmp2_get($device_IP, "public",$oid['rx2CfgSet1Status']), 9);
    if ($rx1CfgSetStatus == "1"){
        //set1 inactive; set 2 active
        $rx1CfgSetStatus = "2";
    } else {
        //set1 active; set 2 inactive
        $rx1CfgSetStatus = "1";
    }
}


function rf2_conf2_read(){
    global $frequency2, $acquisitionBandwidth2, $symbolRate2, $goldCode2, $isi2, $lnbPower2, $signal22KHz2, $compensation2, $profileName2;
    global $oid, $device_IP;

    $profileName2 = substr(snmp2_get($device_IP, "public",$oid['profileName4']), 9);
    $frequency2 = substr(snmp2_get($device_IP, "public",$oid['frequency4']), 9)/1000;
    $acquisitionBandwidth2 = substr(snmp2_get($device_IP, "public",$oid['acquisitionBandwidth4']), 9)/1000;
    $symbolRate2 = substr(snmp2_get($device_IP, "public",$oid['symbolRate4']), 9)/1000;
    $goldCode2 = substr(snmp2_get($device_IP, "public",$oid['goldCode4']), 9);
    $isi2 = substr(snmp2_get($device_IP, "public",$oid['isi4']), 9);
    $lnbPower2 = substr(snmp2_get($device_IP, "public",$oid['lnbPower4']), 9);
    $signal22KHz2 = substr(snmp2_get($device_IP, "public",$oid['signal22KHz4']), 9);
    $compensation2 = substr(snmp2_get($device_IP, "public",$oid['compensation4']), 9);
}
?>