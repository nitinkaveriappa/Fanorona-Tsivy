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
<link href="css/game.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/game.js" type="text/javascript"></script>

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
    	<div align='center'><span id="message"></span></div>
		<div id='gameBoard'> 
    	<table id='pawnTable' cellspacing="50px" > 
        	<tr>
            	<td class='pawn' id='0'></td>
                <td class='pawn' id='1'></td>
                <td class='pawn' id='2'></td>
                <td class='pawn' id='3'></td>
                <td class='pawn' id='4'></td>
                <td class='pawn' id='5'></td>
                <td class='pawn' id='6'></td>
                <td class='pawn' id='7'></td>
                <td class='pawn' id='8'></td>
            </tr>
            <tr>
            	<td class='pawn' id='9'></td>
                <td class='pawn' id='10'></td>
                <td class='pawn' id='11'></td>
                <td class='pawn' id='12'></td>
                <td class='pawn' id='13'></td>
                <td class='pawn' id='14'></td>
                <td class='pawn' id='15'></td>
                <td class='pawn' id='16'></td>
                <td class='pawn' id='17'></td>
            </tr>
            <tr>
            	<td class='pawn' id='18'></td>
                <td class='pawn' id='19'></td>
                <td class='pawn' id='20'></td>
                <td class='pawn' id='21'></td>
                <td class='pawn' id='22'></td>
                <td class='pawn' id='23'></td>
                <td class='pawn' id='24'></td>
                <td class='pawn' id='25'></td>
                <td class='pawn' id='26'></td>
            </tr>
            <tr>
            	<td class='pawn' id='27'></td>
                <td class='pawn' id='28'></td>
                <td class='pawn' id='29'></td>
                <td class='pawn' id='30'></td>
                <td class='pawn' id='31'></td>
                <td class='pawn' id='32'></td>
                <td class='pawn' id='33'></td>
                <td class='pawn' id='34'></td>
                <td class='pawn' id='35'></td>
            </tr>
            <tr>
            	<td class='pawn' id='36'></td>
                <td class='pawn' id='37'></td>
                <td class='pawn' id='38'></td>
                <td class='pawn' id='39'></td>
                <td class='pawn' id='40'></td>
                <td class='pawn' id='41'></td>
                <td class='pawn' id='42'></td>
                <td class='pawn' id='43'></td>
                <td class='pawn' id='44'></td>
            </tr>
         </table>
         </div>
    </div>  
</div>

</div>

</body>
</html>