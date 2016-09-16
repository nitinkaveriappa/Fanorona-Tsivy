
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
$new=0;
$old=0;
$ply1=0;
$ply2=0;

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
		ply1=$ply1<br/>
		ply2 =$ply2<br/>
		old.= $s1<br/>
		mov= $s2<br/>"; 

if(($pos == 1 || $pos == -1 || $pos == 9 || $pos == -9 || $pos == 8 || $pos == -8 || $pos == 10 || $pos == -10) && (($ply1==0&&$ply2==1) || ($ply1==0&&$ply2==2) || ($ply1==1&&$ply2==0) || ($ply1==2&&$ply2==0)))
{
	for($i = $new+$pos;$i>=0&&$i<45;)
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
else
{
	echo "Invalid Move<br/>";
}

echo "curr= $s2";
?>
