<?php
include('log.php');

if(isset($_REQUEST['error']) && $_REQUEST['error'])
{
	$error = $_REQUEST['error'];
}

if($error=="dberror")
{
log_it("Database ERROR");
header("Location:errorpage.html");
}

?>
