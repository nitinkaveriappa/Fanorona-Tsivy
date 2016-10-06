<?php 
session_start();
require_once('dbconnect.php');
//Registers a new player into the player master table. 
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	//Verifies Name is in valid format
	if(isset($_POST['rMemName']) && preg_match("/^[a-zA-Z0-9.']+/",$_POST['rMemName'])  && strlen($_POST['rMemName']) < 35)
	{	 
		$playerName = $_POST['rMemName'];		
	}
	else
	{  
		header("Location:index.html?type=err");
	}
	//Verifies Email is in valid format
	if(isset($_POST['rMemEmail']) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,4}/',$_POST['rMemEmail'])  && strlen($_POST['rMemEmail']) < 50)
	{	 
		$playerEmail = $_POST['rMemEmail'];		
	}
	else
	{  
		header("Location:index.html?type=err");
	}
	//Verifies Password is in valid format
	if(isset($_POST['rMemPassword']) && preg_match('/^[a-zA-Z0-9._%+!$@]+/',$_POST['rMemPassword']) && strlen($_POST['rMemPassword']) < 30)
	{ 
		$playerPassword = $_POST['rMemPassword'];	
	}
	else
	{  
		header("Location:index.html?type=err");
	}
	
	include('player_access.php');
	$register = new access();
	$register->register_player($playerName,$playerEmail,$playerPassword);
	
	
}
	
	
?>
