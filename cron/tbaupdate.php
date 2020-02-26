<?php
include('../prod/tba.php');
include('../prod/backend/db.php');

function importteams($conn, $tbaRequest, $page){
    $teams = tba2array($tbaRequest->getTeams(['page_num' => $page])); //0-14 to get all
    //print_r($teams);
    foreach($teams as $team){
        echo $team['team_number'];
        echo ' - ';
        echo $team['nickname'];
        echo "\n";
        if($team['nickname']){
            $sql = "INSERT INTO `team` (`id`, `name`) VALUES (:number_v, :nickname_v); ";
            $statement = $conn->prepare($sql);
            $statement->bindValue(":number_v",   $team['team_number']);
            $statement->bindValue(":nickname_v", $team['nickname']);
            $count = $statement->execute();
        }
    }
}

function importdistrictevents($conn, $tbaRequest, $district, $year){
    $nedistrict = tba2array($tbaRequest->getDistrictEvents(['district_short' => $district,'year' => $year]));
    foreach($nedistrict as $event){
        $sql = 'INSERT INTO `event` (`tba_key`, `name`) VALUES (:key_value, :name_value);';
        $statement = $conn->prepare($sql);
        $statement->bindValue(":key_value",             $event['key']);
        $statement->bindValue(":name_value",            $event['short_name']);
        $count = $statement->execute();
    }
}

function importteamsatevents($conn, $tbaRequest){
    $sql = "SELECT * FROM event";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $sqlresult = $statement->fetchAll();
    foreach ($sqlresult as $event){
        echo $event['tba_key'];
        $teams = tba2array($tbaRequest->getEventTeams(['event_key' => $event['tba_key']])); //0-14 to get all
        foreach($teams as $team){
            $sql = "INSERT INTO `event_teams` (`event_id`, `team_id`) VALUES (:event_v, :team_v);";
            echo $team['team_number'];
            $statement = $conn->prepare($sql);
            $statement->bindValue(":event_v",       $event['id']);
            $statement->bindValue(":team_v",        $team['team_number']);
            $count = $statement->execute();
        }
    }
}
//Import Teams to Team List
/*
for($i=0;$i<20;$i++){
	importteams($db, $tbaRequest, $i);
	
}
*/

//importdistrictevents($db, $tbaRequest, 'ne', '2020');
//importteamsatevents($db, $tbaRequest);



?>
Cron Runs