<?php
require_once("dbconnect.php");
session_start();
set_time_limit(100);
//Verifies the login credentials, directs to home page or login page 
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST['userName']))
	{ $userName = $_POST['userName'];		}
	if(isset($_POST['userPassword']))
	{ $userPassword = $_POST['userPassword'];	}
	if(isset($_POST['g-recaptcha-response']))
	{ $captcha=$_POST['g-recaptcha-response']; 	}
	else
	{  //header("Location:index.php?type=err");
		echo "failed";   					}
	
	//Load Secret key from config file
	$config = parse_ini_file('config.ini'); 
	$key = $config['secret'];
	//captcha
	 $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	 $data = json_decode($response);
	 if($data->success==false)
        {
          echo 'failed';
        }
		
	else
      {
        $loginQuery = $connection->prepare("SELECT * FROM pl_mst WHERE player_email=:mailID");
		$loginQuery->bindParam(':mailID',$userName);
		$loginQuery->execute();
		$loginResultCount = $loginQuery->rowCount();
		$loginResultRow = $loginQuery->fetch(PDO::FETCH_ASSOC);
		$cryptedPassword = $loginResultRow['player_password'];
	
		if($loginResultCount == 1 && password_verify($userPassword, $cryptedPassword))
		{
			/* $_SESSION['member_id'] =$loginResultRow['member_id'];
			$_SESSION['member_name'] =$loginResultRow['member_name'];
			$_SESSION['member_type']=$loginResultRow['member_type'];
			header("Location:home.php"); */
			echo "logged in";
		}
		else
		{
			//header("Location:index.php?type=err");
			echo "failed";
		}
	  }

}
?>