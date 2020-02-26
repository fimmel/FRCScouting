<?php
include ('backend/TBARequest.php');

function objectToArray($d) {
    if (is_object($d)) { $d = get_object_vars($d);}
    if (is_array($d)) {return array_map(__FUNCTION__, $d);}
    else {return $d;}
}

$tbaRequest = new tbaAPI\TBARequest("frc2370", "2370scout", 1.0);

function tba2array($json){
$tempobjectt = json_decode($json);
$temparray=objectToArray($tempobjectt);

return $temparray;
}

//$TBA_WPI2018_TeamList = tba2array($tbaRequest->getEventTeams(['event_key' => '2018mawor']));
//$TBA_LEW2018_TeamList = tba2array($tbaRequest->getEventTeams(['event_key' => '2018melew']));
//$TBA_NECMP2018_TeamList = tba2array($tbaRequest->getEventTeams(['event_key' => '2018necmp']));








?>