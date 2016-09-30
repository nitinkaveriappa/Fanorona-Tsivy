<?php

//Program to get the stats of a player
//Program requires PlayerID as I/p

class stats
{
	var $player_id;
	var $player_name;
	var $win_count;
	var $draw_count;
	var $loss_count;
	
	function __construct($info)
	{
		$this->player_id = $info;
	}
	
	function get_the_stats()
	{
		require_once('dbconnect.php');
		$query = "SELECT A.player_name, B.win_count, B.draw_count, B.loss_count FROM pl_mst A, pl_sts B WHERE A.player_id=B.player_id AND A.player_id=:playerID;";
		$runQuery = $connection->prepare($query); 
	
		$runQuery->bindParam(':playerID',$this->player_id);
		$runQuery->execute();

		// set the resulting array to associative
		$result = $runQuery->fetch(PDO::FETCH_ASSOC);
		$this->player_name = $result['player_name'];
		$this->win_count = $result['win_count'];
		$this->draw_count = $result['draw_count'];
		$this->loss_count = $result['loss_count'];

		$connection = null;
	}
	
	function print_stats()
	{
		echo "<br/>Player Stats";
		echo "<table>"; // start a table tag in the HTML

		echo "<tr><td>" . $this->player_name . "</td><td>" . $this->win_count . "</td></td>". $this->draw_count . "</td></td>" . $this->loss_count . "</td></tr>";

		echo "</table>"; //Close the table in HTML
	}
	
	function run_stats()
	{
		$this->get_the_stats();
		$this->print_stats();
	}
}
?>