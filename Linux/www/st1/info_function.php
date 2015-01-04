<?php
$sysUpTime = substr(snmp2_get($device_IP,"public","1.3.6.1.2.1.1.3"), 22);
$sysContact = substr(snmp2_get($device_IP,"public","1.3.6.1.2.1.1.4"), 0);
$sysDescr = substr(snmp2_get($device_IP,"public","1.3.6.1.2.1.1.1"), 9);
$sysName = substr(snmp2_get($device_IP,"public","1.3.6.1.2.1.1.5"), 0);
$sysLocation = substr(snmp2_get($device_IP,"public","1.3.6.1"), 0);
$sysObjectID = substr(snmp2_get($device_IP,"public","1.3.6.1.2.1.1.2"), 9);
$sysServices = substr(snmp2_get($device_IP,"public","1.3.6.1.2.1.1.7"), 9);
?>