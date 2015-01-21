<?php
class SNMPLib
{
	$cache = array();
	public __construct()
	{

	}
	public function get($oid)
	{
		if(isset($cache[$oid])) return $cache[$oid];
		return "SNMP Data ";
	}
	public function set($oid, $value)
	{
		$cache[$oid] = $value;
		return true;
	}
}