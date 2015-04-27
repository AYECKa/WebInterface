<?php
require_once(dirname(__FILE__) . '/lib/config.php');
require_once(dirname(__FILE__) . '/lib/MibParser.php');


include('templates/Analyze.template.php');
flush();
$mibs = new MibFiles($searchPath);


$ser = serialize($mibs);
file_put_contents($mibCache, $ser);
echo "<script>location = 'index.php';</script>";
flush();


