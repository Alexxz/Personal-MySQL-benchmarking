<?php 
chdir(dirname(__FILE__));
include('conf.php');

function getValuesForRandomAccess(){
    $arr = array();
    foreach(range(1, 10000) as $i){
        $arr[] = rand(1,100000000);
    }
    return $arr;
}

function getValuesForSequencialAccess(){
    $r = rand(1, 100000000-10000);
    return range($r, $r+10000);
}


$conn = mysql_connect($config['host'], $config['user'], $config['pass']);
if(!$conn){
    die("Can't connect to MySQL server".PHP_EOL);
}

$res = mysql_select_db($config['db'], $conn);
if(!$res){
    die("There is an error in select query ".mysql_error($conn).PHP_EOL);
}

foreach(range(1, 10) as $i){
    //$ids = getValuesForRandomAccess();
    $ids = getValuesForSequencialAccess();
    
    $values = implode(',',$ids);

    $time = microtime(true);
    $res = mysql_query("SELECT * FROM messages where message_id in ($values)", $conn);
    $time = microtime(true)-$time;
    
    $print_time = round($time * 1000);
    echo "$print_time ms".PHP_EOL;

    if(!$res){
        die("There is an error in select query ".mysql_error($conn).PHP_EOL);
    }

    mysql_free_result($res);
}


mysql_close($conn);
