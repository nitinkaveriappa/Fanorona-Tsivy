<?php 
session_start();

$obj = new setup();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$type = $_REQUEST["type"];
	switch($type)
	{
		case 'newGame':
		{
			$obj->newGame();
			break;
		}
		case 'joinGame':
		{
			$obj->joinGame();
			break;
		}
		default:
		{
			echo "Failed";
			break;
		}
	}
}


class setup
{
	
	function newGame()
	{
		include('dbconnect.php');
		$playerId = $_SESSION['player_id'];
		
		//Sets the lobby flag as 1 
		$setLobbyFlagQuery = $connection->prepare("UPDATE flg_ls SET lobby_flag=1 WHERE player_id=:playerId ;");
		$setLobbyFlagQuery->bindParam(':playerId',$playerId);
		$setLobbyFlagQuery->execute();
		//Creates a new game entry 
		$getRowQuery = $connection->prepare("SELECT game_id FROM gm_mst ORDER BY game_id DESC LIMIT 1;");
		$getRowQuery->execute();
	
		if($getRowQuery->rowCount() > 0)
		{	
			$getRowResult = $getRowQuery->fetch(PDO::FETCH_ASSOC);
			$gameId = $getRowResult['game_id'];
			$gameId++;
		}
		else
		{ 
			$gameId =10000;
		}
		$gameStart = '1000-01-01 00:00:00';				//Game has not actually started
		$player2 = 0000;								//Dummy placeholder for opponent
		$player1 = $playerId;
		$addNewGameQuery = $connection->prepare("INSERT INTO gm_mst(game_id,player_id_1,player_id_2,game_start) VALUES(:gameId,:player1,:player2,:gameStart);");
		$addNewGameQuery->bindParam(':gameId',$gameId);
		$addNewGameQuery->bindParam(':player1',$player1);
		$addNewGameQuery->bindParam(':player2',$player2);
		$addNewGameQuery->bindParam(':gameStart',$gameStart);
		$addNewGameQuery->execute();
		$addNewGameQuery=null;
		//Wait for opponent to join for 100 seconds
		$time = time();		
		$timeout = time()+100000;
		while($time<$timeout)
		{
			$time++;
			//Check if opponent has update the game tuple
			$checkOpponentQuery = $connection->prepare("SELECT game_id FROM gm_mst WHERE game_id=:gameId and player_id_2 != 0 and player_id_2 != :playerId;");
			$checkOpponentQuery->bindParam(':gameId',$gameId);
			$checkOpponentQuery->bindParam(':playerId',$player1);
			$checkOpponentQuery->execute();
			//If opponent has joined return the Game ID
			if($checkOpponentQuery->rowCount() == 1)
			{
				$checkOpponentResult = $checkOpponentQuery->fetch(PDO::FETCH_ASSOC);
				$gameId = $checkOpponentResult['game_id'];
				//Set InGame Flag as 1 
				$setInGameFlagQuery = $connection->prepare("UPDATE flg_ls SET ingame_flag=1 WHERE player_id=:playerId ;");
				$setInGameFlagQuery->bindParam(':playerId',$playerId);
				$setInGameFlagQuery->execute();
				$setInGameFlagQuery = null;
				echo "$gameId";
				break;
			}
			
		}
		//Revert Lobby Flag to 0 if new game has started or no opponent was found
		$setLobbyFlagQuery = $connection->prepare("UPDATE flg_ls SET lobby_flag=0 WHERE player_id=:playerId ;");
		$setLobbyFlagQuery->bindParam(':playerId',$playerId);
		$setLobbyFlagQuery->execute();
		$connection=null;
		echo "";
	}
	
	
	
	function joinGame()
	{
		include('dbconnect.php');
		$player_id_2 = $_SESSION['player_id'];
		//Obtain a list of players in the lobby
		$searchPlayerQuery = $connection->query("SELECT player_id FROM flg_ls WHERE lobby_flag=1");
		$searchPlayerQuery->execute();
		//If player are waiting in the lobby		
		if($searchPlayerQuery->rowCount() > 0)
		{
			//Fetches a random player from the result
			for($i=1;$i<=rand(1,$searchPlayerQuery->rowCount());$i++)
			{
				$row = $searchPlayerQuery->fetch(PDO::FETCH_ASSOC);
			}
			$player_id_1 = $row['player_id'];
			$searchPlayerQuery=null;
			
			//Finds the new game tuple created by the opponent
			$getNewGameQuery = $connection->prepare("SELECT game_id FROM gm_mst WHERE player_id_1=:player1 and player_id_2=0 ORDER by game_id DESC LIMIT 1;");
			$getNewGameQuery->bindParam(':player1',$player_id_1);
			$getNewGameQuery->execute();
			$result = $getNewGameQuery->fetch(PDO::FETCH_ASSOC);
			$gameId = $result['game_id'];
			
			//Update the new game with Player id and start time
			$startTime = date('Y-m-d H:i:s');
			$updateNewGameQuery = $connection->prepare("UPDATE gm_mst SET player_id_2=:player2, game_start=:startTime WHERE game_id=:gameId ;");
			$updateNewGameQuery->bindParam(':player2',$player_id_2);
			$updateNewGameQuery->bindParam(':startTime',$startTime);
			$updateNewGameQuery->bindParam(':gameId',$gameId);
			$updateNewGameQuery->execute();
			
			//Set InGame Flag as 1 
			$setInGameFlagQuery = $connection->prepare("UPDATE flg_ls SET ingame_flag=1 WHERE player_id=:playerId ;");
			$setInGameFlagQuery->bindParam(':playerId',$player_id_2);
			$setInGameFlagQuery->execute();
			echo "$gameId";
		}
		else
		{
			echo "";
		}
	}
}
?>