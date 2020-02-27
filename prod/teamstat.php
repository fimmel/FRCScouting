<?php
$team = $_GET['team'];
$colormultiplier = 12; //multiplier for hue

include('backend/db.php');
include('backend/2020botclass.php');
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions
$schedule = new matchschedule($db, $ev_current);
$teamsched = $schedule->getMatchRobotIDsTeam($team);

$pagetitle = ' '.$team.' Statistics';
include('head.php');

$SD = array();
foreach($teamsched as $matchid){
    $extradata = array();
    $extradata['match'] =  $matchid;
    if ($matchid['PosNu']>3){
        $color = "blue";
    }
    else{
        $color = "red";
    }
    $tbadata = array();
    $sql = 'SELECT * FROM `2020_TBA` WHERE `match_ID` = :mid;';
    $statement = $db->prepare($sql);
    $statement->bindValue(":mid", $matchid['MatchID']);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row){
        $tbadata = $row;
    }
    $g_mi = 0;
    $g_lo = $tbadata['autoCellsBottom'];
    $g_ou = $tbadata['autoCellsOuter'];
    $g_in = $tbadata['autoCellsInner'];
    $g_points = $tbadata['autoCellPoints'];
    $extradata['alliance']['autoCellsBottom'] = $tbadata['autoCellsBottom'];
    $extradata['alliance']['autoCellsOuter'] = $tbadata['autoCellsOuter'];
    $extradata['alliance']['autoCellsInner'] = $tbadata['autoCellsInner'];
    $extradata['alliance']['autoCellPoints'] = $tbadata['autoCellPoints'];
    $g_mi = 0;
    $g_lo = $tbadata['teleopCellsBottom'];
    $g_ou = $tbadata['teleopCellsOuter'];
    $g_in = $tbadata['teleopCellsInner'];
    $g_points = $tbadata['teleopCellPoints'];
    $extradata['alliance']['teleopCellsBottom'] = $tbadata['teleopCellsBottom'];
    $extradata['alliance']['teleopCellsOuter'] = $tbadata['teleopCellsOuter'];
    $extradata['alliance']['teleopCellsInner'] = $tbadata['teleopCellsInner'];
    $extradata['alliance']['teleopCellPoints'] = $tbadata['teleopCellPoints'];

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

    $scoutsub = submission($matchid['MRID']);
    $submissioncount = 0;
    foreach($scoutsub as $subm){
        $submissioncount++;
        $scoutname = scoutname($subm['Scout']); //Name Of Scout
        $submission_ID = $subm['ID']; //Submission ID Number
        $submission_Time= $subm['Time']; // Time Of Submission

        $match = matchstats($subm['ID']); //Form data from scout input
        $shots = shotstats($subm['ID']); //Shot Data
        $extradata['sub'][$subm['ID']]['ID'] = $subm['ID'];
        $extradata['sub'][$subm['ID']]['BMID'] = $subm['BM_ID'];
        $extradata['sub'][$subm['ID']]['SID'] = $subm['Scout'];
        $extradata['sub'][$subm['ID']]['Time'] = $subm['Time'];
        $extradata['sub'][$subm['ID']]["name"] = $scoutname;
        $extradata['sub'][$subm['ID']]['match'] = $match[0];
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
        $extradata['sub'][$subm['ID']]['shotset'] = $set;
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



    }
    $extradata['submissioncount'] = $submissioncount;
    $SD[$extradata['match']['MRID']] = $extradata;
}//end match loop
?>
<style>
    .match{
        display: block;
        height: 120px;
        border-bottom: 1px solid #606060;
        margin-bottom: 10px;
    }
    .matchname{
        display: inline-block;
        padding-top: 0px;
        position: absolute;
        font-size: 20px;
        color: #efefef;/*rgb(128,188,0);*/
        font-weight: bold;
    }
    .alliancescore{
        display: inline-block;
        padding-top: 2px;
        position: absolute;
        margin-top: 30px;
    }
    .submissions{
        display: inline-block;
        padding-top: 2px;
        position: absolute;
        left: 280px;
        vertical-align: top;
        height: 120px;
    }
    .scoutsubmission{
        display: block;
        padding-top: 2px;
        height:120px;
        vertical-align: top;
        overflow-x:  hidden;
        overflow-y: hidden;
    }
    .whoscouted{
        display: block;
        position: relative;
        width: 300px;
        top: 0;
        vertical-align: top;
        color: #3a8500;
    }
    .points{
        display: inline-block;
        position: relative;
        top: 10px;
        left: 00px;
        vertical-align: top;
        font-size: 25px;
        color: #efefef;
    }
    .pointssub{
        display: block;
        position: relative;
        top: 0px;
        left: 00px;
        vertical-align: top;
        font-size: 15px;
        color: #4d4d4d;
        font-variant: all-petite-caps;
    }
    .balls{
        display: inline-block;
        position: relative;
        top: 0px;
        left: 00px;
        vertical-align: top;
    }
    .djbooth{
        display: inline-block;
        position: relative;
        top: -20px;
        left: 00px;
        vertical-align: top;
        color: #4d4d4d;
        font-variant: all-petite-caps;
    }
    .hang{
        display: inline-block;
        position: relative;
        top: -20px;
        left: 00px;
        vertical-align: top;
        color: #4d4d4d;
        font-variant: all-petite-caps;
    }
    .hangstaus{

        color: #efefef;
        font-variant: none;
    }
    .defense{
        display: inline-block;
        position: relative;
        top: 0px;
        left: 00px;
        vertical-align: top;
    }
    .defnotes{
        display: inline-block;
        position: relative;
        top: -20px;
        left: 00px;
        height: 100px;
        width: 200px;
        color: #4d4d4d;
        font-variant: all-petite-caps;

        vertical-align: top;
    }
    .matchnotes{
        display: inline-block;
        position: relative;
        top: -20px;
        left: 00px;
        height: 100px;
        width: 200px;
        vertical-align: top;
        color: #4d4d4d;
        font-variant: all-petite-caps;
    }
    .notes{
        color: #efefef;
        font-variant: none;
    }


</style>

<?php
foreach($SD as  $match){
    if ($match['submissioncount'] == 0){
        $rowspan = 1;
        $matchheight = 125;
    }
    else{
        $rowspan = $match['submissioncount'];

        $matchheight = (120* $match['submissioncount'])+5;
    }
    ?>
    <div class="match" style="height: <?php echo $matchheight;?>px">
        <div class="matchname">
            <?php echo $match['match']['name']; ?>
        </div>
        <div class="alliancescore">
            <?php gfx_ballscore(
                '',
                $match['alliance']['autoCellsBottom'],
                $match['alliance']['autoCellsOuter'],
                $match['alliance']['autoCellsInner'],
                $match['alliance']['autoCellPoints'],
                "Aut");
            gfx_ballscore(
                '',
                $match['alliance']['teleopCellsBottom'],
                $match['alliance']['teleopCellsOuter'],
                $match['alliance']['teleopCellsInner'],
                $match['alliance']['teleopCellPoints'],
                "Tel");
            gfx_ballscore(
                '',
                $match['alliance']['teleopCellsBottom']+$match['alliance']['autoCellsBottom'],
                $match['alliance']['teleopCellsOuter']+$match['alliance']['autoCellsOuter'],
                $match['alliance']['teleopCellsInner']+$match['alliance']['autoCellsInner'],
                $match['alliance']['teleopCellPoints']+$match['alliance']['autoCellPoints'],
                "Gam");
            gfx_coathanger(
                $match['alliance']['endgameRungIsLevel'],
                $match['alliance']['endgameRobot1'],
                $match['alliance']['endgameRobot2'],
                $match['alliance']['endgameRobot3'],
                $match['alliance']['mypos']); ?>
        </div>
        <div class = "submissions">
            <?php foreach ($match['sub'] as $sub){ ?>
                <div class="scoutsubmission">

                    <div class="whoscouted">
                        <?php echo $sub['name']; ?> <sup>&commat;</sup>
                        <?php echo date('H:i', strtotime($sub['Time'])); ?>
                    </div>
                    <div class="points">
                        <?php
                        $points =0;
                        foreach($sub['shotset']['a'] as $auto){
                            $ga_mi = $ga_mi + $auto['miss'];
                            $ga_lo = $ga_lo + $auto['low'];
                            $ga_ou = $ga_ou + $auto['outer'];
                            $ga_in = $ga_in + $auto['inner'];
                        }
                        $points += ($ga_lo + ($ga_ou*2) + ($ga_in*3))* 2;
                        foreach($sub['shotset']['t'] as $telop){
                            $gt_mi = $gt_mi + $telop['miss'];
                            $gt_lo = $gt_lo + $telop['low'];
                            $gt_ou = $gt_ou + $telop['outer'];
                            $gt_in = $gt_in + $telop['inner'];
                        }
                        $points += ($gt_lo + ($gt_ou*2) + ($gt_in*3));

                        switch ($sub['match']['sd_eg_hang']) {
                            case 1:
                                //no points
                                break;
                            case 2:
                            case 3:
                                $points += 5;
                                break;
                            case 4:
                                $points += 25;
                                break;
                        }
                        switch ($sub['match']['sd_eg_hang_level']) {
                            case 1:
                            case 2:
                                //no points
                                break;
                            case 3:
                                $points += 15;
                                break;
                        }
                        switch ($sub['match']['sd_cw_rotation']) {
                            case 1;// 1-No Attempt
                                break;
                            case 2;// 2-Attempt
                                break;
                            case 3;// 3-Complete
                                $points += 10;
                                break;
                        }
                        switch ($sub['match']['sd_cw_position']) {
                            case 1;// 1-No Attempt
                                break;
                            case 2;// 2-Attempt
                                break;
                            case 3;// 3-Complete
                                $points += 20;
                                break;
                        }
                        echo $points
                        ?>
                        <div class="pointssub">Points</div>
                    </div>
                    <div class="balls">
                        <?php
                        //Total Autonomous Balls Scored
                        $ga_mi = 0;
                        $ga_lo = 0;
                        $ga_ou = 0;
                        $ga_in = 0;
                        foreach($sub['shotset']['a'] as $auto){
                            $ga_mi = $ga_mi + $auto['miss'];
                            $ga_lo = $ga_lo + $auto['low'];
                            $ga_ou = $ga_ou + $auto['outer'];
                            $ga_in = $ga_in + $auto['inner'];
                        }
                        $ga_points = ($ga_lo + ($ga_ou*2) + ($ga_in*3))* 2;
                        //Total Teleop Balls Scored
                        $gt_mi = 0;
                        $gt_lo = 0;
                        $gt_ou = 0;
                        $gt_in = 0;
                        foreach($sub['shotset']['t'] as $telop){
                            $gt_mi = $gt_mi + $telop['miss'];
                            $gt_lo = $gt_lo + $telop['low'];
                            $gt_ou = $gt_ou + $telop['outer'];
                            $gt_in = $gt_in + $telop['inner'];
                        }
                        $gt_points = ($gt_lo + ($gt_ou*2) + ($gt_in*3));
                        //Autonomous
                        gfx_ballscore(
                            $ga_mi,
                            $ga_lo,
                            $ga_ou,
                            $ga_in,
                            $ga_points,
                            'Aut');
                        //Teleop
                        gfx_ballscore(
                            $gt_mi,
                            $gt_lo,
                            $gt_ou,
                            $gt_in,
                            $gt_points,
                            'Tel');
                        //Total
                        gfx_ballscore(
                            $ga_mi + $gt_mi,
                            $ga_lo + $gt_lo,
                            $ga_ou + $gt_ou,
                            $ga_in + $gt_in,
                            $ga_points + $gt_points,
                            'Gam');
                        ?>
                    </div>

                    <div class="hang">
                        <?php
                        switch ($sub['match']['sd_eg_hang']) {
                            case 1:
                                print 'N/A';
                                //leave gray
                                break;
                            case 2:
                                print 'Park';
                                $points = 5;
                                break;
                            case 3:
                                print 'Atmp';
                                $points = 5;

                                break;
                            case 4:
                                print 'Hang';
                                $points = 25;

                                break;
                        }
                        ?>
                        <div class="hangstaus">
                            <?php gfx_climb($sub['match']['sd_eg_hang'],$sub['match']['sd_eg_hang_level']);
                            //echo $sub['match']['sd_eg_hang']; //1-No Attempt, 2-Parked, 3-Attempted, 4-Successful

                            ?>
                            <?php //echo $sub['match']['sd_eg_hang_level']; //1-No Chance, 2-Attempt, 3-Successful?>
                            <?php //echo $sub['match']['sd_eg_hang_bots']; //Number of Bots on the bar?>
                        </div>
                    </div>
                    <div class="defense">
                        <?php
                        gfx_defencegiven($sub['match']['sd_def_giving_rating']);
                        ?>
                        <?php
                        gfx_defencerecieved($sub['match']['sd_def_receiving_rating']);
                        ?>
                    </div>
                    <div class="defnotes">
                        Defense Notes<br>
                        <div class="notes">
                            <?php echo $sub['match']['sd_def_notes']; //Notes on defense?>
                        </div>
                    </div>
                    <div class="djbooth">
                        DJ Booth
                        <br>
                        <?php
                        switch ($sub['match']['sd_cw_rotation']) {
                            case 1;// 1-No Attempt
                                gfx_djbooth(0);
                                break;
                            case 2;// 2-Attempt
                                gfx_djbooth(1);
                                break;
                            case 3;// 3-Complete
                                gfx_djbooth(2);
                                break;
                        }
                        switch ($sub['match']['sd_cw_position']) {
                            case 1;// 1-No Attempt
                                gfx_djbooth(3);
                                break;
                            case 2;// 2-Attempt
                                gfx_djbooth(4);
                                break;
                            case 3;// 3-Complete
                                gfx_djbooth(5);
                                break;
                        }
                        ?>
                    </div>



                    <div class="matchnotes">
                        Match Notes:<br>
                        <div class="notes">
                            <?php echo $sub['match']['sd_match_notes']; //Match Notes?>
                        </div>
                    </div>

                    <?php
                    /*
                    echo "<div class=\"auto\">";
                    foreach($sub['shotset']['a'] as $auto){
                    $g_mi = $auto['miss'];
                    $g_lo = $auto['low'];
                    $g_ou = $auto['outer'];
                    $g_in = $auto['inner'];
                    $g_points = ($g_lo + ($g_ou*2) + ($g_in*3))* 2;
                    gfx_ballscore($g_mi,$g_lo,$g_ou,$g_in,$g_points,"A");
                    }
                    echo "</div>";//sets
                    */
                    ?>

                    <?php
                    /*
                    echo "<div class=\"telo\">";
                    foreach($sub['shotset']['t'] as $telop){
                    $g_mi = $telop['miss'];
                    $g_lo = $telop['low'];
                    $g_ou = $telop['outer'];
                    $g_in = $telop['inner'];
                    $g_points = ($g_lo + ($g_ou*2) + ($g_in*3));
                    gfx_ballscore($g_mi,$g_lo,$g_ou,$g_in,$g_points,"T");
                    }
                    */
                    ?>

                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <?php
}
?>

<?php include("footer.php"); ?>