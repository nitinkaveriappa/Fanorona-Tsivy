<?php
//Program to get the Player Stats
//Program requires player_id as input

session_start();
$name = $_SESSION['player_name'];
$idle = time() - $_SESSION['created'];
if ($idle > 3000)
{
	header('Location:logout.php');
}
else if($idle > 120)
{
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}


include 'get_stats.php';

$player_id = $_SESSION['player_id'];
$start = new stats($player_id);
$start->run_stats();
?>