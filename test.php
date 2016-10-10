<?php 
$headers='From: paurav66@yahoo.com'. "\r\n" .
         'MIME-Version: 1.0' . "\r\n" .
         'Content-type: text/html; charset=utf-8' ."\r\n" .
		 'X-Mailer: PHP/' . phpversion();
		 
if(mail('nitinkaveriappa@gmail.com','test','hello',$headers ))
{
	echo "works";
}
?>