<!-- Home Page -->
<html>
<head>
<?php 
session_start();
$name = $_SESSION['player_name'];
$idle = time() - $_SESSION['created'];
if ($idle > 3000)
{
	header('Location:logout.php');
}
else if($idle > 120)
{
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fanorona|Home</title>
<link href="css/home.css" rel="stylesheet" type="text/css">

<script src="js/home.js" type="text/javascript"></script>

<script>
window.onload = getStats();
</script>
</head>


<body>

<div class="container">


<div class="header" align="center">
	<div class="Title" align="center">
      	<span class="MainTitle">FANORONA</span><br />
    	<span class="SubTitle">SWE 681 Project</span>	
        <span class="Logout" style="float:right"><a href="logout.php">logout</a></span>
    </div>
</div>
<div class="contents" align="center">
	
    <div class="contentData">
		<p> YOU LOST!!! <br/>
       		<a href="home.php">Back to homepage..</a>
        </p>    	
    </div>  
</div>

</div>

</body>
</html>