<?php
require_once('lib/inc.php');
if(!$mib->isFileSelected())
{
	if(isset($_POST['operation']) && $_POST['operation'] == "Manage" && isset($_POST['mib']) && isset($_POST['ip']))
	{
		if($mib->selectMibTreeByName($_POST['mib']))
		{
			$_SESSION['SELECTED_FILE'] = $_POST['mib'];
			$_SESSION['IP'] = $_POST['ip'];
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