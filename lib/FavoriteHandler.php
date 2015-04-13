<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/31/15
 * Time: 7:44 PM
 */
define('FAV_FILE_LOCATION' , dirname(__FILE__)  . "/../user_data/user_data.json");

class Favorite {
    private $mibFile;
    private $favorites;
    function __construct($mibFile)
    {
        unset($_SESSION['fave']);
        $this->mibFile = $mibFile;
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
            $fileContent = json_decode(file_get_contents(FAV_FILE_LOCATION));
            if($fileContent == false)
            {
                $fileContent = "{}";
                file_put_contents(FAV_FILE_LOCATION, $fileContent);
                $fileContent = json_decode($fileContent);
            }
            if(isset($fileContent->{$mibFile}))
            {
               $this->favorites = $fileContent->{$mibFile};
            }
            $this->favorites = array();
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
        $this->favorites[] = $mibNode;
    }
    private function removeFave($node)
    {
        foreach($this->favorites as $key => $fave)
        {
            if($fave->oid === $node->oid)
                unset($this->favorites[$key]);
        }
    }

    public function setFave($node, $faveStatus)
    {
        if($faveStatus)
            $this->addFave($node);
        else
            $this->removeFave($node);
        $this->saveData();
    }

    private function saveData()
    {
        $_SESSION['fave'] = $this->favorites;
        $fileContent = json_decode(file_get_contents(FAV_FILE_LOCATION));
        $fileContent->{$this->mibFile} = array_values($this->favorites);
        file_put_contents(FAV_FILE_LOCATION,json_encode($fileContent));
    }

}