<?php

/*抓取的数据中，有部分sku跟在名字后面*/


$con = mysql_connect("127.0.0.1","root","luom1ng");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

	mysql_select_db("ksea", $con);

	//$sql = sprintf("SELECT * FROM tb_source where name like '%%%s%%'",'69');
	//$sql = sprintf("SELECT * FROM tb_source where source_id=%d",6047);

	//echo $sql;

	$sql  = "select * from tb_source";

	$result = mysql_query($sql);

	while($row=mysql_fetch_array($result))
	{
		//方法1取后13位，判断是否为数字
		$name =  $row['name'];
		$name = trim($name);
		$sku = substr($name,strlen($name)-8,strlen($name)); 
		$sku = trim($sku);
			
		if(is_numeric($sku)&&(strlen($sku)==8)){

			$name = substr($name,0,strlen($name)-8);
			$name = trim($name);
			$sqlup = sprintf("UPDATE tb_source SET name='%s',sku='%s' where source_id=%d",$name,$sku,$row['source_id']);
			//echo $sqlup;
			//mysql_query($sqlup);

			$hh = sprintf("++++++++++the data is:%s,%s\n",$name,$sku);
			echo $hh;
		}
		
		
		//方法2：取数组分割后的13位判断
		/*
		$name =  $row['name'];
		$pas = explode(' ', $name);

		$sku = trim(end($pas));
		if(is_numeric($sku)&&(strlen($sku)==6))
		{
			$name = str_replace(end($pas), ' ', $name);
			$name = trim($name);

			$sqlup = sprintf("UPDATE tb_source SET name='%s',sku='%s' where source_id=%d",$name,$sku,$row['source_id']);
			//echo $sqlup;
			mysql_query($sqlup);

			$hh = sprintf("++++++++++the data is:%s,%s\n",$name,$sku);
			echo $hh;
		}*/
	}

	// print_r(count($row));

  
	mysql_close($con);



?>