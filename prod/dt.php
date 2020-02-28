<?php
$myteam = $_GET['team'];
if($_GET['team'] == ""){
    $myteam = 4041;
}
$pagetitle = "Drive Team Dashboard";
include('backend/db.php');
include('backend/2020botclass.php');
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions

$schedule = new matchschedule($db, $ev_current);
$teamlist = $schedule->getTeamList();
$teamsched = $schedule->getMatchRobotIDsTeam($team);
$pagetitle = "Match Schedule";

function teamdetails($teamnumber)
{
    global $db;
    global $ev_current;
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

        $scoutsub = submission($matchid['MRID']);

        //print_r($scoutsub);
        //$extradata['submissions'] = $scoutsub;

        foreach ($scoutsub as $subm) {

            $scoutname = scoutname($subm['Scout']); //Name Of Scout
            $submission_ID = $subm['ID']; //Submission ID Number
            $submission_Time = $subm['Time']; // Time Of Submission

            $match = matchstats($subm['ID']); //Form data from scout input
            $shots = shotstats($subm['ID']); //Shot Data
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



        }
        $SD[$extradata['match']['MRID']] = $extradata;
    }//end match loop

    return $SD;
}
function quickstats ($t){
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
$fouls = 0;
$defgiv = 0;
$defrec = 0;
foreach ($t as $m) {
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
    if ($m['alliance'][$egpos] === "Hang") {
        $hang++;
    }
    if ($m['alliance'][$egpos] === "Park") {
        $park++;
    }


    if (isset($m['sub'])) {//match was scouted
        foreach ($m['sub'] as $e) {//each scouted entry

            $fouls += $e['match']['sd_fouls'];
            $defgiv += $e['match']['sd_def_giving_rating']-1;
            $defrec += $e['match']['sd_def_receiving_rating']-1;

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
        if($scouted > 1){
            $fouls = round($fouls / $scouted,1);
            $defgiv = round($defgiv / $scouted,1);
            $defrec = round($defrec / $scouted,1);
            $Aballmiss = round($Aballmiss / $scouted,1);
            $Aballlow = round($Aballlow / $scouted,1);
            $Aballout = round($Aballout / $scouted,1);
            $Aballin = round($Aballin / $scouted,1);
            $Tballmiss = round($Tballmiss / $scouted,1);
            $Tballlow = round($Tballlow / $scouted,1);
            $Tballout = round($Tballout / $scouted,1);
            $Tballin = round($Tballin / $scouted,1);
        }
    }
}

//Team Number & Name
//$ret['dTeamNumber'] = $t;

//Matches Played
$ret['dPlayed'] = $played;
$ret['dScouted'] = $scouted;

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
$ret['dAverageScore'] = round($score, 1);

//Averages
if ($scouted > 0) {
    $ret['dAutMiss'] = round(($Aballmiss / $scouted), 1);
    $ret['dAutLow'] = round(($Aballlow / $scouted), 1);
    $ret['dAutOut'] = round(($Aballout / $scouted), 1);
    $ret['dAutIn'] = round(($Aballin / $scouted), 1);
    $ret['dTelMiss'] = round(($Tballmiss / $scouted), 1);
    $ret['dTelLow'] = round(($Tballlow / $scouted), 1);
    $ret['dTelOut'] = round(($Tballout / $scouted), 1);
    $ret['dTelIn'] = round(($Tballin / $scouted), 1);
} else {
    $ret['dAutMiss'] = 0;
    $ret['dAutLow'] = 0;
    $ret['dAutOut'] = 0;
    $ret['dAutIn'] = 0;
    $ret['dTelMiss'] = 0;
    $ret['dTelLow'] = 0;
    $ret['dTelOut'] = 0;
    $ret['dTelIn'] = 0;
}

//Endgame
$ret['dHang'] = $hang; //round(($hang / $played) * 100);
$ret['dPark'] = $park;//round(($park / $played) * 100);
    $ret['played'] = $played;
    $ret['fouls'] = $fouls;
    $ret['defgiv'] = $defgiv;
    $ret['defrec'] = $defrec;
//Hue Multipliers
$ret['Color_Sat'] = 60;
$ret['Color_Light'] = 27;
$ret['Color_autoBalls'] = 16;
$ret['Color_telopBalls'] = 5;
return $ret;
}

function matchdetails($team1, $team2, $team3){

    $bot1_name = teamname($team1);
    $bot2_name = teamname($team2);
    $bot3_name = teamname($team3);
    //echo $bot1_name;
    $deets1 = quickstats(teamdetails($team1));
    $deets2 = quickstats(teamdetails($team2));
    $deets3 = quickstats(teamdetails($team3));
    //print_r(teamdetails($team1));
    /*[dPlayed] =&gt; 9
    [dScouted] =&gt; 0
    [dAverageScore] =&gt; 2.2
    [dAutMiss] =&gt; 0
    [dAutLow] =&gt; 0
    [dAutOut] =&gt; 0
    [dAutIn] =&gt; 0
    [dTelMiss] =&gt; 0
    [dTelLow] =&gt; 0
    [dTelOut] =&gt; 0
    [dTelIn] =&gt; 0
    [dHang] =&gt; 0
    [dPark] =&gt; 44
    [Color_Sat] =&gt; 60
    [Color_Light] =&gt; 27
    [Color_autoBalls] =&gt; 16
    [Color_telopBalls] =&gt; 5
*/

    echo '<div class="panel">';
    echo '<div class="teamdetail">';
    echo '<div class="teamnumber">' .$team1. ' <div class="teamname"> ' . $bot1_name. '</div></div>';
    echo '<div class="points">' .$deets1['dAverageScore']. '<div class="pointssub">Points</div>'.$deets1['fouls'].'<div class="pointssub">Fouls</div></div>';
    gfx_ballscore(
        $deets1['dAutMiss'],
        $deets1['dAutLow'],
        $deets1['dAutOut'],
        $deets1['dAutIn'],
        ($deets1['dAutLow']+($deets1['dAutOut']*2)+($deets1['dAutIn']*3))*2,
        'Aut');
    //Teleop
    gfx_ballscore(
        $deets1['dTelMiss'],
        $deets1['dTelLow'],
        $deets1['dTelOut'],
        $deets1['dTelIn'],
        ($deets1['dTelLow']+($deets1['dTelOut']*2)+($deets1['dTelIn']*3)),
        'Tel');
    //Total
    gfx_ballscore(
        $deets1['dTelMiss']+$deets1['dAutMiss'],
        $deets1['dTelLow']+$deets1['dAutLow'],
        $deets1['dTelOut']+$deets1['dAutOut'],
        $deets1['dTelIn']+$deets1['dAutIn'],
        (($deets1['dTelLow']+($deets1['dTelOut']*2)+($deets1['dTelIn']*3))*2)+($deets1['dTelLow']+($deets1['dTelOut']*2)+($deets1['dTelIn']*3)),
        'Gam');
    gfx_defense($deets1['defgiv'],$deets1['defrec']);
    echo '<div class="hang">' .$deets1['dHang']. '<span class="outof"> / '.$deets1['played'].'</span><div class="hangsub">Hang</div>'.$deets1['dPark'].'<span class="outof"> / '.$deets1['played'].'</span><div class="hangsub">Park</div></div>';


    echo"</div>";//end of bot

    echo '<div class="teamdetail">';
    echo '<div class="teamnumber">' .$team2. ' <div class="teamname"> ' . $bot2_name. '</div></div>';
    echo '<div class="points">' .$deets2['dAverageScore']. '<div class="pointssub">Points</div>'.$deets2['fouls'].'<div class="pointssub">Fouls</div></div>';
    gfx_ballscore(
        $deets2['dAutMiss'],
        $deets2['dAutLow'],
        $deets2['dAutOut'],
        $deets2['dAutIn'],
        ($deets2['dAutLow']+($deets2['dAutOut']*2)+($deets2['dAutIn']*3))*2,
        'Aut');
    //Teleop
    gfx_ballscore(
        $deets2['dTelMiss'],
        $deets2['dTelLow'],
        $deets2['dTelOut'],
        $deets2['dTelIn'],
        ($deets2['dTelLow']+($deets2['dTelOut']*2)+($deets2['dTelIn']*3)),
        'Tel');
    //Total
    gfx_ballscore(
        $deets2['dTelMiss']+$deets2['dAutMiss'],
        $deets2['dTelLow']+$deets2['dAutLow'],
        $deets2['dTelOut']+$deets2['dAutOut'],
        $deets2['dTelIn']+$deets2['dAutIn'],
        (($deets2['dTelLow']+($deets2['dTelOut']*2)+($deets2['dTelIn']*3))*2)+($deets2['dTelLow']+($deets2['dTelOut']*2)+($deets2['dTelIn']*3)),
        'Gam');

    gfx_defense($deets2['defgiv'],$deets2['defrec']);
    echo '<div class="hang">' .$deets2['dHang']. '<span class="outof"> / '.$deets2['played'].'</span><div class="hangsub">Hang</div>'.$deets2['dPark'].'<span class="outof"> / '.$deets2['played'].'</span><div class="hangsub">Park</div></div>';

    echo"</div>";//end of bot


    echo '<div class="teamdetail">';
    echo '<div class="teamnumber">' .$team3. ' <div class="teamname"> ' . $bot3_name. '</div></div>';
    echo '<div class="points">' .$deets3['dAverageScore']. '<div class="pointssub">Points</div>'.$deets3['fouls'].'<div class="pointssub">Fouls</div></div>';
    gfx_ballscore(
        $deets3['dAutMiss'],
        $deets3['dAutLow'],
        $deets3['dAutOut'],
        $deets3['dAutIn'],
        ($deets3['dAutLow']+($deets3['dAutOut']*2)+($deets3['dAutIn']*3))*2,
        'Aut');
    //Teleop
    gfx_ballscore(
        $deets3['dTelMiss'],
        $deets3['dTelLow'],
        $deets3['dTelOut'],
        $deets3['dTelIn'],
        ($deets3['dTelLow']+($deets3['dTelOut']*2)+($deets3['dTelIn']*3)),
        'Tel');
    //Total
    gfx_ballscore(
        $deets3['dTelMiss']+$deets3['dAutMiss'],
        $deets3['dTelLow']+$deets3['dAutLow'],
        $deets3['dTelOut']+$deets3['dAutOut'],
        $deets3['dTelIn']+$deets3['dAutIn'],
        (($deets3['dTelLow']+($deets3['dTelOut']*2)+($deets3['dTelIn']*3))*2)+($deets3['dTelLow']+($deets3['dTelOut']*2)+($deets3['dTelIn']*3)),
        'Gam');

    gfx_defense($deets3['defgiv'],$deets3['defrec']);
    echo '<div class="hang">' .$deets3['dHang']. '<span class="outof"> / '.$deets3['played'].'</span><div class="hangsub">Hang</div>'.$deets3['dPark'].'<span class="outof"> / '.$deets3['played'].'</span><div class="hangsub">Park</div></div>';
    echo"</div>";//end of bot


    echo "</div>";
}
function schedulerow($teams, $myteam, $played){
    $r1 = ($myteam != $teams['r1'] ? $teams['r1'] : "<span class='mer'>".$teams['r1']."</span>");
    $r2 = ($myteam != $teams['r2'] ? $teams['r2'] : "<span class='mer'>".$teams['r2']."</span>");
    $r3 = ($myteam != $teams['r3'] ? $teams['r3'] : "<span class='mer'>".$teams['r3']."</span>");
    $b1 = ($myteam != $teams['b1'] ? $teams['b1'] : "<span class='meb'>".$teams['b1']."</span>");
    $b2 = ($myteam != $teams['b2'] ? $teams['b2'] : "<span class='meb'>".$teams['b2']."</span>");
    $b3 = ($myteam != $teams['b3'] ? $teams['b3'] : "<span class='meb'>".$teams['b3']."</span>");
    $time = date('G:i',strtotime($teams['Meta']['time']) + 60*60);
    $playstat = ($played ? "played" : "notplayed");
    print ("<div class='match  ".$playstat."'><div class='matchtitle'>".$teams['Meta']['name']." <span class='time'>".$time."</span></div>");
    print ("<div class='redalliance'><div class='red'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['r1']."'>".$r1."</a></div>");
    print ("<div class='red'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['r2']."'>".$r2."</a></div>");
    print ("<div class='red'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['r3']."'>".$r3."</a></div><i class=\"gg-add accordion\"></i>");
    matchdetails($teams['r1'],$teams['r2'],$teams['r3']);
    print ("</div>");
    print ("<div class='bluealliance'><div class='blue'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['b1']."'>".$b1."</a></div>");
    print ("<div class='blue'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['b2']."'>".$b2."</a></div>");
    print ("<div class='blue'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['b3']."'>".$b3."</a></div><i class=\"gg-add accordion\"></i>");
    matchdetails($teams['b1'],$teams['b2'],$teams['b3']);
    print ("</div></div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="google-signin-client_id" content="<?php echo $googleDevKey; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://css.gg/add.css' rel='stylesheet'>
    <meta charset="utf-8">
    <title><?php echo $pagetitle; ?> - FRC Scouting</title>
    <style>
        html, body{
            margin: 0px;
            padding: 0px;
            font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #232323;
        }
        .outof{
            color: #878787;
            font-size: 16px;
        }
        #topbar {
            display: block;
            top: 0px;
            left: 0px;
            right: 0px;
            padding: 10px;
            text-align: center;
            background-color: #3d3d3d;
            color: #efefef;
            border-top: 2px solid;
            border-top-color: <?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
            font-size: large;
        }
        #navbar{
            display: block;
            top: 0px;
            left: 0px;
            right: 0px;
            padding: 6px;
            text-align: center;
            background-color: rgb(128,188,0);
            color: #000;
            border-bottom: 2px solid;
            border-bottom-color: <?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
        }
        #navbar a{
            color: #232323;
        }
        .link{
            color:<?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
        }
        a:hover{
            color:<?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
        }
        .table .table{
            background-color: #222;
        }
        .match{
            display: block;top: 0px;
            left: 0px;
            right: 0px;
            padding: 10px;
            text-align: center;
            background-color: #3d3d3d;
            color: #efefef;
            border-top: 2px solid;
            border-top-color: <?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
            font-size: large;
        }
        .redalliance{
            display: block;
            top: 0px;
            left: 0px;
            right: 0px;
            padding: 7px 3px 5px 3px;
            text-align: center;
            background-color: #B32222;
            color: #bcbcbc;
            border-top: 2px solid;
            border-top-color: #ff4444;
            font-size: large;
        }
        .red a:link, .red a:visited, .red a:active {
            color: #ffD7D7;
            text-decoration: none;
        }
        .red, .blue{
            display: inline-block;
            width: 100px;
        }
        .bluealliance{
            display: block;
            top: 0px;
            left: 0px;
            right: 0px;
            padding: 7px 3px 5px 3px;
            text-align: center;
            background-color: #2222B3;
            color: #bcbcbc;
            border-top: 2px solid;
            border-top-color: #4444ff;
            font-size: large;
        }
        .blue a:link, .blue a:visited, .blue a:active {
            color: #D7D7ff;
            text-decoration: none;
        }

        .meb {
            background-color: #01004C;
            display: block;
            border-radius: 4px;
            padding: 2px;
        }
        .mer {
            background-color: #4C0001;
            display: block;
            border-radius: 4px;
            padding: 2px;
        }
        .match.played{
            background-color: #232323;
            display: none;
        }

        /* Style the buttons that are used to open and close the accordion panel */
        .accordion {
            display: inline-block;
            transition: 0.4s;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active, .accordion:hover {
            background-color: #ccc;
        }

        /* Style the accordion panel. Note: hidden by default */
        .panel {
            /*padding: 0 18px;*/
            background-color: #232323;
            display: none;
            overflow: hidden;
        }

        .points{
            display: inline-block;
            position: relative;
            left: 00px;
            top: 4px;
            vertical-align: top;
            font-size: 25px;
            color: #efefef;
            font-weight: 400;
            line-height: 1.5;
            text-align: left;
            width: 70px;
        }
        .pointssub{
            display: block;
            position: relative;
            top: -13px;
            left: 00px;
            vertical-align: top;
            font-size: 15px;
            color: #4d4d4d;
            font-variant: all-petite-caps;
            padding: 0px;
            height: 0px;
        }
        .hang{
            display: inline-block;
            position: relative;
            left: 00px;
            top: 4px;
            vertical-align: top;
            font-size: 25px;
            color: #efefef;
            font-weight: 400;
            line-height: 1.5;
            text-align: right;
            width: 70px;
        }
        .hangsub{
            display: block;
            position: relative;
            top: -13px;
            left: 00px;
            vertical-align: top;
            font-size: 15px;
            color: #4d4d4d;
            font-variant: all-petite-caps;
            padding: 0px;
            height: 0px;
        }
        .time{

            display: inline-block;
            left: 4px;
            font-size: 20px;
            color: #777;
            font-variant: all-petite-caps;
            padding: 0px;
        }
        .matchtitle{

            display: block;
            position: relative;
            left: 0px;
            padding-left: 0px;
            top: 4px;
            margin-bottom: 3px;
            padding-bottom: 0px;
            border-bottom: 1px solid #444444;
            vertical-align: top;
            font-size: 27px;
            color: #efefef;
            font-weight: 400;
            line-height: 1;
            text-align: left;
        }
        .teamnumber{
            display: block;
            position: relative;
            left: 0px;
            padding-left: 10px;
            top: 4px;
            margin-bottom: 3px;
            padding-bottom: 0px;
            border-bottom: 1px solid #444444;
            vertical-align: top;
            font-size: 27px;
            color: #efefef;
            font-weight: 400;
            line-height: 1;
            text-align: left;
        }
        .teamname{
            display: inline-block;
            left: 4px;
            font-size: 20px;
            color: #666;
            font-variant: all-petite-caps;
            padding: 0px;

        }
        .teamdetail{
            border-bottom: 2px solid rgb(128,188,0);
            width: 100%;
            margin: 0px;
        }
    </style>
</head>
<body>
<div id="topbar">IBOTS Scouting - <strong><?php echo $pagetitle ?></strong>




</div>
<div id="navbar">
    <a href="index.php">Match Schedule</a> -
    <a href="scoutselect.php">Scout Input</a> -
    <a href="eventstats.php">Event Stats</a>
</div>
<div id="mymatches">
    <?php
    foreach($schedule->getMatchList() as $match){
        $teams = $schedule->getTeamsInMatch($match['MatchID']);
        //print_r($teams);
        $played =0;
        //SELECT * FROM `2019_gamepieces` ORDER BY `match_robot_id` ASC
        $sql = "SELECT * FROM `2020_TBA` WHERE `match_ID` = :mid;";
        $statement = $db->prepare($sql);
        $statement->bindValue(":mid", $match['MatchID']);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
            $played = 1;
        }
        if ($myteam == $teams['r1'] || $myteam == $teams['r2'] || $myteam == $teams['r3'] || $myteam == $teams['b1'] || $myteam == $teams['b2'] || $myteam == $teams['b3']){
            schedulerow($teams, $myteam, $played);
        }
    }
    ?>


</div>
<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            /* Toggle between adding and removing the "active" class,
            to highlight the button that controls the panel */
            this.classList.toggle("active");
            /* Toggle between hiding and showing the active panel */
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
</script>