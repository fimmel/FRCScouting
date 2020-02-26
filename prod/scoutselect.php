<?php
$bmid = $_GET['bmid'];

include('backend/db.php');
include('backend/2019botclass.php');

$cursched = new matchschedule($db, $ev_current);


//print_r($cursched->getMatchList());

$pagetitle = "Select Match to Scout";
include ("header.php");
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
		function beenscouted($db, $bmid){
			$scouted = "";
			$sql = "SELECT * FROM `2020_Submission` WHERE `BM_ID` = :mid;";
			$statement = $db->prepare($sql);
			$statement->bindValue(":mid", $bmid);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach ($result as $row){
				$scouted = " scouted";
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
	
	
	print ("<td class='red".beenscouted($db,$teams['r1mbid'])."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['r1mbid']."'>".$teams['r1']."</a></td>");
	print ("<td class='red".beenscouted($db,$teams['r2mbid'])."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['r2mbid']."'>".$teams['r2']."</a></td>");
	print ("<td class='red".beenscouted($db,$teams['r3mbid'])."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['r3mbid']."'>".$teams['r3']."</a></td>");
	print ("<td class='blue".beenscouted($db,$teams['b1mbid'])."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['b1mbid']."'>".$teams['b1']."</a></td>");
	print ("<td class='blue".beenscouted($db,$teams['b2mbid'])."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['b2mbid']."'>".$teams['b2']."</a></td>");
	print ("<td class='blue".beenscouted($db,$teams['b3mbid'])."'><a href='http://frcscouting.net/prod/gamepad.php?bmid=".$teams['b3mbid']."'>".$teams['b3']."</a></td></tr>");
}
	
	?>
		</table>
		
		
	  </div></div></div>
	
<?php include("footer.php"); ?>