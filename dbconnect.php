<?php

function db_connect() {

        // Define connection as a static variable, to avoid connecting more than once
    static $connection;

        // Try and connect to the database, if a connection has not been established yet
    if(!isset($connection)) {
             // Load configuration as an array. Use the actual location of your configuration file
        $config = parse_ini_file('config.php');
		$servername = $config['servername'];
		$dbname = $config['dbname'];

		try {
			$connection = new PDO("mysql:host=$servername;dbname=$dbname", $config['username'], $config['password']);
			// set the PDO error mode to exception
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$setDBQuery = $connection->prepare("USE fanodb;");
			$setDBQuery->execute();
		}
		// If connection was not successful, handle the error
		catch(PDOException $e)
		{
			 // Handle error - notify administrator, log to a file, show an error screen, etc.
			echo "Connection failed: " . $e->getMessage();
		}
    }
    return $connection;
}

// Connect to the database
$connection = db_connect();

?>
