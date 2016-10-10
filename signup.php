<!-- Add new member to the databse -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FANORONA|Register</title>
<link href="css/login.css" rel="stylesheet" type="text/css">
<link href="css/home.css" rel="stylesheet" type="text/css">
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php session_start(); ?>
<style>
.contents {
	padding-top:200px;
}
</style>
<script>
window.onload = function(){


};
</script>
<script src="js/register.js" type="text/javascript"></script>
</head>

<body>

<div class="container">


<div class="header" >
	<div class="Title" align="center">
		<span class="MainTitle">FANORONA</span><br />
   		<span class="SubTitle">SWE 681 Project</span>
    </div>
	<div class="Logout" align="right" >
    	<a href="logout.php" style="vertical-align:middle;">Logout</a></div>
	</div>
</div>

<div class="contents" align="center">
	<div class="contentData">
    	<span class="heading">REGISTER</span>

    <div class="registerForm">
	<form action="createPlayer.php" method="post" onsubmit="return validate();" >
   		<input class="formDetail" type="text" name = "rMemName"  placeholder="Member Name" size="30px" maxlength="35" pattern="^[a-zA-Z0-9.' ]+$" required><br/><br/>
    	<input class="formDetail" type="text" name = "rMemEmail"  placeholder="Email" size="30px" onchange="EmailChecking(this.value);" maxlength="50" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,4}"><br/>
        <span id="EmailExist" style="color:#F00;"></span><br/>
		<input class="formDetail" type="password" name = "rMemPassword" placeholder="Password" size="30px" maxlength="30" pattern="^[a-zA-Z0-9._%+!$@ ]+$" required><br/><br/>
    	<input class="formDetail" type="password" name = "rMemPasswordVerify" placeholder="Retype Password" size="30px" required ><br/><br/>
			<div class="g-recaptcha" data-sitekey="6LdahgcUAAAAAAm5QqZCBVaaIvZIhL5ehTSPXsd2"></div><br/>
        <button class="formBttn" type="submit" name="registerBttn" >Register</button><br/><br/>
    </form>
    </div>

	</div>

</div>

</body>
</html>
