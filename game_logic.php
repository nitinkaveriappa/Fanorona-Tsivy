<?php

$s1 = "111111111111111111121201212222222222222222222";
$s2 = "111111111111101111121211212222222222222222222";

$pos=0;
$new = 0;
$old=0;
$ply=0;

for($i=0;$i<45;$i++)
{
	if(strcmp($s1[$i],$s2[$i]) == -1)
	{
		$new = $i;
		if($s1[$i]!=0)
		$ply = $s1[$i];
	}
	if(strcmp($s2[$i],$s1[$i]) == -1)
	{
		$old = $i;
		if($s1[$i]!=0)
		$ply = $s1[$i];
	}
}
$pos = $new - $old;
echo "	new =$new <br/>
		old = $old<br/>
		pos= $pos <br/>
		old= $s1<br/>
		mov= $s2<br/>"; 


for($i = $new+$pos;$i<45;)
{
	if($s2[$i] != 0 && $s2[$i] != $ply)
	{
		 $s2[$i] = 0;
		 $i += $pos;
	}
	else 
	break;
		
}
echo "cur= $s2";
?>