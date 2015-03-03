<?php
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/MibParser.php');
require_once(dirname(__FILE__) . '/SNMPLib.php');
session_start();

define('ENABLE_MIB_CACHE' , true);
function getValueOrEmptyString($arrayElement)
{
	return isset($arrayElement)?$arrayElement:"";
}

if(isset($_GET['resetSession']))
{
	unset($_SESSION['SELECTED_FILE']);
	unset($_SESSION['mib']);
	die("<script>window.location.href = window.location.href.replace(window.location.search,'');</script>");
}



if(isset($_SESSION['mib']) && ENABLE_MIB_CACHE)
{
	$mib = $_SESSION['mib'];
}
else
{
	$mib = new MibFiles($searchPath);
	if(isset($_SESSION['SELECTED_FILE']) && $_SESSION['SELECTED_FILE'] !== "")
	{
		$mib->selectMibTreeByName($_SESSION['SELECTED_FILE']);
	}	
	$_SESSION['mib'] = $mib;
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
		$snmp->setSNMPSetBehavior(new SNMPMockBehavior());
	}
}