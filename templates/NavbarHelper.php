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


	}

	public function getCurrentMibLocation()
	{
		if(!isset($_GET['location']))
		{
			return false;
		}
		else
		{
			return $this->mib->getNodeByName($_GET['location'])->children;
		}
	}

	private function createNavbarTree()
	{
		$tree = $this->mibMenuRoot;
		$this->filterTree($tree, 'folder');
		$this->friendlyMenuTree = $this->createFriendlyMenuTree($tree);
	}

	private function createFriendlyMenuTree($filteredItemsTree)
	{

		$items = array();
		foreach($filteredItemsTree->children as $key=>$child)
		{
			$item = array();
			$item["name"] = ucwords(str_replace("_", " ", $child->name));
			$item["key"] = $child->name;
			$item["children"] = $this->createFriendlyMenuTree($child);
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