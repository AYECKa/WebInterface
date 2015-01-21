<?php
require_once('inc.php');
if(!$mib->isFileSelected())
{
	if(isset($_POST['operation']) && $_POST['operation'] == "select_file")
	{
		if($mib->selectMibTreeByName($_POST['mib']))
		{
			$_SESSION['SELECTED_FILE'] = $_POST['mib'];
		}
		else
		{
			die('Unexpected input...');
		}
	}
	else
	{
    	include_once('FileSelect.php');
    }
}
else
{
    echo "no operation";
}