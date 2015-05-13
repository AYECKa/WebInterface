<?php
require_once('../lib/inc.php');
header('Content-Type: application/json');
$oids = explode(",", $_GET['oids']);
$res = array();
/*
foreach($oids as $oid)
{
	$res[$oid] = $snmp->get($oid);
}
*/
$results = array();
$results["status"] = true;
$results["data"] = $snmp->get_bulk($oids);
echo json_encode($results);
