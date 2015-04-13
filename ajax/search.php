<?php
/**
 * User: assaf
 * Date: 3/29/15
 * Time: 9:16 PM
 */

require('../lib/inc.php');
header('Content-Type: application/json');

$q = $_GET['query'];
$res = [];
foreach($mibIndex as $item)
{
    if (strpos(strtolower($item['name']), strtolower($q)) !== FALSE)
    {
        $res [] = $item;
    }

}

echo json_encode($res);