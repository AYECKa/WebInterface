<?php
session_start();
error_reporting(0);
$oid = $_SESSION['oid'];
$type = $_SESSION['type'];
require_once('sql.php');
sql_connect();
require_once('array.php');
oidarray();


function rx1_read(){
    global $egrRx1Enabled, $egrRx1DstIpAddress, $egrRx1DstEthAddress, $egrRx1DstUdpPort, $egrRx1Dscp, $egrRx1SrcIpAddress, $egrRx1SrcEthAddress, $egrRx1SrcUdpPort, $egrRx1TSoIPContainerSize, $egrRx1PcrAwareness, $egrRx1MaxTimeout;
    global $oid, $device_IP;
    $egrRx1Enabled = substr(snmp2_get($device_IP,"public",$oid['egrRx1Enabled']), 9);
    $egrRx1DstIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx1DstIpAddress']), 11);
    $egrRx1DstEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx1DstEthAddress']), 11);
    $egrRx1DstUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrRx1DstUdpPort']), 9);
    $egrRx1Dscp = substr(snmp2_get($device_IP,"public",$oid['egrRx1Dscp']), 9);
    $egrRx1SrcIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx1SrcIpAddress']), 11);
    $egrRx1SrcEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx1SrcEthAddress']), 11);
    $egrRx1SrcUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrRx1SrcUdpPort']), 11);
    $egrRx1TSoIPContainerSize = substr(snmp2_get($device_IP,"public",$oid['egrRx1TSoIPContainerSize']), 9);
    $egrRx1PcrAwareness = substr(snmp2_get($device_IP,"public",$oid['egrRx1PcrAwareness']), 9);
    $egrRx1MaxTimeout = substr(snmp2_get($device_IP,"public",$oid['egrRx1MaxTimeout']), 9);
}

function rx2_read(){
    global $egrRx2Enabled, $egrRx2DstIpAddress, $egrRx2DstEthAddress, $egrRx2DstUdpPort, $egrRx2Dscp, $egrRx2SrcIpAddress, $egrRx2SrcEthAddress, $egrRx2SrcUdpPort, $egrRx2TSoIPContainerSize, $egrRx2PcrAwareness, $egrRx2MaxTimeout;
    global $oid, $device_IP;
    $egrRx2Enabled = substr(snmp2_get($device_IP,"public",$oid['egrRx2Enabled']), 9);
    $egrRx2DstIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx2DstIpAddress']), 11);
    $egrRx2DstEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx2DstEthAddress']), 11);
    $egrRx2DstUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrRx2DstUdpPort']), 9);
    $egrRx2Dscp = substr(snmp2_get($device_IP,"public",$oid['egrRx2Dscp']), 9);
    $egrRx2SrcIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx2SrcIpAddress']), 11);
    $egrRx2SrcEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrRx2SrcEthAddress']), 11);
    $egrRx2SrcUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrRx2SrcUdpPort']), 11);
    $egrRx2TSoIPContainerSize = substr(snmp2_get($device_IP,"public",$oid['egrRx2TSoIPContainerSize']), 9);
    $egrRx2PcrAwareness = substr(snmp2_get($device_IP,"public",$oid['egrRx2PcrAwareness']), 9);
    $egrRx2MaxTimeout = substr(snmp2_get($device_IP,"public",$oid['egrRx2MaxTimeout']), 9);
}

function asi_read(){
    global $egrAsiEnabled, $egrAsiDstIpAddress, $egrAsiDstEthAddress, $egrAsiDstUdpPort, $egrAsiDscp, $egrAsiSrcIpAddress, $egrAsiSrcEthAddress, $egrAsiSrcUdpPort, $egrAsiTSoIPContainerSize, $egrAsiPcrAwareness, $egrAsiMaxTimeout;
    global $oid, $device_IP;
    $egrAsiEnabled = substr(snmp2_get($device_IP,"public",$oid['egrAsiEnabled']), 9);
    $egrAsiDstIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrAsiDstIpAddress']), 11);
    $egrAsiDstEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrAsiDstEthAddress']), 11);
    $egrAsiDstUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrAsiDstUdpPort']), 9);
    $egrAsiDscp = substr(snmp2_get($device_IP,"public",$oid['egrAsiDscp']), 9);
    $egrAsiSrcIpAddress = substr(snmp2_get($device_IP,"public",$oid['egrAsiSrcIpAddress']), 11);
    $egrAsiSrcEthAddress = substr(snmp2_get($device_IP,"public",$oid['egrAsiSrcEthAddress']), 11);
    $egrAsiSrcUdpPort = substr(snmp2_get($device_IP,"public",$oid['egrAsiSrcUdpPort']), 11);
    $egrAsiTSoIPContainerSize = substr(snmp2_get($device_IP,"public",$oid['egrAsiTSoIPContainerSize']), 9);
    $egrAsiPcrAwareness = substr(snmp2_get($device_IP,"public",$oid['egrAsiPcrAwareness']), 9);
    $egrAsiMaxTimeout = substr(snmp2_get($device_IP,"public",$oid['egrAsiMaxTimeout']), 9);
}
?>