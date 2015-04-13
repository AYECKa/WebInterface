<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/31/15
 * Time: 7:33 PM
 */
include('../lib/inc.php');
header('Content-Type: application/json');
$fave_state = $_GET['status'];
$oid = $_GET['oid'];

$node = $mib->tree->getNodeByOid($oid);
$fav->setFave($node, $fave_state=="true");

echo json_encode(array("status" => true));

