<?php


//---------------------------------------
//The search path of the mib files
//---------------------------------------

$siteRoot = dirname(__FILE__) . '/../';
$searchPath = $siteRoot . "mibs/";
$mibCache = $siteRoot . "user_data/mib_data.dat";
$mibIndex = $siteRoot . "user_data/mib_index.dat";
define('DATA_FILE_LOCATION' , dirname(__FILE__)  . "/../user_data/user_data.json");
define('ALLOW_CONFIG_CACHE' , true);