<!-- Home Page -->
<html>
<head>
<?php
session_start();
$name = $_SESSION['player_name'];
$idle = time() - $_SESSION['created'];
if ($idle > 300)
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
<span id="welcometxt"><b>Welcome </b></span>
<div class="contents" align="center">

    <div class="contentData">

    	<button class="button" id="newGameBttn" onClick="newGame()">NEW GAME</button>
     	<button class="button" id="joinGameBttn" onClick="joinGame()">JOIN GAME</button><br/>
        <span id='gameStatus'></span>
    	<br/><br/>
        <table class="statsTable" cellspacing="0px" align="center">
        	<tr><td><b>Player Name</b></td><td align="center" id="playerName"></td></tr>
        	<tr><td><b>Games Played</b></td><td align="center" id="gamesCOunt"></td></tr>
       		<tr><td><b>Games Won</b></td><td align="center" id="winCount"></td></tr>
        	<tr><td><b>Games Drawn</b></td><td align="center" id="drawCOunt"></td></tr>
        	<tr><td><b>Games Loss</b></td><td align="center" id="lossCOunt"></td></tr>
        </table>
    </div>
</div>

</div>

</body>
</html>
