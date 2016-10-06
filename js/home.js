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
			document.getElementById('gamesCOunt').innerHTML = total_games;
			document.getElementById('winCount').innerHTML = result[1];
			document.getElementById('drawCOunt').innerHTML = result[2];
			document.getElementById('lossCOunt').innerHTML = result[3];
		}
	}
	AjaxReq.open("POST","player_stats.php",true);
	AjaxReq.send();
}