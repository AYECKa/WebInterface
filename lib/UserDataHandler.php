<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/31/15
 * Time: 7:44 PM
 */
define('DATA_FILE_LOCATION' , dirname(__FILE__)  . "/../user_data/user_data.json");
define('ALLOW_CONFIG_CACHE' , false);

class UserDataHandler
{
    private $config;
    function __construct($mibFileName)
    {
        $this->mibFileName = $mibFileName;


        if(!ALLOW_CONFIG_CACHE) unset($_SESSION['config']);


        //check for cache
        if(isset($_SESSION['config']))
        {
            $this->config = $_SESSION['config'];
        }

        else
        {

            if(!is_writable(DATA_FILE_LOCATION))
            {
                die("please run ./fix_permissions.sh before accessing the web interface.");
            }
            $fileContent = json_decode(file_get_contents(DATA_FILE_LOCATION));
            if($fileContent == false)
            {
                file_put_contents(DATA_FILE_LOCATION, json_encode(new stdClass()));
                $fileContent = json_decode($fileContent);
            }
            if(!isset($fileContent->{$mibFileName}))
            {
                $fileContent->{$mibFileName} = new stdClass;
                $this->saveData();
            }
            $this->config = $fileContent->{$mibFileName};
            $_SESSION['config'] = $this->config;
        }


    }
    public function setData($key, $value)
    {
        $this->config->{$key} = $value;
        $this->saveData();
    }
    public function getData($key)
    {
        if(isset($this->config->{$key}))
            return $this->config->{$key};
        else
            return false;
    }
    private function saveData()
    {
        $_SESSION['config'] = $this->config;
        $fileContent = json_decode(file_get_contents(DATA_FILE_LOCATION));
        $fileContent->{$this->mibFileName} = $this->config;
        file_put_contents(DATA_FILE_LOCATION,json_encode($fileContent));
    }
}


class Favorite {
    private $favorites;
    private $userDataHandler;
    function __construct(UserDataHandler $userDataHandler)
    {
        $this->userDataHandler = $userDataHandler;
        $data = $userDataHandler->getData('fave');
        if(!$data) $data = array();
        $this->favorites = $data;
    }

    private function getIsolatedNode($node)
    {
        $mibNode = $node->cloneNode();
        $oid = $node->getOid();
        unset($mibNode->parent);
        unset($mibNode->children);
        $mibNode->oid = $oid;
        return $mibNode;
    }

    private function addFave($node)
    {
        $mibNode = $this->getIsolatedNode($node);

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
        $node = $this->getIsolatedNode($node);
        foreach($this->favorites as $key => $fave)
        {
            if($fave->oid === $node->oid)
                unset($this->favorites[$key]);
        }
    }

    public function isFaved($oid)
    {
        foreach($this->favorites as $f)
        {
            if($f->oid === $oid)
                return true;
        }
        return false;
    }
    public function getFavorites()
    {
        $fav = array();
        //cast to mibNode
        foreach($this->favorites as $f)
        {
            $mibNode = new MibNode();
            $mibNode->name = $f->name;
            $mibNode->oid = $f->oid;
            $mibNode->type = json_decode(json_encode($f->type), true); //stdClass to array
            $mibNode->status = $f->status;
            $mibNode->description = $f->description;
            $mibNode->canRead = $f->canRead;
            $mibNode->canWrite = $f->canWrite;
            $fav[] = $mibNode;
        }
        return $fav;
    }
    public function setFave($node, $faveStatus)
    {
        if($faveStatus)
            $this->addFave($node);
        else
            $this->removeFave($node);
        $this->userDataHandler->setData('fave', $this->favorites);
    }



}