<?php
error_reporting(E_ALL);



$mibTree = new MibTree('ST1g_v8.01b103.mib');
print_r($mibTree);

class MibNode 
{
	public $name;
	public $oid;
	public $type;
	public $status;
	public $description;
	public $canRead;
	public $canWrite;
	public $children = array();
	public $parent;


	public function addChild($child)
	{
		$this->children[] = $child;
	}

	public function getNodeByName($name)
	{
		if($this->name === $name)
		{
			return $this;
		}
		else
		{
			foreach($this->children as $child)
			{
				$node = $child->getNodeByName($name);
				if($node != null)
					return $node;
			}
			return null;
		}
	}
}



class ObjectIdentifierParser
{
	private $mibFile = "";

	public function __construct($mibFile)
	{
		$this->mibFile = $mibFile;
	}
	private function getObjectIdentifiersMatches()
	{
		$matches = array();
		$lines = explode("\n", $this->mibFile);
		foreach($lines as $line)
		{
			$hasMatch = preg_match('/^(.*)OBJECT IDENTIFIER.*\{(.*) (\d*)\}/', $line, $match);
			if($hasMatch)
			{

				unset($match[0]);
				foreach($match as $key=>$val) $match[$key] = trim($val);
				$matches[] = array_values($match);
			}
		}
		return $matches;
	}
	private function createObjectIdentifiresTreeWithParent($parentMatch)
	{
		$ayecka = new MibNode();
		$ayecka->name = "ayecka";
		$ayecka->oid = ".1.3.6.1.4.1.27928";
		$ayecka->type = "documentRoot";
		$parentObject = new MibNode();
		$parentObject->name = $parentMatch[0];
		$parentObject->oid = $parentMatch[2];
		$parentObject->parent = $ayecka;
		$ayecka->addChild($parentObject);

		return $ayecka;
	}
	public function parseObjectIdentifiers() {

		$oiMatches = $this->getObjectIdentifiersMatches();		
		$mibTree = $this->createObjectIdentifiresTreeWithParent($oiMatches[0]);
		unset($oiMatches[0]); //remove parent from list
		
		foreach($oiMatches as $matches)
		{
			$parent = $mibTree->getNodeByname($matches[1]);
			if($parent == null)
				throw new Exception('Mib object doesnt have a parent');
			$mibNode = new MibNode();
			$mibNode->name = $matches[0];
			$mibNode->oid = $matches[2];
			$mibNode->type = "folder";
			$mibNode->parent = $parent;
			$parent->addChild($mibNode);
		}
		return $mibTree;
	}
}

class MibType
{

}
class MibAccessParser
{
	private $canRead;
	private $canWrite;
	public function __construct($access)
	{
		$canRead = false;
		$canWrite = false;
		if(preg_match('/write/', $access)) $this->canWrite = true;
		if(preg_match('/read/', $access)) $this->canRead = true;
	}
	public function canRead()
	{
		return $this->canRead;
	}
	public function canWrite()
	{
		return $this->canWrite;
	}

}

class MibObjectParser
{
	private $block;
	private $rawNode;
	private $parentName;
	public function __construct($block)
	{
		$block = str_replace("\r\n", " ", $block);
		$block = str_replace("\t", " ", $block);
		$block = preg_replace('!\s+!', ' ', $block);
		$this->block = $block;	
	}
	public function parseObject()
	{
		preg_match('/(.*)\s*OBJECT-TYPE\s*SYNTAX\s*(.*)\s*MAX-ACCESS\s*(.*)\s*STATUS\s*(.*)\s*DESCRIPTION\s*"(.*)" \s*.*\{\s*(.*)\s*\}/', $this->block,$match);
		if(count($match) == 0) throw new Exception('ERROR PARSING BLOCK: ' . $block);

		preg_match('/(\w*)\s*(\d*)/', $match[6], $relationMatch);
		$access = new MibAccessParser($match[3]);
		$this->rawNode = new MibNode();
		$this->rawNode->name = $match[1];
		$this->rawNode->type = $match[2];
		$this->rawNode->status = $match[4];
		$this->rawNode->canRead = $access->canRead();
		$this->rawNode->canWrite = $access->canWrite();
		$this->rawNode->description = $match[5];
		$this->rawNode->oid = $relationMatch[2];
		$this->parentName = $relationMatch[1];
		
	}

	public function getRawNode()
	{
		return $this->rawNode;
	}

	public function getParentName()
	{
		return $this->parentName;
	}
}
class MibObjectParserFactory
{
	public function __construct($mibFile)
	{
		$this->mibFile = $mibFile;
	}
	private function getBlockArray()
	{
		$mibFile = $this->mibFile;
		$blocks = array();
		preg_match_all("/.* OBJECT-TYPE/", $mibFile, $matches, PREG_OFFSET_CAPTURE);
		unset($matches[0][0]); //this is the type inc direction
		$matches = array_values($matches[0]);

		foreach($matches as $match)
		{
			$startIndex = $match[1];
			$newSearchString = substr($mibFile, $startIndex);
			preg_match("/\r\n\s*\r\n/", $newSearchString, $endMatch,PREG_OFFSET_CAPTURE);
			$len = $endMatch[0][1];
			$block = substr($mibFile, $startIndex, $len);
			if(!preg_match('/^--/', $block))
			{
				$blocks[] = $block;
			}
			
		}
		return $blocks;
	}
	public function createMibObjectParsers()
	{
		$objectParsers = array();
		$blocks = $this->getBlockArray();
		foreach($blocks as $block)
		{
			$objectParsers[] = new MibObjectParser($block);
		}
		return $objectParsers;

	}
}


class MibTree
{
	private $rootElement;
	public function __construct($mibFilePath)
	{
		$mibFile = file_get_contents($mibFilePath);
		$objectIdentifiers = new ObjectIdentifierParser($mibFile);
		$this->rootElement = $objectIdentifiers->parseObjectIdentifiers();
		$objectParserFactory = new MibObjectParserFactory($mibFile);
		$objectParsers = $objectParserFactory->createMibObjectParsers();

		foreach($objectParsers as $objectParser)
		{
			$objectParser->parseObject();
			$parentName = $objectParser->getParentName();
			echo $parentName;
			$parentNode = $this->rootElement->getNodeByName($parentName);

			$node = $objectParser->getRawNode();
			$node->parent = $parentNode;
			if($parentNode == null)
			{
				print_r($this->rootElement);
				die();
			}
			else
			{
				$parentNode->addChild($node);
			}
			
		}

	}
}

