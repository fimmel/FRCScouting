<?php
function teamname($team) //Get team name from Team Number
{
    global $db;
    $sql = 'SELECT * FROM `team` WHERE `id` = :team;';
    $statement = $db->prepare($sql);
    $statement->bindValue(":team", $team);
    $statement->execute();
    $result = $statement->fetchAll();
    $pre = '';
    foreach ($result as $row) {
        $pre = $row;
    }
    return $pre['name'];
}
function submission($mrid) //Scouted Data Lookup from Bot Match ID
{
    global $db;
    $sql = "SELECT * FROM `2020_Submission` WHERE `BM_ID` = :bmid;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":bmid", $mrid);
    $statement->execute();
    return $statement->fetchAll();
}
function matchstats($subid)//Match Details from Submission ID
{
    global $db;
    $sql = "SELECT * FROM `2020_Match` WHERE `2020_Match`.`Sub` = :sub;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":sub", $subid);
    $statement->execute();
    return $statement->fetchAll();
}
function shotstats($subid)//Shot / Ball Details
{
    global $db;
    $sql = "SELECT * FROM `2020_Shots` WHERE `2020_Shots`.`Sub` = :sub;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":sub", $subid);
    $statement->execute();
    return $statement->fetchAll();
}
function scoutname($sid)
{//Lookups Scout Name
    global $db;
    $sql = "SELECT * FROM `scout` WHERE `scout`.`internalid` = :sid;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":sid", $sid);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $pre = $row;
    }
    return $pre['name'];
}
