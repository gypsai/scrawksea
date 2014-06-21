<?php


$db_host="127.0.0.1";//服务器
$db_user="root";    //用户名
$db_psw="luom1ng";    //密码
$db_name="sku_source";//数据库名
$filepath = "/Users/gypsai/Desktop/productcsv/060307.csv";
$Colcount = 7;
 //接入数据库
 $conn = mysql_connect($db_host,$db_user,$db_psw) or die("链接错啦");
 mysql_select_db($db_name,$conn) or die("链接又错啦");
 mysql_query("set names utf-8");

//$filepath = $_REQUEST['file']; //获取表单文本框的值 
ini_set("auto_detect_line_endings", 1);
$file = fopen($filepath,"r"); //只读形式打开文件，如果大家对这些函数不清楚的可以去查下php手册
//通过while循环将文件里面的内容一列一列的存入数据库
$count = 1;


//str_replace(PHP_EOL, '', $str);

while(!feof($file) && $data = fgetcsv($file,1000,';'))
{
  $result = array();
     // $cols = $data;
     // foreach( $cols as $key => $val ) {
     //        $cols[$key] = trim( $cols[$key] );
     //        $cols[$key] = iconv('UCS-2', 'UTF-8', $cols[$key]."\0") ;
     //        $cols[$key] = str_replace('""', '"', $cols[$key]);
     //        $cols[$key] = preg_replace("/^\"(.*)\"$/sim", "$1", $cols[$key]);
     //    }

    var_dump($data);

    echo "-----------------".$count."\n";
    echo count($data);



  if($count>1 && !empty($data))
  {
    for($i=0;$i<$Colcount;$i++)
    {
          array_push($result,trim($data[$i]));
    }
//利用sql语句讲文件内容存入数据库
//060301  $sql = sprintf("insert into source_060301 (name,price,sku) values ('%s','%s','%s')",$result[0],$result[1],$result[2]);
//060302 $sql = sprintf("insert into source_060302 (sku,name,rule,unit,make_place,price,notice,normal_price,other_price) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s')",$result[0],$result[1],$result[2],$result[3],$result[4],$result[5],$result[6],$result[7],$result[8]);
//060303  $sql = sprintf("insert into source_060303 (name,spec,sku) values ('%s','%s','%s')",$result[0],$result[1],$result[2]);
//060304       $sql = sprintf("insert into source_060304 (name,cat,sku,remain,im_price,price) values ('%s','%s','%s','%s','%s','%s')",$result[0],$result[1],$result[2],$result[3],$result[4],$result[5]);
//060305 $sql = sprintf("insert into source_060305 (sku,name,spec,unit,price,cost,remain,make_place,discription,catgory,band) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$result[0],$result[1],$result[2],$result[3],$result[4],$result[5],$result[6],$result[7],$result[8],$result[9],$result[10]);
//060306 $sql = sprintf("insert into source_060306 (sku,name,unit,spec,make_place,price,csprc,rsv,r_id) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s')",$result[0],$result[1],$result[2],$result[3],$result[4],$result[5],$result[6],$result[7],$result[8]);

$sql = sprintf("insert into source_060307 (sku,name,unit,spec,make_place,slprc,csprc) values ('%s','%s','%s','%s','%s','%s','%s')",$result[0],$result[1],$result[2],$result[3],$result[4],$result[5],$result[6]);

    echo $sql;
    mysql_query($sql);
  }
 $count++;
}
fclose($file); //关闭文件




?>