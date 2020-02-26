<?php
$myteam = $_GET['team'];
if($_GET['team'] == ""){
    $myteam = 4041;
}
include('backend/db.php');
include('backend/2020botclass.php');
include('backend/functions.php'); //Global functions
include('backend/graphics.php'); //Graphic Icon functions

