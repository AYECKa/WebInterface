<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/29/15
 * Time: 7:49 PM
 */

require('../lib/inc.php');
header('Content-Type: application/json');
$ip = $_GET['ip'];
$readKey = $_GET['community-read'];

function getObjectId($ip,$readKey)
{
    $snmp = new SNMP(SNMP::VERSION_2C,$ip,$readKey);
    $input = @$snmp->get('1.3.6.1.2.1.1.2');
    if($snmp->getErrno() == SNMP::ERRNO_TIMEOUT)
    {
        throw new Exception("TIMEOUT");
    }
    $arr = explode(":", $input);
    unset($arr[0]);
    $output = trim(join($arr, ":"));
    $output = str_replace("\"", "", $output);
    return $output;
}

try {
    $oid = getObjectId($ip, $readKey);
}
catch (Exception $e)
{
    die(json_encode(array('IP' => $ip, 'didFound' => false, 'reason' => 'The device has timed out')));
}
$fileName = $mib->getFileNameByOid($oid);
if($fileName == false)
{
    die(json_encode(array('IP' => $ip, 'didFound' => false, 'reason' => 'Could not find matching MIB for oid' . $oid)));
}

echo json_encode(array('IP' => $ip, 'didFound' => true, 'OID' => $oid, 'FileName' => $fileName));


