<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 4/1/15
 * Time: 5:38 PM
 */

include '../lib/inc.php';
function xss_get($param)
{
    return htmlspecialchars($_GET[$param]);
}

$gauge = array("label"=>xss_get('label'), "oid" => xss_get('oid'), "min" => xss_get('min'), "max"=> xss_get('max'));
$gauges->setGuage(xss_get('id'), $gauge);
header('Content-Type: application/json');
echo json_encode(array("status"=>true));