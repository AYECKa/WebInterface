<?php
include_once('inc.php');
class NavBar
{
	private $mibMenuRoot;

	public function __construct($mib)
	{
		$this->mibMenuRoot = $mib->tree->root->children[0]->children;
	}

	public function createNavbarTree()
	{
		$tree = $this->mibMenuRoot;
		print_r($tree);
		
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
		                <li><a href="index.php">Status</a></li>
		                <li> | </li>
		                <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">ST1 User Manual</a></li>
		            </ul>
		        </div>
		    </div>
		</div>
		<?php
	}
}
$bar = new NavBar($mib);
$bar->createNavbarTree();
//$bar->render();