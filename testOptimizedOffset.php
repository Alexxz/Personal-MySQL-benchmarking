<?php 
chdir(dirname(__FILE__));
include('conf.php');

echo "Performing offset benchmarking".PHP_EOL;

$tests = array(
    '100'   => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 100",
    '1000'  => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 1000",
    '5000'  => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 5000",
    '10000' => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 10000",
    '20000' => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 20000",
    '30000' => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 30000",
    '40000' => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 40000",
    '50000' => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 50000",
    '60000' => "SELECT SQL_NO_CACHE message_id FROM messages WHERE user1=3 and user2=4 order by ts limit 20 offset 60000",
    );

foreach($tests as $name => $test){
    /////////////////////////////////////////////////
    system("sh cleancache.sh");
    $conn = mysql_connect($config['host'], $config['user'], $config['pass']);
    if(!$conn){
        die("Can't connect to MySQL server".PHP_EOL);
    }

    $res = mysql_select_db($config['db'], $conn);
    if(!$res){
        die("There is an error in select query ".mysql_error($conn).PHP_EOL);
    }


    $res = mysql_query("SELECT * FROM messages limit 1", $conn);
    mysql_free_result($res);
    ////////////////////////////////////////////////
    $time = microtime(true);
    
    $ids = array();
    $res = mysql_query($test, $conn);

    if(!$res){
        die("There is an error in select query ".mysql_error($conn).PHP_EOL);
    }

    while($row = mysql_fetch_assoc($res)){
        $ids[] = (int)$row['message_id'];
    }
    
    $values = implode(',',$ids);
    
    $res = mysql_query("SELECT * FROM messages where message_id in ($values)", $conn);

    $time = microtime(true)-$time;
    
    $print_time = round($time * 1000);
    echo "$name\t=>\t$print_time ms".PHP_EOL;

    if(!$res){
        die("There is an error in select query ".mysql_error($conn).PHP_EOL);
    }

    mysql_free_result($res);
    mysql_close($conn);
}
