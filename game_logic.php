
<?php

$s1 = "111111111";
$s1 .="111111111";
$s1 .="121201212";
$s1 .="222222222";
$s1 .="222222222";

$s2 = "111111111";
$s2 .="111111111";
$s2 .="121201212";
$s2 .="222222222";
$s2 .="222222222";

$pos=0;
$new = 0;
$old=0;
$ply=1;

for($i=0;$i<45;$i++)
{
	
	if(strcmp($s1[$i],$s2[$i]) < 0)
	{
		$new = $i;
		if($s1[$i]!=0)
		$ply = $s1[$i];
	}
	if(strcmp($s1[$i],$s2[$i]) > 0)
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
		player $ply<br/>
		old.= $s1<br/>
		mov= $s2<br/>"; 


for($i = $new+$pos;$i>=0&&$i<45;)
{
	if($s2[$i] != 0 && $s2[$i] != $ply)
	{
		 $s2[$i] = 0;
		 $i += $pos;
	}
	else 
	break;
}

echo "curr= $s2";
?>