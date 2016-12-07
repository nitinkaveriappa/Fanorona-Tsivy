// JavaScript Document

function getStats()
{
	var AjaxReq = new XMLHttpRequest();
	AjaxReq.onreadystatechange = function()
	{
		if(AjaxReq.readyState==4)
		{
			var res = AjaxReq.responseText;
			var result = res.split('#');
			total_games = parseInt(result[1],10)+parseInt(result[2],10)+parseInt(result[3],10);
			document.getElementById('welcometxt').innerHTML += '<b>'+result[0]+' !</b>';
			document.getElementById('playerName').innerHTML = result[0];
			document.getElementById('gamesCount').innerHTML = total_games;
			document.getElementById('winCount').innerHTML = result[1];
			document.getElementById('drawCount').innerHTML = result[2];
			document.getElementById('lossCount').innerHTML = result[3];
		}
	}
	AjaxReq.open("POST","player_stats.php",true);
	AjaxReq.send();
}


function newGame()
{
	document.getElementById('gameStatus').innerHTML = "Starting New Game. Looking for opponent...";
	
	var AjaxReq = new XMLHttpRequest();
	AjaxReq.onreadystatechange = function()
	{
		if(AjaxReq.readyState==4)
		{
			var res = AjaxReq.responseText;
			
			if(res=="Connected")
			{
				window.location.href = "game.php";
			}
			else
			{
				document.getElementById('gameStatus').innerHTML = "No opponents found. Try again";
			}
		}
	}
	AjaxReq.open("POST","setup_game.php",true);
	AjaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	AjaxReq.send("type=newGame");
}

function joinGame()
{
	document.getElementById('gameStatus').innerHTML = "Finding New Game. Looking for opponent...";
	
	var AjaxReq = new XMLHttpRequest();
	AjaxReq.onreadystatechange = function()
	{
		if(AjaxReq.readyState==4)
		{
			var res = AjaxReq.responseText;
			if(res=="Connected")
			{
				window.location.href = "game.php";
			}
			else
			{
				document.getElementById('gameStatus').innerHTML = "No opponents found. Try again";
			}
		}
	}
	AjaxReq.open("POST","setup_game.php",true);
	AjaxReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	AjaxReq.send("type=joinGame");
}