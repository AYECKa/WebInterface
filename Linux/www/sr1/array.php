<?php
session_start();
error_reporting(0);
function oidarray(){
$result = mysql_query('SELECT * FROM sr1mib');
$oid = array();
$type = array();
while($row = mysql_fetch_assoc($result))
{
    $oid[$row['name']] = $row['oid'];
    $type[$row['name']] = $row['type'];
}

$_SESSION['oid'] = $oid;
$_SESSION['type'] = $type;
}

function modcodarray(){
$modcod = array(
    'DVB-S2 auto',
    'qpsk 1/4',
    'qpsk 1/3',
    'qpsk 2/5',
    'qpsk 1/2',
    'qpsk 3/5',
    'qpsk 2/3',
    'qpsk 3/4',
    'qpsk 4/5',
    'qpsk 5/6',
    'qpsk 8/9',
    'qpsk 9/10',
    '8psk 3/5',
    '8psk 2/3',
    '8psk 3/4',
    '8psk 5/6',
    '8psk 8/9',
    '8psk 9/10',
    '16apsk 2/3',
    '16apsk 3/4',
    '16apsk 4/5',
    '16apsk 5/6',
    '16apsk 8/9',
    '16apsk 9/10',
    '32apsk 3/4',
    '32apsk_4_5',
    '32apsk 5/6',
    '32apsk 8/9',
    '32apsk 9/10',
    'dvbs auto',
    'dvbs 1/2',
    'dvbs 2/3',
    'dvbs 3/4',
    'dvbs 5/6',
    'dvbs 7/8'
);
$_SESSION['modcod'] = $modcod;
}

function pilotarray(){
    $pilot = array('off','on');
$_SESSION['pilot'] = $pilot;
}

function tunerStatus(){

    $tunerStatus = array("<span class='label label-success'>Locked</span>","<span class='label label-danger'>Unlocked</span>", "<span class='label label-warning'>Error</span>");
    $_SESSION['tunerStatus'] = $tunerStatus;
}
function rolloff(){
    $rolloff = array('35','25','20');
    $_SESSION['rolloff'] = $rolloff;
}

function tableArray(){
    $result = mysql_query('SELECT * FROM sr1tables WHERE forTable = "software" AND row = 1');
    $swRow1 = array();
    while($row = mysql_fetch_assoc($result))
    {
        $swRow1[$row['name']] = $row['oid'];
    }

    $_SESSION['swRow1'] = $swRow1;

    //swRow2
    $result = mysql_query('SELECT * FROM sr1tables WHERE forTable = "software" AND row = 2');
    $swRow2 = array();
    while($row = mysql_fetch_assoc($result))
    {
        $swRow2[$row['name']] = $row['oid'];
    }

    $_SESSION['swRow2'] = $swRow2;


    //fwRow1
    $result = mysql_query('SELECT * FROM sr1tables WHERE forTable = "fpga" AND row = 1');
    $fwRow1 = array();
    while($row = mysql_fetch_assoc($result))
    {
        $fwRow1[$row['name']] = $row['oid'];
    }

    $_SESSION['fwRow1'] = $fwRow1;

    //fwRow2
    $result = mysql_query('SELECT * FROM sr1tables WHERE forTable = "fpga" AND row = 2');
    $fwRow2 = array();
    while($row = mysql_fetch_assoc($result))
    {
        $fwRow2[$row['name']] = $row['oid'];
    }

    $_SESSION['fwRow2'] = $fwRow2;


    //BL
    $result = mysql_query('SELECT * FROM sr1tables WHERE forTable = "bl" AND row = 1');
    $bl = array();
    while($row = mysql_fetch_assoc($result))
    {
        $bl[$row['name']] = $row['oid'];
    }

    $_SESSION['bl'] = $bl;
}

$_SESSION['srVer'] = "2.1.3";



?>