<?php

//Verifies the login credentials, directs to home page or login page 
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	//Verifies Username(email id) is in valid format
	if(isset($_POST['userName']) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,4}$/',$_POST['userName'])  && strlen($_POST['userName']) < 50)
	{	 
		$userName = $_POST['userName'];		
	}
	else
	{  
		header("Location:index.html?type=err");
	}
	//Verifies Password is in valid format
	if(isset($_POST['userPassword']) && preg_match("/^[a-zA-Z0-9._%+!$@ ]+$/",$_POST['userPassword']) && strlen($_POST['userPassword']) < 30)
	{ 
		$userPassword = $_POST['userPassword'];	
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
	//If not a bot 	then tries to login in player
	else
      {
		  include('player_access.php');
		  $login = new access();
          $login->login_player($userName, $userPassword);
	  }

}
?>