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
	
	$sqli = "INSERT INTO `log` (`IP`, `user`, `page`, `function`, `message`)
						VALUES (:ip, :user, :page, :func, :msg);
						";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":ip",  $ip);
		$statementi->bindValue(":user",   $user);
		$statementi->bindValue(":page",   $page);
		$statementi->bindValue(":func",   $function);
		$statementi->bindValue(":msg", $message);
		$count = $statementi->execute();
		
}

function convertHSL($h, $s, $l, $toHex=true){
    $h /= 360;
    $s /=100;
    $l /=100;

    $r = $l;
    $g = $l;
    $b = $l;
    $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
    if ($v > 0){
          $m;
          $sv;
          $sextant;
          $fract;
          $vsf;
          $mid1;
          $mid2;

          $m = $l + $l - $v;
          $sv = ($v - $m ) / $v;
          $h *= 6.0;
          $sextant = floor($h);
          $fract = $h - $sextant;
          $vsf = $v * $sv * $fract;
          $mid1 = $m + $vsf;
          $mid2 = $v - $vsf;

          switch ($sextant)
          {
                case 0:
                      $r = $v;
                      $g = $mid1;
                      $b = $m;
                      break;
                case 1:
                      $r = $mid2;
                      $g = $v;
                      $b = $m;
                      break;
                case 2:
                      $r = $m;
                      $g = $v;
                      $b = $mid1;
                      break;
                case 3:
                      $r = $m;
                      $g = $mid2;
                      $b = $v;
                      break;
                case 4:
                      $r = $mid1;
                      $g = $m;
                      $b = $v;
                      break;
                case 5:
                      $r = $v;
                      $g = $m;
                      $b = $mid2;
                      break;
          }
    }
    $r = round($r * 255, 0);
    $g = round($g * 255, 0);
    $b = round($b * 255, 0);

    if ($toHex) {
        $r = ($r < 15)? '0' . dechex($r) : dechex($r);
        $g = ($g < 15)? '0' . dechex($g) : dechex($g);
        $b = ($b < 15)? '0' . dechex($b) : dechex($b);
        return "#$r$g$b";
    } else {
        return "rgb($r, $g, $b)";    
    }
}

$ev_current = '2020isde1';//'2020week0';// '2019mawne'; // 2019mawne   2019nhdur   2019necmp  2019cars
$event_id = '14';//'7'; //7-wne  10-unh 9-dc 15-carson

?>