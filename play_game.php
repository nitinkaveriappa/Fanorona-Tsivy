<?php

include 'game_logicv0.6.php';
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

if(isset($_REQUEST['type']))
{
	switch($_REQUEST['type'])
	{
		case 'play':
			play_move();
			break;
		case 'state':
			send_state();
			break;
		case 'check':
			check_move();
			break;
	}
}

//Program requires New Node State, GameID, PlayerID as I/p
function play_move()
{
	require_once('dbconnect.php');
	if(isset($_POST['nodeList']) && $_POST['nodeList']!='' && $_POST['nodeList']!='Error')
	{
		$s2 = $_POST['nodeList'];
		$game_id=$_SESSION['game_id'];
		$player_id=$_SESSION['player_id'];
		$forbidden_move ='';
		$locked_pawn='';

		$query = "SELECT node_list, move_count, restricted_moves FROM fanodb.gm_st where game_id=:gameID AND player_id=:playerID ORDER BY counter DESC LIMIT 1;";
		$oldNodeQuery = $connection->prepare($query);
		$oldNodeQuery->bindParam(':gameID',$game_id);
		$oldNodeQuery->bindParam(':playerID',$player_id);
		$oldNodeQuery->execute();

		// set the resulting array to associative
		$result = $oldNodeQuery->fetch(PDO::FETCH_ASSOC);
		$s1=$result['node_list'];
		$move_count=$result['move_count'];
		$restricted_moves=$result['restricted_moves'];

		$start = new game_logic($s1,$s2,$move_count,$restricted_moves);
		$returnValues = $start->run_game();

		$returned = explode(',',$returnValues);
		$response = $returned[0];

		//If the move made is valid
		if($response == 1)
		{
			$new_node_list = $returned[1];

			$next_player = $returned[3];
			$player_change = $returned[4];

			if(!$player_change)
			{
				$locked_pawn = $returned[2];
				//If there are restricted moves existing it adds a , before inserting the new value
				if(!empty($restricted_moves))
				{
					$restricted_moves .= ',';
				}
				$restricted_moves .=  $returned[5];
				$forbidden_move = $returned[6];
				++$move_count;
			}
			else
			{
				$locked_pawn = '';
				$restricted_moves = '';
				$forbidden_move = '';
				$move_count = 1;
			}


			//Get both player id in the game
			$getPlayersQuery = $connection->prepare("SELECT player_id_1, player_id_2 FROM fanodb.gm_mst WHERE game_id=:gameID;");
			$getPlayersQuery->bindParam(':gameID',$game_id);
			$getPlayersQuery->execute();
			$players_result = $getPlayersQuery->fetch(PDO::FETCH_ASSOC);
			$player_id_1=$players_result['player_id_1'];
			$player_id_2=$players_result['player_id_2'];

			$insertNewNodeQuery = $connection->prepare("INSERT INTO gm_st(game_id,player_id,node_list,restricted_moves, move_count) VALUES(:gameID,:playerID,:nodeList, :restrictedMoves,:moveCount);");

			$insertNewNodeQuery->bindParam(':gameID',$game_id);

			if($next_player == 1)
					$insertNewNodeQuery->bindParam(':playerID',$player_id_1);
			else
					$insertNewNodeQuery->bindParam(':playerID',$player_id_2);

			$insertNewNodeQuery->bindParam(':nodeList', $new_node_list);
			$insertNewNodeQuery->bindParam(':restrictedMoves',$restricted_moves);
			$insertNewNodeQuery->bindParam(':moveCount',$move_count);
			$insertNewNodeQuery->execute();

			$result = $response.','.$move_count.','.$locked_pawn.','.$forbidden_move;
		echo htmlspecialchars($result);
		}

		//If the player won
		if($response == 2)
		{
			$new_node_list = $returned[1];
			++$move_count;
			$restricted_moves = '';

			$insertNewNodeQuery = $connection->prepare("INSERT INTO gm_st(game_id,player_id,node_list,restricted_moves, move_count) VALUES(:gameID,:playerID,:nodeList, :restrictedMoves,:moveCount);");

			//Update the game state
			$insertNewNodeQuery->bindParam(':gameID',$game_id);
			$insertNewNodeQuery->bindParam(':playerID',$player_id);
			$insertNewNodeQuery->bindParam(':nodeList', $new_node_list);
			$insertNewNodeQuery->bindParam(':restrictedMoves',$restricted_moves);
			$insertNewNodeQuery->bindParam(':moveCount',$move_count);
			$insertNewNodeQuery->execute();

			//Update the game with end time
			$endTime = date('Y-m-d H:i:s');
			$updateNewGameQuery = $connection->prepare("UPDATE gm_mst SET game_end=:endTime WHERE game_id=:gameId ;");
			$updateNewGameQuery->bindParam(':endTime',$endTime);
			$updateNewGameQuery->bindParam(':gameId',$game_id);
			$updateNewGameQuery->execute();

			//Get and Update the player stats
			$getPlayerStatsQuery = $connection->prepare("SELECT win_count FROM pl_sts WHERE player_id=:playerId");
			$getPlayerStatsQuery->bindParam(':playerId',$player_id);
			$getPlayerStatsQuery->execute();
			$resultPlayerStats = $getPlayerStatsQuery->fetch(PDO::FETCH_ASSOC);
			$win_count = $resultPlayerStats['win_count'];
			$win_count++;
			$updatePlayerStatsQuery = $connection->prepare("UPDATE pl_sts SET win_count=:winCount WHERE player_id=:playerId");
			$updatePlayerStatsQuery->bindParam(':winCount',$win_count);
			$updatePlayerStatsQuery->bindParam(':playerId',$player_id);
			$updatePlayerStatsQuery->execute();

			//Unset the in game flag
			$flag=0;
			$updateInGameFlagQuery = $connection->prepare("UPDATE flg_ls SET ingame_flag=:inGame WHERE player_id=:playerId");
			$updateInGameFlagQuery->bindParam(':inGame',$flag);
			$updateInGameFlagQuery->bindParam(':playerId',$player_id);
			$updateInGameFlagQuery->execute();

			echo $response;
			}
	}

	$connection = null;
}

//Checks if a move has been made
function check_move()
{
	require_once('dbconnect.php');
	$game_id = $_SESSION['game_id'];
	$player_id = $_SESSION['player_id'];
	$response = 2;

	//Check if game is over
	$checkPlayerLossQuery = $connection->prepare("SELECT game_end FROM gm_mst WHERE game_id=:gameId");
	$checkPlayerLossQuery->bindParam(':gameId',$game_id);
	$checkPlayerLossQuery->execute();
	$checkPlayerLossResult = $checkPlayerLossQuery->fetch(PDO::FETCH_ASSOC);
	$game_end = $checkPlayerLossResult['game_end'];

	if($game_end == '')
	{
		//Get the next player id from game state table
		$checkPlayerQuery = $connection->prepare("SELECT player_id FROM gm_st WHERE game_id=:gameId ORDER BY counter DESC LIMIT 1;");
		$checkPlayerQuery->bindParam(':gameId',$game_id);
		$checkPlayerQuery->execute();
		$checkPlayerResult = $checkPlayerQuery->fetch(PDO::FETCH_ASSOC);
		$next_player = $checkPlayerResult['player_id'];

		//If the id is equal current player id send 1
		if($next_player == $player_id)
		{
			$response = 1;
		}
		else
		{
			$response = 2;
		}
	}
	else
	{
		//Get and Update the player stats
		$getPlayerStatsQuery = $connection->prepare("SELECT loss_count FROM pl_sts WHERE player_id=:playerId");
		$getPlayerStatsQuery->bindParam(':playerId',$player_id);
		$getPlayerStatsQuery->execute();
		$resultPlayerStats = $getPlayerStatsQuery->fetch(PDO::FETCH_ASSOC);
		$loss_count = $resultPlayerStats['loss_count'];
		$loss_count++;
		$updatePlayerStatsQuery = $connection->prepare("UPDATE pl_sts SET loss_count=:lossCount WHERE player_id=:playerId");
		$updatePlayerStatsQuery->bindParam(':lossCount',$loss_count);
		$updatePlayerStatsQuery->bindParam(':playerId',$player_id);
		$updatePlayerStatsQuery->execute();

		//Unset the in game flag
		$flag=0;
		$updateInGameFlagQuery = $connection->prepare("UPDATE flg_ls SET ingame_flag=:inGame WHERE player_id=:playerId");
		$updateInGameFlagQuery->bindParam(':inGame',$flag);
		$updateInGameFlagQuery->bindParam(':playerId',$player_id);
		$updateInGameFlagQuery->execute();
		$response = 3;

	}

	//OUTPUT
	echo $response;

	$connection = null;
}

//Sends the current node state
function send_state()
{
	require_once('dbconnect.php');
	$game_id = $_SESSION['game_id'];
	$player_id = $_SESSION['player_id'];
	$playa=0;
	//get current game state
	$query = "SELECT node_list, move_count, restricted_moves, player_id FROM fanodb.gm_st where game_id=:gameID ORDER BY counter DESC LIMIT 1; ";
	$dataQuery = $connection->prepare($query);
	$dataQuery->bindParam(':gameID',$game_id);
	$dataQuery->execute();
	$result = $dataQuery->fetch(PDO::FETCH_ASSOC);
	$s1=$result['node_list'];
	$move_count=$result['move_count'];
	$restricted_moves=$result['restricted_moves'];
	$turn=$result['player_id'];

	//Get both player id in the game
	$query2 = "SELECT player_id_1, player_id_2 FROM fanodb.gm_mst WHERE game_id=:gameID;";
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

	$arr = array("node_list" => "$s1", "move_count" => "$move_count", "restricted_moves" => "$restricted_moves", "player_turn" => "$playa");
    header("Content-Type: application/json; charset=utf-8");
	$response =  json_encode($arr);
	if($response === false)
		echo "$arr";
	else
		echo $response;			//OUTPUT



	$connection = null;
}
?>
