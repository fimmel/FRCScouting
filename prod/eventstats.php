<!doctype html>

<?php
$team = $_GET['team'];


include('backend/db.php');
include('backend/2020botclass.php');


$event = new frcevent($db, $ev_current);
//print_r($week0->getMatchList());

$teamlist = $event->getTeamList();
//print_r($teamlist);

//print_r($teamsched);
function teamname($db, $team)
{//Scouted Data Lookup
    $sql = "SELECT * FROM `team` WHERE `id` = :team;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":team", $team);
    $statement->execute();
    $result = $statement->fetchAll();
    $pre = "";
    foreach ($result as $row) {
        $pre = $row;
    }
    return $pre['name'];

}

function submission($db, $mrid)
{//Scouted Data Lookup
    $sql = "SELECT * FROM `2020_Submission` WHERE `BM_ID` = :bmid;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":bmid", $mrid);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $pre = $row;
    }
    return $result;

}

function matchstats($db, $subid)
{//Match Details
    $sql = "SELECT * FROM `2020_Match` WHERE `2020_Match`.`Sub` = :sub;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":sub", $subid);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $pre = $row;
    }
    return $result;
}

function shotstats($db, $subid)
{//Shot / Ball Details
    $sql = "SELECT * FROM `2020_Shots` WHERE `2020_Shots`.`Sub` = :sub;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":sub", $subid);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $pre = $row;
    }
    return $result;
}

function scoutname($db, $sid)
{//Lookups Scout Name

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

function gradient($level, $colormultiplier = 10)
{//Hue to RGB for Color Coding

    // 0 = red
    //133 = green
    global $colormultiplier;
    $hue = ((int)$level * $colormultiplier);
    $sat = 50;
    $lum = 40;

    //$hue /= 360;
    //$sat /= 100;
    //$lum /= 100;

    $result = convertHSL($hue, $sat, $lum);
    /*var_dump($result); echo '<br>';
    printf("rgb = %d,%d,%d<br>", $result['r'], $result['g'], $result['b']);
	switch($level){
		case "0":
			$c="#333333";
			break;
		case "1":
			$c="#890000";
			break;
		case "2":
			$c="#843e00";
			break;
		case "3":
			$c="#7e7700";
			break;
		case "4":
			$c="#467900";
			break;
		case "5":
			$c="#0d7300";
			break;
		default:
			$c="#0d9900";
			break;
	
	}*/
    return $result;
}

function graphic_set($miss, $low, $out, $in, $points, $period = "")
{//Ball Score graphic

    $svgfill_miss = gradient($miss);
    $svgfill_low = gradient($low);
    $svgfill_out = gradient($out);
    $svgfill_in = gradient($in);
    $gradient = "Default";
    if ($period == "A") {
        $gradient = "Auto";
    }
    if ($period == "T") {
        $gradient = "Teleop";
    }
    if ($period == "G") {
        $gradient = "Game";
    }

    //$svgfill_out = "#01ac00";
    print '<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="50px" height="85px" >
<style type="text/css">
	.svg_miss{stroke:#000000;stroke-miterlimit:10;}
	.svg_out{stroke:#222222;stroke-miterlimit:10;}
	.svg_in{stroke:#222222;stroke-miterlimit:10;}
	.svg_low{stroke:#222222;stroke-miterlimit:10;}
	text{
	font-size:12px;
	font-weight:bold;
	}
</style>

  <defs>
    <linearGradient id="Auto" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:rgb(86,19,145);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(86,19,145);stop-opacity:0.8" />
    </linearGradient>
    <linearGradient id="Teleop" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:rgb(19,74,145);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(19,74,145);stop-opacity:0.9" />
    </linearGradient>
    <linearGradient id="Game" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:rgb(145,19,109);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(145,19,109);stop-opacity:0.9" />
    </linearGradient>
    <linearGradient id="Default" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:rgb(120,120,120);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(120,120,120);stop-opacity:0.5" />
    </linearGradient>
  </defs>
<rect fill="url(#' . $gradient . ')"x="0.5" y="0.5" class="svg_miss" width="49" height="84"/>
<polygon fill="' . $svgfill_out . '" class="svg_out" points="35.5,5 14.5,5 4,23 14.5,41 35.5,41 46,23 "/>
<circle fill="' . $svgfill_in . '" class="svg_in" cx="25" cy="23" r="9"/>
<rect fill="' . $svgfill_low . '" x="7" y="49" class="svg_low" width="36" height="14"/>
<text text-anchor="start" x="2" y="12" fill="#cc9999">' . $miss . '</text>
<text text-anchor="middle" x="18" y="40" fill="#efefef">' . $out . '</text>
<text text-anchor="middle" x="24" y="28" fill="#efefef">' . $in . '</text>
<text text-anchor="middle" x="25" y="60" fill="#efefef">' . $low . '</text>
<text text-anchor="end" x="48" y="81" fill="#efefef">' . $points . '</text>
<text text-anchor="start" x="2" y="81" fill="#eee">' . $period . '</text>
</svg>';

}

function djbooth($stage)
{//Color Wheel Graphic

    switch ($stage) {
        case "0":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#444444" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#444444" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#444444" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#444444" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#444444" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#444444" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#444444" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#444444" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <path class="st" fill="#444444" d="M68.13,11.01L68.13,11.01l3.62-1.97L60.63,5L57.5,16.79l4.32-2.34l0,0c2.27,4.5,3.56,9.61,3.56,15.04
            c0,5.78-1.46,11.2-4.02,15.9L67.58,49c3.13-5.77,4.92-12.43,4.92-19.52C72.5,22.81,70.92,16.54,68.13,11.01z"/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">0</text>
            </svg>
            ';
            break;
        case "1":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <path class="st" fill="#444444" d="M68.13,11.01L68.13,11.01l3.62-1.97L60.63,5L57.5,16.79l4.32-2.34l0,0c2.27,4.5,3.56,9.61,3.56,15.04
            c0,5.78-1.46,11.2-4.02,15.9L67.58,49c3.13-5.77,4.92-12.43,4.92-19.52C72.5,22.81,70.92,16.54,68.13,11.01z"/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">0</text>
            </svg>
            ';
            break;
        case "2":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <path class="st" fill="#39B54A" d="M68.13,11.01L68.13,11.01l3.62-1.97L60.63,5L57.5,16.79l4.32-2.34l0,0c2.27,4.5,3.56,9.61,3.56,15.04
            c0,5.78-1.46,11.2-4.02,15.9L67.58,49c3.13-5.77,4.92-12.43,4.92-19.52C72.5,22.81,70.92,16.54,68.13,11.01z"/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">10</text>
            </svg>
            ';
            break;
        case "3":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#444444" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#444444" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#444444" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#444444" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#444444" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#444444" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#444444" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#444444" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <polygon class="st" fill="#444444"  points="76.98,26.36 69.64,26.36 69.64,20.47 60.09,30.02 69.64,39.57 69.64,33.67 76.98,33.67 "/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">10</text>
            </svg>
            ';
            break;
        case "4":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <polygon class="st" fill="#444444"  points="76.98,26.36 69.64,26.36 69.64,20.47 60.09,30.02 69.64,39.57 69.64,33.67 76.98,33.67 "/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">10</text>
            </svg>
            ';
            break;
        case "5":
            print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="85px"  xml:space="preserve">
            <style type="text/css">
                .st{stroke:#000000;stroke-miterlimit:10;}
            </style>
            <path class="st" fill="#39B54A" d="M42.06,6.91l-9.54,23.09l23.06-9.55C53.13,14.53,48.43,9.55,42.06,6.91z"/>
            <path class="st" fill="#29ABE2" d="M22.95,6.9l9.57,23.1l9.54-23.09C36.15,4.46,29.32,4.26,22.95,6.9z"/>
            <path class="st" fill="#FFFF00" d="M9.4,20.44l23.07,9.58L22.9,6.92C17,9.36,12.03,14.06,9.4,20.44z"/>
            <path class="st" fill="#ED1C24" d="M9.41,39.57l23.06-9.55L9.4,20.44C6.95,26.35,6.77,33.19,9.41,39.57z"/>
            <path class="st" fill="#39B54A" d="M22.95,53.09l9.54-23.09L9.43,39.56C11.88,45.47,16.58,50.45,22.95,53.09z"/>
            <path class="st" fill="#29ABE2" d="M42.06,53.1l-9.57-23.1l-9.54,23.09C28.86,55.54,35.69,55.74,42.06,53.1z"/>
            <path class="st" fill="#FFFF00" d="M55.61,39.56l-23.07-9.58l9.57,23.1C48.01,50.64,52.98,45.94,55.61,39.56z"/>
            <path class="st" fill="#ED1C24" d="M55.6,20.43l-23.06,9.55l23.07,9.58C58.05,33.65,58.24,26.81,55.6,20.43z"/>
            <polygon class="st" fill="#39B54A"  points="76.98,26.36 69.64,26.36 69.64,20.47 60.09,30.02 69.64,39.57 69.64,33.67 76.98,33.67 "/>
            <text text-anchor="middle" x="32" y="70" fill="#efefef">20 + RP</text>
            </svg>
            ';
            break;

        default:

            break;
    }
}

function coathanger($level, $bot1, $bot2, $bot3, $mypos)
{

    //coathanger($match['alliance']['endgameRungIsLevel'],$match['alliance']['endgameRobot1'],$match['alliance']['endgameRobot2'],$match['alliance']['endgameRobot3']);


    $offset = 0;
    $poscolorleft = "#444444";
    $poscolormiddle = "#444444";
    $poscolorright = "#444444";

    switch ($mypos) {
        case 0:
            //leave gray
            break;
        case 1:
            $poscolorleft = "#1ff258";
            break;
        case 2:
            $poscolormiddle = "#1ff258";
            break;
        case 3:
            $poscolorright = "#1ff258";
            break;
    }

    if ($level != "IsLevel") {
        $offset = 10;
    }


    $barbonus = 0;

    print '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"width="100px" height="85px"  xml:space="preserve">
<style type="text/css">
	.st0park{display:none;fill:none;stroke:#888888;stroke-miterlimit:10;}
	.st1park{stroke:#888;stroke-miterlimit:10;}
	.st0hang{fill:none;stroke:#CCCCCC;stroke-miterlimit:10;}
	.st1hang{stroke:#E6E6E6;stroke-miterlimit:10;}
	.bar{stroke:#000000;stroke-miterlimit:10;}
</style>';
    switch ($bot1) {
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="20" y1="34.8" x2="20" y2="55.03"/>';
            print '<rect fill="' . $poscolorleft . '" x="6.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            break;
        case "Hang":
            print '<line class="st0hang" x1="20" y1="' . (18 + $offset) . '.8" x2="20" y2="' . (39 + $offset) . '.03"/>';
            print '<rect fill="' . $poscolorleft . '" x="6.68" y="' . (39 + $offset) . '.03" class="st1hang" width="26.64" height="12.16"/>';
            $barbonus = 1;
            break;
    }
    switch ($bot2) {
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="50" y1="34.8" x2="50" y2="55.03"/>';
            print '<rect fill="' . $poscolormiddle . '" x="36.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            break;
        case "Hang":
            print '<line class="st0hang" x1="50" y1="18.8" x2="50" y2="39.03"/>';
            print '<rect fill="' . $poscolormiddle . '" x="36.68" y="' . (39) . '.03" class="st1hang" width="26.64" height="12.16"/>';
            $barbonus = 1;
            break;
    }
    switch ($bot3) {
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="80" y1="34.8" x2="80" y2="55.03"/>';
            print '<rect fill="' . $poscolorright . '" x="66.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            break;
        case "Hang":
            print '<line class="st0hang" x1="80" y1="' . (18 - $offset) . '" x2="80" y2="' . (39 - $offset) . '.03"/>';
            print '<rect fill="' . $poscolorright . '" x="66.68" y="' . (39 - $offset) . '.03" class="st1hang" width="26.64" height="12.16"/>';
            $barbonus = 1;
            break;
    }
    if ($level == "IsLevel") {
        if ($barbonus == 1) {
            $bar = '<rect fill="#39B54A" x="5" y="15" class="bar" width="90" height="4"/>';
        } else {
            $bar = '<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>';
        }


    } else {
        if ($barbonus == 1) {
            $bar = '<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>';
        } else {
            $bar = '<rect x="5" y="15" fill="#444444" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>';
        }
    }
    print $bar . '</svg> ';


    /* switch($stage){
		case "0"://0 Parked
            print '
	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "1"://1 Parked
            print '
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "2"://2 Parked
            print '
	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "3"://3 Parked
            print '
	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>
</svg> ';
			break;
		case "4"://1 Level - 0 Parked
            print '
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
    
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</svg> ';
			break;
		case "5"://1 Level - 1 Parked
            print '

	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>

	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>

    <rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</svg> ';
			break;
		case "6"://1 Level - 2 Parked
            print '

<g id="Parked">
	<line class="st0" x1="20" y1="34.8" x2="20" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Level">
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	 <rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "7"://2 Level - 0 Parked
            print '


<g id="Level">
	<line class="st0" x1="20" y1="18.8" x2="20" y2="39.03"/>
	<line class="st0" x1="80" y1="18.8" x2="80" y2="39.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "8"://2 Level - 1 Parked
            print '

<g id="Parked">
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Level">
	<line class="st0" x1="20" y1="18.8" x2="20" y2="39.03"/>
	<line class="st0" x1="80" y1="18.8" x2="80" y2="39.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "9"://3 Level
            print '

<g id="Level">
	<line class="st0" x1="20" y1="18.8" x2="20" y2="39.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<line class="st0" x1="80" y1="18.8" x2="80" y2="39.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="39.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect fill="#39B54A" x="5" y="15" class="st10" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "10"://1 Not Level - 0 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "11"://1 Not Level - 1 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Parked">
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "12"://1 Not Level - 2 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Parked">
	<line class="st0" x1="50" y1="34.8" x2="50" y2="55.03"/>
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "13"://2 Not Level - 0 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>

<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "14"://2 Not Level - 1 Parked
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Parked">
	<line class="st0" x1="80" y1="34.8" x2="80" y2="55.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;
		case "15"://3 Not Level
            print '
<g id="Not_Level">
	<line class="st0" x1="20" y1="28.8" x2="20" y2="49.03"/>
	<line class="st0" x1="50" y1="18.8" x2="50" y2="39.03"/>
	<line class="st0" x1="80" y1="8.8" x2="80" y2="29.03"/>
	<rect fill="'.$poscolorright.'" x="66.68" y="29.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolormiddle.'" x="36.68" y="39.03" class="st1" width="26.64" height="12.16"/>
	<rect fill="'.$poscolorleft.'" x="6.68" y="49.03" class="st1" width="26.64" height="12.16"/>
</g>
<g id="Bar">
	<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>
</g>
</svg> ';
			break;

		default:
			
			break;
	}

*/
}

function defencerecieved($level)
{
    print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="20px" height="85px"  xml:space="preserve">
<style type="text/css">
	.defensecolor{fill:#FFBC64;stroke:#F7931E;stroke-miterlimit:10;}
	.defensenocolor{fill:#333333;stroke:#666666;stroke-miterlimit:10;}
	.defensena{fill:#222222;stroke:#333333;stroke-miterlimit:10;}
</style>';
    switch ($level) {
        case "1":
            print '
            <polygon class="defensena" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensena" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensena" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensena" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensena" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "2":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensenocolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "3":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "4":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "5":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
        case "6":
            print '
             <polygon class="defensecolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Rec</text></svg>';
            break;
    }

}

function defencegiven($level)
{
    print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="20px" height="85px"  xml:space="preserve">
<style type="text/css">
	.defensecolor{fill:#FFBC64;stroke:#F7931E;stroke-miterlimit:10;}
	.defensenocolor{fill:#333333;stroke:#666666;stroke-miterlimit:10;}
	.defensena{fill:#222222;stroke:#333333;stroke-miterlimit:10;}
</style>';
    switch ($level) {
        case "1":
            print '
            <polygon class="defensena" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensena" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensena" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensena" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensena" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "2":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensenocolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "3":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensenocolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "4":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensenocolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "5":
            print '
             <polygon class="defensenocolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
        case "6":
            print '
             <polygon class="defensecolor" points="10,10.81 12.11,15.09 16.82,15.77 13.41,19.1 14.22,23.79 10,21.58 5.78,23.79 6.59,19.1 3.18,15.77 7.89,15.09 "/>
            <polygon class="defensecolor" points="10,25.31 12.11,29.58 16.82,30.27 13.41,33.59 14.22,38.29 10,36.07 5.78,38.29 6.59,33.59 3.18,30.27 7.89,29.58 "/>
            <polygon class="defensecolor" points="10,39.8 12.11,44.08 16.82,44.76 13.41,48.09 14.22,52.78 10,50.57 5.78,52.78 6.59,48.09 3.18,44.76 7.89,44.08 "/>
            <polygon class="defensecolor" points="10,54.3 12.11,58.57 16.82,59.26 13.41,62.58 14.22,67.28 10,65.06 5.78,67.28 6.59,62.58 3.18,59.26 7.89,58.57 "/>
            <polygon class="defensecolor" points="10,68.8 12.11,73.07 16.82,73.75 13.41,77.08 14.22,81.78 10,79.56 5.78,81.78 6.59,77.08 3.18,73.75 7.89,73.07 "/>
            <text text-anchor="middle" x="10" y="12" fill="#efefef">Giv</text></svg>';
            break;
    }

}

?>
<?php
$pagetitle = "Event Statistics";
?>
<html>

<head>
    <meta name="google-signin-client_id"
          content="<?php echo $googleDevKey; ?>">
    <script src="includes/libraries-eventstats.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>


    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/fc-3.3.0/fh-3.1.6/datatables.min.css"/>

    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/fc-3.3.0/fh-3.1.6/datatables.min.js"></script>
    <link href="includes/style.css" rel="stylesheet">
    <meta charset="utf-8">
    <title><?php echo $pagetitle; ?> - FRC Scouting</title>
    <style>
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif";
            padding: 0;
            margin: 0;
        }

        body {
            background-color: #252525;
            color: #BCBCBC !important;
        }

        .noshow {
            border: hsla(0, 0%, 100%, 0.00) !important;
            background-color: hsla(0, 0%, 100%, 0.00) !important;
        }

        #topbar {
            width: 100%;
            margin: 0;
            padding: 4px;
            font-size: 20px;
            background-color: #333333;
            color: #F4F4F4;
            border-bottom: 6px solid;
            border-bottom-color: <?php echo ($color == "blue" ? '#4444ff' : '#ff4444'); ?>;
            padding-top: 10px;
            height: 54px;
        }

        .teamnumber {
            font-size: 28px;
            font-weight: bold;
        }

        #ga_pic img {
            width: 48px;
            height: 48px;
            display: block;
            position: absolute;
            right: 0px;
            top: 0px;
        }

        #ga_name {
            width: 174px;
            height: 48px;
            display: block;
            position: absolute;
            right: 48px;
            top: 6px;
        }

        #ga_logout {
            width: 174px;
            height: 48px;
            display: block;
            position: absolute;
            right: 48px;
            top: 25px;
            font-size: 12px;
        }

        .g-signin2 {
            width: 300px;
            height: 48px;
            display: block;
            position: absolute;
            right: 48px;
            top: 6px;
        }

        #ga_logout a:link {
            color: #C1C1C1;
        }

        #ga_logout a:visited {
            color: #C1C1C1;
        }

        #ga_logout a:hover {
            color: #ffffff;
        }

        #ajaxstatus {
            text-align: center;
            width: 100%;
            height: 24px;
            background-color: #333333;
            color: #F4F4F4;
            padding-top: 2px;
            font-weight: bold;
        }

    </style>
    <style>
        h1 {
            color: #D2D2D2;
            font-size: 24px;
            padding: 0px;
            margin: 0px;
            margin-top: 10px;
        }

        .container {
            max-width: 99%;
        }

        .schedule {
            border: 1px solid;
            border-color: #555;
            width: 99%;
        }

        .schedule th {
            color: #efefef;
        }

        .gen {
            background-color: #363636;
            color: #efefef;
            padding: 3px;
            text-align: center;

            border-bottom: 1px solid;
            border-bottom-color: #666666;
        }

        .played {
            background-color: #363636;
            color: #efefef;
            padding: 3px;
            text-align: center;
        }

        .notplayed {
            background-color: #5C5C5C;
            color: #efefef;
            padding: 3px;
            text-align: center;
        }

        .red {
            background-color: #B32222;
            padding: 3px;
            text-align: center;
            border-bottom: 1px solid;
            border-bottom-color: #BF4446;
        }

        .red a:link {
            color: #ffD7D7;
        }

        .red a:visited {
            color: #ffD7D7;
        }

        .red a:hover {
            color: #D7D7D7;
        }

        .red a:active {
            color: #ffD7D7;
        }

        .blue {
            background-color: #2222B3;
            padding: 3px;
            text-align: center;
            border-bottom: 1px solid;
            border-bottom-color: #4644BF;
        }

        .blue a:link {
            color: #D7D7ff;
        }

        .blue a:visited {
            color: #D7D7ff;
        }

        .blue a:hover {
            color: #D7D7D7;
        }

        .blue a:active {
            color: #D7D7ff;
        }

        .mer {
            background-color: #4C0001;
            display: block;
            border-radius: 4px;
        }

        .meb {
            background-color: #01004C;
            display: block;
            border-radius: 4px;
        }

        .time {
            font-size: 13px;
            color: #aaa;
        }

        .teamlist {
            width: 99%;
        }

        .teamlist td {
            background-color: #222222;
            padding: 3px;
            text-align: center;
            border-bottom: 1px solid;
            border-bottom-color: #464446;
            margin-bottom: 2px;
        }

        .teamlist a:link {
            color: #bbb;
        }

        .teamlist a:visited {
            color: #bbb;
        }

        .teamlist a:hover {
            color: #D7D7D7;
        }

        .teamlist a:active {
            color: #bbb;
        }
    </style>
</head>

<body>
<div id="topbar">IBOTS Scouting - <strong><?php echo $pagetitle ?></strong></span>


    <div class="login-content">
        <div id='ga_name'></div>
        <div id='ga_pic'></div>
        <div class="g-signin2" data-onsuccess="onSignIn"></div>

        <div id='ga_logout'><a href="#" class="dropdown-item" onclick="signOut();">Sign out</a></div>
    </div>

</div>
<div id="ajaxstatus"><a href="index.php">Match Schedule</a> - <a href="scoutselect.php">Scout Input</a> - <a
            href="eventstats.php">Event Stats</a></div>
<style>
    pre {
        color: #B38586;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #444;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #999;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #666;
    }

    .table {
        display: table;
        color: #B7B7B7;
        margin-bottom: .1rem;
    }

    .tr {
        display: table-row;
    }

    .td {
        display: table-cell;
    }

    #status {
        display: table-row;
    }

    #actionbar {
        border-spacing: 6px;
    }

    #gamepadstatus {
        display: table-cell;
        width: 150px;
        height: 60px;
        border-radius: 5px;
        vertical-align: middle;
        text-align: center;
        border-spacing: 2px;
        font-size: 22px;
    }

    .disconnected {
        background-color: #660000;
        border: 1px solid;
        border-color: #AA0000;
        color: #FFC8C8;
    }

    .connected {
        background-color: #006600;
        border: 1px solid;
        border-color: #00AA00;
        color: #C8FFC8;
    }

    #matchperiod {
        display: table-cell;
        width: 150px;
        height: 60px;
        border-radius: 5px;
        vertical-align: middle;
        text-align: center;
        margin: 2px;
        font-size: 22px;
    }

    .action {
        display: table-cell;
        width: 150px;
        height: 60px;
        border-radius: 5px;
        vertical-align: middle;
        text-align: center;
        margin: 2px;
        border: 1px solid;
        font-size: 22px;
    }

    .auton {
        border: 1px solid;
        background: #463c8c;
        color: #e2dff7;
        border-color: #6655e0;
    }

    .telop {
        background-color: #006600;
        border: 1px solid;
        border-color: #00AA00;
        color: #C8FFC8;
    }

    .prematch {
        background-color: #660000;
        border: 1px solid;
        border-color: #AA0000;
        color: #FFC8C8;
    }

    #gameplay {
        width: 240px;
        display: table-cell;
        margin-right: 2px;
    }

    .list-group-item {
        padding-top: 0.05rem;
        padding-right: 1.25rem;
        padding-bottom: 0.05rem;
        padding-left: 1.25rem;
        font-size: 14px;
        margin: 1px;
    }

    .start {
        background: #3C5C1D;
        color: #B7EC7B;
    }

    .reload, #act_reload {
        background: #6112a1;
        color: #ecdff7;
        border-color: #a455e0;
    }

    .miss, #act_miss {
        background: #a12012;
        color: #f7e1df;
        border-color: #e05e55;
    }

    .lowgoal, #act_low {
        background: #61a112;
        color: #ecf7df;
        border-color: #a1e055;
    }

    .outergoal, #act_outer {
        background: #2c6cc7;
        color: #dfe9f7;
        border-color: #5588e0;
    }

    .innergoal, #act_inner {
        background: #c29415;
        color: #f7f1df;
        border-color: #e0bd55;
    }

    #box {
        display: block;
        position: absolute;
        top: 200px;
        left: 2px;
        bottom: 2px;
        width: 240px;
        overflow-y: scroll;
        overflow-x: hidden;
        padding-right: 3px;
    }

    #postgame .table {
        border-spacing: 10px;
    }

    #postgame .td {
        width: 18%;
        padding: 5px;
        border: 1px solid;
        border-color: #505050;
        border-radius: 10px;
        background-color: #2a2a2a;
    }

    h2 {
        color: #E5E5E5;
    }

    h3 {
        color: #E0E0E0;
    }

    .fullwidth {
        display: block;
        width: 98%;
        margin-right: 5px;
        margin-left: 10px;
    }

    .sel_grey {
        background-color: #c7c7c7;
    }

    .sel_red {
        background-color: #ffc9c9;
    }

    .sel_orange {
        background-color: #ffe0c9;
    }

    .sel_yellow {
        background-color: #fff9c9;
    }

    .sel_gror {
        background-color: #f4ffc9;
    }

    .sel_green {
        background-color: #dbffc9;
    }

    .whoscouted {
        font-size: 12px;
        color: #909090;
    }

    tr {


    }

    .matchdata {
        border-bottom: 2px double;
        border-bottom-color: #8B8B8B;
    }

    .notes {
        display: block;
        width: 200px;
        height: 90px;
        overflow-y: scroll;

        font-size: 12px;
        padding: 5px;
    }

    .matchmeta {
        text-align: center;
        font-weight: bold;
    }

    .table td {
        border-top: 1px solid #000000;
        border-left: 1px dotted #000000;
        color: #FFFFFF;
        font-weight: bold;
        text-align: center;
    }
</style>


<?php

function teamdetails($db, $teamnumber, $ev_current)
{
    $schedule = new matchschedule($db, $ev_current);
    $teamsched = $schedule->getMatchRobotIDsTeam($teamnumber);


    // This makes data for a team into an array
    $SD = array();
    foreach ($teamsched as $matchid) {
        $extradata = array();
        //print_r($matchid);

        $extradata['match'] = $matchid;
        //echo $matchid['PosNa'];

        if ($matchid['PosNu'] > 3) {
            $color = "blue";
        } else {
            $color = "red";
        }
        $tbadata = array();
        $sql = "SELECT * FROM `2020_TBA` WHERE `match_ID` = :mid;";
        $statement = $db->prepare($sql);
        $statement->bindValue(":mid", $matchid['MatchID']);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row) {
            //print_r($row);
            $tbadata = $row;
        }
        //echo "auton";
        $g_mi = 0;
        $g_lo = $tbadata['autoCellsBottom'];
        $g_ou = $tbadata['autoCellsOuter'];
        $g_in = $tbadata['autoCellsInner'];
        $g_points = $tbadata['autoCellPoints'];
        $extradata['alliance']['autoCellsBottom'] = $tbadata['autoCellsBottom'];
        $extradata['alliance']['autoCellsOuter'] = $tbadata['autoCellsOuter'];
        $extradata['alliance']['autoCellsInner'] = $tbadata['autoCellsInner'];
        $extradata['alliance']['autoCellPoints'] = $tbadata['autoCellPoints'];
        //graphic_set($g_mi,$g_lo,$g_ou,$g_in,$g_points);

        //echo "telop";
        $g_mi = 0;
        $g_lo = $tbadata['teleopCellsBottom'];
        $g_ou = $tbadata['teleopCellsOuter'];
        $g_in = $tbadata['teleopCellsInner'];
        $g_points = $tbadata['teleopCellPoints'];
        $extradata['alliance']['teleopCellsBottom'] = $tbadata['teleopCellsBottom'];
        $extradata['alliance']['teleopCellsOuter'] = $tbadata['teleopCellsOuter'];
        $extradata['alliance']['teleopCellsInner'] = $tbadata['teleopCellsInner'];
        $extradata['alliance']['teleopCellPoints'] = $tbadata['teleopCellPoints'];

        //graphic_set($g_mi,$g_lo,$g_ou,$g_in,$g_points);
        $extradata['alliance']['endgameRungIsLevel'] = $tbadata['endgameRungIsLevel'];
        $extradata['alliance']['endgameRobot1'] = $tbadata['endgameRobot1'];
        $extradata['alliance']['endgameRobot2'] = $tbadata['endgameRobot2'];
        $extradata['alliance']['endgameRobot3'] = $tbadata['endgameRobot3'];

        if ($matchid['PosNu'] > 3) {
            $extradata['alliance']['mypos'] = $matchid['PosNu'] - 3;
        } else {
            $extradata['alliance']['mypos'] = $matchid['PosNu'];
        }
        //echo "<pre>";
        //print_r($matchid);
        //echo "match";
        //print_r($match);
        //echo "</pre>";

        $scoutsub = submission($db, $matchid['MRID']);

        //print_r($scoutsub);
        //$extradata['submissions'] = $scoutsub;

        foreach ($scoutsub as $subm) {

            $scoutname = scoutname($db, $subm['Scout']); //Name Of Scout
            $submission_ID = $subm['ID']; //Submission ID Number
            $submission_Time = $subm['Time']; // Time Of Submission

            $match = matchstats($db, $subm['ID']); //Form data from scout input
            $shots = shotstats($db, $subm['ID']); //Shot Data
            $extradata['sub'][$subm['ID']]['ID'] = $subm['ID'];
            $extradata['sub'][$subm['ID']]['BMID'] = $subm['BM_ID'];
            $extradata['sub'][$subm['ID']]['SID'] = $subm['Scout'];
            $extradata['sub'][$subm['ID']]['Time'] = $subm['Time'];
            $extradata['sub'][$subm['ID']]["name"] = $scoutname;

            $extradata['sub'][$subm['ID']]['match'] = $match[0];
            //$extradata['data'][$subm['ID']]['shots'] = $shots;
            //echo $scoutname;


            $a_miss = 0;
            $a_low = 0;
            $a_outer = 0;
            $a_in = 0;
            $a_reload = 0;

            $t_miss = 0;
            $t_low = 0;
            $t_outer = 0;
            $t_in = 0;
            $t_reload = 0;

            $set = array();
            $setnum = 0;

            $period_ofGame = 0;
            $set['a'][$setnum]['miss'] = 0;
            $set['a'][$setnum]['low'] = 0;
            $set['a'][$setnum]['outer'] = 0;
            $set['a'][$setnum]['inner'] = 0;
            $set['t'][$setnum]['miss'] = 0;
            $set['t'][$setnum]['low'] = 0;
            $set['t'][$setnum]['outer'] = 0;
            $set['t'][$setnum]['inner'] = 0;
            foreach ($shots as $shot) {
                //$scoutname = scoutname($db, $shot['Scout']);


                //echo $scoutname;
                if ($shot['period'] == 0) {//prematch
                    $set['a'][$setnum]['miss'] = 0;
                    $set['a'][$setnum]['low'] = 0;
                    $set['a'][$setnum]['outer'] = 0;
                    $set['a'][$setnum]['inner'] = 0;
                    $set['t'][$setnum]['miss'] = 0;
                    $set['t'][$setnum]['low'] = 0;
                    $set['t'][$setnum]['outer'] = 0;
                    $set['t'][$setnum]['inner'] = 0;
                }
                if ($shot['period'] == 1) {//Auto
                    switch ($shot['Action']) {
                        case 10;//"miss":
                            $a_miss++;
                            $set['a'][$setnum]['miss']++;
                            break;
                        case 11;//"reload":
                            $a_reload++;
                            $setnum++;
                            $set['a'][$setnum]['miss'] = 0;
                            $set['a'][$setnum]['low'] = 0;
                            $set['a'][$setnum]['outer'] = 0;
                            $set['a'][$setnum]['inner'] = 0;
                            break;
                        case 12;//"low":
                            $a_low++;
                            $set['a'][$setnum]['low']++;
                            break;
                        case 13;//"outer":
                            $a_outer++;
                            $set['a'][$setnum]['outer']++;
                            break;
                        case 14;//"inner":
                            $a_in++;
                            $set['a'][$setnum]['inner']++;
                            break;
                        default:
                            break;
                    }
                }
                if ($shot['period'] == 2) {//Telop
                    switch ($shot['Action']) {
                        case 10;//"miss":
                            $a_miss++;
                            $set['t'][$setnum]['miss']++;
                            break;
                        case 11;//"reload":
                            $a_reload++;
                            $setnum++;
                            $set['t'][$setnum]['miss'] = 0;
                            $set['t'][$setnum]['low'] = 0;
                            $set['t'][$setnum]['outer'] = 0;
                            $set['t'][$setnum]['inner'] = 0;
                            break;
                        case 12;//"low":
                            $a_low++;
                            $set['t'][$setnum]['low']++;
                            break;
                        case 13;//"outer":
                            $a_outer++;
                            $set['t'][$setnum]['outer']++;
                            break;
                        case 14;//"inner":
                            $a_in++;
                            $set['t'][$setnum]['inner']++;
                            break;
                        default:
                            break;
                    }
                }
            }
            /*	echo "a - miss: ".$a_miss;
                echo "a - low: ".$a_low;
                echo "a - outer: ".$a_outer;
                echo "a - inner: ".$a_in;
                echo "a - reload: ".$a_reload;

                echo "t - miss: ".$t_miss;
                echo "t - low: ".$t_low;
                echo "t - outer: ".$t_outer;
                echo "t - inner: ".$t_in;
                echo "t - reload: ".$t_reload;*/

            $extradata['sub'][$subm['ID']]['shotset'] = $set;
            //echo "<pre>";
            //print_r($set);

            //echo "</pre>";


            foreach ($set as $period) {
                $period_ofGame++;
                if ($period_ofGame == 1) {
                    //echo "Auton: ";
                }
                if ($period_ofGame == 2) {
                    //echo "Telop: ";
                }
                foreach ($period as $batch) {
                    //print_r($batch);
                    if ($period_ofGame == 1) {//auto
                        $multiplier = 2;
                    } else {
                        $multiplier = 1;
                    }
                    $g_mi = $batch['miss'];
                    $g_lo = $batch['low'];
                    $g_ou = $batch['outer'];
                    $g_in = $batch['inner'];
                    $g_points = ($g_lo + ($g_ou * 2) + ($g_in * 3)) * $multiplier;
                    //graphic_set($g_mi,$g_lo,$g_ou,$g_in,$g_points);
                    //graphic_set(2,4,0,1);

                }
            }


            print("</div>");//td
            print("</div>");//tr
        }
        $SD[$extradata['match']['MRID']] = $extradata;
    }//end match loop

    return $SD;
}

$eventarray = array();
foreach ($teamlist as $teamnumber) {
    $eventarray[$teamnumber]['number'] = $teamnumber;
    $eventarray[$teamnumber]['details'] = teamdetails($db, $teamnumber, $ev_current);
}
//	
//print_r($eventarray);
?>


<div class="table">
    <table id="teamstats">
        <thead>
        <tr>
            <th>Team</th>
            <th>Matches Played</th>
            <th>Secret Sauce</th>
            <th>Average Points</th>
            <th>A Avg Misses</th>
            <th>A Avg Low</th>
            <th>A Avg Outer</th>
            <th>A Avg Inner</th>
            <th>T Avg Misses</th>
            <th>T Avg Low</th>
            <th>T Avg Outer</th>
            <th>T Avg Inner</th>
            <th>% Climb</th>
            <th>% Park</th>
            <!--<th>% DJ Revolve</th>
            <th>% DJ Match</th>
            <th>Average Defense Give</th>
            <th>Average Defense Recieve</th>-->
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($eventarray as $t) {
            $played = 0;
            $scouted = 0;
            $Aballmiss = 0;
            $Aballlow = 0;
            $Aballout = 0;
            $Aballin = 0;
            $Tballmiss = 0;
            $Tballlow = 0;
            $Tballout = 0;
            $Tballin = 0;
            $hang = 0;
            $park = 0;

            foreach ($t['details'] as $m) {
                $played++;
                switch ($m['match']['PosNu']) {
                    case 1;//"r1":
                        $egpos = "endgameRobot1";
                        break;
                    case 2;//"r2":
                        $egpos = "endgameRobot2";
                        break;
                    case 3;//"r3":
                        $egpos = "endgameRobot3";
                        break;
                    case 4;//"b1":
                        $egpos = "endgameRobot1";
                        break;
                    case 5;//"b2":
                        $egpos = "endgameRobot2";
                        break;
                    case 6;//"b3":
                        $egpos = "endgameRobot3";
                        break;
                }
                if ($m['alliance'][$egpos] == "Hang") {
                    $hang++;
                }
                if ($m['alliance'][$egpos] == "Park") {
                    $park++;
                }


                if (isset($m['sub'])) {//match was scouted
                    foreach ($m['sub'] as $e) {//each scouted entry
                        $scouted++;
                        //print_r($e['shotset']['a']);
                        foreach ($e['shotset']['a'] as $ss) {
                            //print_r($ss);
                            $Aballmiss = $Aballmiss + $ss['miss'];
                            $Aballlow = $Aballlow + $ss['low'];
                            $Aballout = $Aballout + $ss['outer'];
                            $Aballin = $Aballin + $ss['inner'];
                        }
                        foreach ($e['shotset']['t'] as $ss) {
                            $Tballmiss = $Tballmiss + $ss['miss'];
                            $Tballlow = $Tballlow + $ss['low'];
                            $Tballout = $Tballout + $ss['outer'];
                            $Tballin = $Tballin + $ss['inner'];
                        }

                    }
                }
            }

            //Team Number & Name
            $dTeamNumber = $t['number'];

            //Matches Played
            $dPlayed = $played;
            $dScouted = $scouted;

            //Average Score
            $score = 0;
            if ($scouted > 0) {
                $score += (($Aballlow / $scouted) * 2);
                $score += (($Aballout / $scouted) * 4);
                $score += (($Aballin / $scouted) * 6);
                $score += (($Tballlow / $scouted) * 1);
                $score += (($Tballout / $scouted) * 2);
                $score += (($Tballin / $scouted) * 3);
            }
            $score += (($hang / $played) * 25);
            $score += (($park / $played) * 5);
            $dAverageScore = round($score, 1);

            //Averages
            if ($scouted > 0) {
                $dAutMiss = round(($Aballmiss / $scouted), 2);
                $dAutLow = round(($Aballlow / $scouted), 2);
                $dAutOut = round(($Aballout / $scouted), 2);
                $dAutIn = round(($Aballin / $scouted), 2);
                $dTelMiss = round(($Tballmiss / $scouted), 2);
                $dTelLow = round(($Tballlow / $scouted), 2);
                $dTelOut = round(($Tballout / $scouted), 2);
                $dTelIn = round(($Tballin / $scouted), 2);
            } else {
                $dAutMiss = 0;
                $dAutLow = 0;
                $dAutOut = 0;
                $dAutIn = 0;
                $dTelMiss = 0;
                $dTelLow = 0;
                $dTelOut = 0;
                $dTelIn = 0;
            }

            //Endgame
            $dHang = round(($hang / $played) * 100);
            $dPark = round(($park / $played) * 100);


            //Hue Multipliers
            $Color_Sat = 60;
            $Color_Light = 27;
            $Color_autoBalls = 16;
            $Color_telopBalls = 5;
            ?>
            <tr>
                <td data-sort="<?php echo $dTeamNumber; ?>">
                    <a href="https://frcscouting.net/prod/teamstat.php?team=<?php echo $dTeamNumber; ?>">
                        <?php echo $dTeamNumber; ?>
                        <br><?php echo teamname($db, $dTeamNumber); ?>
                    </a>
                </td>
                <td data-sort="<?php echo $dPlayed; ?>"><?php echo $dPlayed; ?> (<?php echo $dScouted; ?>)</td>
                <td data-sort="0">???</td>
                <td data-sort="<?php echo $dAverageScore ?>"
                    style="background-color: <?php echo convertHSL(round($dAverageScore * 4.5), $Color_Sat, $Color_Light); ?>"><?php echo $dAverageScore ?></td>

                <td data-sort="<?php echo $dAutMiss; ?>"
                    style="background-color: <?php echo convertHSL(round(130 - ($dAutMiss * $Color_autoBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dAutMiss; ?>
                    <br></td>
                <td data-sort="<?php echo $dAutLow; ?>"
                    style="background-color: <?php echo convertHSL(round(($dAutLow * $Color_autoBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dAutLow; ?>
                    <br><?php echo $dAutLow * 2; ?></td>
                <td data-sort="<?php echo $dAutOut; ?>"
                    style="background-color: <?php echo convertHSL(round(($dAutOut * $Color_autoBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dAutOut; ?>
                    <br><?php echo $dAutOut * 4; ?></td>
                <td data-sort="<?php echo $dAutIn; ?>"
                    style="background-color: <?php echo convertHSL(round(($dAutIn * $Color_autoBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dAutIn; ?>
                    <br><?php echo $dAutIn * 6; ?></td>

                <td data-sort="<?php echo $dTelMiss; ?>"
                    style="background-color: <?php echo convertHSL(round(130 - ($dTelMiss * $Color_telopBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dTelMiss; ?>
                    <br></td>
                <td data-sort="<?php echo $dTelLow; ?>"
                    style="background-color: <?php echo convertHSL(round(($dTelLow * $Color_telopBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dTelLow; ?>
                    <br><?php echo $dTelLow; ?></td>
                <td data-sort="<?php echo $dTelOut; ?>"
                    style="background-color: <?php echo convertHSL(round(($dTelOut * $Color_telopBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dTelOut; ?>
                    <br><?php echo $dTelOut * 2; ?></td>
                <td data-sort="<?php echo $dTelIn; ?>"
                    style="background-color: <?php echo convertHSL(round(($dTelIn * $Color_telopBalls)), $Color_Sat, $Color_Light); ?>"><?php echo $dTelIn; ?>
                    <br><?php echo $dTelIn * 3; ?></td>

                <td data-sort="<?php echo $dHang; ?>"
                    style="background-color: <?php echo convertHSL(round($dHang * 1.3), $Color_Sat, $Color_Light); ?>"><?php echo $dHang; ?>
                    % <?php echo $hang; ?><br><?php echo $dHang * .25; ?></td>
                <td data-sort="<?php echo $dPark; ?>"
                    style="background-color: <?php echo convertHSL(round($dPark * 1.3), $Color_Sat, $Color_Light); ?>"><?php echo $dPark; ?>
                    % <?php echo $park; ?><br><?php echo $dPark * .05; ?></td>
                <!--<td><pre><?php //print_r($t);
                ?></pre></td>-->

            </tr>
            <?php
        }//end of team list loop

        ?></tbody>
    </table>

</div>
<script>


    $(document).ready(function () {
        $('#teamstats').DataTable({
            paging: false
        });
    });
</script>

<?php include("footer.php"); ?>
