<?php
require_once('../lib/inc.php');
echo $snmp->get($_GET['oid']);
