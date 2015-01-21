<?php
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/MibParser.php');

session_start();
$mib = new MibFiles($searchPath);
if(isset($_GET['resetSession']))
{
	unset($_SESSION['SELECTED_FILE']);
	die("<script>window.location.href = window.location.href.replace(window.location.search,'');</script>");
}

if(isset($_SESSION['SELECTED_FILE']) && $_SESSION['SELECTED_FILE'] !== "")
{
	$mib->selectMibTreeByName($_SESSION['SELECTED_FILE']);
}