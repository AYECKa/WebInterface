<?php
require_once(dirname(__FILE__) . '/MibParser.php');
require_once(dirname(__FILE__) . '/SNMPLib.php');
require_once(dirname(__FILE__) . '/sysinfo.php');
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/UserDataHandler.php');
session_start();

//check if dirs are writable
if(!is_writable(DATA_FILE_LOCATION))
{
    die("please run ./fix_permissions.sh before accessing the web interface.");
}
//check if mibs are compiled
if(!file_exists($mibCache))
{	
	die("Please run 'php make.php' before entering the web interface");
}
else
{
	$cache = file_get_contents($mibCache);
	$mib = unserialize($cache);
	
}

function getValueOrEmptyString($arrayElement)
{
	return isset($arrayElement)?$arrayElement:"";
}

if(isset($_GET['resetSession']))
{
	unset($_SESSION['SELECTED_FILE']);
	unset($_SESSION['SYS_INFO']);
	unset($_SESSION['host']);
	unset($_SESSION['community-write']);
	unset($_SESSION['community-read']);
	unset($_SESSION['config']);
	header('Location: index.php');
	die();
}

if(isset($_SESSION['SELECTED_FILE']))
{
	$mib->selectMibTreeByName($_SESSION['SELECTED_FILE']);
	$userDataHandler = new UserDataHandler($_SESSION['SELECTED_FILE']);
	$fav = new Favorite($userDataHandler);
	$gauges = new Gauges($userDataHandler);
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
		$snmp->setSNMPGetBehavior(new SNMPBashBehavior());
		$snmp->setSNMPSetBehavior(new SNMPPHPExtentionBehavior());
	}
}

//checkout for system desc
if(!isset($_SESSION['SYS_INFO']) && isset($_SESSION['host']))
{
	$sysInfo = getSysInfo();
	$_SESSION['SYS_INFO'] = $sysInfo;
}