<?php

$filepath = "/Users/gypsai/Desktop/test.txt";
$handle = fopen($filepath,"r"); //只读形式打开文件，如果大家对这些函数不清楚的可以去查下php手册

echo "start to ----------------";

       if ($handle) {
       		$i = 0;
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                echo "start :".$i;
                echo $buffer;
                echo "\n";
                $i++;

                if($i==20)
                {

                	break;
                }
            }
            fclose($handle);
        }

?>