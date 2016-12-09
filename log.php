<?php
//call log_it along with the necessary 'message' to be written in log file
function log_it($message)
{
  $config = parse_ini_file('config.php');
  $logfile = $config['logfile'];
  
  // Get time of request
  if( ($time = $_SERVER['REQUEST_TIME']) == '') {
    $time = time();
  }

  // Get IP address
  if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
    $remote_addr = "REMOTE_ADDR_UNKNOWN";
  }

  // Get requested script
  if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
    $request_uri = "REQUEST_URI_UNKNOWN";
  }

  // Format the date and time
  date_default_timezone_set('America/New_York');
  $date = date("Y-m-d H:i:s", $time);

  // Append to the log file
  if(file_exists($logfile))
  {
  	$writelog = fopen($logfile, "a");
  	$logdata = fputcsv($writelog, array($date, $remote_addr, $request_uri, $message));
  	fclose($writelog);
  }

}
//Test if log.php is working or not
//$test = log_it("test logger");
//output format
//"2016-10-31 23:16:09",::1,/log.php,"test logger"
?>
