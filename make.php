<?php
require_once(dirname(__FILE__) . '/lib/config.php');
require_once(dirname(__FILE__) . '/lib/MibParser.php');

echo "parsing mibs...\n\r";
$mibs = new MibFiles($searchPath);
$ser = serialize($mibs);
file_put_contents($mibCache, $ser);
echo "done!\n\r";
exit(0);