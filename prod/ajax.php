<?php
require_once 'vendor/autoload.php';
include('backend/db.php');


if (1 || isset($_POST['scout'])){
$id_token = $_POST['scout'];

$scoutnumber = -1;
	
$client = new Google_Client();
$client->setApplicationName("FRC Scouting");
$client->setDeveloperKey($googleDevKey);


$payload = $client->verifyIdToken($id_token);
if ($payload) {
	$sql = "SELECT * FROM `scout` WHERE `id` = :id"; 
	$result = $db->prepare($sql);
	$result->bindValue(":id", $payload['sub']);
	$result->execute(); 
	$scouts = $result->fetchAll();
	if ($result->rowCount() > 0) {
	  	foreach($scouts as $scout){
			//print_r($scout);
			echo $scout['permission'];
			if ($scout['permission']== 0){
				echo 'Permission Error - Talk to Forest';
				exit;
			}
			$scoutnumber = $scout['internalid'];
		}
	} else {
		echo 'Permission Error - Not Registered';
		exit;
		
	}
	
	if($number_of_rows == 1){
		//Registered Valid User
		
		//Update DB with Google Info
	}
	else {
		//New Valid User
		
		//Add user to DB, then redirect to fill out other info
	}
	
	$userid = $payload['sub'];
	$email = $payload['email'];
	$name = $payload['name'];
	$picture = $payload['picture'];
	
	
	//echo $name;
} else {
	//Invalid User
  echo "Invalid";
	exit;
}

print_r($_POST);
$bmid = $_POST['bmid'];
$form = $_POST['form'];
$balls = json_decode($_POST['balls'], true);

$period = 0;
$pos = 0;
		$sqli = "INSERT INTO `2020_Submission` (`BM_ID`, `Scout`)
						VALUES (:bmid, :scout);";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":bmid",  $bmid);
		$statementi->bindValue(":scout",   $scoutnumber);
		$count = $statementi->execute();
	

	$sql= "SELECT * FROM `2020_Submission` WHERE `BM_ID` = :bmid AND `Scout` = :scout ORDER BY ID DESC LIMIT 1;";
	$statement = $db->prepare($sql);
		$statement->bindValue(":bmid", $bmid);
		$statement->bindValue(":scout", $scoutnumber);
		$statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
			$pre = $row;
		}
		$submissionid = $pre['ID'];
	
foreach ($balls as $ball){
		//action
		/*	10-Miss
			11-Reload
			12-Low
			13-Outer
			14-Inner */
	switch ($ball['action']) {
		case "miss":
			$act = "10";
			break;
		case "reload":
			$act = "11";
			break;
		case "low":
			$act = "12";
			break;
		case "outer":
			$act = "13";
			break;
		case "inner":
			$act = "14";
			break;
			
			
		case "pre":
			$act = "0";
			$period = 0;
			break;
		case "auton":
			$act = "1";
			$period = 1;
			break;
		case "telop":
			$act = "2";
			$period = 2;
			break;
		case "post":
			$act = "3";
			$period = 3;
			break;
		default:
			$act = "-1";
			break;
	}
	
	//Period
		/*	0-Pre Match
			1-Auton
			2-Telop
			3-Post Match */

		
		
	
	$sqli = "INSERT INTO `2020_Shots` (`BM_ID`, `Sub`, `Array_Position`, `Period`, `Time`, `Action`, `Title`)
						VALUES (:bmid, :sub, :pos, :period, :time, :action, :title);
						";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":bmid",  $bmid);
		$statementi->bindValue(":sub",   $submissionid);
		$statementi->bindValue(":pos",   $pos);
		$statementi->bindValue(":period",   $period);
		$statementi->bindValue(":time", $ball['time']);
		$statementi->bindValue(":action", $act);
		$statementi->bindValue(":title", $ball['title']);
		$count = $statementi->execute();
	$pos++;
}
	
	

//print_r($balls); 

	
	$sqli = "INSERT INTO `2020_Match` (`BM_ID`, `Sub`,`sd_cw_rotation`, `sd_cw_position`, `sd_eg_hang`, `sd_eg_hang_level`, `sd_eg_hang_bots`, `sd_def_giving_rating`, `sd_def_receiving_rating`, `sd_def_notes`, `sd_match_notes`, `sd_fouls`) VALUES (:bmid, :sub, :sd_cw_rotation, :sd_cw_position, :sd_eg_hang, :sd_eg_hang_level, :sd_eg_hang_bots, :sd_def_giving_rating, :sd_def_receiving_rating, :sd_def_notes, :sd_match_notes, :sd_fouls);";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":bmid",  $bmid);
		$statementi->bindValue(":sub",   $submissionid);
		$statementi->bindValue(":sd_cw_rotation", $form['sd_cw_rotation']);
		$statementi->bindValue(":sd_cw_position", $form['sd_cw_position']);
		$statementi->bindValue(":sd_eg_hang", $form['sd_eg_hang']);
		$statementi->bindValue(":sd_eg_hang_level", $form['sd_eg_hang_level']);
		$statementi->bindValue(":sd_eg_hang_bots", $form['sd_eg_hang_bots']);
		$statementi->bindValue(":sd_def_giving_rating", $form['sd_def_giving_rating']);
		$statementi->bindValue(":sd_def_receiving_rating", $form['sd_def_receiving_rating']);
		$statementi->bindValue(":sd_def_notes", $form['sd_def_notes']);
    $statementi->bindValue(":sd_match_notes", $form['sd_match_notes']);
    $statementi->bindValue(":sd_fouls", $form['sd_fouls']);
		$count = $statementi->execute();


$_POST['location'];
$_POST['stats'];
//check scout login

//insert and update in DB

//score
//unscore

/*
Data: Array
(
    [scout] => 1
    [robot] => 1234
    [location] => FarTopLeft
    [stats] => score
)


*/
$_POST['scout'];
$_POST['robot'];
$_POST['location'];
$_POST['stats'];


/*
$logdata = $email." Set: ".$_POST['location']. " = ".$_POST['stats'];
		dblog($db, "ajax", "BM=".$_POST['robot'], $logdata);
	
	
    $statementi = $db->prepare($sqli);
    $statementi->bindValue(":data", $_POST['stats']);
    $statementi->bindValue(":scout", 0);//$payload['sub']);
    $statementi->bindValue(":bmid", $_POST['robot']);
    $count = $statementi->execute();
	
	
echo " ".$_POST['location']." ".($_POST['stats'] == 1 ? "Scored" : "De-Scored");
*/


}
else{
echo "Not Logged In!!!";
}

?>
