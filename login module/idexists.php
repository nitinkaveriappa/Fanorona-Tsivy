<?php 
require_once('dbconnect.php');
//checks if the email id is already being used by another user

if($_SERVER["REQUEST_METHOD"] == "GET")
{
	$playerEmail = $_REQUEST["m"];
	
	$verifyQuery = $connection->prepare("SELECT player_name FROM pl_mst WHERE player_email=:mailid;");
	$verifyQuery->bindParam(':mailid',$playerEmail);
	$verifyQuery->execute();
	
	$verifyCount = $verifyQuery->rowCount();
	if($verifyCount > 0)
	{
		echo "<br/>ID already exists";
	}
	else
	echo "";
}
?>