<?php
class game_logic
{
	var $s1;
	var $s2;
	var $move_num;
	var $restrict_move;
	var $curr;
	var $pos;
	var $new;
	var $old;
	var $player;
	var $turn;

	//Set variables
	function __construct($node1, $node2, $move_count, $restrict_moves){
		$this->s1 = $node1;
		$this->s2 = $node2;
		$this->move_num = $move_count;
		$this->restrict_move = explode(",",$restrict_moves);

		$this->curr = $this->s2;

		$this->pos=0;
		$this->new=0;
		$this->old=0;
		$this->player=0;
		$this->turn=0;
	}


	//Checks to see if the win condition has been reached
	//If $flag=1 then win
	function check_win()
	{
		$flag=1;
		for($i=0;$i<45;$i++)
		{
			if($this->s2[$i]!=0)
			{
				if($this->s2[$i]!=$this->player)
				{
					$flag=0;
				}
			}
		}
		return $flag;
	}

	//To validate if legal move
	function check_validmove()
	{
		$validate = false;

		if(($this->old % 2) == 0)
		{
			$valid = array("-10", "-9", "-8", "-1", "1", "8", "9", "10");
		}
		else
		{
			$valid = array("-9", "-1", "1", "9");
		}

		//Removes the previous move direction value to prevent move again in the same direction
		$move = $this->old - ($this->restrict_move[count($this->restrict_move)-1]);
		$valid = array_merge(array_diff($valid, array($move)));


		for($i=0;$i<sizeof($valid);$i++)
		{
			if($this->pos == $valid[$i])
			{
				$validate = true;
			}
		}
		// To validate if pawn has moved to a empty slot
		if($validate)
		{
			if( $this->player > 0 && $this->player < 3)
			{
				$validate = true;				//echo "valid move";
			}
			else
			{
				$validate = false;				//echo "invalid move";
			}
		}

		//To check that move is not made into a previously held position while on move increment
		for($i=0;$i<count($this->restrict_move);$i++)
		{
			if($this->new == $this->restrict_move[$i])
			{
				$validate = false;			//echo "Cant move to same spot";
			}
		}
		return $validate;
	}

	// To read the change in the state
	function check_change(){

		for($i=0;$i<45;$i++)
		{
			if($this->s1[$i] != $this->s2[$i])
			{
				if($this->s1[$i] < $this->s2[$i])
				{
					$this->new = $i;
				}
				else
				{
					$this->old = $i;
				}
			}

		}
		$this->pos = $this->new - $this->old;
		if($this->s2[$this->new]!=$this->s2[$this->old])
		$this->player = $this->s2[$this->new]+$this->s2[$this->old];

	}


	function remove_pawn(){
		//Removes the pawns related
		$player_change = true;
		if($this->check_validmove())
		{
		$level = (int)$this->old/9;


			for($i = $this->new + $this->pos;$i>=0&&$i<45;)
			{
				if($this->pos == 1 || $this->pos == -1)
				{
					if($this->s2[$i] != 0 && $this->s2[$i] != $this->player && $i/9 == $level)
					{
						$this->curr[$i] = 0;
						$i += $this->pos;
						$player_change = false;				//If pawns are removed then same player plays next turn
					}
					else
						break;
				}
				else if($this->pos == 8 || $this->pos == 9 || $this->pos == 10)
				{
					if($this->s2[$i] != 0 && $this->s2[$i] != $this->player && $i/9 == ++$level)
					{
						$this->curr[$i] = 0;
						$i += $this->pos;
						$player_change = false;				//If pawns are removed then same player plays next turn
					}
					else
						break;

				}
				else if($this->pos == -8 || $this->pos == -9 || $this->pos == -10)
				{
					if($this->s2[$i] != 0 && $this->s2[$i] != $this->player && $i/9 == --$level)
					{
						$this->curr[$i] = 0;
						$i += $this->pos;
						$player_change = false;				//If pawns are removed then same player plays next turn
					}
					else
						break;

				}
			}

			if($player_change)
			{
			$this->pos *= -1;
			$level = (Int)$this->old/9;

			for($i = $this->new + $this->pos;$i>=0&&$i<45;)
			{
				if($this->pos == 1 || $this->pos == -1)
				{
					if($this->s2[$i] != 0 && $this->s2[$i] != $this->player && $i/9 == $level)
					{
						$this->curr[$i] = 0;
						$i += $this->pos;
						$player_change = false;				//If pawns are removed then same player plays next turn
					}
					else
						break;
				}
				else if($this->pos == 8 || $this->pos == 9 || $this->pos == 10)
				{
					if($this->s2[$i] != 0 && $this->s2[$i] != $this->player && $i/9 == ++$level)
					{
						$this->curr[$i] = 0;
						$i += $this->pos;
						$player_change = false;				//If pawns are removed then same player plays next turn
					}
					else
						break;

				}
				else if($this->pos == -8 || $this->pos == -9 || $this->pos == -10)
				{
					if($this->s2[$i] != 0 && $this->s2[$i] != $this->player && $i/9 == --$level)
					{
						$this->curr[$i] = 0;
						$i += $this->pos;
						$player_change = false;				//If pawns are removed then same player plays next turn
					}
					else
						break;

				}
			}

			}

		$this->turn = $this->decide_turn($player_change);

			//Check if Player has made the winning move
			if($this->check_win())
			{
				$status =2; 			//Win
			}
			else
			{
				$status =1;				//Valid Move
			}
		}
		else
		{
			//Checks if move is Not made at all
			if ($this->new == 0 && $this->old == 0)
			{
				$status =3;				//No move made
			}
			else
			{
				$status =4;				//Invalid Move
			}
		}

		$returnValue = $status.','.$this->curr.','.$this->turn;
		return $returnValue;
	}

	function decide_turn($player_change)
	{
		$next_player = $this->player;
		if(!$player_change)							//If the turn doesnot change
		{
				$player_change = true;
				$empty_count = 0;
				$empty_nodes = array();
				$k = 0;

				if(($this->new % 2) == 0)
				{
					$valid = array("-10", "-9", "-8", "-1", "1", "8", "9", "10");
				}
				else
				{
					$valid = array("-9", "-1", "1", "9");
				}

				for($i=0; $i<sizeof($valid)-1; $i++)
					{
						if(	$this->new+$valid[$i] >= 0 && $this->new+$valid[$i] <= 44)
						{				//Check if valid moves are empty and not in the same direction
							if($this->curr[$this->new + $valid[$i]] == 0 && $valid[$i] != $this->pos && $this->new + $valid[$i] != $this->old )
							{
								$empty_nodes[$k++] = $this->new+$valid[$i];				//Add the postion to empty node array
							}
						}
					}
			$empty_count = sizeof($empty_nodes);
				for($i=0;$i<sizeof($empty_nodes)-1;$i++)
				{
					for($j=0;$j<sizeof($this->restrict_move)-1;$j++)
					{
						if(	$empty_nodes[$i] == $this->restrict_move[$j])			//Check if position is not restricted move
						{
								$empty_count--;										//Increase the available move counter
						}
					}
				}

				if($empty_count > 0)												//If atleast 1 valid move is available
				{
					$player_change=false;											//Then turn remains in the same player
				}
		}

			if($player_change)														//If turn changed switch the next player  value
			{
				if($this->player == 1)
					$next_player = 2;
				else
					$next_player = 1;

				return $next_player.','.$player_change;												//Return just the player number
			}
			else											//If not, return the player and add the old position and the temporary resticted move along the same direction
			{
				$forbidden = $this->pos+$this->new;
				return $next_player.'.'.$player_change.','.$this->old.','.$forbidden;
			}

	}
	function run_game()
	{
		$this->check_change();
		$returned=$this->remove_pawn();
		return $returned;
	}
}

?>
