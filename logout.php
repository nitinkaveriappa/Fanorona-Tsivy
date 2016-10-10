<?php
   session_start();
  require_once('dbconnect.php');
  $playerId = $_SESSION['player_id'];
  	//Unset all the flags
	$unsetFlagsQuery = $connection->prepare("UPDATE flg_ls SET login_flag=0, lobby_flag=0, ingame_flag=0 WHERE player_id=:playerId ;");
	$unsetFlagsQuery->bindParam(':playerId',$playerId);
	$unsetFlagsQuery->execute();
	
	// Unset all of the session variables
	$_SESSION = array();

	// Delete the session cookie
	if (ini_get("session.use_cookies")) 
	{
    	$params = session_get_cookie_params();
    	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	//Destroy session
   	if(session_destroy()) {
      header("Location:index.html");
   }
?>