<?php
$bmid = $_GET['bmid'];

include('backend/db.php');
include('backend/2020botclass.php');
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions

$schedule = new matchschedule($db, $ev_current);
$teamlist = $schedule->getTeamList();
$teamsched = $schedule->getMatchRobotIDsTeam($team);



$cursched = new matchschedule($db, $ev_current);


//print_r($cursched->getMatchList());

$pagetitle = "Select Match to Scout";
include("head.php");
?>
<style>
	.red.scouted {
		background-color:#772222;
	}
	.blue.scouted {
		background-color:#222277;
	}
</style>
<div class="container">
  <div class="row">
    <div class="col-8">
		<h1>Select who you are scouting</h1>
	


		<table class="schedule">
			<tr><th class='gen'>Match</th><th class='red'>Red 1</th><th class='red'>Red 2</th><th class='red'>Red 3</th><th class='blue'>Blue 1</th><th class='blue'>Blue 2</th><th class='blue'>Blue 3</th></tr>
	<?php
    function beenscouted($bmid, $color){
        global $db;
        $scouted = "";
        $sql = "SELECT * FROM `2020_Submission` WHERE `BM_ID` = :mid;";
        $statement = $db->prepare($sql);
        $statement->bindValue(":mid", $bmid);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach ($result as $row){
            if($color == "r"){
                $scouted = " mer";
            }
            if($color == "b"){
                $scouted = " meb";
            }
        }
        return $scouted;
    }
    foreach($cursched->getMatchList() as $match){
	//print_r($match);
$teams = $cursched->getTeamsInMatch($match['MatchID']);

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
	
	//print_r($teams);
	$time = date('G:i',strtotime($teams['Meta']['time']) + 60*60);
	$playstat = ($played ? "played" : "notplayed");
	print ("<tr><td class='".$playstat."'>".$teams['Meta']['name']." <span class='time'>".$time."</span></td>");
	
	//print ("<tr><td>".$teams['Meta']['name']."</td>");
	
	
	print ("<td class='red'><span class='".beenscouted($teams['r1mbid'],"r")."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['r1mbid']."'>".$teams['r1']."</a></span></td>");
	print ("<td class='red'><span class='".beenscouted($teams['r2mbid'],"r")."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['r2mbid']."'>".$teams['r2']."</a></span></td>");
	print ("<td class='red'><span class='".beenscouted($teams['r3mbid'],"r")."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['r3mbid']."'>".$teams['r3']."</a></span></td>");
	print ("<td class='blue'><span class='".beenscouted($teams['b1mbid'],"b")."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['b1mbid']."'>".$teams['b1']."</a></span></td>");
	print ("<td class='blue'><span class='".beenscouted($teams['b2mbid'],"b")."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['b2mbid']."'>".$teams['b2']."</a></span></td>");
	print ("<td class='blue'><span class='".beenscouted($teams['b3mbid'],"b")."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['b3mbid']."'>".$teams['b3']."</a></span></td></tr>");
}
	
	?>
		</table>
		
		
	  </div></div></div>
	
<?php include("footer.php"); ?>