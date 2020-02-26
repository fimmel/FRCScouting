<!doctype html>
<?php
include('backend/db.php'); //DB Connection
include('backend/2020botclass.php'); //Class for dealing with TBA
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions

$event = new frcevent($db, $ev_current);

$teamlist = $event->getTeamList();
$pagetitle = 'Event Statistics';

include('head.php'); //Global functions

function teamdetails($teamnumber, $ev_current)
{
    global $db;
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
    $eventarray[$teamnumber]['details'] = teamdetails($teamnumber, $ev_current);
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
                        <br><?php echo teamname($dTeamNumber); ?>
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
