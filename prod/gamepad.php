<?php
//Database handling stuff
include('backend/db.php');
include('backend/2020botclass.php');
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions

$schedule = new matchschedule($db, $ev_current);
$teamlist = $schedule->getTeamList();
$teamsched = $schedule->getMatchRobotIDsTeam($team);
$pagetitle = 'Match Schedule';


//Get Botmatch ID from URL
$bmid = $_GET['bmid'];
$botmatchid = $bmid;

//Find the details about our team from the match_robot table
$sql1 = 'SELECT * FROM `match_robot` WHERE `id` = :bmid;';
	$statement1 = $db->prepare($sql1);
	$statement1->bindValue(':bmid', $bmid);
	$statement1->execute();
	$result1 = $statement1->fetchAll();
	foreach ($result1 as $row1){
		$bmidsql = $row1;
	}
//Assign that data to some variables for later
$teamnumber = $bmidsql['team_id'];
$matchid = $bmidsql['match_id'];
$position = $bmidsql['position'];


//Lookup what type of and number of match we are scouting
$sql2 = 'SELECT * FROM `matches` WHERE id = :id;';
	$statement2 = $db->prepare($sql2);
	$statement2->bindValue(':id', $matchid);
	$statement2->execute();
	$sqlresult2 = $statement2->fetchAll();
	foreach ($sqlresult2 as $match){
		switch ($match['level']) {
			case 2:
				$fullname = 'Qualification';
				$shortname = "Q";
				$matchname = "Qual " . $match['match_num'];
				break;
			case 3:
				$fullname = "Quarter Final";
				$shortname = "QF";
				$matchname = "Quarter ". $match['set_num'] . " M " . $match['match_num'];
				break;
			case 4:
				$fullname = "Semi Final";
				$shortname = "SF";
				$matchname = "Semi ". $match['set_num'] . " M " . $match['match_num'];
				break;
			case 5:
				$fullname = "Final";
				$shortname = "FF";
				$matchname = "Final ". $match['set_num'] . " M " . $match['match_num'];
				break;
			default:
				$fullname = "Practice";
				$shortname = "P";
				$matchname = "Prac " . $match['match_num'];
				break;
		}

	}

//Translate position number to human readable
switch ($bmidsql['position']) {
	case 1:
		$botpos = "Red 1";
		$color = "red";
		break;
	case 2:
		$botpos = "Red 2";
		$color = "red";
		break;
	case 3:
		$botpos = "Red 3";
		$color = "red";
		break;
	case 4:
		$botpos = "Blue 1";
		$color = "blue";
		break;
	case 5:
		$botpos = "Blue 2";
		$color = "blue";
		break;
	case 6:
		$botpos = "Blue 3";
		$color = "blue";
		break;
	default:

		break;
}

//send the client some debug data
echo "<!-- \n";
echo 'Team Number: ' .$teamnumber."\n";
echo "Match ID: ".$matchid."\n";
echo "Bot Match ID: ".$botmatchid."\n";
echo "Match Name Short: ".$shortname."\n";
echo "Match Name Med: ".$matchname."\n";
echo "Match Name Full: ".$fullname."\n";
echo "Position #: ".$position."\n";
echo "Position Name: ".$botpos."\n";
echo "Alliance Color: ".$color."\n";
echo "-->";

?>

<?php 
$pagetitle = "Scouting Team ".$teamnumber." playing ".$matchname." (".$botpos.")";
include("head.php"); ?>

<style>
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
    .form-control:disabled{
        background-color: #4D4D4D;
    }
    .custom-select:disabled{
        
        background-color: #4D4D4D;
    }
</style>
<div class="table" id="actionbar">
	<div id="status">
		<div id="gamepadstatus" class="disconnected">Gamepad Disconnected</div>
		<div id="matchperiod" class="prematch" onClick="matchbegin()">Pre Match<br><img src="images/controller/360_Start.png" width="40px" height="40px" /></div>
		<div id="act_reload" class="action" onClick="scout_reload()">Reload<br><img src="images/controller/360_LB.png" width="40px" height="40px" /><img src="images/controller/360_RB.png" width="40px" height="40px" /> <img src="images/keyboard/Space1.png" width="40px" height="12px" /></div>
		<div id="act_miss" class="action" onClick="scout_miss()">Miss!<br><img src="images/controller/360_B.png" width="40px" height="40px" /> <img src="images/keyboard/A1.png" width="40px" height="40px" /></div>	
		<div id="act_low" class="action" onClick="scout_low()">Low Goal<br><img src="images/controller/360_A.png" width="40px" height="40px" /> <img src="images/keyboard/S1.png" width="40px" height="40px" /></div>	
		<div id="act_outer" class="action" onClick="scout_outer()">Outer Goal<br><img src="images/controller/360_X.png" width="40px" height="40px" /> <img src="images/keyboard/D1.png" width="40px" height="40px" /></div>	
		<div id="act_inner" class="action" onClick="scout_inner()">Inner Goal<br><img src="images/controller/360_Y.png" width="40px" height="40px" /> <img src="images/keyboard/F1.png" width="40px" height="40px" /></div>	
	</div>
</div>
<div class="table"><div class="tr">
	<div id="gameplay" class="td">
		<h2>Game Actions</h2>
		<div id="box">
			<ul id="simpleList" class="list-group">
				<li class="list-group-item prematch" time="<?php echo (time()*1000);?>" action="pre">Scouting Start</li>
			</ul>
		</div>
	</div>
	
	<div id="postgame" class="td">
		<h2>Scouting Data</h2>
		<form id="gamedata">
			<div class="table">
				<div class="tr">
					<div class="td">
						<h3>Control Panel</h3>
						<div class="form-group">
							<label for="formGroupExampleInput">Rotation Control</label>
							<select class="custom-select mr-sm-2" id="sd_cw_rotation" name="sd_cw_rotation">
								<option value="1">Not Attempted</option>
								<option value="2">Attempted</option>
								<option value="3">Sucessful</option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
							  Stage 1 - Spin color wheel
							</small>
						</div>
						<div class="form-group">
							<label for="formGroupExampleInput">Position Control</label>
							<select class="custom-select mr-sm-2" id="sd_cw_position" name="sd_cw_position">
								<option value="1">Not Attempted</option>
								<option value="2">Attempted</option>
								<option value="3">Sucessful</option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
							  Stage 2 - Position on certain color
							</small>
						</div>
					</div>
					<div class="td ">
						<h3>Climbing</h3>
						<div class="form-group">
							<label for="formGroupExampleInput">Hanging</label>
							<select class="custom-select mr-sm-2" id="sd_eg_hang" name="sd_eg_hang">
								<option value="1" class="sel_red">Not Attempted</option>
								<option value="2" class="sel_orange">Parked</option>
								<option value="3" class="sel_yellow">Attempted</option>
								<option value="4" class="sel_green">Sucessful</option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
							  Did this climb?
							</small>
						</div>
						<div class="form-group">
							<label for="formGroupExampleInput">Level</label>
							<select class="custom-select mr-sm-2" id="sd_eg_hang_level" name="sd_eg_hang_level">
								<option value="1">No Chance</option>
								<option value="2">Attempt</option>
								<option value="3">Sucessful</option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
							  Was the bar level?
							</small>
						</div>
						<div class="form-group">
							<label for="formGroupExampleInput">Number of Bots on Bar</label>
							<select class="custom-select mr-sm-2" id="sd_eg_hang_bots" name="sd_eg_hang_bots">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
							  How many bots climbed?
							</small>
						</div>
					</div>
					<div class="td">
						<h3>Defense</h3>
						<div class="form-group">
							<label for="formGroupExampleInput">Being a defender</label>
							<select class="custom-select mr-sm-2" id="sd_def_giving_rating" name="sd_def_giving_rating">
								<option value="1" class="sel_grey">Not Played</option>
								<option value="2" class="sel_red">1 - There was an attempt</option>
								<option value="3" class="sel_orange">2 - Effective 10%-29% of time</option>
								<option value="4" class="sel_yellow">3 - Effective 30%-49% of time</option>
								<option value="5" class="sel_gror">4 - Effective 50%-79% of time</option>
								<option value="6" class="sel_green">5 - Effective 80%-100% of time</option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
								Rate this bots ability to <strong>be a defender</strong>
							</small>
						</div><div class="form-group">
							<label for="formGroupExampleInput">Being defended against</label>
							<select class="custom-select mr-sm-2" id="sd_def_receiving_rating" name="sd_def_receiving_rating">
								<option value="1" class="sel_grey">No one touched them</option>
								<option value="2" class="sel_red">1 - Push around / Shut down 80%-100% of time</option>
								<option value="3" class="sel_orange">2 - Push around / Shut down 50%-79% of time</option>
								<option value="4" class="sel_yellow">3 - Push around / Shut down 30%-49% of time</option>
								<option value="5" class="sel_gror">4 - Push around / Shut down 10%-29% of time</option>
								<option value="6" class="sel_green">5 - Unstoppable </option>
							</select>
							<small id="passwordHelpInline" class="text-muted">
							  Rate this bots ability to <strong>work around being defended</strong>
							</small>
						</div>
						<div class="form-group">
							<label for="formGroupExampleInput">Defense Notes</label>
							<textarea rows="3" cols="30" class="form-control" id="sd_def_notes" name="sd_def_notes" ></textarea>
							<small class="text-muted">
							  Any strategies that worked well or were not effective having to do with defense
							</small>
						</div>
					</div>
					<div class="td">
                        <h3>Fouls</h3>
                        <label for="formGroupExampleInput">Number of Tech Fouls</label>
                        <select class="custom-select mr-sm-2" id="sd_fouls" name="sd_fouls">
                            <option value="0">None</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10 or More</option>
                        </select>
                        <small id="passwordHelpInline" class="text-muted">
                            Number of <strong>TECH Fouls</strong>
                        </small>

						<h3>Notes</h3>
						
						<div class="form-group">
							<label for="formGroupExampleInput">Match & Robot Notes</label>
							<textarea rows="5" cols="30" class="form-control" id="sd_match_notes" name="sd_match_notes" ></textarea>
							<small class="text-muted">
							  Any general notes about the robot, notable mechanisms, patterns or strategies they seem to do well
							</small>
						</div>
					</div>
				</div>
			</div>
		
		
		</form>
		<button id="senddata" class="btn btn-success fullwidth" onclick="formsubmit()">Submit Data</button>
		<div id="submitresult"></div>
	</div>
	</div>
	<script>
    // Simple list
    Sortable.create(simpleList, { /* options */ });
</script>
<script>
	var haveEvents = 'ongamepadconnected' in window;
	var controllers = {};
	var buttonstatus = {};
	var scout_actions = [];
	var shots_taken = 0;
	var matchtimer = 150;
	var matchperiod = 0;
	
	function scout_telop(){
		var d = new Date();
		var n = d.getTime();
		$('ul#simpleList').append('<li class="list-group-item telop" time="'+n+'" action="telop">Telop Begins</li>');
		scout_actions.push(["telop",n,shots_taken]);
		var gamepad = controllers[0];
		if (gamepad && gamepad.vibrationActuator) {
			gamepad.vibrationActuator.playEffect("dual-rumble", {
			  startDelay: 0,
			  duration: 150,
			  weakMagnitude: 1,
			  strongMagnitude: 1
			});
		}
		
	}
	function scout_reload(){
        if (matchperiod == 1 || matchperiod == 2){
            var d = new Date();
            var n = d.getTime();
            if(shots_taken == 0){
                //Dont show reload multiple times
            }else{
                $('ul#simpleList').append('<li class="list-group-item reload" time="'+n+'" action="reload">Reload</li>');
            }
            scout_actions.push(["reload",n,shots_taken]);
            var gamepad = controllers[0];
            if (gamepad && gamepad.vibrationActuator) {
                gamepad.vibrationActuator.playEffect("dual-rumble", {
                  startDelay: 0,
                  duration: 120,
                  weakMagnitude: 0,
                  strongMagnitude: 1
                });
            }
            var elem = document.getElementById('box');
            elem.scrollTop = elem.scrollHeight;
            shots_taken = 0;
        }
	}
	function scout_inner(){
        if (matchperiod == 1 || matchperiod == 2){
            var d = new Date();
            var n = d.getTime();
            if(shots_taken >= 5){
                $('ul#simpleList').append('<li class="list-group-item innergoal" time="'+n+'" action="inner">Inner Scored (Over 5 Limit)</li>');
            }else{
                $('ul#simpleList').append('<li class="list-group-item innergoal" time="'+n+'" action="inner">Inner Scored</li>');
            }
            scout_actions.push(["inner",n,shots_taken]);
            var gamepad = controllers[0];
            if (gamepad && gamepad.vibrationActuator) {
                gamepad.vibrationActuator.playEffect("dual-rumble", {
                  startDelay: 0,
                  duration: 75,
                  weakMagnitude: 1,
                  strongMagnitude: 0
                });
            }
            var elem = document.getElementById('box');
            elem.scrollTop = elem.scrollHeight;
            shots_taken++;
        }
	}
	function scout_outer(){
        if (matchperiod == 1 || matchperiod == 2){
            var d = new Date();
            var n = d.getTime();
            if(shots_taken >= 5){
                $('ul#simpleList').append('<li class="list-group-item outergoal" time="'+n+'" action="outer">Outer Scored (Over 5 Limit)</li>');
            }else{
                $('ul#simpleList').append('<li class="list-group-item outergoal" time="'+n+'" action="outer">Outer Scored</li>');
            }
            scout_actions.push(["outer",n,shots_taken]);
            var gamepad = controllers[0];
            if (gamepad && gamepad.vibrationActuator) {
                gamepad.vibrationActuator.playEffect("dual-rumble", {
                  startDelay: 0,
                  duration: 75,
                  weakMagnitude: 1,
                  strongMagnitude: 0
                });
            }
            var elem = document.getElementById('box');
            elem.scrollTop = elem.scrollHeight;
            shots_taken++;
        }
	}
	function scout_low(){
        if (matchperiod == 1 || matchperiod == 2){
            var d = new Date();
            var n = d.getTime();
            if(shots_taken >= 5){
                $('ul#simpleList').append('<li class="list-group-item lowgoal" time="'+n+'" action="low">Lower Scored (Over 5 Limit)</li>');
            }else{
                $('ul#simpleList').append('<li class="list-group-item lowgoal" time="'+n+'" action="low">Lower Scored</li>');
            }
            scout_actions.push(["low",n,shots_taken]);
            var gamepad = controllers[0];
            if (gamepad && gamepad.vibrationActuator) {
                gamepad.vibrationActuator.playEffect("dual-rumble", {
                  startDelay: 0,
                  duration: 75,
                  weakMagnitude: 1,
                  strongMagnitude: 0
                });
            }
            var elem = document.getElementById('box');
            elem.scrollTop = elem.scrollHeight;
            shots_taken++;
        }
	}
	function scout_miss(){
        if (matchperiod == 1 || matchperiod == 2){
            var d = new Date();
            var n = d.getTime();
            if(shots_taken >= 5){
                $('ul#simpleList').append('<li class="list-group-item miss" time="'+n+'" action="miss">Miss (Over 5 Limit)</li>');
            }else{
                $('ul#simpleList').append('<li class="list-group-item miss" time="'+n+'" action="miss">Miss</li>');
            }
            scout_actions.push(["miss",n,shots_taken]);
            var gamepad = controllers[0];
            if (gamepad && gamepad.vibrationActuator) {
                gamepad.vibrationActuator.playEffect("dual-rumble", {
                  startDelay: 0,
                  duration: 150,
                  weakMagnitude: 1,
                  strongMagnitude: 0
                });
            }
            var elem = document.getElementById('box');
            elem.scrollTop = elem.scrollHeight;
            shots_taken++;
        }
	}
	
	function matchbegin(){
		if (matchperiod == 0){
			var d = new Date();
			var n = d.getTime();
			matchperiod = 1;
			scout_actions.push(["matchstart",n]);
			countdown();
			//setTimeout(function(){ telopbegin(); }, 3000);
			$('ul#simpleList').append('<li class="list-group-item auton" time="'+n+'" action="auton">Game Start (Auton)</li>');
		}
		var elem = document.getElementById('box');
		elem.scrollTop = elem.scrollHeight;
	}
	function countdown(){
		var d = new Date();
		var n = d.getTime();
		
		
		matchtimer--;
		auton = matchtimer-135;
		if(matchtimer > 135){ //auton
			$('#matchperiod').html('Auton ('+ auton +')');
			$("#matchperiod").attr('class', 'auton');
			$("#gamedata :input").prop("disabled", true);
		}
		else if(matchtimer == 135){ 
			$('#matchperiod').html('Telop ('+ matchtimer +')');
			$("#matchperiod").attr('class', 'telop');
			$('ul#simpleList').append('<li class="list-group-item telop" time="'+n+'" action="telop">Telop Begins</li>');
		var elem = document.getElementById('box');
		elem.scrollTop = elem.scrollHeight;
			scout_actions.push(["telop",n]);
			matchperiod = 2;
		}
		else if(matchtimer > 0){ //telop
			$('#matchperiod').html('Telop ('+ matchtimer +')');
			$("#matchperiod").attr('class', 'telop');
		}
		else{ //match over
			$('#matchperiod').html('Match Ended');
			$("#matchperiod").attr('class', 'prematch');
			$('ul#simpleList').append('<li class="list-group-item prematch" time="'+n+'" action="post">Match Over</li>');
			matchperiod = 3;
            
			$("#gamedata :input").prop("disabled", false);
		}
		
		if(matchtimer > 0){
			setTimeout(function(){ countdown(); }, 1000);
		}
		
	}
	async function testVibration() {
		console.log("test");
		var gamepad = controllers[0];
	  if (gamepad && gamepad.vibrationActuator) {
		gamepad.vibrationActuator.playEffect("dual-rumble", {
		  startDelay: 0,
		  duration: 200,
		  weakMagnitude: 0,
		  strongMagnitude: 1.0
		});
		 await new Promise(r => setTimeout(r, 200)); 
		gamepad.vibrationActuator.playEffect("dual-rumble", {
		  startDelay: 0,
		  duration: 300,
		  weakMagnitude: 1,
		  strongMagnitude: 0
		});
			await new Promise(r => setTimeout(r, 300)); 
		gamepad.vibrationActuator.playEffect("dual-rumble", {
		  startDelay: 0,
		  duration: 300,
		  weakMagnitude: 0,
		  strongMagnitude: 1
		});
	  }
	}
	
	function connecthandler(e) {
	  addgamepad(e.gamepad);
	}
	function addgamepad(gamepad) {
		controllers[gamepad.index] = gamepad;
		$("#gamepadstatus").attr('class', 'connected');
		$("#gamepadstatus").html("Gamepad Connected");

		requestAnimationFrame(updateStatus);
	}
	function disconnecthandler(e) {
		removegamepad(e.gamepad);
	}
	function removegamepad(gamepad) {
		
		$("#gamepadstatus").attr('class', 'disconnected');
		$("#gamepadstatus").html("Disconnected, move joystick to reconnect");
		delete controllers[gamepad.index];
	}
	function pressedButton(buttonNumber){
		console.log("Pressed: " + buttonNumber);
		switch(buttonNumber) {
			case 4: // LB
				console.log("Reload");
				scout_reload();
				break;
			case 5: // RB
				console.log("Reload");
				scout_reload();
				break;
			case 3: // Y
				console.log("Outer Goal");
				scout_outer();
				break;
			case 2: // X
				console.log("Inner Goal");
				scout_inner();
				break;
			case 1: // B
				console.log("Miss");
				scout_miss();
				break;
			case 0: // A
				console.log("Low Goal");
				scout_low();
				break;
			case 9: // Start
				console.log("Match Begin");
				matchbegin();
				break;
			default:
			// code block
			}
		
		console.log(scout_actions);
		updateDashboard()
	}
	
    
	document.onkeyup = function(e) {
		console.log("Keyboard: " + e.which);
		switch(e.which) {
			case 32: // Space Bar
				console.log("Reload");
				scout_reload();
                break;
			case 68: // D
				console.log("Outer Goal");
				scout_outer();
				break;
			case 70: // F
				console.log("Inner Goal");
				scout_inner();
				break;
			case 65: // A
				console.log("Miss");
				scout_miss();
				break;
			case 83: // S
				console.log("Low Goal");
				scout_low();
				break;
			case 71: // G
				//console.log("Match Begin");
				//matchbegin();
				break;
			default:
			// code block
			}
		
		console.log(scout_actions);
		updateDashboard()
	};
	
	function updateDashboard(){
		for (var key in scout_actions) {
			if (scout_actions.hasOwnProperty(key)) {
				console.log(scout_actions[key].id);
			}
		}
		console.log(controllers);
	}
	function releasedButton(buttonNumber){
		console.log("Released: " + buttonNumber);

		//testVibration();
	}

	function updateStatus() {
		if (!haveEvents) {
			scangamepads();
		}

		var i = 0;
		var j;

		for (j in controllers) {
			var controller = controllers[j];

			for (i = 0; i < controller.buttons.length; i++) {
				var val = controller.buttons[i];
				var pressed = val == 1.0;
				if (typeof(val) == "object") {
					pressed = val.pressed;
					val = val.value;
				}

				var pct = Math.round(val * 100) + "%";

				if (pressed) {
					if(val == 1){
						if(buttonstatus[i] == 0){
							pressedButton(i);
						}
						buttonstatus[i] = 1;
					}
				} else {
					if(buttonstatus[i] == 1){
						releasedButton(i);
					}
					buttonstatus[i] = 0;
				}
			}
		}
		requestAnimationFrame(updateStatus);
	}

	function scangamepads() {
		var gamepads = navigator.getGamepads ? navigator.getGamepads() : (navigator.webkitGetGamepads ? navigator.webkitGetGamepads() : []);
		for (var i = 0; i < gamepads.length; i++) {
			if (gamepads[i]) {
				if (gamepads[i].index in controllers) {
					controllers[gamepads[i].index] = gamepads[i];
				} else {
					addgamepad(gamepads[i]);
				}
			}
		}
	}

	window.addEventListener("gamepadconnected", connecthandler);
	window.addEventListener("gamepaddisconnected", disconnecthandler);

	if (!haveEvents) {
		setInterval(scangamepads, 500);
	}
	function deparam(query) {
		var pairs, i, keyValuePair, key, value, map = {};
		// remove leading question mark if its there
		if (query.slice(0, 1) === '?') {
			query = query.slice(1);
		}
		if (query !== '') {
			pairs = query.split('&');
			for (i = 0; i < pairs.length; i += 1) {
				keyValuePair = pairs[i].split('=');
				key = decodeURIComponent(keyValuePair[0]);
				value = (keyValuePair.length > 1) ? decodeURIComponent(keyValuePair[1]) : undefined;
				map[key] = value;
			}
		}
		return map;
	}
	function formsubmit(){
		$("#submitresult").html("Data Sumitted, Please Wait.");
		document.getElementById("senddata").disabled = true;
		var actions = {};
		var i=0;
		var items = $('.list-group').find('li').map(function() {
			var item = { };
			

			item['time'] = $(this).attr("time");
			item['action'] = $(this).attr("action");
			item['title'] = $(this).text();
			actions[i]=item;
			i++;
			 // item;
		});
		//items.forEach(element => console.log(element));
		var postmatch = $('form').serialize();
		//console.log(items);
		var formdata = deparam(postmatch);
		console.log(actions);
		console.log(typeof actions);
		
		
		var matchdata = JSON.stringify(actions);
		console.log(matchdata);
		
		 $.post("ajax.php",
  {
    scout: ajaxtoken,
	bmid: <?php echo $botmatchid; ?>,
    balls: matchdata,
	form: formdata
  },
  function(data, status){
    console.log("Data: " + data + "\nStatus: " + status);
	if(status === "success"){
        if (data === "Not Logged In!!!"){

            $("#submitresult").html("Permission Error");


            document.getElementById("senddata").disabled = false;
        }
        else if (data === "Permission Error - Talk to Forest"){

            $("#submitresult").html("Permission Error - Talk to Forest");

            document.getElementById("senddata").disabled = false;

        }
        else if (data === "Permission Error - Not Registered"){

            $("#submitresult").html("Permission Error");

            document.getElementById("senddata").disabled = false;

        }
		else{
			//$("#ajaxstatus").text(data);
			$("#submitresult").html("Submitted");
			console.log(data);

			window.location.replace("https://frcscouting.net/prod/scoutselect.php");
		}
		
	}
  });
		//console.log(formdata.serializeArray());
	}
</script>	
<?php include('footer.php'); ?>