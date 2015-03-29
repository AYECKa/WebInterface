<?php
/**
 * Created by PhpStorm.
 * User: assaf
 * Date: 3/29/15
 * Time: 9:47 PM
 */

function indexMib($rootElement)
{
    return addToIndexRecursivly([], $rootElement);
}




function getNodeUrl(MibNode $node)
{
    if($node->parent == null || $node->parent->parent == null) /* root object */ return "";
    else return "?location=" . $node->parent->name;
}


function addToIndexRecursivly($obj, MibNode $rootNode)
{
    $url = getNodeUrl($rootNode);
    if($url != "") {
        $obj[] = array("id" => $url, "name" => $rootNode->name);
        if ($rootNode->description !== null && $rootNode->description !== "")
            $obj[] = array("id" => $url, "name" => $rootNode->description);
    }


    foreach($rootNode->children as $child)
    {
        $obj = array_merge($obj, addToIndexRecursivly([], $child));
    }
    return $obj;
}
