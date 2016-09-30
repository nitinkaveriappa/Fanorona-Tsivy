<?php 
session_start();
require_once('dbconnect.php');
//Registers a new player into the player master table. 
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	
	$playerName = $_POST['rMemName'];
	$playerEmail = $_POST['rMemEmail'];
	$playerPassword = $_POST['rMemPassword'];
	
	
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
		
	
	//Add verification code with player id into fanodb
	$addVerifyQuery = $connection->prepare("INSERT INTO vr_ls(player_id,verify_code) VALUES (:playerId,:code);");
	$addVerifyQuery->bindParam(':playerId',$playerId);
	$addVerifyQuery->bindParam(':code',$code);
	$addVerifyQuery->execute();
	
	//Sends the verification url to member
	sendVerification($playerEmail,$code);
		
	header('Location:index.php');
}


function sendVerification($email, $code)
{
	$headers='From: paurav66@yahoo.com'. "\r\n" .'MIME-Version: 1.0' . "\r\n" .'Content-type: text/html; charset=utf-8' ."\r\n" .'X-Mailer: PHP/' . phpversion();
	$subject = 'Fanorona Account Verification';
	$link = "https://localhost/fan/verify.php?code=$code";
	$content = "Please copy the link on your browser: $link";
	mail($email,$subject,$content,$headers );
}
?>
