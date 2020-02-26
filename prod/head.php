<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="google-signin-client_id" content="<?php echo $googleDevKey; ?>">
    <script src="includes/libraries-eventstats.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/fc-3.3.0/fh-3.1.6/datatables.min.js"></script>
    <link href="includes/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/fc-3.3.0/fh-3.1.6/datatables.min.css"/>
    <link href="includes/scoutstyle.css" rel="stylesheet">
    <meta charset="utf-8">
    <title><?php echo $pagetitle; ?> - FRC Scouting</title>
    <style>
        #topbar {
            border-bottom-color: <?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
        }
        a{
            color:<?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
        }
        a:hover{
            color:<?php echo ($color == "blue" ? '#4444ff' : ($color == "red" ? '#ff4444' : 'rgb(128,188,0)')); ?>;
        }
        .table .table{
            background-color: #222;
        }
    </style>
</head>

<body>
<div id="topbar">IBOTS Scouting - <strong><?php echo $pagetitle ?></strong>


    <div class="login-content">
        <div id='ga_name'></div>
        <div id='ga_pic'></div>
        <div class="g-signin2" data-onsuccess="onSignIn"></div>

        <div id='ga_logout'><a href="#" class="dropdown-item" onclick="signOut();">Sign out</a></div>
    </div>

</div>
<div id="ajaxstatus">
    <a href="index.php">Match Schedule</a> -
    <a href="scoutselect.php">Scout Input</a> -
    <a href="eventstats.php">Event Stats</a>
</div>

