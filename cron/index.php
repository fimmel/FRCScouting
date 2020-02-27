<?php
$myteam = $_GET['team'];
if($_GET['team'] == ""){
    $myteam = 4041;
}
include('../prod/backend/db.php');
include('../prod/backend/2020botclass.php');
include('../prod/backend/functions.php'); //Global functions
include('../prod/backend/graphics.php'); //Graphic Icon functions

$schedule = new matchschedule($db, $ev_current);
$teamlist = $schedule->getTeamList();
$teamsched = $schedule->getMatchRobotIDsTeam($team);
$pagetitle = "Utilities";
include("../prod/head.php");
?>
<h1>FRC Scouting Utilities</h1><ul>
    <l1>
        <a href="scheduleupdate.php">Update Schedule</a>
    </l1>
    <li>
        <a href="tbamatchupdate.php">Manually Sync TBA Scores</a> This should run every 2 min automatically
    </li>
    <li>
        <a href="tbaupdate.php">Sync Events, Teams, and Teams at Events</a>  This is only needed once.
    </li>
</ul>




<?php
include('../prod/footer.php');
?>
