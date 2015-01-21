<?php
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/MibParser.php');

session_start();
$mib = new MibFiles($searchPath);
if(isset($_SESSION['SELECTED_FILE']) && $_SESSION['SELECTED_FILE'] !== "")
{
	$mib->selectMibTreeByName($_SESSION['SELECTED_FILE']);
}