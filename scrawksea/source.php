<?php
require('Snoopy.class.php');
//require('db.php');
require('simple_html_dom.php');



for($i=0;i<12500;$i++)
{
	$prod = array();
	$url = "http://www.ksea.com.cn/products/product-".$i.".html";
	$filepath = "/Users/gypsai/Documents/ksea/".$i.".html";
	$snoopy = new Snoopy; 
	$snoopy->fetch($url); //获取所有内容 


	file_put_contents($filepath,$snoopy->results);

	$prod = parseProd($snoopy->results,$i);
	store_data($prod);
	echo "SC||---------------".$i."----------------------\n";
	var_dump($prod);

	sleep(3);

}

function check_dup($source_id)
{

	$con = mysql_connect("127.0.0.1","root","luom1ng");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

	mysql_select_db("ksea", $con);

	$sql = sprintf("SELECT * FROM tb_source where source_id = %s",$source_id);

	$result = mysql_query($sql);

	if(!mysql_fetch_row($result))
	{
		return flase;
	}else{

		return true;
	}

	// print_r(count($row));

  
	mysql_close($con);

}

function store_data($prod)
{

	$con = mysql_connect("127.0.0.1","root","luom1ng");

	if (!$con)
   {
 	 die('Could not connect: ' . mysql_error());
   }


	mysql_select_db("ksea", $con);

	$sql = sprintf("INSERT INTO tb_source (name,sku,kseano,source_id) VALUES ('%s','%s','%s',%d)",$prod['name'],$prod['sku'],$prod['kseano'],$prod['source_id']);


	mysql_query($sql);

	mysql_close($con);


}


function parseProd($html,$i)
{

	$name = null;
	$sku = null;
	$prod = null;
	$kseano = null;
	$html = str_get_html($html);

	$name = $html->find('div[class=pdtname clear]',0)->plaintext;
	$kseano = $html->find('span[id=productno]',0)->plaintext;
	
	if($name===null)
	{

		$name = "无此商品";
		$sku = "000000000000";
		$kseano = "000000000000";

	}else{

		$str = explode(' ', $name);
		if($str[1]!=null)
		{
			$name = $str[0];
			$sku = $str[1];
		}else {

			$sku = $kseano;

		}


	}
	$prod  = array('name' => $name, 'sku' => $sku, 'kseano'=> $kseano ,'source_id'=>$i);

	return $prod;
}



?>
