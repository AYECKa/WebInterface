<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 4/1/15
 * Time: 6:04 PM
 */

include '../lib/inc.php';
header('Content-Type: application/json');
echo json_encode($gauges->getGauges());