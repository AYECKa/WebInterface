<?php
include_once('inc.php');
class NavBar
{
	private $mibMenuRoot;
	private $friendlyMenuTree;

	public function __construct($mib)
	{
		$this->mibMenuRoot = $mib->tree->root->children[0];
		$this->createNavbarTree();
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

	public function renderMenuItem($item, $isSubmenu = false)
	{
		if(count($item["children"]) === 0)
			return '<li><a href="' . '#' . '">' . $item["name"] . '</a></li>' . "\r\n";
			//if(!$isSubmenu)
				
			//else
			//	return '<li class="dropdown-submenu"><a href="' . '#' . '">' . $item["name"] . '</a></li>' . "\r\n";
		else
		{
			$ret = "<li>\r\n";
			$ret .= '<a href="' . '#' . '" class="dropdown-toggle" data-toggle="dropdown">'. $item["name"] . '<b class="caret"></b></a>' . "\r\n";
			$ret .= '<ul class="dropdown-menu multi-level">' . "\r\n";
			foreach($item["children"] as $child)
				$ret .= $this->renderMenuItem($child, true);
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
		            <ul class="nav navbar-nav">
		            	                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu 1 <b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">One more separated link</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
		                <?php
		                	foreach($this->friendlyMenuTree as $item)
		                		echo $this->renderMenuItem($item);
		                ?>
		                <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">ST1 User Manual</a></li>
		            </ul>
		        </div>
		    </div>
		</div>
		<?php
	}
}