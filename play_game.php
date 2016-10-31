<?php

include 'game_logicv0.4.php';
session_start();
$name = $_SESSION['player_name'];
$idle = time() - $_SESSION['created'];
if ($idle > 300)
{
	header('Location:logout.php');
}
else if($idle > 120)
{
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

//Program requires New Node State, GameID, PlayerID as I/p
function play()
{
	require_once('dbconnect.php');
	$s2 = "111111111";
	$s2 .="111111111";
	$s2 .="121212212";
	$s2 .="222220222";
	$s2 .="222222222";

	$game_id=10000;
	$player_id=1000;

	$query = "SELECT node_list, move_count, restricted_moves FROM fanodb.gm_st where game_id=:gameID AND player_id=:playerID ORDER BY move_count DESC LIMIT 1;";
	$oldNodeQuery = $connection->prepare($query);
	$oldNodeQuery->bindParam(':gameID',$game_id);
	$oldNodeQuery->bindParam(':playerID',$player_id);
	$oldNodeQuery->execute();

	// set the resulting array to associative
	$result = $oldNodeQuery->fetch(PDO::FETCH_ASSOC);
	$s1=$result['node_list'];
	$move_count=$result['move_count'];
	$restricted_moves=$result['restricted_moves'];

	echo "s1=$s1<br/>";

	$start = new game_logic($s1,$s2,$move_count,$restricted_moves);
	$start->run_game();

	$connection = null;
}

function send_state()
{
	require_once('dbconnect.php');
	$game_id = $_SESSION['game_id'];
	$player_id = $_SESSION['player_id'];

	//get current game state
	$query = "SELECT node_list, move_count, restricted_moves, player_id FROM fanodb.gm_st where game_id=:gameID LIMIT 1;";
	$dataQuery = $connection->prepare($query);
	$dataQuery->bindParam(':gameID',$game_id);
	$dataQuery->execute();
	// set the resulting array to associative
	$result = $dataQuery->fetch(PDO::FETCH_ASSOC);

	$s1=$result['node_list'];
	$move_count=$result['move_count'];
	$restricted_moves=$result['restricted_moves'];
	$turn=$result['player_id'];
	
	//Get both player id in the game
	$query2 = "SELECT player_id_1, player_id_2 FROM fanodb.gm_mst WHERE game_id:gameID;";
	$dataQuery2 = $connection->prepare($query2);
	$dataQuery2->bindParam(':gameID',$game_id);
	$dataQuery2->execute();
	$result2 = $dataQuery2->fetch(PDO::FETCH_ASSOC);

	$player_id_1=$result2['player_id_1'];
	$player_id_2=$result2['player_id_2'];
 //Check if the next turn is of current player
 if($player_id==$turn)
 {
	//Check if the current player is player 1 then return 1 
	if($player_id_1==$player_id)
	{
		$playa=1;					
	}
	//Check if the current player is player 2 then return 2
	else if($player_id_2==$player_id) {
		$playa=2;
	}
 }
 //If it is not current player's turn then return 0
else {
		$playa=0;
	}

	$arr = array('node_list' => "$s1", 'move_count' => "$move_count", 'restricted_moves' => "$restricted_moves", 'player_turn' => "$playa");
  echo json_encode($arr);

	$connection = null;
}
?>
