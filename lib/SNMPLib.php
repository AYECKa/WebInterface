<?php

interface SNMPSetBehavior
{
	public function set($host, $writeCommunity, $oid, $type ,$value);
}

interface SNMPGetBehavior
{
	public function get($host, $readCommunity, $oid);
}

interface SNMPGetBulkBehavior
{
	public function get_bulk($host, $readCommunity, $oid);
}


class SNMPBashBehavior implements SNMPGetBehavior, SNMPGetBulkBehavior
{
	

	private function exec($cmd) 
	{
		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		   2 => array("pipe", "w") // stderr is a pipe that the child will write to
		);
		$process = proc_open($cmd, $descriptorspec, $pipes);
		if (is_resource($process)) {
			$stdout = stream_get_contents($pipes[1]);
			$stderr = stream_get_contents($pipes[2]);
			proc_close($process);
			return $stdout . $stderr;

		}
		else
		{
			return "INTERNAL ERROR";
		}

	}
	private function responseHasError($response)
	{
		if(strpos($response, "Error in packet") !== false) return "Error: No such oid, probably wrong MIB";
		if(strpos($response, "Timeout") !== false) return "Error: Timeout";
		if(strpos($response, "INTERNAL ERROR") !== false) return $response;
		return false;
	}
	private function getErrorArray($oids, $error)
	{
		$ret = array();
		foreach($oids as $o)
			$ret[$o] = $error;
		return $ret;
	}

	private function parseOutput($input)
	{
		$arr = explode(":", $input);
		unset($arr[0]);
		$output = trim(join($arr, ":"));
		$output = str_replace("\"", "", $output);
		return $output;
	}

	public function get_bulk($host, $readCommunity, $oids)
	{
		$cmd = "snmpget -O v -v 1 -c " . $readCommunity . " " . $host . " " . join(" ", $oids);
		$results = $this->exec($cmd);
		$err = $this->responseHasError($results);
		if($err)
		{
			return $this->getErrorArray($oids, $err);
		}			
		$results = explode("\n", $results);
		$ret = array();

		


		foreach($oids as $key => $oids)
		{
			$ret[$oids] = $this->parseOutput($results[$key]);
		}
		return $ret;
	}
	public function get($host, $readCommunity, $oid)
	{	
		return $this->get_bulk($host,$readCommunity,array($oid))[$oid];
	}
}

class SNMPPHPExtentionBehavior implements SNMPSetBehavior, SNMPGetBehavior
{
	
	public function get($host, $readCommunity, $oid)
	{	
		if(!function_exists('snmpset'))
			throw new Exception("SNMP-PHP Extention is not installed");


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
		if(function_exists('snmpset'))
			return snmpset($host, $writeCommunity, $oid, $type, $value);
		else
			throw new Exception("SNMP-PHP Extention is not installed");
	}
}

class SNMPMockBehavior implements SNMPSetBehavior, SNMPGetBehavior
{

	private function mockSysInfo()
	{

		return array( "1.3.6.1.2.1.1.6" => 'Somewhere over the rainbow',
					"1.3.6.1.2.1.1.3" =>  '232 hours 18 minutes 35 seconds (83631550)',
					"1.3.6.1.2.1.1.4" => 'GOD',
					"1.3.6.1.2.1.1.1" => 'Mock SNMP Device',
					"1.3.6.1.2.1.1.5" => 'Mock',
					"1.3.6.1.2.1.1.2" => 'Mock Object Id',
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
	private $snmpGetBulk;

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
	public function setSNMPGetBulkBehavior($behavior)
	{
		$this->snmpGetBulk = $behavior;
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

	public function get_bulk($oids)
	{
		if(!$this->snmpGetBulk instanceof SNMPGetBulkBehavior)
		{
			$ret = array();
			foreach($oids as $oid)
			{
				$ret[$oid] = $this->get($oid);
			}
			return $ret;
		}
		return $this->snmpGet->get_bulk($this->host, $this->readCommunity, $oids);
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