<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/31/15
 * Time: 7:33 PM
 */
include('../lib/inc.php');
header('Content-Type: application/json');
$name = $_GET['label'];
$oid = $_GET['oid'];
$status = $fav->renameFave($oid, $name);
echo json_encode(array("status" => $status));

