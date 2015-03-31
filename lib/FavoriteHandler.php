<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/31/15
 * Time: 7:44 PM
 */
define('FAV_FILE_LOCATION' , dirname(__FILE__)  . "/../user_data/user_fave.json");

class Favorite {

    private $favorites;
    function __construct()
    {
        if(!is_writable(FAV_FILE_LOCATION))
        {
            die("please run ./fix_permissions.sh before accessing the web interface.");
        }
        //check for cache
        if(isset($_SESSION['fave']))
        {
            $this->favorites = $_SESSION['fave'];
        }
        else
        {
            $this->favorites = json_decode(file_get_contents(FAV_FILE_LOCATION));
            $_SESSION['fave'] = $this->favorites;
        }
    }

    private function addFave($node)
    {
        $mibNode = $node->cloneNode();
        $oid = $node->getOid();
        unset($mibNode->parent);
        unset($mibNode->children);
        $mibNode->oid = $oid;

        //look for the fave to prevent duplicates
        foreach($this->favorites as $fave)
        {
            if($fave->oid === $oid)
                return;
        }
        echo "Added OID: " . $oid;
        $this->favorites[] = $mibNode;
    }
    private function removeFave($node)
    {

    }

    public function setFave($node, $faveStatus)
    {
        echo "LETS SET THE FAVE";
        if($faveStatus)
            $this->addFave($node);
        else
            $this->removeFave($node);
        $this->saveData();
    }

    private function saveData()
    {
        print_r($this->favorites);
        $_SESSION['fave'] = $this->favorites;
        file_put_contents(FAV_FILE_LOCATION,json_encode($this->favorites));
    }

}