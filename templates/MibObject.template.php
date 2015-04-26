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


	public function render($isFavoritePage=false)
	{

		if(isset($_GET['table']))
		{
			return $this->tableRender();
		}
		else
		{
			return $this->objectsRender($isFavoritePage);
		}

	}

	private function objectsRender($isFavoritePage = false)
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
				$ret .= $object->render($isFavoritePage);
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
		$ret = "<div class=\"table-responsive\">";
		$ret .= "<table id=\"oid-table\" class=\"table oid-table\">\r\n\t\t\t<thead><tr>\n";

						foreach($type['type'] as $key => $t)
						{
							$oid = $col[$key]->getOid();

							global $mib;
							$node = $mib->tree->getNodeByOid($oid);
							if($node->type['metaType'] == 'OPTIONS')
							{
								$type = json_encode($node->type['type']);
							}
							else
							{
								$type = 'LITERAL';
							}
							$readOnly = $node->canWrite?'FALSE':'TRUE';
							$name = $this->butifyFieldName($t['name']);
							$ret .= "\r\t\t\t\t<th readonly=\"{$readOnly}\"oid=\"{$oid}\" type='{$type}'>{$name}</th>\r\n";
						}
		$ret .=	"\t\t\t</tr></thead><tbody></tbody>

				</table></div>";

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
	private function isFave()
	{
		global $fav;
		return $fav->isFaved($this->oid);
	}
	public function render($isFavoritePage = false)
	{
		$name = $this->renderName();

		$fave_render = $this->isFave()?"":"-empty";
		$fave_icon = "<div oid=\"".$this->oid. "\" class=\"glyphicon glyphicon-star". $fave_render . " favorite\"></div>";
		$edit_icon = "<div class=\"glyphicon glyphicon-pencil edit\"></div>";
		$oid_title = "<span class=\"data-title-link\" data-toggle=\"tooltip\" title=\"". $name ."\"> " . $name . ":</span>";
		$render = "";
		
		$render .= "<dt>";
		$render .= ($isFavoritePage)?$edit_icon:$fave_icon;
		$render .= $oid_title;
		$render .= "</dt><dd>

						";

		$canWrite = $this->mibObject->canWrite?'FALSE':'TRUE';
		if($this->mibObject->type['metaType'] == 'TABLE')
			$render .= "<a href='?table={$this->mibObject->name}'>Open Table</a>";
		else if($this->mibObject->type['metaType'] == 'LITERAL')
			$render .=		"
							<a class=\"editable-link\" href=\"#\" id=\"" . $this->mibObject->name . "\" readonly=\"" . $canWrite ."\" oid=\"" . $this->oid ."\" data-name=\"" . $this->oid ."\" data-type=\"text\" data-pk=\"0\" data-url=\"ajax/snmpset.php\">" . 'Fetching...' . "</a>
							<img class=\"data-loader-ajax-loader\" src=\"img/loading.gif\" id=\"". $this->mibObject->name . "-loader\"/>
						</dd>\r\n";
		else if($this->mibObject->type['metaType'] == 'OPTIONS')
		{
			$render .= "<a href=\"#\" class=\"editable-options\" id=\"" . $this->mibObject->name ."\" readonly=\"". $canWrite . "\" oid=\"" . $this->oid . "\" data-name=\"" . $this->oid ."\" data-type=\"select\" data-pk=\"0\" data-url=\"ajax/snmpset.php\" data-options='" . json_encode($this->mibObject->type['type']) ."'>" . 'Fetching...' . "</a>
						<img class=\"data-loader-ajax-loader\" src=\"img/loading.gif\" id=\"". $this->mibObject->name . "-loader\"/>
						</dd>\r\n";

		}

		
		return $render;
	}
}