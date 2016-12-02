// JavaScript Document

var turn =0;
var restrictedMoves = new Array();
//Loads the node when the page loads
$(function()
{
	getGameState();
});

//Gets the state of all the pawns and the player turn in the game 
function getGameState()
{
	var func = "type=state";
	var funcURL = "play_game.php";
	$.ajax({
		url:funcURL, 
		data: func, 
		type:"POST", 
		dataType:"json",
		success: function(response)
							{
								nodeList = response.node_list;
								turn = response.player_turn;
								if(response.restricted_moves != '')
									restrictedMoves = response.restricted_moves.split(',');
								alert(nodeList+'---'+turn+'---'+restrictedMoves.length);
								clearBoard();
								setPlayerColor(nodeList);
								setPlayerTurn(turn);
							},
		error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError); }
	});
}
//Clears all the classes at each pawn 
function clearBoard()
{
	$('.pawn').each( function() { 		
										$(this).removeClass('player1');
										$(this).removeClass('player2');
										$(this).removeClass('empty');
								});
}

//Sets the pawn colors based on player and current state of the game
function setPlayerColor(nodeList)
{
	for(var i=0; i<45; i++)
	{
		var id = '#'+i;
		if(nodeList[i] == 1)
			{
				$(id).addClass('player1');
			}
		if(nodeList[i] == 2)
			{
				$(id).addClass('player2');
			}
		if(nodeList[i] == 0)
			{
				$(id).addClass('empty');
			}
	}	if(restrictedMoves.length > 0 && turn != 0)
		{
			for(var i=0; i<restrictedMoves.length-1;i++)
			{
				var index = '#'+restrictedMoves[i];
				$(index).removeClass('empty').addClass('restricted');
			}
		}
	
	
}

//Enables the onclick function to select pawn for the player whose turn it is to play.
function setPlayerTurn(turn)
{
	if(turn == 0)
		{
			$('#message').text("Waiting for opponent to make a move");
			waitForOpponent();
		}
		else if(turn == 1 || turn == 2)
		{
			$('#message').text("");
			var player = '.player'+turn;
			$(player).each( function() { $(this).on('click',selectPawn) });
		}
}
	
//Allows the player to pick a pawn to move 
function selectPawn()
{
		$('.pawn').each( function() { 
										$(this).removeClass('selected');		//remove existing selected pawn
										$(this).removeClass('available');		//remove existing available pawns
										$(this).off('click',makeMove)		//remove the makeMove function from available pawns
										});
		var id = $(this).attr("id");
		var rid = "#"+id;
		$(rid).addClass('selected');
		movesAvailable(id);
}
			
//Finds the moves available for a selected pawn
function movesAvailable(id)
{
	if((id % 2) == 0)
		{
			var valid = new Array("-10", "-9", "-8", "-1", "1", "8", "9", "10"); 
		}
	else {
			var valid = new Array("-9", "-1", "1", "9");
		}
		
	//Checks if any of valid moves are empty
	for(var i=0; i<valid.length; i++)
	{		
		
		var nextID = parseInt(id,10) + parseInt(valid[i],10); 
		
		if(parseInt(nextID,10) >= 0)
		{	
			nextID = '#'+nextID;
			if($(nextID).hasClass('empty'))				//If empty, make it available for move and adds the makeMove method to each of these nodes
			{
				$(nextID).addClass('available');		
				$(nextID).on('click',{'oldID':id},makeMove);
			}
		}
	}
}

//Moves the pawn from old node to new node
function makeMove(param)
{
	id = $(this).attr('id');
	oldID = param.data.oldID;
	restrictedMoves.push(oldID);
	
	oldID = '#'+oldID;
	id= '#'+id;
	
	//If current player is player 1 or player 2, change the empty node to appropriate player pawn and make old node empty
	if(turn == 1)
	{
		$(oldID).removeClass('player1');
		$(id).addClass('player1');
	}	
	
	if(turn == 2)
	{
		$(oldID).removeClass('player2');
		$(id).addClass('player2');
	}
	//Make the old node empty
	$(oldID).addClass('empty');
	
	//Remove the empty class from new node
	$(id).removeClass('empty') 
	
	//Disable all other classes and methods after the move
 	$('.pawn').each(function() { 	$(this).removeClass('available');
									$(this).removeClass('selected');
									$(this).off('click',selectPawn);
									$(this).off('click',makeMove); });
	sendGameState();
}

//Send the current state of the game to the server
function sendGameState()
{
	var currentNodeList = '';
	$('.pawn').each(function() { 
									if($(this).hasClass('player1'))
										currentNodeList += 1;
									else if($(this).hasClass('player2'))
										currentNodeList += 2;
									else if($(this).hasClass('empty') || $(this).hasClass('restricted'))
										currentNodeList += 0;
									else 
										currentNodeList = "Error";
								});
									
	var data = "type=play&nodeList="+currentNodeList;
	var url = "play_game.php" ; 
	alert("Sending:"+currentNodeList);
	$.ajax({
			url: url,
			data: data,
			type: 'POST',
			dataType: 'text',
			success: function(response){	alert("Response status: "+response);
											switch(response)
											{
												case '1': getGameState(); break;
												case '2': winCondition(); break;
												case '3': getGameState(); break;
												case '4': getGameState(); break;
											}
										},
			error: function() {alert("Move made Error")} 
			
			});
}


function winCondition() 
{
	href.location('winner.php');	
	
}


function waitForOpponent()
{
	setTimeout(checkMoveMade, 10000);
}

function checkMoveMade()
{
	var url = "play_game.php";
	var data = "type=check";
	$.ajax({
			url: url,
			data: data,
			type: 'POST', 
			dataType: 'text',
			success: function(response){	alert("Response="+response);
											switch(response)
											{
												case '1': getGameState(); break;
												case '2': waitForOpponent(); break;
											}
										},
			error: function() {alert("Error")} 
			
			});
}