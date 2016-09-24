<?php

include 'game_logicv0.4.php';
require_once('dbconnect.php');

//Program requires New Node State, GameID, PlayerID as I/p

$s2 = "111111111";
$s2 .="111111111";
$s2 .="121212212";
$s2 .="222220222";
$s2 .="222222222";

$game_id=10000;
$player_id=1000;

//Query to fetch the last move state of the player in the game from db
$query = "SELECT node_list FROM fanodb.gm_st where game_id=:gameID AND player_id=:playerID ORDER BY move_count DESC LIMIT 1;";
$oldNodeQuery = $connection->prepare($query); 
$oldNodeQuery->bindParam(':gameID',$game_id);
$oldNodeQuery->bindParam(':playerID',$player_id);
$oldNodeQuery->execute();

// set the resulting array to associative
$result = $oldNodeQuery->fetch(PDO::FETCH_ASSOC);
$s1=$result['node_list'];

echo "s1=$s1<br/>";

//using the old and new node states run game
$start = new game_logic($s1,$s2);
$start->run_game();


	
$connection = null;

?>