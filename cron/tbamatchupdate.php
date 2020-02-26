<?php
//This updates data from TBA for scouting (climbs and start pos)
include('/var/www/html/prod/tba.php');
include('/var/www/html/prod/backend/db.php');

//dblog($db, "TBAMAtchUpdate.php", "Updating Match Data", "Beginning of update");
//2019mawne
//$matches1 = tba2array($tbaRequest->getEventMatches(['event_key' => "2019marea"]));

//print_r($matches1);
//$ev_current = "2020week0";

$matchdata = tba2array($tbaRequest->getEventMatches(['event_key' => $ev_current]));

//print_r($matchdata);


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

	//print_r($match);
	
	/*
	Array
        (
            [comp_level] => f
            [match_number] => 1
            [videos] => Array
                (
                    [0] => Array
                        (
                            [type] => youtube
                            [key] => NvOWhyGp9pA
                        )

                )

            [time_string] => 
            [set_number] => 1
            [key] => 2020week0_f1m1
            [time] => 1581794460
            [score_breakdown] => Array
                (
                    [blue] => Array
                        (
                            [teleopPoints] => 59
                            [autoPoints] => 29
                            [autoCellsOuter] => 2
                            [stage3TargetColor] => Unknown
                            [controlPanelPoints] => 0
                            [foulCount] => 0
                            [teleopCellsOuter] => 13
                            [foulPoints] => 24
                            [techFoulCount] => 1
                            [rp] => 0
                            [adjustPoints] => 0
                            [stage2Activated] => 
                            [tba_numRobotsHanging] => 1
                            [initLineRobot2] => Exited
                            [initLineRobot3] => Exited
                            [autoCellsBottom] => 0
                            [initLineRobot1] => Exited
                            [teleopCellsBottom] => 0
                            [stage3Activated] => 
                            [shieldEnergizedRankingPoint] => 
                            [shieldOperationalRankingPoint] => 
                            [endgameRungIsLevel] => NotLevel
                            [endgameRobot1] => Park
                            [autoInitLinePoints] => 15
                            [endgameRobot3] => Hang
                            [totalPoints] => 112
                            [teleopCellPoints] => 29
                            [tba_shieldEnergizedRankingPointFromFoul] => 
                            [teleopCellsInner] => 1
                            [endgameRobot2] => None
                            [endgamePoints] => 30
                            [stage1Activated] => 1
                            [autoCellsInner] => 1
                            [autoCellPoints] => 14
                        )

                    [red] => Array
                        (
                            [teleopPoints] => 54
                            [autoPoints] => 10
                            [autoCellsOuter] => 0
                            [stage3TargetColor] => Unknown
                            [controlPanelPoints] => 0
                            [foulCount] => 3
                            [teleopCellsOuter] => 2
                            [foulPoints] => 15
                            [techFoulCount] => 1
                            [rp] => 0
                            [adjustPoints] => 0
                            [stage2Activated] => 
                            [tba_numRobotsHanging] => 1
                            [initLineRobot2] => Exited
                            [initLineRobot3] => None
                            [autoCellsBottom] => 0
                            [initLineRobot1] => Exited
                            [teleopCellsBottom] => 5
                            [stage3Activated] => 
                            [shieldEnergizedRankingPoint] => 
                            [shieldOperationalRankingPoint] => 
                            [endgameRungIsLevel] => IsLevel
                            [endgameRobot1] => Park
                            [autoInitLinePoints] => 10
                            [endgameRobot3] => None
                            [totalPoints] => 79
                            [teleopCellPoints] => 9
                            [tba_shieldEnergizedRankingPointFromFoul] => 
                            [teleopCellsInner] => 0
                            [endgameRobot2] => Hang
                            [endgamePoints] => 45
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
                                    [0] => frc3117
                                    [1] => frc4041
                                    [2] => frc501
                                )

                            [score] => 112
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
                                    [0] => frc1153
                                    [1] => frc5962
                                    [2] => frc238
                                )

                            [score] => 79
                            [dqs] => Array
                                (
                                )

                        )

                )

            [event_key] => 2020week0
        )
	*/
	matchtodb($db, $mid, $match['key'], $match, $play);
		
    //pos = 1-r1, 2-r2, 3-r3, 4-b1, 5-b2, 6-b3 // TBA uses 0,1,2*** in their robot team id
 /*   updategamedata($db, getbotmatchid($db, $mid, 1), $match['score_breakdown']['red']['preMatchLevelRobot1'], $match['score_breakdown']['red']['habLineRobot1'], $match['score_breakdown']['red']['endgameRobot1'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 2), $match['score_breakdown']['red']['preMatchLevelRobot2'], $match['score_breakdown']['red']['habLineRobot2'], $match['score_breakdown']['red']['endgameRobot2'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 3), $match['score_breakdown']['red']['preMatchLevelRobot3'], $match['score_breakdown']['red']['habLineRobot3'], $match['score_breakdown']['red']['endgameRobot3'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 4), $match['score_breakdown']['blue']['preMatchLevelRobot1'], $match['score_breakdown']['blue']['habLineRobot1'], $match['score_breakdown']['blue']['endgameRobot1'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 5), $match['score_breakdown']['blue']['preMatchLevelRobot2'], $match['score_breakdown']['blue']['habLineRobot2'], $match['score_breakdown']['blue']['endgameRobot2'],$play);
    updategamedata($db, getbotmatchid($db, $mid, 6), $match['score_breakdown']['blue']['preMatchLevelRobot3'], $match['score_breakdown']['blue']['habLineRobot3'], $match['score_breakdown']['blue']['endgameRobot3'],$play);
	*/
    
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
function matchtodb($db, $matchID, $TBAkey, $data, $played)
{
	
	/*
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
    */
	$redindb = 0;
	$sql = "SELECT * FROM 2020_TBA WHERE `match_ID` = :mid AND `color` = :color";
	$statement = $db->prepare($sql);
    $statement->bindValue(":mid", $matchID);
    $statement->bindValue(":color", "red");
	$statement->execute();
	$sqlresult = $statement->fetchAll();
	foreach ($sqlresult as $row){
		$redindb++;
	}
	$blueindb = 0;
	$sql = "SELECT * FROM 2020_TBA WHERE `match_ID` = :mid AND `color` = :color";
	$statement = $db->prepare($sql);
    $statement->bindValue(":mid", $matchID);
    $statement->bindValue(":color", "blue");
	$statement->execute();
	$sqlresult = $statement->fetchAll();
	foreach ($sqlresult as $row){
		$blueindb++;
	}
	
	echo "Match ID: ".$matchID." \n";
	echo "TBA Key: ".$TBAkey." \n";
	echo "Played: ".$played." \n";
	echo "Match ID: ".$matchID." \n";
	echo "Time: ".$data['time']." \n";
	echo "Red Telop Points: ".$data['score_breakdown']['red']['teleopPoints']." \n";
	
	print_r($data);
	
	
	if ($played == 1){
		if($blueindb == 0){
			$color = "blue";
			 $sql = "INSERT INTO `2020_TBA` (
			 	`match_ID`, 
				`TBAkey`, 
				`time`, 
				`color`, 
				`autoPoints`, 
				`autoCellsOuter`, 
				`stage3TargetColor`, 
				`controlPanelPoints`, 
				`foulCount`, 
				`teleopCellsOuter`, 
				`foulPoints`, 
				`techFoulCount`, 
				`rp`, 
				`adjustPoints`, 
				`stage2Activated`, 
				`initLineRobot2`, 
				`initLineRobot3`, 
				`autoCellsBottom`, 
				`initLineRobot1`, 
				`teleopCellsBottom`, 
				`stage3Activated`, 
				`shieldEnergizedRankingPoint`, 
				`shieldOperationalRankingPoint`, 
				`endgameRungIsLevel`, 
				`endgameRobot1`, 
				`autoInitLinePoints`, 
				`endgameRobot3`, 
				`totalPoints`, 
				`teleopCellPoints`, 
				`tba_shieldEnergizedRankingPointFromFoul`, 
				`teleopCellsInner`, 
				`endgameRobot2`, 
				`endgamePoints`, 
				`stage1Activated`, 
				`autoCellsInner`, 
				`autoCellPoints`, 
				`teleopPoints`) 
				VALUES (
				:match_ID, 
				:TBAkey, 
				:time, 
				:color, 
				:autoPoints, 
				:autoCellsOuter, 
				:stage3TargetColor, 
				:controlPanelPoints, 
				:foulCount, 
				:teleopCellsOuter, 
				:foulPoints, 
				:techFoulCount, 
				:rp, 
				:adjustPoints, 
				:stage2Activated, 
				:initLineRobot2, 
				:initLineRobot3, 
				:autoCellsBottom, 
				:initLineRobot1, 
				:teleopCellsBottom, 
				:stage3Activated, 
				:shieldEnergizedRankingPoint, 
				:shieldOperationalRankingPoint, 
				:endgameRungIsLevel, 
				:endgameRobot1, 
				:autoInitLinePoints, 
				:endgameRobot3, 
				:totalPoints, 
				:teleopCellPoints, 
				:tba_shieldEnergizedRankingPointFromFoul, 
				:teleopCellsInner, 
				:endgameRobot2, 
				:endgamePoints, 
				:stage1Activated, 
				:autoCellsInner, 
				:autoCellPoints, 
				:teleopPoints);
                  ";
                $statement = $db->prepare($sql);
			
				$statement->bindValue(":match_ID", $matchID);
				$statement->bindValue(":TBAkey",  $data['key']);
				$statement->bindValue(":time",  $data['time']);
				$statement->bindValue(":color",  $color);
				$statement->bindValue(":autoPoints",  $data['score_breakdown'][$color]['autoPoints']);
				$statement->bindValue(":autoCellsOuter",  $data['score_breakdown'][$color]['autoCellsOuter']);
				$statement->bindValue(":stage3TargetColor",  $data['score_breakdown'][$color]['stage3TargetColor']);
				$statement->bindValue(":controlPanelPoints",  $data['score_breakdown'][$color]['controlPanelPoints']);
				$statement->bindValue(":foulCount",  $data['score_breakdown'][$color]['foulCount']);
				$statement->bindValue(":teleopCellsOuter",  $data['score_breakdown'][$color]['teleopCellsOuter']);
				$statement->bindValue(":foulPoints",  $data['score_breakdown'][$color]['foulPoints']);
				$statement->bindValue(":techFoulCount",  $data['score_breakdown'][$color]['techFoulCount']);
				$statement->bindValue(":rp",  $data['score_breakdown'][$color]['rp']);
				$statement->bindValue(":adjustPoints",  $data['score_breakdown'][$color]['adjustPoints']);
				$statement->bindValue(":stage2Activated",  $data['score_breakdown'][$color]['stage2Activated']);
				$statement->bindValue(":initLineRobot2",  $data['score_breakdown'][$color]['initLineRobot2']);
				$statement->bindValue(":initLineRobot3",  $data['score_breakdown'][$color]['initLineRobot3']);
				$statement->bindValue(":autoCellsBottom",  $data['score_breakdown'][$color]['autoCellsBottom']);
				$statement->bindValue(":initLineRobot1",  $data['score_breakdown'][$color]['initLineRobot1']);
				$statement->bindValue(":teleopCellsBottom",  $data['score_breakdown'][$color]['teleopCellsBottom']);
				$statement->bindValue(":stage3Activated",  $data['score_breakdown'][$color]['stage3Activated']);
				$statement->bindValue(":shieldEnergizedRankingPoint",  $data['score_breakdown'][$color]['shieldEnergizedRankingPoint']);
				$statement->bindValue(":shieldOperationalRankingPoint",  $data['score_breakdown'][$color]['shieldOperationalRankingPoint']);
				$statement->bindValue(":endgameRungIsLevel",  $data['score_breakdown'][$color]['endgameRungIsLevel']);
				$statement->bindValue(":endgameRobot1",  $data['score_breakdown'][$color]['endgameRobot1']);
				$statement->bindValue(":autoInitLinePoints",  $data['score_breakdown'][$color]['autoInitLinePoints']);
				$statement->bindValue(":endgameRobot3",  $data['score_breakdown'][$color]['endgameRobot3']);
				$statement->bindValue(":totalPoints",  $data['score_breakdown'][$color]['totalPoints']);
				$statement->bindValue(":teleopCellPoints",  $data['score_breakdown'][$color]['teleopCellPoints']);
				$statement->bindValue(":tba_shieldEnergizedRankingPointFromFoul",  $data['score_breakdown'][$color]['tba_shieldEnergizedRankingPointFromFoul']);
				$statement->bindValue(":teleopCellsInner",  $data['score_breakdown'][$color]['teleopCellsInner']);
				$statement->bindValue(":endgameRobot2",  $data['score_breakdown'][$color]['endgameRobot2']);
				$statement->bindValue(":endgamePoints",  $data['score_breakdown'][$color]['endgamePoints']);
				$statement->bindValue(":stage1Activated",  $data['score_breakdown'][$color]['stage1Activated']);
				$statement->bindValue(":autoCellsInner",  $data['score_breakdown'][$color]['autoCellsInner']);
				$statement->bindValue(":autoCellPoints",  $data['score_breakdown'][$color]['autoCellPoints']);
				$statement->bindValue(":teleopPoints", $data['score_breakdown'][$color]['teleopPoints']);
                $count = $statement->execute();
		}
		if($redindb == 0){
			$color = "red";
			 $sql = "INSERT INTO `2020_TBA` (
			 	`match_ID`, 
				`TBAkey`, 
				`time`, 
				`color`, 
				`autoPoints`, 
				`autoCellsOuter`, 
				`stage3TargetColor`, 
				`controlPanelPoints`, 
				`foulCount`, 
				`teleopCellsOuter`, 
				`foulPoints`, 
				`techFoulCount`, 
				`rp`, 
				`adjustPoints`, 
				`stage2Activated`, 
				`initLineRobot2`, 
				`initLineRobot3`, 
				`autoCellsBottom`, 
				`initLineRobot1`, 
				`teleopCellsBottom`, 
				`stage3Activated`, 
				`shieldEnergizedRankingPoint`, 
				`shieldOperationalRankingPoint`, 
				`endgameRungIsLevel`, 
				`endgameRobot1`, 
				`autoInitLinePoints`, 
				`endgameRobot3`, 
				`totalPoints`, 
				`teleopCellPoints`, 
				`tba_shieldEnergizedRankingPointFromFoul`, 
				`teleopCellsInner`, 
				`endgameRobot2`, 
				`endgamePoints`, 
				`stage1Activated`, 
				`autoCellsInner`, 
				`autoCellPoints`, 
				`teleopPoints`) 
				VALUES (
				:match_ID, 
				:TBAkey, 
				:time, 
				:color, 
				:autoPoints, 
				:autoCellsOuter, 
				:stage3TargetColor, 
				:controlPanelPoints, 
				:foulCount, 
				:teleopCellsOuter, 
				:foulPoints, 
				:techFoulCount, 
				:rp, 
				:adjustPoints, 
				:stage2Activated, 
				:initLineRobot2, 
				:initLineRobot3, 
				:autoCellsBottom, 
				:initLineRobot1, 
				:teleopCellsBottom, 
				:stage3Activated, 
				:shieldEnergizedRankingPoint, 
				:shieldOperationalRankingPoint, 
				:endgameRungIsLevel, 
				:endgameRobot1, 
				:autoInitLinePoints, 
				:endgameRobot3, 
				:totalPoints, 
				:teleopCellPoints, 
				:tba_shieldEnergizedRankingPointFromFoul, 
				:teleopCellsInner, 
				:endgameRobot2, 
				:endgamePoints, 
				:stage1Activated, 
				:autoCellsInner, 
				:autoCellPoints, 
				:teleopPoints);
                  ";
                $statement = $db->prepare($sql);
			
				$statement->bindValue(":match_ID", $matchID);
				$statement->bindValue(":TBAkey",  $data['key']);
				$statement->bindValue(":time",  $data['time']);
				$statement->bindValue(":color",  $color);
				$statement->bindValue(":autoPoints",  $data['score_breakdown'][$color]['autoPoints']);
				$statement->bindValue(":autoCellsOuter",  $data['score_breakdown'][$color]['autoCellsOuter']);
				$statement->bindValue(":stage3TargetColor",  $data['score_breakdown'][$color]['stage3TargetColor']);
				$statement->bindValue(":controlPanelPoints",  $data['score_breakdown'][$color]['controlPanelPoints']);
				$statement->bindValue(":foulCount",  $data['score_breakdown'][$color]['foulCount']);
				$statement->bindValue(":teleopCellsOuter",  $data['score_breakdown'][$color]['teleopCellsOuter']);
				$statement->bindValue(":foulPoints",  $data['score_breakdown'][$color]['foulPoints']);
				$statement->bindValue(":techFoulCount",  $data['score_breakdown'][$color]['techFoulCount']);
				$statement->bindValue(":rp",  $data['score_breakdown'][$color]['rp']);
				$statement->bindValue(":adjustPoints",  $data['score_breakdown'][$color]['adjustPoints']);
				$statement->bindValue(":stage2Activated",  $data['score_breakdown'][$color]['stage2Activated']);
				$statement->bindValue(":initLineRobot2",  $data['score_breakdown'][$color]['initLineRobot2']);
				$statement->bindValue(":initLineRobot3",  $data['score_breakdown'][$color]['initLineRobot3']);
				$statement->bindValue(":autoCellsBottom",  $data['score_breakdown'][$color]['autoCellsBottom']);
				$statement->bindValue(":initLineRobot1",  $data['score_breakdown'][$color]['initLineRobot1']);
				$statement->bindValue(":teleopCellsBottom",  $data['score_breakdown'][$color]['teleopCellsBottom']);
				$statement->bindValue(":stage3Activated",  $data['score_breakdown'][$color]['stage3Activated']);
				$statement->bindValue(":shieldEnergizedRankingPoint",  $data['score_breakdown'][$color]['shieldEnergizedRankingPoint']);
				$statement->bindValue(":shieldOperationalRankingPoint",  $data['score_breakdown'][$color]['shieldOperationalRankingPoint']);
				$statement->bindValue(":endgameRungIsLevel",  $data['score_breakdown'][$color]['endgameRungIsLevel']);
				$statement->bindValue(":endgameRobot1",  $data['score_breakdown'][$color]['endgameRobot1']);
				$statement->bindValue(":autoInitLinePoints",  $data['score_breakdown'][$color]['autoInitLinePoints']);
				$statement->bindValue(":endgameRobot3",  $data['score_breakdown'][$color]['endgameRobot3']);
				$statement->bindValue(":totalPoints",  $data['score_breakdown'][$color]['totalPoints']);
				$statement->bindValue(":teleopCellPoints",  $data['score_breakdown'][$color]['teleopCellPoints']);
				$statement->bindValue(":tba_shieldEnergizedRankingPointFromFoul",  $data['score_breakdown'][$color]['tba_shieldEnergizedRankingPointFromFoul']);
				$statement->bindValue(":teleopCellsInner",  $data['score_breakdown'][$color]['teleopCellsInner']);
				$statement->bindValue(":endgameRobot2",  $data['score_breakdown'][$color]['endgameRobot2']);
				$statement->bindValue(":endgamePoints",  $data['score_breakdown'][$color]['endgamePoints']);
				$statement->bindValue(":stage1Activated",  $data['score_breakdown'][$color]['stage1Activated']);
				$statement->bindValue(":autoCellsInner",  $data['score_breakdown'][$color]['autoCellsInner']);
				$statement->bindValue(":autoCellPoints",  $data['score_breakdown'][$color]['autoCellPoints']);
				$statement->bindValue(":teleopPoints", $data['score_breakdown'][$color]['teleopPoints']);
                $count = $statement->execute();
		}
	//	INSERT INTO `2020_TBA` (`ID`, `match_ID`, `TBAkey`, `time`, `color`, `autoPoints`, `autoCellsOuter`, `stage3TargetColor`, `controlPanelPoints`, `foulCount`, `teleopCellsOuter`, `foulPoints`, `techFoulCount`, `rp`, `adjustPoints`, `stage2Activated`, `initLineRobot2`, `initLineRobot3`, `autoCellsBottom`, `initLineRobot1`, `teleopCellsBottom`, `stage3Activated`, `shieldEnergizedRankingPoint`, `shieldOperationalRankingPoint`, `endgameRungIsLevel`, `endgameRobot1`, `autoInitLinePoints`, `endgameRobot3`, `totalPoints`, `teleopCellPoints`, `tba_shieldEnergizedRankingPointFromFoul`, `teleopCellsInner`, `endgameRobot2`, `endgamePoints`, `stage1Activated`, `autoCellsInner`, `autoCellPoints`, `teleopPoints`) VALUES ('', '1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
	}
	
 //ob_flush();
 //       flush();  
}


//TODO - Add rest of scored data



echo "Ran at: ".date("M,d,Y h:i:s A") . "\n"; 
?>