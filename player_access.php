<?php


class access
{

	function login_player($userName,$userPassword)
	{
		include('log.php');
		include('dbconnect.php');

		$loginQuery = $connection->prepare("SELECT a.* ,b.login_flag FROM pl_mst a, flg_ls b WHERE player_email=:mailID and b.player_id = a.player_id ;");
		log_it("Database connection login check successful");
		$loginQuery->bindParam(':mailID',$userName);
		$loginQuery->execute();
		$loginResultCount = $loginQuery->rowCount();

		//If user exists
		if($loginResultCount == 1)
		{
			//Obtain password and verified flag
			$loginResultRow = $loginQuery->fetch(PDO::FETCH_ASSOC);
			$cryptedPassword = $loginResultRow['player_password'];
			$verifiedFlag = $loginResultRow['player_verified'];
			$loginFlag = $loginResultRow['login_flag'];

			//If password matches and user is verified
			if(password_verify($userPassword, $cryptedPassword) && $verifiedFlag == 1 && $loginFlag == 0)
			{
				//Creates a session and sets session variables
				session_start();
				if (!isset($_SESSION['created']))
				{
    				$_SESSION['created'] = time();
				}
				$_SESSION['player_id'] =$loginResultRow['player_id'];
				$_SESSION['player_name'] =$loginResultRow['player_name'];

				//Sets the flags
				$setFlagQuery= $connection->prepare("UPDATE flg_ls SET login_flag=1 WHERE player_id=:playerId;");
				$setFlagQuery->bindParam(':playerId',$loginResultRow['player_id']);
				$setFlagQuery->execute();

				log_it("$userName user login successful");
				header("Location:home.php");
			}
			//if user needs to reconnect after abrupt disconnection from the game
			else if(password_verify($userPassword, $cryptedPassword) && $verifiedFlag == 1 && $loginFlag == 1)
			{
				$checkGameQuery= $connection->prepare("SELECT game_id,game_end FROM gm_mst WHERE player_id_1=:playerId or player_id_2=:playerId;");
				$checkGameQuery->bindParam(':playerId',$loginResultRow['player_id']);
				$checkGameQuery->execute();
				$checkGameResult = $checkGameQuery->fetch(PDO::FETCH_ASSOC);
				$game_end = $checkGameResult['game_end'];
				$game_id = $checkGameResult['game_id'];

				session_start();
				if(isset($_SESSION))
				{
					$idle = time() - $_SESSION['created'];
					if ($idle > 300)
					{
						log_it("$userName user logged out forcefully (Session Timout)");
						header('Location:logout.php');
					}

					if($idle < 300 && $_SESSION['game_id'] == $game_id && $_SESSION['player_id'] == $loginResultRow['player_id'])
					{
						log_it("$userName user re-join game successful");
						header("Location:game.php");
					}
					else if($idle < 300 && $_SESSION['player_id'] == $loginResultRow['player_id'])
					{
						log_it("$userName user re-login successful");
						header("Location:home.php");
					}
				}
			}
			else
			{
				header("Location:index.html?type=err");
			}
	  	}
		else
			{
				header("Location:index.html?type=err");
			}
	}


	function register_player($playerName, $playerEmail, $playerPassword)
	{
		include('log.php');
		include('dbconnect.php');
		//Finds the latest player ID and increments it
		$getRowQuery = $connection->prepare("SELECT player_id FROM pl_mst ORDER BY player_id DESC LIMIT 1;");
		$getRowQuery->execute();

		if($getRowQuery->rowCount() > 0)
		{
			$getRowResult = $getRowQuery->fetch(PDO::FETCH_ASSOC);
			$playerId = $getRowResult['player_id'];
			$playerId++;
		}
		//If no players in the database then sets the first player as 1000
		else
		{
			$playerId =1000;
		}

		//Encrypt Password blowfish algorithm
 		$options = array("cost"=>13);
		$playerPassword = password_hash($playerPassword,PASSWORD_BCRYPT, $options);

		//Generates a code with email id
		$encryptemail = password_hash($playerEmail,PASSWORD_BCRYPT, $options);
		$code = substr($encryptemail,29,31);

		//Add the new player details into fanodb
		$addPlayerQuery = $connection->prepare("INSERT INTO pl_mst(player_id, player_name, player_email, player_password, player_verified) VALUES(:id,:name,:email,:password,0);");
		$addPlayerQuery->bindParam(':id',$playerId);
		$addPlayerQuery->bindParam(':name',$playerName);
		$addPlayerQuery->bindParam(':email',$playerEmail);
		$addPlayerQuery->bindParam(':password',$playerPassword);
		$addPlayerQuery->execute();

		//Add the player to player stats
		$addPlayerStatsQuery = $connection->prepare("INSERT INTO pl_sts(player_id,win_count,draw_count,loss_count) VALUES(:playerId,:winCount,:drawCount,:lossCount);");
		$addPlayerStatsQuery->bindParam(':playerId',$playerId);
		$wc = 0;
		$dc = 0;
		$lc =0;
		$addPlayerStatsQuery->bindParam(':winCount',$wc);
		$addPlayerStatsQuery->bindParam(':drawCount',$dc);
		$addPlayerStatsQuery->bindParam(':lossCount',$lc);
		$addPlayerStatsQuery->execute();

		//Sets the flags
		$setFlagQuery= $connection->prepare("INSERT INTO flg_ls(player_id,login_flag,lobby_flag,ingame_flag) VALUES(:playerId,:loginFlag,:lobbyFlag,:ingameFlag);");
		$setFlagQuery->bindParam(':playerId',$playerId);
		$loginFlag = 0;
		$lobbyFlag= 0;
		$ingameFlag =0;
		$setFlagQuery->bindParam(':loginFlag',$loginFlag);
		$setFlagQuery->bindParam(':lobbyFlag',$lobbyFlag);
		$setFlagQuery->bindParam(':ingameFlag',$ingameFlag);
		$setFlagQuery->execute();

		//Add verification code with player id into fanodb
		$addVerifyQuery = $connection->prepare("INSERT INTO vr_ls(player_id,verify_code) VALUES (:playerId,:code);");
		$addVerifyQuery->bindParam(':playerId',$playerId);
		$addVerifyQuery->bindParam(':code',$code);
		$addVerifyQuery->execute();

		//Sends the verification url to member
		$this->sendVerification($playerEmail,$code);

		log_it("$playerEmail registration successful, pending verification");
		header('Location:index.html');
	}


	function sendVerification($email, $code)
	{
		//get verifylink
			$config = parse_ini_file('config.php');
			$verifylink = $config['verifylink'];

		$headers='From: nitinkaveriappa@yahoo.in'. "\r\n" .'MIME-Version: 1.0' . "\r\n" .'Content-type: text/html; charset=utf-8' ."\r\n" .'X-Mailer: PHP/' . phpversion();
		$subject = 'Fanorona Account Verification';
		$link = "$verifylink$code";
		$content = "Please copy the link on your browser: $link";
		mail($email,$subject,$content,$headers );
	}


}





?>
