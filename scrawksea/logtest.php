<?php


for($i=0;$i<20;$i++)
{
	printLog("./log","hahah".$i."\n",a);

}

	function printLog($file,$str,$mode='w') 
{ 
$oldmask = @umask(0); 
$fp = @fopen($file,$mode); 
	@flock($fp, 3); 
if(!$fp) 
{ 
	return false; 
} 
else 
{ 
	@fwrite($fp,$str); 
	@fclose($fp); 
	@umask($oldmask); 
	return true; 
} 
} 


?>