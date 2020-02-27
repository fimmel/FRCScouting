<?php
include ("/var/www/secret.php");

try {
    $db = new PDO("mysql:host=$DB_server;dbname=$DB_name", $DB_username, $DB_password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }



function dblog($db, $page, $function, $message){
    $user = 0; // togo use google
    if ($_SERVER['REMOTE_ADDR'] == null){
        $ip="Console";
    }
    else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $sqli = 'INSERT INTO `log` (`IP`, `user`, `page`, `function`, `message`)
						VALUES (:ip, :user, :page, :func, :msg);
						';
    $statementi = $db->prepare($sqli);
    $statementi->bindValue(":ip",  $ip);
    $statementi->bindValue(":user",   $user);
    $statementi->bindValue(":page",   $page);
    $statementi->bindValue(":func",   $function);
    $statementi->bindValue(":msg", $message);
    $count = $statementi->execute();

}



$ev_current = '2020week0';// '2019mawne'; // 2019mawne   2019nhdur   2019necmp  2019cars '2020isde1';
$event_id = '13';//'7'; //7-wne  10-unh 9-dc 15-carson
$forcelogin = TRUE;
?>