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
		$snmp = new SNMP(SNMP::VERSION_2C, $host, $readCommunity);
		$snmp->exceptions_enabled = SNMP::ERRNO_ANY;
		try {
			$input = @$snmp->get($oid);
		}
		catch(Exception $e)
		{
			switch($snmp->getErrno())
			{
				case SNMP::ERRNO_ERROR_IN_REPLY:
					return "Error: No such oid, probably wrong MIB";
				case SNMP::ERRNO_TIMEOUT:
					return "Error: Timeout";
			}
			return $e->getMessage() . " Error No. " . $snmp->getErrno();
		}


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

	private function mockSysInfo()
	{

		return array( "1.3.6.1.2.1.1.6" => '55:55:55',
					"1.3.6.1.2.1.1.3" => 'Mock Object Id',
					"1.3.6.1.2.1.1.4" => 'GOD',
					"1.3.6.1.2.1.1.1" => 'Mock SNMP Device',
					"1.3.6.1.2.1.1.5" => 'Mock',
					"1.3.6.1.2.1.1.2" => 'Somewhere over the rainbow',
					"1.3.6.1.2.1.1.7" => 'SNMP');
	}

	public function get($host, $readCommunity, $oid)
	{
		global $mib;
		$ret = "";
		$sys = $this->mockSysInfo();
		if(isset($sys[$oid])) return $sys[$oid];
		if($oid === "") return;
		$node = $mib->tree->getNodeByOid($oid);
		//if($node == null) return "Error: Bad Name"; //will stop table from loading data...
		if($node != null && $node->type['metaType'] == "OPTIONS")
		{
			$index = rand(0, count($node->type['type'])-1);
			$ret = $node->type['type'][$index]['value'];
		}
		else
		{
			$ret = rand(0,1000);

		}
		sleep(rand(0,1000) / 1000);
		return (string)$ret;

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