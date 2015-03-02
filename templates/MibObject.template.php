<?php



class MibPageRender {

	private $mibGroups;
	public $hasPage;
	private $mibObject;
	public function __construct($mibObjects)
	{
		$this->mibObject = $mibObjects;
		if($mibObjects !== false)
			$this->hasPage = true;
		else
		{
			$this->hasPage = false;
			return;
		}
		$this->mibGroups = array();
		foreach($mibObjects as $object)
		{
			if($object->type !== 'folder')
			{
				$renderer = new MibObjectRender($object);
				$groupName = $renderer->getGroupName();
				if(!isset($this->mibGroups[$groupName]))
					$this->mibGroups[$groupName] = array();
				$this->mibGroups[$groupName][] = $renderer;
			}
		}
		ksort($this->mibGroups);
	}


	public function render()
	{

		if(isset($_GET['table']))
		{
			return $this->tableRender();
		}
		else
		{
			return $this->objectsRender();
		}

	}

	private function objectsRender()
	{
		$ret = "";
		$ret.= '<div class="row">' . "\n\r";
		foreach($this->mibGroups as $groupName => $group)
		{

			$ret .= "<div class=\"col-md-4 col-sm-6 col-lg-3 col-xs-12\">\n";
			$ret .= "<div class=\"panel panel-primary\">\n";
			$ret .= "<div class=\"panel-heading\">" . $groupName . "</div>\n";
			$ret .= "<div class=\"panel-body\">\n";
			$ret .= "<dl class=\"dl-horizontal\">\n";
			foreach($group as  $object)
			{
				$ret .= $object->render();
			}
			$ret .= "</dl>\n";
			$ret .= "</div>\n";
			$ret .= "</div>\n";
			$ret .= "</div>\n";

		}
		$ret.= '</div>' . "\n\r";
		return $ret;
	}
	private function butifyFieldName($name)
	{
		$ret = "";
		$arr = preg_split('/(?=\p{Lu})/u', $name);
		if($arr[0] === '') unset($arr[0]); //remove first element, if string starts with a capital letter.
		foreach($arr as $word)
		{
			$ret .= ucwords($word) . " ";
		}
		return $ret;

	}
	private function tableRender()
	{
		//try and get the table type...
		$col = $this->mibObject[0]->children;
		$type = $this->mibObject[0]->parent->type;
		$ret = "<table class=\"table table-responsive oid-table\">\r\n\t\t\t<tr>\n";
						foreach($type as $key => $t)
						{
							$oid = $col[$key]->getOid();
							$name = $this->butifyFieldName($t['name']);
							$ret .= "\r\t\t\t\t<td oid=\"{$oid}\">{$name}</td>\r\n";
						}
		$ret .=	"\t\t\t</tr>

				</table>";

		return $ret;

	}

}


class MibObjectRender {
	private $mibObject;
	private $oid;
	public function __construct($mibObject)
	{
		$this->mibObject = $mibObject->cloneNode();
		$this->oid = $mibObject->getOid();
		unset($this->mibObject->parent);
		unset($this->mibObject->children);

	}

	public function getGroupName()
	{
		if($this->mibObject->description !== "")
			return explode(' ', trim($this->mibObject->description))[0];

		$arr = preg_split('/(?=\p{Lu})/u', $this->mibObject->name);
		if($arr[0] === '') unset($arr[0]); //remove first element, if string starts with a capital letter.

		return ucwords(array_values($arr)[0]);
	}

	private function renderName()
	{
		if($this->mibObject->description !== "")
			return trim($this->mibObject->description);
		$ret = "";
		//TODO: update to desc instead of name
		$arr = preg_split('/(?=\p{Lu})/u', $$this->mibObject->name);
		if($arr[0] === '') unset($arr[0]); //remove first element, if string starts with a capital letter.
		foreach($arr as $word)
		{
			$ret .= ucwords($word) . " ";
		}
		return $ret;
	}
	public function render()
	{
		$render = "";
		$name = $this->renderName();
		$render .= "";
		$render .= "<dt><a href=\"#\" class=\"data-title-link\" data-toggle=\"tooltip\" title=\"". $name ."\"> " . $name . ":</a> </dt>";
		$render .= "<dd>

						";
		if(is_array($this->mibObject->type))
			$render .= "<a href='?table={$this->mibObject->name}'>Open Table</a>";
		else
			$render .=		"
							<a class=\"editable-link\" href=\"#\" id=\"" . $this->mibObject->name . "\" oid=\"" . $this->oid ."\">" . 'Fetching...' . "</a>
							<img class=\"data-loader-ajax-loader\" src=\"img/loading.gif\" id=\"". $this->mibObject->name . "-loader\"/>
						</dd>\r\n";


		
		return $render;
	}
}