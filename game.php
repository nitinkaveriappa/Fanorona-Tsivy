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
<script src="js/home.js" type="text/javascript"></script>

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
		<div id='gameBoard'>
        <!-- <img src="Images/layout.jpg" width="1040px" height="600px"/> --> 
    	<table id='pawnTable' cellspacing="50px" > 
        	<tr>
            	<td class='pawn' id='A1'></td>
                <td class='pawn' id='A2'></td>
                <td class='pawn' id='A3'></td>
                <td class='pawn' id='A4'></td>
                <td class='pawn' id='A5'></td>
                <td class='pawn' id='A6'></td>
                <td class='pawn' id='A7'></td>
                <td class='pawn' id='A8'></td>
                <td class='pawn' id='A9'></td>
            </tr>
            <tr>
            	<td class='pawn' id='B1'></td>
                <td class='pawn' id='B2'></td>
                <td class='pawn' id='B3'></td>
                <td class='pawn' id='B4'></td>
                <td class='pawn' id='B5'></td>
                <td class='pawn' id='B6'></td>
                <td class='pawn' id='B7'></td>
                <td class='pawn' id='B8'></td>
                <td class='pawn' id='B9'></td>
            </tr>
            <tr>
            	<td class='pawn' id='C1'></td>
                <td class='pawn' id='C2'></td>
                <td class='pawn' id='C3'></td>
                <td class='pawn' id='C4'></td>
                <td class='pawn' id='C5'></td>
                <td class='pawn' id='C6'></td>
                <td class='pawn' id='C7'></td>
                <td class='pawn' id='C8'></td>
                <td class='pawn' id='C9'></td>
            </tr>
            <tr>
            	<td class='pawn' id='D1'></td>
                <td class='pawn' id='D2'></td>
                <td class='pawn' id='D3'></td>
                <td class='pawn' id='D4'></td>
                <td class='pawn' id='D5'></td>
                <td class='pawn' id='D6'></td>
                <td class='pawn' id='D7'></td>
                <td class='pawn' id='D8'></td>
                <td class='pawn' id='D9'></td>
            </tr>
            <tr>
            	<td class='pawn' id='E1'></td>
                <td class='pawn' id='E2'></td>
                <td class='pawn' id='E3'></td>
                <td class='pawn' id='E4'></td>
                <td class='pawn' id='E5'></td>
                <td class='pawn' id='E6'></td>
                <td class='pawn' id='E7'></td>
                <td class='pawn' id='E8'></td>
                <td class='pawn' id='E9'></td>
            </tr>
         </table>
         </div>
    </div>  
</div>

</div>

</body>
</html>