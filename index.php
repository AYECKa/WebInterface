<?php
require_once('lib/inc.php');
if(!$mib->isFileSelected())
{
	if(isset($_POST['operation']) && $_POST['operation'] == "Manage" && isset($_POST['mib']) && isset($_POST['host']))
	{
		if($mib->selectMibTreeByName($_POST['mib']))
		{
			$_SESSION['SELECTED_FILE'] = getValueOrEmptyString($_POST['mib']);
			$_SESSION['host'] = getValueOrEmptyString($_POST['host']);
			$_SESSION['community-read'] = getValueOrEmptyString($_POST['community-read']);
			$_SESSION['community-write'] = getValueOrEmptyString($_POST['community-write']);
			$_SESSION['use-mock'] = isset($_POST['use-mock']);

			die('<script>window.location.reload();</script>');
		}
		else
		{
			die('Unexpected input...');
		}
	}
	else
	{
    	include_once('templates/FileSelect.template.php');
    }
}
else
{
    include_once('templates/MainView.template.php');
}