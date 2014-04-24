<?php   


check_dup(10000);

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

	print_r('Prod is:'.$prod);

	$con = mysql_connect("127.0.0.1","root","luom1ng");

	if (!$con)
   {
 	 die('Could not connect: ' . mysql_error());
   }


	mysql_select_db("ksea", $con);

	$sql = "INSERT INTO tb_source (name,sku,kseano) VALUES ('$prod->name','$prod->sku','$prod->kseano')";

	echo $sql;

	mysql_query($sql);

	mysql_close($con);


}



?>