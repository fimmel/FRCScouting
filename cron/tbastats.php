<?php
//This updates data from TBA for scouting (climbs and start pos)
include('/var/www/html/prod/tba.php');
include('/var/www/html/prod/backend/db.php');

//dblog($db, "TBAMAtchUpdate.php", "Updating Match Data", "Beginning of update");
//2019mawne
//$matches1 = tba2array($tbaRequest->getEventMatches(['event_key' => "2019marea"]));

//print_r($matches1);
$ev_current = "2020week0";

$matchdata = tba2array($tbaRequest->getEventMatches(['event_key' => $ev_current]));

//print_r($matchdata);
/*
[0] => Array
        (
            [comp_level] => qm
            [match_number] => 1
            [videos] => Array
                (
                )

            [time_string] => 
            [set_number] => 1
            [key] => 2020week0_qm1
            [time] => 1581774300
            [score_breakdown] => Array
                (
                    [blue] => Array
                        (
                            [teleopPoints] => 7
                            [autoPoints] => 5
                            [autoCellsOuter] => 0
                            [stage3TargetColor] => Unknown
                            [controlPanelPoints] => 0
                            [foulCount] => 0
                            [teleopCellsOuter] => 0
                            [foulPoints] => 0
                            [techFoulCount] => 0
                            [rp] => 2
                            [adjustPoints] => 0
                            [stage2Activated] => 
                            [initLineRobot2] => Exited
                            [initLineRobot3] => None
                            [autoCellsBottom] => 0
                            [initLineRobot1] => None
                            [teleopCellsBottom] => 7
                            [stage3Activated] => 
                            [shieldEnergizedRankingPoint] => 
                            [shieldOperationalRankingPoint] => 
                            [endgameRungIsLevel] => IsLevel
                            [endgameRobot1] => None
                            [autoInitLinePoints] => 5
                            [endgameRobot3] => None
                            [totalPoints] => 12
                            [teleopCellPoints] => 7
                            [tba_shieldEnergizedRankingPointFromFoul] => 
                            [teleopCellsInner] => 0
                            [endgameRobot2] => None
                            [endgamePoints] => 0
                            [stage1Activated] => 
                            [autoCellsInner] => 0
                            [autoCellPoints] => 0
                        )

                    [red] => Array
                        (
                            [teleopPoints] => 0
                            [autoPoints] => 5
                            [autoCellsOuter] => 0
                            [stage3TargetColor] => Unknown
                            [controlPanelPoints] => 0
                            [foulCount] => 0
                            [teleopCellsOuter] => 0
                            [foulPoints] => 0
                            [techFoulCount] => 0
                            [rp] => 0
                            [adjustPoints] => 0
                            [stage2Activated] => 
                            [initLineRobot2] => Exited
                            [initLineRobot3] => None
                            [autoCellsBottom] => 0
                            [initLineRobot1] => None
                            [teleopCellsBottom] => 0
                            [stage3Activated] => 
                            [shieldEnergizedRankingPoint] => 
                            [shieldOperationalRankingPoint] => 
                            [endgameRungIsLevel] => IsLevel
                            [endgameRobot1] => None
                            [autoInitLinePoints] => 5
                            [endgameRobot3] => None
                            [totalPoints] => 5
                            [teleopCellPoints] => 0
                            [tba_shieldEnergizedRankingPointFromFoul] => 
                            [teleopCellsInner] => 0
                            [endgameRobot2] => None
                            [endgamePoints] => 0
                            [stage1Activated] => 
                            [autoCellsInner] => 0
                            [autoCellPoints] => 0
                        )

                )

            [alliances] => Array
                (
                    [blue] => Array
                        (
                            [surrogates] => Array
                                (
                                )

                            [teams] => Array
                                (
                                    [0] => frc1721
                                    [1] => frc296
                                    [2] => frc151
                                )

                            [score] => 12
                            [dqs] => Array
                                (
                                )

                        )

                    [red] => Array
                        (
                            [surrogates] => Array
                                (
                                )

                            [teams] => Array
                                (
                                    [0] => frc5813
                                    [1] => frc2342
                                    [2] => frc3323
                                )

                            [score] => 5
                            [dqs] => Array
                                (
                                )

                        )

                )

            [event_key] => 2020week0
        )
*/
$m_totalnumber = 0;
$m_lowscored_min = 100;
$m_lowscored_total = 0;
$m_lowscored_max = 0;

$m_outscored_min = 100;
$m_outscored_total = 0;
$m_outscored_max = 0;

$m_inscored_min = 100;
$m_inscored_total = 0;
$m_inscored_max = 0;


$m_a_lowscored_min = 100;
$m_a_lowscored_total = 0;
$m_a_lowscored_max = 0;

$m_a_outscored_min = 100;
$m_a_outscored_total = 0;
$m_a_outscored_max = 0;

$m_a_inscored_min = 100;
$m_a_inscored_total = 0;
$m_a_inscored_max = 0;

foreach ($matchdata as $match) {

$a_b = $match['score_breakdown']['blue']['autoCellsBottom'];
$a_o = $match['score_breakdown']['blue']['autoCellsOuter'];
$a_i = $match['score_breakdown']['blue']['autoCellsInner'];
	
$t_b = $match['score_breakdown']['blue']['teleopCellsBottom'];
$t_o = $match['score_breakdown']['blue']['teleopCellsOuter'];
$t_i = $match['score_breakdown']['blue']['teleopCellsInner'];
	
	if ($m_a_lowscored_min >= ($a_b + $t_b)){
		$m_a_lowscored_min = ($a_b + $t_b);
	}
	if ($m_a_outscored_min >= ($a_o + $t_o)){
		$m_a_outscored_min = ($a_o + $t_o);
	}
	if ($m_a_inscored_min >= ($a_i + $t_i)){
		$m_a_inscored_min = ($a_i + $t_i);
	}
	
	if ($m_a_lowscored_max <= ($a_b + $t_b)){
		$m_a_lowscored_max = ($a_b + $t_b);
	}
	if ($m_a_outscored_max <= ($a_o + $t_o)){
		$m_a_outscored_max = ($a_o + $t_o);
	}
	if ($m_a_inscored_max <= ($a_i + $t_i)){
		$m_a_inscored_max = ($a_i + $t_i);
	}
	
	$m_a_lowscored_total = $m_a_lowscored_total + $a_b + $t_b;
	$m_a_outscored_total = $m_a_outscored_total + $a_o + $t_o;
	$m_a_inscored_total = $m_a_inscored_total + $a_i + $t_i;
	
	
	
	
	
$a_b = $match['score_breakdown']['red']['autoCellsBottom'];
$a_o = $match['score_breakdown']['red']['autoCellsOuter'];
$a_i = $match['score_breakdown']['red']['autoCellsInner'];
	
$t_b = $match['score_breakdown']['red']['teleopCellsBottom'];
$t_o = $match['score_breakdown']['red']['teleopCellsOuter'];
$t_i = $match['score_breakdown']['red']['teleopCellsInner'];

	
	
		if ($m_a_lowscored_min >= ($a_b + $t_b)){
		$m_a_lowscored_min = ($a_b + $t_b);
	}
	if ($m_a_outscored_min >= ($a_o + $t_o)){
		$m_a_outscored_min = ($a_o + $t_o);
	}
	if ($m_a_inscored_min >= ($a_i + $t_i)){
		$m_a_inscored_min = ($a_i + $t_i);
	}
	
	if ($m_a_lowscored_max <= ($a_b + $t_b)){
		$m_a_lowscored_max = ($a_b + $t_b);
	}
	if ($m_a_outscored_max <= ($a_o + $t_o)){
		$m_a_outscored_max = ($a_o + $t_o);
	}
	if ($m_a_inscored_max <= ($a_i + $t_i)){
		$m_a_inscored_max = ($a_i + $t_i);
	}
	
	$m_a_lowscored_total = $m_a_lowscored_total + $a_b + $t_b;
	$m_a_outscored_total = $m_a_outscored_total + $a_o + $t_o;
	$m_a_inscored_total = $m_a_inscored_total + $a_i + $t_i;
	$m_totalnumber = $m_totalnumber + 2;
/*	
	[score_breakdown] => Array
                (
                    [blue] => Array
                        (
                        [teleop
	
autoPoints
	autoCellsOuter
	teleopCellsOuter
	autoCellsBottom
	teleopCellsBottom
	autoCellsInner
	teleopCellsInner
	
	
[teleopPoints] => 7
[autoPoints] => 5
[autoCellsOuter] => 0
[stage3TargetColor] => Unknown
[controlPanelPoints] => 0
[foulCount] => 0
[teleopCellsOuter] => 0
[foulPoints] => 0
[techFoulCount] => 0
[rp] => 2
[adjustPoints] => 0
[stage2Activated] => 
[initLineRobot2] => Exited
[initLineRobot3] => None
[autoCellsBottom] => 0
[initLineRobot1] => None
[teleopCellsBottom] => 7
[stage3Activated] => 
[shieldEnergizedRankingPoint] => 
[shieldOperationalRankingPoint] => 
[endgameRungIsLevel] => IsLevel
[endgameRobot1] => None
[autoInitLinePoints] => 5
[endgameRobot3] => None
[totalPoints] => 12
[teleopCellPoints] => 7
[tba_shieldEnergizedRankingPointFromFoul] => 
[teleopCellsInner] => 0
[endgameRobot2] => None
[endgamePoints] => 0
[stage1Activated] => 
[autoCellsInner] => 0
[autoCellPoints] => 0
*/
	
}

echo "Low Min ".$m_a_lowscored_min."\n";
echo "Low Max ".$m_a_lowscored_max."\n";
echo "Low Average ".$m_a_lowscored_total/$m_totalnumber."\n";
echo "Low Total ".$m_a_lowscored_total."\n";

echo "Out Min ".$m_a_outscored_min."\n";
echo "Out Max ".$m_a_outscored_max."\n";
echo "Out Average ".$m_a_outscored_total/$m_totalnumber."\n";
echo "Out Total ".$m_a_outscored_total."\n";

echo "In Min ".$m_a_inscored_min."\n";
echo "In Max ".$m_a_inscored_max."\n";
echo "In Average ".$m_a_inscored_total/$m_totalnumber."\n";
echo "In Total ".$m_a_inscored_total."\n";

/*
//find match robot id for each TBA match
foreach ($matchdata as $match) {
	
    $level = $match['comp_level'];
    
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
    } //$level
	
	//echo "<hr /><b> Match Start </b> \n";
	//echo "Level: ".$level."\n ";
    //echo "Match: ".$match['match_number']."\n ";
    //echo "Set: ".$match['set_number']."\n ";
    //echo "TBA Key: ".$match['key']."\n";
    //print_r($match);
    
    $sql       = "SELECT `id` FROM matches WHERE
                            `event_id` = :eid AND
                            `level` = :mt AND
                            `match_num` = :mn AND
                            `set_num` = :ms;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":eid", $event_id);
    $statement->bindValue(":mt", $leveldb);
    $statement->bindValue(":mn", $match['match_number']);
    $statement->bindValue(":ms", $match['set_number']);
    $statement->execute();
    $matchid = $statement->fetchAll();
    
    //echo $number_of_rows;
    //echo "<br />\n";
    foreach ($matchid as $id) {
        $mid = $id['id'];
    } //$matchid as $id
    //echo "Match ID: ".$mid;
	
	$logdata = "Match Key: ".$match['key']." Match ID: ".$mid;
    dblog($db, "TBAMAtchUpdate.php", "Match Loop", $logdata);
    
	
	if (empty($match['score_breakdown'])){
	$play = 0;
}
else{
	$play = 1;
}
	//$play = $match['score_breakdown']['red']['preMatchLevelRobot1']
	
	
    // Match ID = $mid

    //pos = 1-r1, 2-r2, 3-r3, 4-b1, 5-b2, 6-b3 // TBA uses 0,1,2*** in their robot team id
    updategamedata($db, getbotmatchid($db, $mid, 1), $match['score_breakdown']['red']['preMatchLevelRobot1'], $match['score_breakdown']['red']['habLineRobot1'], $match['score_breakdown']['red']['endgameRobot1'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 2), $match['score_breakdown']['red']['preMatchLevelRobot2'], $match['score_breakdown']['red']['habLineRobot2'], $match['score_breakdown']['red']['endgameRobot2'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 3), $match['score_breakdown']['red']['preMatchLevelRobot3'], $match['score_breakdown']['red']['habLineRobot3'], $match['score_breakdown']['red']['endgameRobot3'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 4), $match['score_breakdown']['blue']['preMatchLevelRobot1'], $match['score_breakdown']['blue']['habLineRobot1'], $match['score_breakdown']['blue']['endgameRobot1'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 5), $match['score_breakdown']['blue']['preMatchLevelRobot2'], $match['score_breakdown']['blue']['habLineRobot2'], $match['score_breakdown']['blue']['endgameRobot2'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 6), $match['score_breakdown']['blue']['preMatchLevelRobot3'], $match['score_breakdown']['blue']['habLineRobot3'], $match['score_breakdown']['blue']['endgameRobot3'],$play);
    
} //$matchdata as $match

function getbotmatchid($db, $matchid, $pos)
{
    $sql       = "SELECT * FROM match_robot WHERE
                            `match_id` = :match AND
                            `position` = :pos;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":match", $matchid);
    $statement->bindValue(":pos", $pos);
    $statement->execute();
    $bmid = $statement->fetchAll();
    foreach ($bmid as $id) {
        $bmid_1 = $id['id'];
		//echo "<br />\n";
        //echo "Team: ".$id['team_id']." ";
        //echo "Bot Match ID: ".$bmid_1;
		//echo "<br />\n";
		$logdata = "Team: ".$id['team_id']." Bot Match ID: ".$id['id'];
		dblog($db, "TBAMAtchUpdate.php", "Bot Match ID Lookup", $logdata);
    } //$bmid as $id
    return $bmid_1;
    //print_r($bmid);
    
}
function updategamedata($db, $bmid, $start, $cross, $climb, $played)
{
    switch ($start) {
        case "HabLevel1":
            $start_pos = 1;
            break;
        case "HabLevel2":
            $start_pos = 2;
            break;
        case "HabLevel3":
            $start_pos = 3;
            break;
        default:
            $start_pos = 0;
            break;
    } //$start
    switch ($cross) {
        case "CrossedHabLineInSandstorm":
            $hab_line = 1;
            break;
        case "CrossedHabLineInTeleop":
            $hab_line = 2;
            break;
        default:
            $hab_line = 0;
            break;
    } //$cross
    
    switch ($climb) {
        case "HabLevel1":
            $end_pos = 1;
            break;
        case "HabLevel2":
            $end_pos = 2;
            break;
        case "HabLevel3":
            $end_pos = 3;
            break;
        default:
            $end_pos = 0;
            break;
    } //$climb
    
    $logdata = "Start POS: ".$start." Auto POS: ".$cross." End POS: ".$climb;
    dblog($db, "TBAMAtchUpdate.php", "Update Data", $logdata);
	//echo "Start POS: ".$start."<br />Auto POS: ".$cross."<br />End POS: ".$climb;
	//echo "<br />";
    $sqli       = "UPDATE `2019_gamepieces` SET 
	
                `tbaplay` = :play, 
                `hab_line` = :hab, 
                `end_pos` = :end, 
                `start_pos` = :start  
                WHERE `2019_gamepieces`.`match_robot_id` = :bmid;";
    $statementi = $db->prepare($sqli);
    $statementi->bindValue(":play", $played);
    $statementi->bindValue(":hab", $hab_line);
    $statementi->bindValue(":end", $end_pos);
    $statementi->bindValue(":start", $start_pos);
    $statementi->bindValue(":bmid", $bmid);
    $count = $statementi->execute();
    
 //ob_flush();
 //       flush();  
}
*/

//TODO - Add rest of scored data



echo "Ran at: ".date("M,d,Y h:i:s A") . "\n"; 
?>