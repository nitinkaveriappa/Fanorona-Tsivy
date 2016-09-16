<?php

$s1 = "111111111111111111121201212222222222222222222";
$s2 = "111111111111111111121210212222222222222222222";

$pos=0;
$new=0;
$old=0;
$ply1=0;
$ply2=0;



for($i=0;$i<45;$i++)
{
	if(strcmp($s1[$i],$s2[$i]) == -1)
	{
		$new = $i;
		if($s1[$i]!=0)
		$ply1 = $s1[$i];
	}
	if(strcmp($s2[$i],$s1[$i]) == -1)
	{
		$old = $i;
		if($s1[$i]!=0)
		$ply2 = $s1[$i];
	}
}

$pos = $new - $old;
echo "	new =$new <br/>
		ply1=$ply1<br/>
		ply2 =$ply2<br/>
		old = $old<br/>
		pos= $pos <br/>
		old= $s1<br/>
		mov= $s2<br/>"; 

if(($pos == 1 || $pos == -1 || $pos == 9 || $pos == -9 || $pos == 8 || $pos == -8 || $pos == 10 || $pos == -10) && (($ply1==0&&$ply2==1) || ($ply1==0&&$ply2==2) || ($ply1==1&&$ply2==0) || ($ply1==2&&$ply2==0)))
{
for($i = $new+$pos;$i>-1&&$i<45;)
{
	if($s2[$i] != 0 && $s2[$i] != $ply2)
	{
		 $s2[$i] = 0;
		 $i += $pos;
	}
	else 
	break;
		
}
}
else{
echo "Invalid Move<br/>";}
echo "cur= $s2";
?>
