<?php 

require('simple_html_dom.php');


for($i=0;$i<12971;$i++)
{

if(isKX($i))
  {
  	$data = parseSKU(getFile($i));
  	$data = array_values($data);

  	$info = $data[0];
  	echo "[";
  	echo $i;
  	echo "]";
  	echo "-------------------[";
  	echo $info['code'];
  	echo "]-------------------";

	UpDateSKU($info['code'],$i);

  }

}



function UpDateSKU($sku,$source_id)
{
	$con = mysql_connect("127.0.0.1","root","luom1ng");
	if (!$con)
 	 {
 		 die('Could not connect: ' . mysql_error());
  	 }

	mysql_select_db("ksea", $con);

	$sql = sprintf("UPDATE tb_source SET sku='%s' WHERE source_id=%d",$sku,$source_id);

	$result = mysql_query($sql);

	mysql_close($con);

}



function getFile($i)
{

	$filepath = $filepath = "/Users/gypsai/Documents/ksea/".$i.".html";

	$html = file_get_html($filepath);

	$text = file_get_contents($filepath);

	return $text;

}





function parseSKU($text)
{

	$match = array();
	$data = array();
	if(preg_match("/SkuMap\=\{.*\}\;/",$text,$match))
	{
		$rst = array();

	if(preg_match("/\{.*\}/",$match[0],$rst))
	{
		$data = json_decode($rst[0],true);

	}else{
		$data = null;

	}
    }else
	{
		$data = null;
	}

	return $data;
}




function isKX($source_id)
{

	$con = mysql_connect("127.0.0.1","root","luom1ng");
	if (!$con)
 	 {
 		 die('Could not connect: ' . mysql_error());
  	 }

	mysql_select_db("ksea", $con);

	$sql = sprintf("SELECT * FROM tb_source where source_id = %s",$source_id);

	$result = mysql_query($sql);

	if($row = mysql_fetch_array($result))
	{
			
		if(preg_match("/KX/",$row['sku'],$matches))
		{
			return true;
		}
	}else{
		return false;
	}

	mysql_close($con);

}



?>