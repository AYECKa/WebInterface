<?php
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/MibParser.php');
require_once(dirname(__FILE__) . '/SNMPLib.php');
require_once(dirname(__FILE__) . '/MibIndexer.php');
require_once(dirname(__FILE__) . '/UserDataHandler.php');
session_start();

define('ENABLE_MIB_CACHE' , true);
function getValueOrEmptyString($arrayElement)
{
	return isset($arrayElement)?$arrayElement:"";
}

if(isset($_GET['resetSession']))
{
	include('templates/Analyze.template.php');
	flush();
	unset($_SESSION['SELECTED_FILE']);
	unset($_SESSION['mib']);
	unset($_SESSION['SYS_DESC']);
	unset($_SESSION['host']);
	unset($_SESSION['community-write']);
	unset($_SESSION['community-read']);
	unset($_SESSION['mibIndex']);
	unset($_SESSION['config']);
	die("<script>window.location.href = window.location.href.replace(window.location.search,'');</script>");
}

if(isset($_SESSION['SELECTED_FILE']))
{
	$userDataHandler = new UserDataHandler($_SESSION['SELECTED_FILE']);
	$fav = new Favorite($userDataHandler);
	$gauges = new Gauges($userDataHandler);
}





if(isset($_SESSION['mib']) && ENABLE_MIB_CACHE)
{
	$mib = $_SESSION['mib'];
}
else
{

	if(ENABLE_MIB_CACHE)
	{
		include('templates/Analyze.template.php');
		flush();
	}
	$mib = new MibFiles($searchPath);
	if(isset($_SESSION['SELECTED_FILE']) && $_SESSION['SELECTED_FILE'] !== "")
	{
		$mib->selectMibTreeByName($_SESSION['SELECTED_FILE']);

	}	
	$_SESSION['mib'] = $mib;
	if(ENABLE_MIB_CACHE)
	{
		echo "<script>location.reload();</script>";
		flush();
	}



}


if(isset($_SESSION['mibIndex']) && false)
{
	$mibIndex = $_SESSION['mibIndex'];
}
else
{
	if(isset($mib->tree->root))
	{
		$mibIndex = indexMib($mib->tree->root);
		$_SESSION['mibIndex'] = $mibIndex;
	}
}



$snmp = new SNMPClient();

if(isset($_SESSION['host']))
	$snmp->setHost($_SESSION['host']);
if(isset($_SESSION['community-write']))
	$snmp->setWriteCommunityKey($_SESSION['community-write']);
if(isset($_SESSION['community-read']))
	$snmp->setReadCommunityKey($_SESSION['community-read']);

if(isset($_SESSION['use-mock']))
{
	if($_SESSION['use-mock'] == true)
	{
		$snmp->setSNMPGetBehavior(new SNMPMockBehavior());
		$snmp->setSNMPSetBehavior(new SNMPMockBehavior());
	}
	else
	{
		$snmp->setSNMPGetBehavior(new SNMPPHPExtentionBehavior());
		$snmp->setSNMPSetBehavior(new SNMPPHPExtentionBehavior());
	}
}

//checkout for system desc
if(!isset($_SESSION['SYS_INFO']) && isset($_SESSION['host']))
{
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

	$_SESSION['SYS_INFO'] = $sysInfo;
}