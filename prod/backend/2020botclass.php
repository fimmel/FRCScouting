<?php

class frcteam
{
	private $db;
	public $team_number = 0;
	public $team_nickname = 0;
	
	// @TODO Lookup team number and add other info to vars from SQL
	public function __construct($dbhandle, $teamnumber){
		$this->db = $dbhandle;
		$this->team_number = $teamnumber;
	}
	
	public function getTeamNumber(){
		return $this->teamnumber;
	}
	
	public function getTeamNickname(){
		return $this->team_nickname;
	}
}

//This is the class used to update/add scouted info for a bot to the DB (2019)
class scoutBotMatch extends frcteam
{
	public $db;
	public $match_id = 0;
	public $matchbot_id = 0; // need to get from sql?
	public $scout = 0;
	private $sc_perm_level = 0; //0-none 1-entry 2-trustedentry 10-scoutcaptain 100-superadmin
	//Deep Space Data
	public $ss_start  = 1; // 1 Which level do they start on
	public $ss_cross  = 0; // 2 Crosses Line in Sandstorm
	public $ss_hatch  = 0; // 3 Number of Hatches Scored in Sandstorm
	public $ss_cargo  = 0; // 4 Number of Cargo Scored in Sandstorm
	public $h_low     = 0; // 5 Number of low level Hatches Scored (max 12)
	public $h_med     = 0; // 6 Number of middle level Hatches Scored (max 4)
	public $h_high    = 0; // 7 Number of high level Hatches Scored (Max 4)
	public $h_dropped = 0; // 8 Number of dropped hatch panels (Not Scored)
	public $h_floor   = 0; // 9 Can pickup Hatches from the floor -1=No Attempt 0=no 1=yes
	public $c_low     = 0; // 10 Number of low level Cargo Scored (max 12)
	public $c_med     = 0; // 11 Number of middle level Cargo Scored (max 4)
	public $c_high    = 0; // 12 Number of high level Cargo Scored (Max 4)
	public $c_dropped = 0; // 13 Number of dropped Cargo (Not Scored)
	public $c_floor   = 0; // 14 -1=No Attempt 0=no 1=yes - Can pickup Cargo from the floor
	public $defense   = 0; // 15 5-Excellent, 4-Very Good, 3-Avg, 2-Below Avg., 1=Poor, 0=No defense played
	public $h_level   = 0; // 16 Endgame HAB Level 0=not on platform
	public $h_assist  = 0; // 17 number of bots assisted getting to higher levels
	public $rating    = 3; // 18 Overall Robot Rating 5-Excellent, 4-Very Good, 3-Avg, 2-Below Avg., 1=Poor (Default 3)
	public $notes    = ""; // 19 General Notes of this bot in this match


	public function __construct($dbhandle, $teamnumber, $matchid, $scout){
		parent::__construct($dbhandle, $teamnumber);
		$this->match_id = $matchid;
		
		// @TODO Get robot match ID based on match ID and Team number
		//$this->matchbot_id = $matchbotID;
		
		// @TODO Lookup Scout permissions and set level 
		// scout.permission
		//$this->sc_perm_level
	}
	
	//Set Sandstorm Data
	public function setSandstorm($start,$cross,$hatch,$cargo){
		$this->ss_start = $start;
		$this->ss_cross = $cross;
		$this->ss_hatch = $hatch;
		$this->ss_cargo = $cargo;
	}
	
	//Set Hatch Data
	public function setHatch($low,$med,$high,$dropped,$floor){
		$this->h_low = $low;
		$this->h_med = $med;
		$this->h_high = $high;
		$this->h_dropped = $dropped;
		$this->h_floor = $floor;
	}
	
	//Set Cargo Data
	public function setCargo($low,$med,$high,$dropped,$floor){
		$this->c_low = $low;
		$this->c_med = $med;
		$this->c_high = $high;
		$this->c_dropped = $dropped;
		$this->c_floor = $floor;
	}
	
	//Set Defense Data
	public function setDefense($def){
		$this->defense = $def;
	}
	
	//Set HAB Data
	public function setHAB($level,$assist){
		$this->h_level = $level;
		$this->h_assist = $assist;
	}
	
	//Set Overall Data
	public function setOverall($rating,$notes){
		$this->rating = $rating;
		$this->notes = $notes;
	}
	
	//Writes data to DB for Captian to approve
	public function submitDB_scout(){
		
		//@TODO add permission check
		$sqli = "INSERT INTO `2019_mdata_int` (`2019_mschema_id`, `submitted`, `match_robot_id`, `scout_id`, `data`, `approved`)
				VALUES
				(1, :submitted, :matbotid, :scout, :ss_start, 0),
				(2, :submitted, :matbotid, :scout, :ss_cross, 0),
				(3, :submitted, :matbotid, :scout, :ss_hatch, 0),
				(4, :submitted, :matbotid, :scout, :ss_cargo, 0),
				(5, :submitted, :matbotid, :scout, :h_low, 0),
				(6, :submitted, :matbotid, :scout, :h_med, 0),
				(7, :submitted, :matbotid, :scout, :h_high, 0),
				(8, :submitted, :matbotid, :scout, :h_dropped, 0),
				(9, :submitted, :matbotid, :scout, :h_floor, 0),
				(10, :submitted, :matbotid, :scout, :c_low, 0),
				(11, :submitted, :matbotid, :scout, :c_med, 0),
				(12, :submitted, :matbotid, :scout, :c_high, 0),
				(13, :submitted, :matbotid, :scout, :c_dropped, 0),
				(14, :submitted, :matbotid, :scout, :c_floor, 0),
				(15, :submitted, :matbotid, :scout, :defense, 0),
				(16, :submitted, :matbotid, :scout, :h_level, 0),
				(17, :submitted, :matbotid, :scout, :h_assist, 0),
				(18, :submitted, :matbotid, :scout, :rating, 0);";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":submitted",date("Y-m-d H:i:s",$time));
		$statementi->bindValue(":matbotid", $this->matchbot_id);// Match Robot ID
		$statementi->bindValue(":scout",    $this->scout);     // Scout ID
		$statementi->bindValue(":ss_start", $this->ss_start);
		$statementi->bindValue(":ss_cross", $this->ss_cross);
		$statementi->bindValue(":ss_hatch", $this->ss_hatch);
		$statementi->bindValue(":ss_cargo", $this->ss_cargo);
		$statementi->bindValue(":h_low",    $this->h_low);
		$statementi->bindValue(":h_med",    $this->h_med);
		$statementi->bindValue(":h_high",   $this->h_high);
		$statementi->bindValue(":h_dropped",$this->h_dropped);
		$statementi->bindValue(":h_floor",  $this->h_floor);
		$statementi->bindValue(":c_low",    $this->c_low);
		$statementi->bindValue(":c_med",    $this->c_med);
		$statementi->bindValue(":c_high",   $this->c_high);
		$statementi->bindValue(":c_dropped",$this->c_dropped);
		$statementi->bindValue(":c_floor",  $this->c_floor);
		$statementi->bindValue(":defense",  $this->defense);
		$statementi->bindValue(":h_level",  $this->h_level);
		$statementi->bindValue(":h_assist", $this->h_assist);
		$statementi->bindValue(":rating",   $this->rating);
		$count = $statementi->execute();
		
		$sqlt = "INSERT INTO `2019_mdata_text` (`2019_mschema_id`, `submitted`, `match_robot_id`, `scout_id`, `data`, `approved`)
				VALUES
				(19, :submitted, :matbotid, :scout, :notes, 0);";
		$statementt = $db->prepare($sqlt);
		$statementt->bindValue(":submitted",date("Y-m-d H:i:s",$time));
		$statementt->bindValue(":matbotid", $this->matchbot_id);// Match Robot ID
		$statementt->bindValue(":scout",    $this->scout);     // Scout ID
		$statementt->bindValue(":notes", $this->notes);
		$count = $statementt->execute();
		
		return $count;
	}
	
	//Pull Data from DB into class for captain
	public function retriveDB_captain(){
		//@TODO All of it
	}
	
	//Approve / update data as captain
	public function approveDB_captain(){
		//@TODO All of it
	}
	
	
	
	public function getProperty(){
		return $this->prop1 . "<br />";
	}
}

//This is the class used to retrieve scouted info for a bot for a particular match from the DB (2019)
class statsBotMatch extends frcteam
{
	public $db;
	public $match_id = 0;

	public $sandstorm = array();

	public function __construct($dbhandle, $teamnumber, $matchid){
		parent::__construct($dbhandle, $teamnumber);
		$this->match_id = $matchid;
	}

	public function setProperty($newval){
		$this->prop1 = $newval;
	}

	public function getProperty(){
		return $this->prop1 . "<br />";
	}
}

//@TODO Event Class
// Basics of an event (gets team list, and event details)
class frcevent
{
	public $db;
	public $eventkey = 0;
	public $eventName = "";
	public $eventID = 0;
	
	// @TODO Lookup team number and add other info to vars from SQL
	public function __construct($dbhandle, $eventkey){
		$this->db = $dbhandle;
		$this->eventkey = $eventkey;
		
		$sql = "SELECT * FROM event WHERE
							`tba_key` = :key;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":key", $eventkey);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $event){
			$this->eventID = $event['id'];
			$this->eventName = $event['name'];
                
        }
		//echo $this->eventID;
		//echo $this->eventName;
	}
	
	public function getTeamList(){
		$teamlist = array();
		$sql = "SELECT * FROM event_teams WHERE
							`event_id` = :event
							ORDER BY CAST(team_id AS unsigned)";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":event", $this->eventID);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $team){
			
			array_push($teamlist, $team['team_id']);
                
        }
		return $teamlist;
	}
	public function getTeamName($number){
		$name = null;
		$sql = "SELECT * FROM team WHERE
							`id` = :number;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":number", $number);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $team){
			
			$name = $team['name'];
                
        }
		return $name;
	}
	
	public function getEventName(){
		return $this->eventName;
	}
	
	public function getEventID(){
		return $this->eventID;
	}
	
	public function getMatchList(){
		$matchlist = array();
		$sql = "SELECT matches.id AS 'mid', matches.level AS 'mlevel', matches.time AS 'mtime', matches.match_num AS 'mnum', matches.set_num AS 'snum' FROM matches WHERE matches.event_id = :event ORDER BY matches.level, matches.match_num, matches.set_num ;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":event", $this->eventID);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $match){
			
			switch ($match['mlevel']) {
			case 2:
					$fullname = "Qualification";
					$shortname = "Q";
					$matchname = "Qual " . $match['mnum'];
					break;
			case 3:
					$fullname = "Quarter Final";
					$shortname = "QF";
					$matchname = "Quarter ". $match['snum'] . " M " . $match['mnum'];
					break;
			case 4:
					$fullname = "Semi Final";
					$shortname = "SF";
					$matchname = "Semi ". $match['snum'] . " M " . $match['mnum'];
					break;
			case 5:
					$fullname = "Final";
					$shortname = "FF";
					$matchname = "Final ". $match['snum'] . " M " . $match['mnum'];
					break;
			default:
					$fullname = "Practice";
					$shortname = "P";
					$matchname = "Prac " . $match['mnum'];
					break;
		}
		$data = array("MatchID"=>$match['mid'],"LN"=>$fullname,"SN"=>$shortname,"Match"=>$match['mnum'],"Set"=>$match['snum'],"name"=>$matchname, "time"=>$match['mtime']);
			array_push($matchlist, $data);
        }
		return $matchlist;
	}

}

//@TODO Class for match schedule
// Extends Event Class
// Gets teams in particular match
// Gets Status of match scouting for a match
// Ability to look up who to scout and what teams are playing etc
class matchschedule extends frcevent{
	public function __construct($dbhandle, $eventkey){
		parent::__construct($dbhandle, $eventkey);
	}
	
	public function getMatchRobotIDsTeam($teamnumber){ // Get match_robot IDs
		$matchlist = array();
		$sql = "SELECT match_robot.id AS 'mrid', match_robot.position AS 'pos', matches.id AS 'mid', matches.level AS 'mlevel', matches.match_num AS 'mnum', matches.set_num AS 'snum' FROM match_robot INNER JOIN matches ON match_robot.match_id = matches.id WHERE match_robot.team_id = :team AND matches.event_id = :event ORDER BY matches.level, matches.match_num, matches.set_num;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":event", $this->eventID);
		$statement->bindValue(":team", $teamnumber);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $match){
			switch ($match['mlevel']) {
			case 2:
					$fullname = "Qualification";
					$shortname = "Q";
					$matchname = "Qual " . $match['mnum'];
					break;
			case 3:
					$fullname = "Quarter Final";
					$shortname = "QF";
					$matchname = "Quarter ". $match['snum'] . " M " . $match['mnum'];
					break;
			case 4:
					$fullname = "Semi Final";
					$shortname = "SF";
					$matchname = "Semi ". $match['snum'] . " M " . $match['mnum'];
					break;
			case 5:
					$fullname = "Final";
					$shortname = "FF";
					$matchname = "Final ". $match['snum'] . " M " . $match['mnum'];
					break;
			default:
					$fullname = "Practice";
					$shortname = "P";
					$matchname = "Prac " . $match['mnum'];
					break;
		}
			switch ($match['pos']) {
			case 1:
					$posname = "Red 1";
					break;
			case 2:
					$posname = "Red 2";
					break;
			case 3:
					$posname = "Red 3";
					break;
			case 4:
					$posname = "Blue 1";
					break;
			case 5:
					$posname = "Blue 2";
					break;
			case 6:
					$posname = "Blue 3";
					break;
			default:
					
					break;
			}
			
			$data = array("MRID"=>$match['mrid'],"MatchID"=>$match['mid'],"PosNu"=>$match['pos'],"PosNa"=>$posname,"LN"=>$fullname,"SN"=>$shortname,"Match"=>$match['mnum'],"Set"=>$match['snum'],"name"=>$matchname);
			array_push($matchlist, $data);
        }
		return $matchlist;
	}
	
	public function getMatchID($number, $level = 2, $set = 1){ //default to qual
		$sql = "SELECT id FROM `matches` WHERE event_id = :event AND level = :level AND set_num = :set AND match_num = :number;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":event", $this->eventID);
		$statement->bindValue(":number", $number);
		$statement->bindValue(":level", $level);
		$statement->bindValue(":set", $set);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $match){
			$id = $match['id'];
        }
		return $id;
	}
	
	
	public function getTeamsInMatch($matchID){ // return array of Teams and Positions
		$teamlist = array();
		$sql = "SELECT match_robot.team_id AS 'team', match_robot.id AS 'mbid', match_robot.position AS 'position' FROM match_robot WHERE match_robot.match_id = :matchid ORDER BY match_robot.position;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":matchid", $matchID);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $bot){
			switch ($bot['position']) {
			case 1:
					$teamlist["r1"] = $bot['team'];
					$teamlist["r1mbid"] = $bot['mbid'];
					break;
			case 2:
					$teamlist["r2"] = $bot['team'];
					$teamlist["r2mbid"] = $bot['mbid'];
					break;
			case 3:
					$teamlist["r3"] = $bot['team'];
					$teamlist["r3mbid"] = $bot['mbid'];
					break;
			case 4:
					$teamlist["b1"] = $bot['team'];
					$teamlist["b1mbid"] = $bot['mbid'];
					break;
			case 5:
					$teamlist["b2"] = $bot['team'];
					$teamlist["b2mbid"] = $bot['mbid'];
					break;
			case 6:
					$teamlist["b3"] = $bot['team'];
					$teamlist["b3mbid"] = $bot['mbid'];
					break;
			default:
					
					break;
			}
        }
		
		$sql = "SELECT matches.level AS 'mlevel', matches.time AS 'mtime', matches.match_num AS 'mnum', matches.set_num AS 'snum', event.tba_key AS 'tbakey', event.name AS 'eventname' FROM matches INNER JOIN event ON matches.event_id = event.id WHERE matches.id = :matchid;";
		$statement = $this->db->prepare($sql);
		$statement->bindValue(":matchid", $matchID);
		$statement->execute();
        $sqlresult = $statement->fetchAll();
        foreach ($sqlresult as $match){
			switch ($match['mlevel']) {
				case 2:
					$fullname = "Qualification";
					$shortname = "Q";
					$matchname = "Qual " . $match['mnum'];
					break;
				case 3:
					$fullname = "Quarter Final";
					$shortname = "QF";
					$matchname = "Quarter ". $match['snum'] . " M " . $match['mnum'];
					break;
				case 4:
					$fullname = "Semi Final";
					$shortname = "SF";
					$matchname = "Semi ". $match['snum'] . " M " . $match['mnum'];
					break;
				case 5:
					$fullname = "Final";
					$shortname = "FF";
					$matchname = "Final ". $match['snum'] . " M " . $match['mnum'];
					break;
				default:
					$fullname = "Practice";
					$shortname = "P";
					$matchname = "Prac " . $match['mnum'];
					break;
			}
			$teamlist["Meta"] = array("Event"=>$match['eventname'],"TBAKey"=>$match['tbakey'],"LN"=>$fullname,"SN"=>$shortname,"Match"=>$match['mnum'],"Set"=>$match['snum'],"name"=>$matchname,"time"=>$match['mtime']);
        }

		return $teamlist;
	}
	
	public function getLastScoutedMatch(){ // return match ID of last scouted match with approved Data 
		//return $this->team_nickname;
	}
	
	public function getScoutStatusMatch($matchID){ // get status of data for each robot in match id
		//return $this->team_nickname;
	}	
	
}



//@TODO Class for scout managment
// Logged in user check for tasks / permissions etc
// Scout Queue Managment

//@TODO Class for Team Statistics @ Event
// Extend frcteam
// Look up stats for a particular team at an event

//@TODO Class for Game Element Statistics @ Event
// Look up stats for a particular Game Element at an event







 
?>