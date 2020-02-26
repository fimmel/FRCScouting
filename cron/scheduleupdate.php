<?php
include('../prod/tba.php');
include('../prod/backend/db.php');

date_default_timezone_set('EST');

//$matches2 = tba2array($tbaRequest->getEventMatches(['event_key' => '2017mawor']));
//print_r ($matches2);


function sched_addmatch($db,$m,$event_id){
	$level = $m['comp_level'];
	$match = $m['match_number'];
	$set = $m['set_number'];
	$time = $m['time'];
	switch ($level) {
			case "qm":
					$leveldb = 2;
					break;
			case "qf":
					$leveldb = 3;
					break;
			case "sf":
					$leveldb = 4;
					break;
			case "f":
					$leveldb = 5;
					break;
			default:
					$leveldb = 1;
					break;
	}

	$sql = "SELECT count(*) FROM matches WHERE
							`event_id` = :eid AND
							`level` = :mt AND
							`match_num` = :mn AND
							`set_num` = :ms;";
	$statement = $db->prepare($sql);
	$statement->bindValue(":eid",   $event_id);
	$statement->bindValue(":mt",    $leveldb);
	$statement->bindValue(":mn",    $match);
	$statement->bindValue(":ms",    $set);
	$statement->execute();
	$number_of_rows = $statement->fetchColumn();

	//echo $number_of_rows;
	if ($number_of_rows < 1){
		$sqli = "INSERT INTO `matches` (`event_id`, `level`, `match_num`, `set_num`, `time`)
						VALUES (:eid, :mt, :mn, :ms, :mtime);
						";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":eid",  $event_id);
		$statementi->bindValue(":mt",   $leveldb);
		$statementi->bindValue(":mn",   $match);
		$statementi->bindValue(":ms",   $set);
		$statementi->bindValue(":mtime",date("Y-m-d H:i:s",$time));
		$count = $statementi->execute();
		
		print("Inserted ".$event_id." - ".$level." ".$set." Match ".$match." With a time of ".date("Y-m-d H:i:s",$time)."\n");
	}
	else{
		print("EXISTS ".$event_id." - ".$level." ".$set." Match ".$match." With a time of ".date("Y-m-d H:i:s",$time)."\n");
	}
}
function sched_addteamtomatch($db,$matchid,$pos,$team){
	$sql = "SELECT count(*) FROM match_robot WHERE
							`match_id` = :match AND
							`team_id` = :team;";
	$statement = $db->prepare($sql);
	$statement->bindValue(":match", $matchid);
	$statement->bindValue(":team",  $team);
	$statement->execute();
	$number_of_rows = $statement->fetchColumn();

	if ($number_of_rows < 1){
		$sqli = "INSERT INTO `match_robot` (`match_id`, `team_id`, `position`)
						VALUES (:match, :team, :pos);";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":match", $matchid);
		$statementi->bindValue(":team",  $team);
		$statementi->bindValue(":pos",   $pos);
		$count = $statementi->execute();
		
		$sql = "SELECT id FROM match_robot WHERE
							`match_id` = :match AND
							`team_id` = :team;";
		$statement2 = $db->prepare($sql);
		$statement2->bindValue(":match", $matchid);
		$statement2->bindValue(":team",  $team);
		$statement2->execute();
		$mrid = $statement2->fetchall();
		//print_r ($mrid);
		//echo $mrid['0']['id'];
		

			
		
	}
}

function updatematchschedule($db, $tbaRequest, $event){
	$matches = tba2array($tbaRequest->getEventMatches(['event_key' => $event]));
$sql = "SELECT `id` FROM event WHERE
								`tba_key` = :key;";
		$statement = $db->prepare($sql);
		$statement->bindValue(":key",   $event);
		$statement->execute();
		$event_id = $statement->fetchColumn();
	print_r ($matches);

	foreach ($matches as $m){


		//set is like QF 1 match is the match number so 2nd QF of alliance one would be match 2 set 1
		$level = $m['comp_level'];
		$match = $m['match_number'];
		$set = $m['set_number'];
		$time = $m['time'];
		$t = array();
		$t['b1'] = substr($m['alliances']['blue']['teams'][0],3);
		$t['b2'] = substr($m['alliances']['blue']['teams'][1],3);
		$t['b3'] = substr($m['alliances']['blue']['teams'][2],3);
		$t['r1'] = substr($m['alliances']['red']['teams'][0],3);
		$t['r2'] = substr($m['alliances']['red']['teams'][1],3);
		$t['r3'] = substr($m['alliances']['red']['teams'][2],3);

		switch ($level) {
			case "qm":
					$leveldb = 2;
					break;
			case "qf":
					$leveldb = 3;
					break;
			case "sf":
					$leveldb = 4;
					break;
			case "f":
					$leveldb = 5;
					break;
			default:
					$leveldb = 1;
					break;
		}

		sched_addmatch($db,$m,$event_id);
		
		//Now insert teams into matchteams
		$sql = "SELECT `id` FROM matches WHERE
								`event_id` = :eid AND
								`level` = :mt AND
								`match_num` = :mn AND
								`set_num` = :ms;";
		$statement = $db->prepare($sql);
		$statement->bindValue(":eid",   $event_id);
		$statement->bindValue(":mt",    $leveldb);
		$statement->bindValue(":mn",    $match);
		$statement->bindValue(":ms",    $set);
		$statement->execute();
		$matchid = $statement->fetchColumn();

		echo $matchid;
		//1 - red1
		//2 - red2
		//3 - red3
		//4 - blue1
		//5 - blue2
		//6 - blue3
		sched_addteamtomatch($db,$matchid,1,$t['r1']);
		sched_addteamtomatch($db,$matchid,2,$t['r2']);
		sched_addteamtomatch($db,$matchid,3,$t['r3']);
		sched_addteamtomatch($db,$matchid,4,$t['b1']);
		sched_addteamtomatch($db,$matchid,5,$t['b2']);
		sched_addteamtomatch($db,$matchid,6,$t['b3']);
	}
}
//$ev_current = "2020week0";
updatematchschedule($db, $tbaRequest, $ev_current);

?>
