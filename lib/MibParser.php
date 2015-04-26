<?php
error_reporting(E_ALL);
class MibNode 
{
	public $name;
	public $oid;
	public $type;
	public $status;
	public $description;
	public $canRead;
	public $canWrite;
	public $parent = null;
	public $children = array();
	


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

	public function getOid()
	{
		if($this->parent == null) return $this->oid;
		return $this->parent->getOid() . "." . $this->oid;
	}

	public function getNodesOfType($type)
	{
		$nodes = array();
		if($this->type === $type)
			$nodes[] = $this;
		foreach($this->children as $child)
		{
			$nodes = array_merge($nodes, $child->getNodesOfType($type));
		}
		return $nodes;
	}

	public function cloneNode()
	{
		$ret = new MibNode();
		$ret->name = $this->name;
		$ret->oid = $this->oid;
		$ret->type = $this->type;
		$ret->status = $this->status;
		$ret->description = $this->description;
		$ret->canRead = $this->canRead;
		$ret->canWrite = $this->canWrite;
		foreach($this->children as $child)
			$ret->addChild($child->cloneNode());
		return $ret;
	}

	public function walkTo($oid)
	{
		//echo $this->oid . "-";
		if($oid == "")
		{
			return $this;
		}

		$x = explode('.', $oid);
		$current = $x[0];
		unset($x[0]);
		$rest = join('.', $x);
		foreach($this->children as $child)
		{

			if($child->oid === $current)
			{
				return $child->walkTo($rest);
			}
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
		$ayecka->oid = "1.3.6.1.4.1.27928";
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
			$mibNode->name = trim($matches[0]);
			$mibNode->oid = $matches[2];
			$mibNode->type = "folder";
			$mibNode->parent = $parent;
			$parent->addChild($mibNode);
		}
		return $mibTree;
	}
}


class MibTypeParser
{
	private $typeTree;
	public function __construct($mibFile)
	{
		$typeTree = array();
		//remove all newlines and cr

		preg_match_all('/.* ::= SEQUENCE/', $mibFile, $results,PREG_OFFSET_CAPTURE);
		$results = $results[0];
		foreach($results as $result)
		{
			//create a block string.

			$block = substr($mibFile, $result[1]);
			$block = substr($block,0,strpos($block,"}"));
			$block = str_replace("\r", "" , $block);
			$block = explode("\n",$block);
			unset($block[count($block) - 1]); //remove the whitespace
			$title = $block[0];
			preg_match('/(.*)::=/',$title, $c);
			$title = trim($c[1]);
			unset($block[0]); //remove the title
			$members = array();
			foreach($block as $member)
			{
				$item = array();
				$member = str_replace(",", "", $member); //remove delimiter
				$member = preg_split('#\s+#', $member, null, PREG_SPLIT_NO_EMPTY);
				$item["name"] = $member[0];
				$item["type"] = $member[1];
				$members[] = $item;
			}
			$typeTree[$title] = $members;
		}
		$this->typeTree = $typeTree;
	}

	public function getType($typeString)
	{
		$typeString = str_replace('SIZE', '', $typeString);
		$typeString = str_replace('((', '(', $typeString);	
		$typeString = str_replace('))', ')', $typeString);	
		//check for sequence
		if(preg_match('/SEQUENCE OF (.*)/',$typeString,$match))
		{
			//get sequence type:
			$seqType = trim($match[1]);
			return array('metaType'=>'TABLE', 'type' => $this->typeTree[$seqType]);

		}
		else if(preg_match('/(INTEGER|Integer32) \{(.*)\}/', $typeString, $match))
		{
			$oidType = $match[1];
			$options_match = explode(',', $match[2]);
			$options = array();
			foreach($options_match as $match)
			{
				preg_match('/(.*)\((.*)\)/', $match, $typeMatch);
				$desc = trim($typeMatch[1]);
				$desc = ucwords(str_replace('_', ' ', $desc));
				$options[] = array('value'=>trim($typeMatch[2]), 'text' => $desc);
			}

			return array('metaType'=>'OPTIONS' , 'oidType' => $oidType, 'type' => $options);
		}
		else if(preg_match('/(.*)\((-?\d+)..(-?\d+)\)/', $typeString, $match))
		{
			$ret = array('metaType' => 'LITERAL');
			$ret['oidType'] = $ret['type'] = $match[1];
			$ret['min'] = $match[2];
			$ret['max'] = $match[3];
			return $ret;
		}
		else
		{
			$ret = array('metaType' => 'LITERAL');
			$typeString = strtoupper(trim($typeString));
			

			//explicit every type
			switch ($typeString)
			{
				case "UNSIGNED32":
				case "INTEGER32":
				case "MACADDRESS":
				case "IPADDRESS":
				case "OCTET STRING":
				case "INTEGER":
					$ret['oidType'] = $ret['type'] = $typeString;
					break;
				default:
					//not supported type
			}
			return $ret;

		}

	}


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
	public function __construct($block,MibTypeParser $typeParser)
	{
		$this->block = $block;
		$this->typeParser= $typeParser;
	}
	public function parseObject()
	{
		preg_match('/(.*)\s*OBJECT-TYPE\s*SYNTAX\s*(.*)\s*MAX-ACCESS\s*(.*)\s*STATUS\s*(.*)\s*DESCRIPTION\s*"(.*)" \s*.*\{\s*(.*)\s*\}/', $this->block,$match);
		if(count($match) == 0) throw new Exception('ERROR PARSING BLOCK: "' .$this->block . '"');

		preg_match('/(\w*)\s*(\d*)/', $match[6], $relationMatch);
		$access = new MibAccessParser($match[3]);
		$this->rawNode = new MibNode();
		$this->rawNode->name = trim($match[1]);
		$this->rawNode->type = $this->typeParser->getType($match[2]);
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
	private $mibTypeParser;
	public function __construct($mibFile)
	{
		$this->mibFile = $mibFile;
		$this->mibTypeParser = new MibTypeParser($mibFile);
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
			preg_match('/::=.*\n/', $newSearchString, $endMatch,PREG_OFFSET_CAPTURE);
			$len = @$endMatch[0][1] + @strlen($endMatch[0][0]);
			$block = substr($mibFile, $startIndex, $len);
			$block = str_replace("\r\n", "\r", $block);
			$block = str_replace("\n", "\r", $block);
			$block = str_replace("\r", " ", $block);
			$block = str_replace("\t", " ", $block);
			$block = preg_replace('!\s+!', ' ', $block);
			$block = trim($block);
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
		foreach($blocks as $key=> $block)
		{
			$objectParsers[] = new MibObjectParser($block, $this->mibTypeParser);
		}
		return $objectParsers;

	}
}


class MibTree
{
	public $root;
	private $mibFileName;
	public function __construct($mibFilePath, $fileName)
	{
		$mibFile = file_get_contents($mibFilePath . '/' . $fileName);
		$this->mibFileName = $fileName;

		$mibTypeParser =  new MibTypeParser($mibFile);

		$objectIdentifiers = new ObjectIdentifierParser($mibFile);
		$this->root = $objectIdentifiers->parseObjectIdentifiers();
		$objectParserFactory = new MibObjectParserFactory($mibFile);
		$objectParsers = $objectParserFactory->createMibObjectParsers();

		foreach($objectParsers as $key=>$objectParser)
		{
			try {
				$objectParser->parseObject();
			}
			catch(Exception $e)
			{
				echo "Exception was thrown, ";
				echo "current block index: " . $key . "\r\n";
				throw $e;
				

			}
			$node = $objectParser->getRawNode();
			$parentNode = $this->root->getNodeByName($objectParser->getParentName());
			if($parentNode == null)
				throw new Exception("Could not find parent " . $objectParser->getParentName() ."in for object " . $node->name);
			$node->parent = $parentNode;
			$parentNode->addChild($node);
			
		}

	}

	public function getOidByName($name)
	{
		$node = $this->root->getNodeByName($name);
		return $node->getFullOid();
	}

	public function getNodesOfType($type)
	{
		return $this->root->getNodesOfType($type);
	}

	public function getNodeByOid($oid)
	{
		$oid = str_replace($this->root->oid . ".", "", $oid);
		$node = $this->root->walkTo($oid);
		return $node;

	}
}

class MibFiles
{
	private $searchPath;
	private $fileList;
	private $mibList;
	public $tree;
	private $selectedFileName;
	public function __construct($searchPath)
	{
		$this->searchPath = $searchPath;
		$this->loadMibs();
		$this->tree = false;
	}

	public function getFileNameByOid($oid)
	{
		foreach($this->mibList as $key=>$file)
		{
			$fileOid = $file->root->children[0]->getOid();
			if($fileOid === $oid)
				return $key;
		}
		return false;
	}

	public function selectMibTreeByName($name)
	{
		if(!isset($this->mibList[$name])) return false;
		$this->selectedFileName = $name;
		$this->tree = $this->mibList[$name];
		return true;
	}

	public function getSelectedMibFileName()
	{
		return $this->selectedFileName;
	}
	public function getMibFileList()
	{
		return $this->fileList;
	}
	public function isFileSelected()
	{
		return !($this->tree === false);
	}
	private function loadMibs()
	{
		$mibs = array();
		$files = scandir($this->searchPath);	
		foreach($files as $file)
			if(preg_match('/\.mib$/', $file))
			{
				$this->mibList[$file] = new MibTree($this->searchPath, $file);
				$this->fileList[] = $file;
			}

	}
}