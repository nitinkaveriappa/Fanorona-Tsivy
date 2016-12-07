<?php 
require_once('dbconnect.php');
//Get code from URL
if(isset($_REQUEST['code']) && $_REQUEST['code'])
{
	$code = $_REQUEST['code'];	
}

//Get player ID for the code from database
$getPlayerQuery = $connection->prepare("SELECT player_id FROM vr_ls WHERE verify_code=:code;");
$getPlayerQuery->bindParam(':code',$code);
$getPlayerQuery->execute();

//Update the verify flag in player master and remove the tuple from verify list
if($getPlayerQuery->rowCount() == 1)
{
	$result = $getPlayerQuery->fetch(PDO::FETCH_ASSOC);
	$id = $result['player_id'];
	$verifyPlayerQuery = $connection->query("UPDATE pl_mst SET player_verified=1 WHERE player_id=$id;");
	$deleteVerifyQuery = $connection->query("DELETE FROM vr_ls WHERE player_id=$id;");
	header("LOCATION:index.html");
}
else
{
echo "VERIFICATION FAILED";
}
?>