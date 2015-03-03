<?php

interface SNMPSetBehavior
{
	public function set($host, $writeCommunity, $oid, $type ,$value);
}

interface SNMPGetBehavior
{
	public function get($host, $readCommunity, $oid);
}


class SNMPPHPExtentionBehavior implements SNMPSetBehavior, SNMPGetBehavior
{
	
	public function get($host, $readCommunity, $oid)
	{	
		ob_start();
		$input = snmpget($host, $readCommunity, $oid);
		$errors = ob_get_contents();
		ob_clean();
		if($errors !== "") return $errors;
		$arr = explode(":", $input);
		unset($arr[0]);
		$output = trim(join($arr, ":"));
		$output = str_replace("\"", "", $output);
		return $output;
	}
	public function set($host, $writeCommunity, $oid, $type ,$value)
	{
		return snmpset($host, $writeCommunity, $oid, $type, $value);
	}
}

class SNMPMockBehavior implements SNMPSetBehavior, SNMPGetBehavior
{
	public function get($host, $readCommunity, $oid)
	{
		$a = rand(0,1000) / 1000;
		sleep($a);
		return (string)$a;
	}
	public function set($host, $writeCommunity, $oid, $type ,$value)
	{
		$a = rand(0,1000) / 1000;
		sleep($a);	
	}
}

class SNMPClient
{
	private $writeCommunity;
	private $readCommunity;
	private $host;
	private $snmpGet;
	private $snmpSet;

	public function setHost($host)
	{
		$this->host = $host;
	}
	public function setWriteCommunityKey($key)
	{
		$this->writeCommunity = $key;
	}
	public function setReadCommunityKey($key)
	{
		$this->readCommunity = $key;
	}
	public function setSNMPGetBehavior($behavior)
	{
		$this->snmpGet = $behavior;
	}
	public function setSNMPSetBehavior($behavior)
	{
		$this->snmpSet = $behavior;
	}

	public function get($oid)
	{
		if(!$this->snmpGet instanceof SNMPGetBehavior)
		{
			throw new Exception("SNMP Get behavior is not set");
		}
		return $this->snmpGet->get($this->host, $this->readCommunity, $oid);
	}
	public function set($oid, $type ,$value)
	{
		if(!$this->snmpSet instanceof SNMPSetBehavior)
		{
			throw new Exception("SNMP Set behavior is not set");
		}
		return $this->snmpSet->set($this->host, $this->writeCommunity, $oid,$type ,$value);
	}

}