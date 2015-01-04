<?php
function sql_connect(){

$link = mysql_connect("localhost", "root", “password”);
if (!$link) {
    die("Could not connect: " . mysql_error());
}


$db_selected = mysql_select_db("Ayecka", $link);
if (!$db_selected) {
    die ("Can't use internet_database : " . mysql_error());
}

}
?>