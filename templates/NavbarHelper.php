<?php
class NavBar
{
	private $mib;
	private $mibMenuRoot;
	private $friendlyMenuTree;
	private $currentLocation;
	public function __construct($mib)
	{
		$this->mib = $mib->tree->root;
		$mibMenu = $this->mib->children[0];
		$this->mibMenuRoot = $mibMenu->cloneNode();
		$this->createNavbarTree();
		$this->currentLocation = isset($_GET['location'])?$_GET['location']:false;

	}

	public function getCurrentCategoryName()
	{
		if($this->currentLocation)
			return ucwords(join(explode('_' ,$this->currentLocation), ' '));
		return "";
	}

	public function getCurrentMibLocation()
	{
		if($this->currentLocation)
			return $this->mib->getNodeByName($this->currentLocation)->children;
		return false;
	}

	private function createNavbarTree()
	{
		$tree = $this->mibMenuRoot;
		$this->filterTree($tree, 'folder');
		$this->friendlyMenuTree = $this->createFriendlyMenuTree($tree);
	}

	private function hasObjectChildren($menuName)
	{
		$node = $this->mib->getNodeByName($menuName);
		foreach($node->children as $child)
		{
			if($child->type !== 'folder')
				return true;
		}
		return false;
	}

	private function hasFolderChildren($menuName)
	{
		$node = $this->mib->getNodeByName($menuName);
		foreach($node->children as $child)
		{
			if($child->type === 'folder')
				return true;
		}
		return false;
	}

	private function createFriendlyMenuTree($filteredItemsTree)
	{

		$items = array();
		foreach($filteredItemsTree->children as $key=>$child)
		{
			$item = array();
			$item["name"] = ucwords(str_replace("_", " ", $child->name));
			$item["key"] = $child->name;

			$item["children"] = array();
			$thisItem = array("name" => $item["name"] . " Settings",
							  "key" => $item["key"], 
							  "children" => array());
			if($this->hasObjectChildren($item['key']) && $this->hasFolderChildren($item['key']))
			{
				$item["children"][] = $thisItem;
			}
			$item["children"] = array_merge($item["children"], $this->createFriendlyMenuTree($child));
			$items[] = $item;
		}
		return $items;
	}

	private function filterTree($tree, $type)
	{
		foreach($tree->children as $key=>$child)
		{
			if($child->type !== $type)
				unset($tree->children[$key]);
			else
				$this->filterTree($child, $type);
		}
	}

	public function renderMenuItem($item)
	{
		if(count($item["children"]) === 0)
			return '<li><a href="' . "?location=" . $item['key'] . '">' . $item["name"] . '</a></li>';
		else
		{
			$ret = "<li>\r\n";
			$ret .= '<a class="trigger" >'. $item["name"] . '<b class="caret"></b></a>' . "\r\n";
			$ret .= '<ul class="dropdown-menu sub-menu navbarmenu-items">' . "\r\n";
			foreach($item["children"] as $child)
				$ret .= $this->renderMenuItem($child);
			$ret .= "</ul>\r\n";
			$ret .= "</li>\r\n";
			return $ret;
		}
	}
	public function render()
	{
		?>
		<div class="navbar navbar-inverse">
		    <div class="container">
		        
		        <div class="navbar-header">

		        </div>
				
		        <div class="navbar-collapse collapse">

		            <ul class="nav navbar-nav navbarmenu-items">
		            	<li><a href=".">Dashboard</a></li>
		                <?php
		                	foreach($this->friendlyMenuTree as $item)
		                		echo $this->renderMenuItem($item);
		                ?>
		                <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">User Manual</a></li>
		                <li> <a href="?resetSession">Manage another device</a></li>
		            </ul>
		        </div>
		    </div>
		</div>
		<?php
	}
}