<?php
require_once('../lib/inc.php');
header('Content-Type: application/json');
$oids = explode(",", $_GET['oids']);
$res = array();
foreach($oids as $oid)
{
	$res[$oid] = $snmp->get($oid);
}
$results = array();
$results["status"] = true;
$results["data"] = $res;
echo json_encode($results);
