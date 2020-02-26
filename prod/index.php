<?php
$myteam = $_GET['team'];
if($_GET['team'] == ""){
	$myteam = 4041;
}
include('backend/db.php');
include('backend/2020botclass.php');
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions

$schedule = new matchschedule($db, $ev_current);
$teamlist = $schedule->getTeamList();
$teamsched = $schedule->getMatchRobotIDsTeam($team);
$pagetitle = "Match Schedule";
include("head.php");
function schedulerow($teams, $myteam, $played){
    $r1 = ($myteam != $teams['r1'] ? $teams['r1'] : "<span class='mer'>".$teams['r1']."</span>");
    $r2 = ($myteam != $teams['r2'] ? $teams['r2'] : "<span class='mer'>".$teams['r2']."</span>");
    $r3 = ($myteam != $teams['r3'] ? $teams['r3'] : "<span class='mer'>".$teams['r3']."</span>");
    $b1 = ($myteam != $teams['b1'] ? $teams['b1'] : "<span class='meb'>".$teams['b1']."</span>");
    $b2 = ($myteam != $teams['b2'] ? $teams['b2'] : "<span class='meb'>".$teams['b2']."</span>");
    $b3 = ($myteam != $teams['b3'] ? $teams['b3'] : "<span class='meb'>".$teams['b3']."</span>");
    $time = date('G:i',strtotime($teams['Meta']['time']) + 60*60);
    $playstat = ($played ? "played" : "notplayed");
    print ("<tr><td class='".$playstat."'>".$teams['Meta']['name']." <span class='time'>".$time."</span></td>");
    print ("<td class='red'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['r1']."'>".$r1."</a></td>");
    print ("<td class='red'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['r2']."'>".$r2."</a></td>");
    print ("<td class='red'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['r3']."'>".$r3."</a></td>");
    print ("<td class='blue'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['b1']."'>".$b1."</a></td>");
    print ("<td class='blue'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['b2']."'>".$b2."</a></td>");
    print ("<td class='blue'><a href='https://frcscouting.net/prod/teamstat.php?team=".$teams['b3']."'>".$b3."</a></td></tr>");
}

?>

<div class="container">
    <div class="row">
        <div class="col-3">
            <h1>Team List</h1>
            <table class="teamlist" >
                <?php
                //$teamlist
                foreach($teamlist as $team){
                    $name = $schedule->getTeamName($team);
                    ?>
                    <tr>
                        <td>
                            <a href='http://frcscouting.net/prod/teamstat.php?team=<?php echo $team ?>' style="display:block; width:100%;"><?php echo $team ?></a>
                        </td>
                        <td>
                            <a href='http://frcscouting.net/prod/teamstat.php?team=<?php echo $team ?>' style="display:block; width:100%;"><?php echo $name ?></a>
                        </td>
                    </tr>
                <?php } //end of team list loop ?>
            </table>
        </div>
        <div class="col">
            <h1>Matches <?php echo $myteam; ?> is playing</h1>
            <table class="schedule">
                <tr><th class='gen'>Match</th><th class='red'>Red 1</th><th class='red'>Red 2</th><th class='red'>Red 3</th><th class='blue'>Blue 1</th><th class='blue'>Blue 2</th><th class='blue'>Blue 3</th></tr>
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
            </table>
        </div>
        <div class="col">
            <h1>Full Match Schedule</h1>
            <table class="schedule">
                <tr>
                    <th class='gen'>Match</th>
                    <th class='red'>Red 1</th>
                    <th class='red'>Red 2</th>
                    <th class='red'>Red 3</th>
                    <th class='blue'>Blue 1</th>
                    <th class='blue'>Blue 2</th>
                    <th class='blue'>Blue 3</th>
                </tr>
                <?php
                foreach($schedule->getMatchList() as $match){
                    $teams = $schedule->getTeamsInMatch($match['MatchID']);
                    $played = FALSE;
                    $sql = "SELECT * FROM `2020_TBA` WHERE `match_ID` = :mid;";
                    $statement = $db->prepare($sql);
                    $statement->bindValue(":mid", $match['MatchID']);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach ($result as $row){
                        $played = TRUE;
                    }
                    schedulerow($teams, $myteam, $played);
                }
                ?>
            </table>
        </div>

    </div>
</div>

		
		
		
<?php include("footer.php"); ?>	
