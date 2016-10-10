<?php
//Registers a new player into the player master table.
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	//Verifies Name is in valid format
	if(isset($_POST['rMemName']) && preg_match("/^[a-zA-Z0-9.' ]+/",$_POST['rMemName'])  && strlen($_POST['rMemName']) < 35)
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
	if(isset($_POST['rMemPassword']) && preg_match('/^[a-zA-Z0-9._%+!$@ ]+/',$_POST['rMemPassword']) && strlen($_POST['rMemPassword']) < 30)
	{
		$playerPassword = $_POST['rMemPassword'];
	}
	else
	{
		header("Location:index.html?type=err");
	}

	//Verifies the captcha is set
	if(isset($_POST['g-recaptcha-response']))
	{
		$captcha=$_POST['g-recaptcha-response'];
	}
	else
	{
		header("Location:index.html?type=err");
	}


	//Validates the captcha value from google server
	$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdahgcUAAAAAOd6zMYFIhe7KLFIN_m-yva7iWt7&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$data = json_decode($response);


	//If its a bot it redirects to index
	if($data->success==false)
		 {
			 header("Location:index.html?type=err");
		 }
	//If not a bot 	then tries to register the player
	else
	{
		include('player_access.php');
		$register = new access();
		$register->register_player($playerName,$playerEmail,$playerPassword);
	}

}


?>
