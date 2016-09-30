<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php 

$move="c,b,a";
$vari=explode(",",$move);
var_dump($vari);
echo count($vari);
echo $vari[count($vari)-1];

$valid = array("-10", "-9", "-8", "-1", "1", "8", "9", "10");
var_dump($valid);
$valid = array_merge(array_diff($valid,array("-8")));
var_dump($valid);


 ?>
</body>
</html>