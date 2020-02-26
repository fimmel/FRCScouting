<html>
	
<head>
	<meta name="google-signin-client_id" content="<?php echo $googleDevKey; ?>">
	<script src="includes/libraries.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
	<link href="includes/style.css" rel="stylesheet">
	
<meta charset="utf-8">
<title><?php echo $pagetitle; ?> - FRC Scouting</title>
<style>
	html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video{
		font-family:Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif";
		padding: 0;
		margin: 0;
	}
	body{
		background-color:#252525;
		color: #BCBCBC !important;
	}
	
	.noshow{
		border: hsla(0,0%,100%,0.00) !important;
		background-color: hsla(0,0%,100%,0.00) !important;
	}
	
	#topbar{
		width: 100%;
		margin:0;
		padding: 4px;
		font-size: 20px;
		background-color: #333333;
		color: #F4F4F4;
		border-bottom: 6px solid;
		border-bottom-color: <?php echo ($color == "blue" ? '#4444ff' : '#ff4444'); ?>;
		padding-top: 10px;
		height: 54px;
	}
	.teamnumber{
		font-size: 28px;
		font-weight: bold;
	}
	#ga_pic img {
		width:48px;
		height: 48px;
		display: block;
		position: absolute;
		right: 0px;
		top: 0px;
	}
	#ga_name {
		width:174px;
		height: 48px;
		display: block;
		position: absolute;
		right: 48px;
		top: 6px;
	}
	#ga_logout {
		width:174px;
		height: 48px;
		display: block;
		position: absolute;
		right: 48px;
		top: 25px;
		font-size: 12px;
	}
	.g-signin2 {
		width:300px;
		height: 48px;
		display: block;
		position: absolute;
		right: 48px;
		top: 6px;
	}
	
	#ga_logout a:link {
		color:#C1C1C1;
	}
	#ga_logout a:visited {
		color:#C1C1C1;
	}
	#ga_logout a:hover {
		color:#ffffff;
	}
	#ajaxstatus{
		text-align: center;
		width: 100%;
		height: 24px;
		background-color: #333333;
		color: #F4F4F4;
		padding-top: 2px;
		font-weight: bold;
	}
	
</style>
	<style>
	h1{
		color: #D2D2D2;
		font-size: 24px;
		padding: 0px;
		margin: 0px;
		margin-top: 10px;
	}
	.container {
		max-width: 99%;
	}
	.schedule{
		border: 1px solid;
		border-color: #555;
		width: 99%;
	}
	.schedule th{
		color: #efefef;
	}
	.gen{
		background-color: #363636;
		color: #efefef;
		padding: 3px;
		text-align: center;
		
		border-bottom: 1px solid;
		border-bottom-color: #666666;
	}
	.played{
		background-color: #363636;
		color: #efefef;
		padding: 3px;
		text-align: center;
	}
	.notplayed{
		background-color:#5C5C5C;
		color: #efefef;
		padding: 3px;
		text-align: center;
	}
	.red{
		background-color: #B32222;
		padding: 3px;
		text-align: center;
		border-bottom: 1px solid;
		border-bottom-color: #BF4446;
	}
	.red a:link {
		color:#ffD7D7;
	}

	.red a:visited {
		color:#ffD7D7;
	}

	.red a:hover {
		color:#D7D7D7;
	}

	.red a:active {
		color:#ffD7D7;
	}
	
	.blue{
		background-color: #2222B3;
		padding: 3px;
		text-align: center;
		border-bottom: 1px solid;
		border-bottom-color: #4644BF;
	}
	.blue a:link {
		color:#D7D7ff;
	}

	.blue a:visited {
		color:#D7D7ff;
	}

	.blue a:hover {
		color:#D7D7D7;
	}

	.blue a:active {
		color:#D7D7ff;
	}
	.mer {
		background-color:#4C0001;
		display: block;
		border-radius: 4px;
	}
	.meb {
		background-color:#01004C;
		display: block;
		border-radius: 4px;
	}
	.time{
		font-size: 13px;
		color: #aaa;
	}
	.teamlist{
		width: 99%;
	}
	.teamlist td{
		background-color: #222222;
		padding: 3px;
		text-align: center;
		border-bottom: 1px solid;
		border-bottom-color: #464446;
		margin-bottom: 2px;
	}
	.teamlist a:link {
		color:#bbb;
	}

	.teamlist a:visited {
		color:#bbb;
	}

	.teamlist a:hover {
		color:#D7D7D7;
	}

	.teamlist a:active {
		color:#bbb;
	}
</style>
</head>

	<body><div id="topbar">IBOTS Scouting - <strong><?php echo $pagetitle ?></strong></span>
		
		
		
		<div class="login-content">
	<div id='ga_name'></div>
	<div id='ga_pic'></div>
	<div class="g-signin2" data-onsuccess="onSignIn"></div>
	
	<div id='ga_logout'><a href="#" class="dropdown-item" onclick="signOut();">Sign out</a></div>
</div>
		
		</div>
	<div id="ajaxstatus"><a href="index.php">Match Schedule</a> - <a href="scoutselect.php">Scout Input</a> - <a href="eventstats.php">Event Stats</a></div>
