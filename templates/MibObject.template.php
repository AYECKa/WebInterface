<?php
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
		$render = "<tr>";
		$render .= "<td>" . $this->renderName() . "</td>";
		$render .= "<td><a class=\"editable-link\" \"href=\"#\"id=\"" . $this->mibObject->name . "\">" . $this->oid . "</a><img class=\"data-loader-ajax-loader\" src=\"img/loading.gif\" id=\"". $this->mibObject->name . "-loader\"/></td>";


		$render .= "</tr>";
		return $render;
	}
}