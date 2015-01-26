<?php

class MibPageRender {

	private $mibGroups;
	public $hasPage;
	public function __construct($mibObjects)
	{
		if($mibObjects !== false)
			$this->hasPage = true;
		else
			$this->hasPage = false;


		foreach($mibObjects as $object)
		{
			$renderer = new MibObjectRender($object);
			if(!isset($this->mibGroups[$renderer->getGroupName()]))
				$this->mibGroups[$renderer->getGroupName()] = array();
			$this->mibGroups[$renderer->getGroupName()][] = $renderer;
		}
	}


	public function render()
	{
		$ret = "";
		$ret.= '<div class="row">' . "\n\r";
		foreach($this->mibGroups as $groupName => $group)
		{

			$ret .= "<div class=\"col-md-4\">";
			$ret .= "<h4 style=\"font-weight: bold;\" >" . $groupName . "</h4>";
			foreach($group as  $object)
			{
				
				$ret .= $object->render();
			}
			$ret .= "</div>";
		}
		$ret.= '</div>' . "\n\r";
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
		$arr = preg_split('/(?=\p{Lu})/u', $this->mibObject->name);
		return ucwords($arr[0]);
	}

	private function renderName()
	{
		$ret = "";
		$arr = preg_split('/(?=\p{Lu})/u', $this->mibObject->name);
		foreach($arr as $word)
		{
			$ret .= ucwords($word) . " ";
		}
		return $ret;
	}
	public function render()
	{
		$render = "";
		
		$render .= "<span>" . $this->renderName() . ": </span>";
		$render .= "<span><a class=\"editable-link\" href=\"#\" id=\"" . $this->mibObject->name . "\" oid=\"" . $this->oid ."\">" . 'Fetching...' . "</a><img class=\"data-loader-ajax-loader\" src=\"img/loading.gif\" id=\"". $this->mibObject->name . "-loader\"/></span></br>\r\n";


		
		return $render;
	}
}