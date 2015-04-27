<?php
function getSysInfo()
{
	global $mib, $snmp;

	    //get the oid's of the system info
    $fpgaOid = $mib->tree->root->getNodeByName('fpgaVersion')->getOid();
    $softOid = $mib->tree->root->getNodeByName('softwareVersion')->getOid();
    $firmOid = $mib->tree->root->getNodeByName('hardwareVersion')->getOid();
    $serialOid = $mib->tree->root->getNodeByName('serialNumber')->getOid();

	$sysInfo = array();
	$sysInfo["OID"] 		= 	$snmp->get('1.3.6.1.2.1.1.2');
	$sysInfo["CONTACT"] 	= 	$snmp->get('1.3.6.1.2.1.1.4');
	$sysInfo["DESC"] 		= 	$snmp->get('1.3.6.1.2.1.1.1');
	$sysInfo["NAME"] 		= 	$snmp->get('1.3.6.1.2.1.1.5');
	$sysInfo["LOCATION"] 	= 	$snmp->get('1.3.6.1.2.1.1.6');
	$sysInfo["SERVICES"] 	= 	$snmp->get('1.3.6.1.2.1.1.7');
	$sysInfo["FPGA"] 		= 	$snmp->get($fpgaOid);
	$sysInfo["SOFT"] 		= 	$snmp->get($softOid);
	$sysInfo["FRIM"] 		= 	$snmp->get($firmOid);
	$sysInfo["SERIAL"] 		= 	$snmp->get($serialOid);
	return $sysInfo;
}