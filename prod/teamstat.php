<!doctype html>

<?php
$team = $_GET['team'];
$colormultiplier = 12; //multiplier for hue


include('backend/db.php');
include('backend/2020botclass.php');

$schedule = new matchschedule($db, $ev_current);
//print_r($week0->getMatchList());



$teamsched = $schedule->getMatchRobotIDsTeam($team);

//print_r($teamsched);
function submission($db, $mrid){
		//SELECT * FROM `2019_gamepieces` ORDER BY `match_robot_id` ASC
	$sql = "SELECT * FROM `2020_Submission` WHERE `BM_ID` = :bmid;";
	$statement = $db->prepare($sql);
		$statement->bindValue(":bmid", $mrid);
		$statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
			$pre = $row;
		}
	return $result;
	
}
function matchstats($db, $subid){
	//SELECT * FROM `2019_gamepieces` ORDER BY `match_robot_id` ASC
	$sql = "SELECT * FROM `2020_Match` WHERE `2020_Match`.`Sub` = :sub;";
	$statement = $db->prepare($sql);
		$statement->bindValue(":sub", $subid);
		$statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
			$pre = $row;
		}
	return $result;
}
function shotstats($db, $subid){
	//SELECT * FROM `2019_gamepieces` ORDER BY `match_robot_id` ASC
	$sql= "SELECT * FROM `2020_Shots` WHERE `2020_Shots`.`Sub` = :sub;";
	$statement = $db->prepare($sql);
		$statement->bindValue(":sub", $subid);
		$statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
			$pre = $row;
		}
	return $result;
}
function scoutname($db, $sid){
	//SELECT * FROM `2019_gamepieces` ORDER BY `match_robot_id` ASC
	$sql= "SELECT * FROM `scout` WHERE `scout`.`internalid` = :sid;";
	$statement = $db->prepare($sql);
		$statement->bindValue(":sid", $sid);
		$statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
			$pre = $row;
		}
	return $pre['name'];
}
function gradient($level){
	
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
function graphic_set($miss,$low,$out,$in,$points ,$period = ""){

	if($miss >0){ $svgfill_miss = gradient($miss); }else{$svgfill_miss = "#444444"; }
	if($low >0){ $svgfill_low = gradient($low); }else{$svgfill_low = "#444444"; }
	if($out >0){ $svgfill_out = gradient($out); }else{$svgfill_out = "#444444"; }
	if($in >0){ $svgfill_in = gradient($in); }else{$svgfill_in = "#444444"; }

	$gradient = "Default";
    if($period=="A"){
        $gradient = "Auto";
    }
    if($period=="T"){
        $gradient = "Teleop";
    }
    if($period=="G"){
        $gradient = "Game";
    }
	
	//$svgfill_out = "#01ac00";
	print '<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="50px" height="85px" >
<style type="text/css">
	.svg_miss{stroke:#000000;stroke-miterlimit:10;
	border 0;}
	.svg_out{stroke:#000000;stroke-miterlimit:10;}
	.svg_in{stroke:#000000;stroke-miterlimit:10;}
	.svg_low{stroke:#000000;stroke-miterlimit:10;}
	text{
	font-size:12px;
	font-weight:bold;
	}
</style>

  <defs>
    <linearGradient id="Auto" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="70%" style="stop-color:#000000;stop-opacity:0" />
      <stop offset="100%" style="stop-color:#ED1C24;stop-opacity:.5" />
    </linearGradient>
    <linearGradient id="Teleop" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="70%" style="stop-color:#000000;stop-opacity:0" />
      <stop offset="100%" style="stop-color:#29ABE2;stop-opacity:.5" />
    </linearGradient>
    <linearGradient id="Game" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="70%" style="stop-color:#000000;stop-opacity:0" />
      <stop offset="100%" style="stop-color:#39B54A;stop-opacity:.5" />
    </linearGradient>
    <linearGradient id="Default" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:rgb(120,120,120);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(120,120,120);stop-opacity:0.5" />
    </linearGradient>
  </defs>
<rect fill="url(#'.$gradient.')"x="0.5" y="0.5" width="49" height="84"/>
<polygon fill="'.$svgfill_out.'" class="svg_out" points="35.5,5 14.5,5 4,23 14.5,41 35.5,41 46,23 "/>
<circle fill="'.$svgfill_in.'" class="svg_in" cx="25" cy="23" r="9"/>
<rect fill="'.$svgfill_low.'" x="7" y="49" class="svg_low" width="36" height="14"/>
<text text-anchor="start" x="2" y="12" fill="#cc9999">'.$miss.'</text>
<text text-anchor="middle" x="18" y="40" fill="#efefef">'.$out.'</text>
<text text-anchor="middle" x="24" y="28" fill="#efefef">'.$in.'</text>
<text text-anchor="middle" x="25" y="60" fill="#efefef">'.$low.'</text>
<text text-anchor="end" x="48" y="81" fill="#efefef">'.$points.'</text>
<text text-anchor="start" x="2" y="81" fill="#eee">'.$period.'</text>
</svg>';
	
}

function djbooth($stage){

    switch($stage){
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

function coathanger($level, $bot1, $bot2, $bot3, $mypos){
    
    //coathanger($match['alliance']['endgameRungIsLevel'],$match['alliance']['endgameRobot1'],$match['alliance']['endgameRobot2'],$match['alliance']['endgameRobot3']);
    
    
    $offset = 0;
    $poscolorleft = "#444444";
    $poscolormiddle = "#444444";
    $poscolorright = "#444444";
    
    switch($mypos){
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

    if($level != "IsLevel"){
        $offset = 10;
    }
    
    
    $barbonus = 0;
    
print '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"width="100px" height="85px"  xml:space="preserve">
<style type="text/css">
	.st0park{display:none;fill:none;stroke:#888888;stroke-miterlimit:10;}
	.st1park{stroke:#000;stroke-miterlimit:10;}
	.st0hang{fill:none;stroke:#CCCCCC;stroke-miterlimit:10;}
	.st1hang{stroke:#000;stroke-miterlimit:10;}
	.bar{stroke:#000000;stroke-miterlimit:10;}
</style>';
$points = 0;
$mypoints = 0;


    switch($bot1){
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="20" y1="34.8" x2="20" y2="55.03"/>';
            print '<rect fill="'.$poscolorleft.'" x="6.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            $points += 5;
            if ($mypos == 1){$mypoints += 5;}
            break;
        case "Hang":
            print '<line class="st0hang" x1="20" y1="'.(18 + $offset).'.8" x2="20" y2="'.(39 + $offset).'.03"/>';
            print '<rect fill="'.$poscolorleft.'" x="6.68" y="'.(39 + $offset).'.03" class="st1hang" width="26.64" height="12.16"/>';
            $points += 25;
            if ($mypos == 1){$mypoints += 25;}
            $barbonus = 1;
            break;
        }
    switch($bot2){
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="50" y1="34.8" x2="50" y2="55.03"/>';
            print '<rect fill="'.$poscolormiddle.'" x="36.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            if ($mypos == 2){$mypoints += 5;}
            $points += 5;
            break;
        case "Hang":
            print '<line class="st0hang" x1="50" y1="18.8" x2="50" y2="39.03"/>';
            print '<rect fill="'.$poscolormiddle.'" x="36.68" y="'.(39).'.03" class="st1hang" width="26.64" height="12.16"/>';
            if ($mypos == 2){$mypoints += 25;}
            $points += 25;
            $barbonus = 1;
            break;
        }
    switch($bot3){
        case "None":
            //leave gray
            break;
        case "Park":
            print '<line class="st0park" x1="80" y1="34.8" x2="80" y2="55.03"/>';
            print '<rect fill="'.$poscolorright.'" x="66.68" y="55.03" class="st1park" width="26.64" height="12.16"/>';
            if ($mypos == 3){$mypoints += 5;}
            $points += 5;
            break;
        case "Hang":
            print '<line class="st0hang" x1="80" y1="'.(18 - $offset).'" x2="80" y2="'.(39 - $offset).'.03"/>';
            print '<rect fill="'.$poscolorright.'" x="66.68" y="'.(39 - $offset).'.03" class="st1hang" width="26.64" height="12.16"/>';
            if ($mypos == 3){$mypoints += 25;}
            $points += 25;
            $barbonus = 1;
            break;
    }
    if($level == "IsLevel"){
        if ($barbonus == 1){
            $bar = '<rect fill="#39B54A" x="5" y="15" class="bar" width="90" height="4"/>';
             $points += ($barbonus * 10);
        }
        else{
            $bar = '<rect fill="#444444" x="5" y="15" class="bar" width="90" height="4"/>';
        }
        
        
    }else{
        if ($barbonus == 1){
            $bar = '<rect x="5" y="15" fill="#B5394A" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>';
        }
        else{
            $bar = '<rect x="5" y="15" fill="#444444" transform="matrix(0.95 -0.3123 0.3123 0.95 -2.8083 16.4658)" class="bar" width="90" height="4"/>';
        }
    }

    print $bar;
    print '<text text-anchor="start" x="2" y="81" fill="#efefef">A '.$points.'</text>';
    print '<text text-anchor="end" x="98" y="81" fill="#efefef">T '.$mypoints.'</text>';
    print '</svg> ';
    
    
    
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

function defencerecieved($level){
      print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="20px" height="85px"  xml:space="preserve">
<style type="text/css">
	.defensecolor{fill:#FFBC64;stroke:#F7931E;stroke-miterlimit:10;}
	.defensenocolor{fill:#333333;stroke:#666666;stroke-miterlimit:10;}
	.defensena{fill:#222222;stroke:#333333;stroke-miterlimit:10;}
</style>'; 
 switch($level){
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
function defencegiven($level){
   print '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="20px" height="85px"  xml:space="preserve">
<style type="text/css">
	.defensecolor{fill:#FFBC64;stroke:#F7931E;stroke-miterlimit:10;}
	.defensenocolor{fill:#333333;stroke:#666666;stroke-miterlimit:10;}
	.defensena{fill:#222222;stroke:#333333;stroke-miterlimit:10;}
</style>'; 
    switch($level){
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
$pagetitle = " ".$team." Statistics";
include("header.php"); ?>
<style>
	pre{
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
	.table{
		display: table;
		color: #B7B7B7;
		margin-bottom: .1rem;
	}
	.tr{
		display: table-row;
	}
	.td{
		display: table-cell;
	}
	#status{
		display: table-row;
	}
	#actionbar{
		border-spacing: 6px;
	}
	#gamepadstatus{
		display: table-cell;
		width: 150px;
		height: 60px;
		border-radius: 5px;
		vertical-align: middle;
		text-align: center;
		border-spacing: 2px;
		font-size: 22px;
	}
	.disconnected{
		background-color: #660000;
		border: 1px solid;
		border-color: #AA0000;
		color: #FFC8C8;
	}
	.connected{
		background-color: #006600;
		border: 1px solid;
		border-color: #00AA00;
		color: #C8FFC8;
	}
	
	#matchperiod{
		display: table-cell;
		width: 150px;
		height: 60px;
		border-radius: 5px;
		vertical-align: middle;
		text-align: center;
		margin: 2px;
		font-size: 22px;
	}
	.action{
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
	.telop{
		background-color: #006600;
		border: 1px solid;
		border-color: #00AA00;
		color: #C8FFC8;
	}
	.prematch{
		background-color: #660000;
		border: 1px solid;
		border-color: #AA0000;
		color: #FFC8C8;
	}
	
	#gameplay{
		width: 240px;
		display: table-cell;
		margin-right: 2px;
	}
	.list-group-item{
		padding-top: 0.05rem;
		padding-right: 1.25rem;
		padding-bottom: 0.05rem;
		padding-left: 1.25rem;
		font-size: 14px;
		margin: 1px;
	}
	.start{
		background: #3C5C1D;
		color: #B7EC7B;
	}
	.reload, #act_reload{
		background: #6112a1;
		color: #ecdff7;
		border-color: #a455e0;
	}
	.miss, #act_miss{
		background: #a12012;
		color: #f7e1df;
		border-color: #e05e55;
	}
	.lowgoal, #act_low{
		background: #61a112;
		color: #ecf7df;
		border-color: #a1e055;
	}
	.outergoal, #act_outer{
		background: #2c6cc7;
		color: #dfe9f7;
		border-color: #5588e0;
	}
	.innergoal, #act_inner{
		background: #c29415;
		color: #f7f1df;
		border-color: #e0bd55;
	}
	#box{
		display: block;
		position: absolute;
		top: 200px;
		left: 2px;
		bottom: 2px;
		width: 240px;
		overflow-y:scroll;
		overflow-x: hidden;
		padding-right: 3px;
	}
	#postgame .table{
    	border-spacing:10px;
	}
	#postgame .td{
		width: 18%;
		padding: 5px;
		border: 1px solid;
		border-color:#505050;
		border-radius: 10px;
		background-color: #2a2a2a;
	}
	h2{
		color: #E5E5E5;
	}
	h3{
		color: #E0E0E0;
	}
	.fullwidth{
		display: block;
		width: 98%;
		margin-right: 5px;
		margin-left: 10px;
	}
	.sel_grey{
		background-color: #c7c7c7;
	}
	.sel_red{
		background-color: #ffc9c9;
	}
	.sel_orange{
		background-color: #ffe0c9;
	}
	.sel_yellow{
		background-color: #fff9c9;
	}
	.sel_gror{
		background-color: #f4ffc9;
	}
	.sel_green{
		background-color: #dbffc9;
	}
    .whoscouted{
        font-size: 12px;
        color: #909090;
    }
    tr{
        
        
    }
    .matchdata{
        border-bottom: 2px double ;
        border-bottom-color:#8B8B8B;
    }
    .notes{
        display: block;
        width: 200px;
        height: 90px;
        overflow-y: scroll;
        
        font-size: 12px;
        padding: 5px;
    }
    .matchmeta{
        text-align: center;
        font-weight: bold;
    }
</style>
<?php //print_r($teamsched); ?>





<div class="table">
				
<?php



$SD = array();

foreach($teamsched as $matchid){
	$extradata = array();
	//print_r($matchid);
	
	$extradata['match'] =  $matchid;
	//echo $matchid['PosNa'];
	
	if ($matchid['PosNu']>3){
		$color = "blue";
	}
	else{
		$color = "red";
	}
	$tbadata = array();
	$sql = "SELECT * FROM `2020_TBA` WHERE `match_ID` = :mid;";
    $statement = $db->prepare($sql);
    $statement->bindValue(":mid", $matchid['MatchID']);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row){
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
	
    if ($matchid['PosNu']>3){
		$extradata['alliance']['mypos'] = $matchid['PosNu'] - 3;
	}
	else{
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
	
	foreach($scoutsub as $subm){
		
		$scoutname = scoutname($db, $subm['Scout']); //Name Of Scout
		$submission_ID = $subm['ID']; //Submission ID Number
		$submission_Time= $subm['Time']; // Time Of Submission
		
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
		foreach($shots as $shot){
			//$scoutname = scoutname($db, $shot['Scout']);


			//echo $scoutname;
			if($shot['period'] == 0){//prematch
				$set['a'][$setnum]['miss'] = 0;
				$set['a'][$setnum]['low'] = 0;
				$set['a'][$setnum]['outer'] = 0;
				$set['a'][$setnum]['inner'] = 0;
				$set['t'][$setnum]['miss'] = 0;
				$set['t'][$setnum]['low'] = 0;
				$set['t'][$setnum]['outer'] = 0;
				$set['t'][$setnum]['inner'] = 0;
			}
			if($shot['period'] == 1){//Auto
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
			if($shot['period'] == 2){//Telop
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
		
		
        foreach($set as $period){
            $period_ofGame++;
            if($period_ofGame == 1){
                //echo "Auton: ";
            }
            if($period_ofGame == 2){
                //echo "Telop: ";
            }
            foreach($period as $batch){
                //print_r($batch);
                if ($period_ofGame == 1){//auto
                    $multiplier = 2;
                }
                else{
                    $multiplier = 1;
                }
                $g_mi = $batch['miss'];
                $g_lo = $batch['low'];
                $g_ou = $batch['outer'];
                $g_in = $batch['inner'];
                $g_points = ($g_lo + ($g_ou*2) + ($g_in*3))*$multiplier;
                //graphic_set($g_mi,$g_lo,$g_ou,$g_in,$g_points);
                //graphic_set(2,4,0,1);

            }
        }
		
			
			print("</div>");//td
		print("</div>");//tr
		}
	$SD[$extradata['match']['MRID']] = $extradata; 
}//end match loop
//	echo "<pre>";
//print_r($match);
//echo "</pre>";
	?>
	<table><tr><th>Match</th><th colspan="3">Alliance Summary</th><th>Scouted Data</th></tr>
	<?php
	
foreach($SD as  $match){
	?>
	<tr class=\"matchdata\">
	<td>
	<div class=\"matchmeta\">
	<?php echo $match['match']['name']; ?>
	</td>
	<td>
    <?php graphic_set(0,$match['alliance']['autoCellsBottom'],$match['alliance']['autoCellsOuter'],$match['alliance']['autoCellsInner'],$match['alliance']['autoCellPoints'],"A"); ?>
	</td>
	<td>
	<?php graphic_set(0,$match['alliance']['teleopCellsBottom'],$match['alliance']['teleopCellsOuter'],$match['alliance']['teleopCellsInner'],$match['alliance']['teleopCellPoints'],"T"); ?>
	</td>
	<td>
	<?php graphic_set(0,$match['alliance']['teleopCellsBottom']+$match['alliance']['autoCellsBottom'],$match['alliance']['teleopCellsOuter']+$match['alliance']['autoCellsOuter'],$match['alliance']['teleopCellsInner']+$match['alliance']['autoCellsInner'],$match['alliance']['teleopCellPoints']+$match['alliance']['autoCellPoints'],"G"); ?>
	</div>

	<?php
    //print_r($match['alliance']);
    echo "<!--";
	echo $match['alliance']['endgameRungIsLevel'];
    echo $match['alliance']['endgameRobot1'];
    echo $match['alliance']['endgameRobot2'];
    echo $match['alliance']['endgameRobot3'];
    echo "-->";
    
    coathanger($match['alliance']['endgameRungIsLevel'],$match['alliance']['endgameRobot1'],$match['alliance']['endgameRobot2'],$match['alliance']['endgameRobot3'],$match['alliance']['mypos']);
   // coathanger($match['alliance']['endgameRungIsLevel'],$match['alliance']['endgameRobot1'],$match['alliance']['endgameRobot2'],$match['alliance']['endgameRobot3'],1);
   // coathanger("","Hang",$match['alliance']['endgameRobot2'],"Hang",1);
    
    
	echo "</td>";
	echo "<td>";
	echo "<div class=\"sets\">";
	echo "<table>";
		foreach ($match['sub'] as $sub){
            
	echo "<tr>";
	echo '<td><div class="whoscouted">';
		echo $sub['name'];
            echo "<br />";
		echo $sub['Time'];
			//print_r($sub);
			echo "</div></td>";
	echo "<td>";
			echo "<div class=\"postmatch\">";
			?>
	<table>
		<tr>
            <td>
            <?php
            $ga_mi = 0;
            $ga_lo = 0;
            $ga_ou = 0;
            $ga_in = 0;
            
            $gt_mi = 0;
            $gt_lo = 0;
            $gt_ou = 0;
            $gt_in = 0;
            foreach($sub['shotset']['a'] as $auto){
				$ga_mi = $ga_mi + $auto['miss'];
                $ga_lo = $ga_lo + $auto['low'];
                $ga_ou = $ga_ou + $auto['outer'];
                $ga_in = $ga_in + $auto['inner'];
					
			}
            $ga_points = ($ga_lo + ($ga_ou*2) + ($ga_in*3))* 2;
			graphic_set($ga_mi,$ga_lo,$ga_ou,$ga_in,$ga_points, "A");
			
            
			foreach($sub['shotset']['t'] as $telop){
				$gt_mi = $gt_mi + $telop['miss'];
                $gt_lo = $gt_lo + $telop['low'];
                $gt_ou = $gt_ou + $telop['outer'];
                $gt_in = $gt_in + $telop['inner'];
			}
            
            $gt_points = ($gt_lo + ($gt_ou*2) + ($gt_in*3));
            graphic_set($gt_mi,$gt_lo,$gt_ou,$gt_in,$gt_points, "T");
            
            
            graphic_set($ga_mi + $gt_mi, $ga_lo + $gt_lo, $ga_ou + $gt_ou, $ga_in + $gt_in, $ga_points + $gt_points, "G");
            
            
            ?>
            
            
            
            
            </td>
			<td>


                <?php 
                
                switch ($sub['match']['sd_cw_rotation']) {
					case 1;// 1-No Attempt
						djbooth(0);
						break;
					case 2;// 2-Attempt
						djbooth(1);
						break;
					case 3;// 3-Complete
						djbooth(2);
						break;      
                }
                $djstatus2 = ""; // Stage 2 Position
                switch ($sub['match']['sd_cw_position']) {
					case 1;// 1-No Attempt
						djbooth(3);
						break;
					case 2;// 2-Attempt
						djbooth(4);
						break;
					case 3;// 3-Complete
						djbooth(5);
						break;      
                }
                ?>
				<?php //echo $sub['match']['sd_cw_rotation']; //1-No Attempt, 2-Attempt, 3-Completed ?>
				<?php //echo $sub['match']['sd_cw_position']; //1-No Attempt, 2-Attempt, 3-Completed ?>
			</td>
			<td>
                <?php 
            
            ?>
                Climb:<br>
                <?php echo $sub['match']['sd_eg_hang']; //1-No Attempt, 2-Parked, 3-Attempted, 4-Successful?>
				<?php echo $sub['match']['sd_eg_hang_level']; //1-No Chance, 2-Attempt, 3-Successful?>
				<?php echo $sub['match']['sd_eg_hang_bots']; //Number of Bots on the bar?>
			</td>
			<td><?php //echo $sub['match']['sd_def_giving_rating']; //1-Not played 2-5 Skill 5 is better
                
                defencegiven($sub['match']['sd_def_giving_rating']);
                ?>
				<?php //echo $sub['match']['sd_def_receiving_rating'];//1-Not recieved 2-5 Skill 5 is better at working around
                
                defencerecieved($sub['match']['sd_def_receiving_rating']);
                ?>
                </td>
			<td>
                <div class="notes">
				<?php echo $sub['match']['sd_def_notes']; //Notes on defense?></div>
			</td>
			<td><div class="notes"><?php echo $sub['match']['sd_match_notes']; //Match Notes?></div>
			</td>
		</tr>
	</table>
			<?php
			echo "</div>";//sets
			echo "</td>";
	echo "<td>";
	/*
	echo "<div class=\"auto\">";
			foreach($sub['shotset']['a'] as $auto){
				$g_mi = $auto['miss'];
					$g_lo = $auto['low'];
					$g_ou = $auto['outer'];
					$g_in = $auto['inner'];
					$g_points = ($g_lo + ($g_ou*2) + ($g_in*3))* 2;
					graphic_set($g_mi,$g_lo,$g_ou,$g_in,$g_points,"A");
			}
			echo "</div>";//sets
	*/
			echo "</td>";
	echo "<td>";

	/*
			echo "<div class=\"telo\">";
			foreach($sub['shotset']['t'] as $telop){
				$g_mi = $telop['miss'];
					$g_lo = $telop['low'];
					$g_ou = $telop['outer'];
					$g_in = $telop['inner'];
					$g_points = ($g_lo + ($g_ou*2) + ($g_in*3));
					graphic_set($g_mi,$g_lo,$g_ou,$g_in,$g_points,"T");
			}
	*/
			echo "</td>";//td
	echo "</tr>";//tr
			echo "</div>";//sets
		}
	echo "</table>";
	echo "</div>";//sets
	
	echo "</td>";//td
	echo "</tr>";//tr
}
echo "</table>";
?>

	
</div>		
		
		
<?php include("footer.php"); ?>