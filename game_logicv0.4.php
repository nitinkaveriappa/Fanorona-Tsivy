<?php
/**
 * Created by PhpStorm.
 * User: Paurav & Nitin
 * Date: 9/18/2016
 * Time: 10:09 PM
 */
class game_logic
{
	var $s1;
	var $s2;
	var $curr;
	var $pos;
	var $new;
	var $old;
	var $player;
	
	//Set variables
	function __construct($node1, $node2){
		$this->s1 = $node1;
		$this->s2 = $node2;
	
		$this->curr = $this->s2;
		
		$this->pos=0;
		$this->new=0;
		$this->old=0;
		$this->player=0;
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

	function check_validmove(){
		$validate = false;
		//To validate if legal move
		if(($this->old % 2) == 0)
		{
			$valid = array("-9", "-1", "1", "9");
		}
		else {
			$valid = array("-10", "-9", "-8", "-1", "1", "8", "9", "10");
		}

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
				$validate = true;
				echo "valid move<br/>";
			}
			else
			{
				$validate = false;
				echo "invalid move<br/>";
			}
		}
		return $validate;
	}

	function check_change(){
		// To read the change in the state
		for($i=0;$i<45;$i++)
		{
			if($this->s1[$i] != $this->s2[$i])
			{
				if($this->s1[$i] < $this->s2[$i])
				{
					$this->new = $i;
				}
			else
				$this->old = $i;
			}

		}
		$this->pos = $this->new - $this->old;
		if($this->s2[$this->new]!=$this->s2[$this->old])
		$this->player = $this->s2[$this->new]+$this->s2[$this->old];
	}

	function print_stats(){
		echo "	new = $this->new <br/>
				old = $this->old <br/>
				pos = $this->pos <br/>
				player = $this->player <br/>
				old = $this->s1 <br/>
				mov = $this->s2 <br/>
				curr= $this->curr<br/>";	
	}
			
	function remove_pawn(){
		//Removes the pawns related
		if($this->check_validmove())
		{
			for($i = $this->new + $this->pos;$i>=0&&$i<45;)
			{
				if($this->s2[$i] != 0 && $this->s2[$i] != $this->player)
				{
					$this->curr[$i] = 0;
					$i += $this->pos;
				}
				else
					break;
			}
			
			//Check if Player has made the winning move
			if($this->check_win())
			{
				echo "Player $player Wins";
			}
		}
		else {
			//Checks if move is Not made at all
			if ($this->new == 0 && $this->old == 0) {
				echo "No Move Made<br/>";
			} 
			else {
				echo "Invalid Move<br/>";
			}
		}
	}
	
	function run_game()
	{
		$this->check_change();
		$this->remove_pawn();
		$this->print_stats();
	}
} 

?>