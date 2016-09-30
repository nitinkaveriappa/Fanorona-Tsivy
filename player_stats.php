<?php

include 'get_stats.php';

//Program to get the Player Stats
//Program requires player_id as input
$player_id = 1000;

$start = new stats($player_id);
$start->run_stats();

?>