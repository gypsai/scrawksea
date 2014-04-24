<?php
//引入snoopy的类文件
require('Snoopy.class.php');
//初始化snoopy类
$snoopy = new Snoopy;
$url = "http://t.qq.com";
//开始采集内容
$snoopy->fetch($url);
 //保存采集内容到$lines_string
$snoopy->fetchlinks;
$lines_string = $snoopy->results;
//输出内容，嘿嘿，大家也可以保存在自己的服务器上
echo $lines_string;


