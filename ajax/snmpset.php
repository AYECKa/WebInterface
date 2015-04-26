<?php
require_once('../lib/inc.php');
header('Content-Type: application/json');


$typeTable = array('INTEGER' => 'i', 'STRING' => 's', 'HEX STRING' =>'x', 'DECIMAL STRING' => 'd', 'NULLOBJ' => 'n', 'OBJID' => 'o', 'TIMETICKS' => 't', 'IPADDRESS' => 'a', 'BITS' => 'b', 'MACADDRESS' => 'x', 'UNSIGNED32' => 'u' ,'OCTET STRING' => 's');

function getOidType($isTable,$oid)
{
    global $mib;
    if($isTable)
    {
        $oid = explode('.', $oid);
        unset($oid[count($oid) - 1]);
        $oid = join('.', $oid);
    }

    $node = $mib->tree->getNodeByOid($oid);
    $typeString = "";
    if($isTable)
    {
        //remove row number
        $oidIndex = explode('.' ,$oid);
        unset($oidIndex[count($oidIndex)]);
        $noRowOid = join($oidIndex, '.');


        $node = $mib->tree->getNodeByOid($oid);
        $typeString = $node->type['oidType'];
    }
    else
    {
        $typeString = strtoupper(trim($node->type['oidType']));

    }

    global $typeTable;
    if(!isset($typeTable[$typeString])) {die(json_encode(array('status' => 'error', 'desc' => 'Unknown type ' . $typeString)));}
    return $typeTable[$typeString];

}

if(!isset($_POST['name']) || !isset($_POST['value']) || !isset($_POST['pk'])) die(json_encode(array('error'=> 'expected params')));
$oid = $_POST['name'];
$val = $_POST['value'];
$isTable = $_POST['pk'] === "1";

$type = getOidType($isTable, $oid);


$res[$oid] = $snmp->set($oid,$type,$val);

$results = array();
$results["status"] = true;
$results["object"] = array("oid" => $oid, "type" => $type, "value" => $val);

echo json_encode($results);
