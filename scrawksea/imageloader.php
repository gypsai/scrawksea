<?php


require('simple_html_dom.php');


/// foreach(as $ele)
//    {
// 	echo $ele->plaintext."\n";

//     }
//grabImage($url);

/*抓取图片地址*/
/*
for($i=0;$i<12971;$i++)
{
	// $url = "http://www.ksea.com.cn/products/product-10706.html";
// $html = file_get_html($url);

// foreach ($html->find('[src*=productdetail]') as $ele ) {
// 	echo $ele->src;
// 	echo "\n";
// }

	//$i = 10706;
	$urls = parseImageURL($i);
	
	foreach ($urls as $url) {
		echo $i;
		echo "+++++++++++++++++++++++++\n";
		echo $url;
		echo "--------------------------\n";
		$info = array('source_id'=>$i,'source_url'=>$url);

		upImageDB($info);
	}
}
*/

reloadImages();



function test()
{
	//$sourceurl = "/upload/productdetail/时尚超市/3、时尚食品/速食类/饼干/超市图片/DSCN3551.jpg";
	//$sourceurl = "/upload/productdetail/New_Supermarket/Food/JinMaiLang20120530/P1060565.jpg";
	//$imageurl = "http://www.ksea.com.cn".$sourceurl;
    
    //$imageurl = cn_urlencode($imageurl);
	$imageurl = "http://www.ksea.com.cn/upload/productdetail/%E6%97%B6%E5%B0%9A%E8%B6%85%E5%B8%82/3%E3%80%81%E6%97%B6%E5%B0%9A%E9%A3%9F%E5%93%81/%E9%80%9F%E9%A3%9F%E7%B1%BB/%E9%A5%BC%E5%B9%B2/%E8%B6%85%E5%B8%82%E5%9B%BE%E7%89%87/DSCN3551.jpg";

	$imageurl2 = "http://www.ksea.com.cn/upload/productdetail/%E6%97%B6%E5%B0%9A%E8%B6%85%E5%B8%82/3、%E6%97%B6%E5%B0%9A%E9%A3%9F%E5%93%81/%E9%80%9F%E9%A3%9F%E7%B1%BB/%E9%A5%BC%E5%B9%B2/%E8%B6%85%E5%B8%82%E5%9B%BE%E7%89%87/DSCN3551.jpg";
	$testurl = "http://www.ksea.com.cn/upload/productdetail/时尚超市/3、时尚食品/速食类/饼干/超市图片/DSCN3551.jpg";
	echo cn_urlencode($testurl);
	$requrl = cn_urlencode($testurl); 
	$dinfo = getImage($requrl,"/Users/gypsai/Documents/",md5($requrl),1);
	var_dump($dinfo);

}
//有些图片没有上传成功，重新弄一下
function reloadImages()
{
	$con = mysql_connect("127.0.0.1","root","luom1ng");
	if (!$con)
 	 {
 		 die('Could not connect: ' . mysql_error());
  	 }

	mysql_select_db("ksea", $con);

	$sql_none_image  = "select * from tb_template where is_up=0";
	
	//$sql = sprintf("SELECT * FROM tb_image where name =‘%s’",);

	$result = mysql_query($sql_none_image);

	while($row = mysql_fetch_array($result))
	{
		$sourceurl = getSourceUrl($row["avatar"],$con);
		echo $sourceurl."\n\r\r";

		$imageurl = "http://www.ksea.com.cn".$sourceurl;
		$imageurl = cn_urlencode($imageurl);

		echo "**********start to get image ".$row['id']."\n";
		echo md5($imageurl);
		$dinfo = getImage($imageurl,"/Users/gypsai/Documents/productimages/",md5($imageurl),1);
			if($dinfo['error']!=0)
		{

			//保存失败就打印日志
			$tm = date('Y-m-d H:i:s');
			$loginfo = "[".$tm."]".$imageurl."\n";
			printLog("./log",$loginfo,'a');
			print_r("image %d  get fail!\n",$row['id']);

		}else
		{
			//保存成功就存储下来
			$sql_update = sprintf("update tb_template set avatar='%s' where id=%s",$dinfo['file_name'],$row['id']);
			mysql_query($sql_update);
			echo $sql_update;
		}
	}

	mysql_close($con);

}

function getSourceUrl($avatar,$con)
	{
		mysql_select_db("ksea", $con);

		$sql  = sprintf("select * from tb_image where name='%s' ",$avatar);
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);

		if(!empty($row['source_url']))
		{
			return $row['source_url'];
		}else
		{

			return null;
		}

	}


function loadImages()

{

	$con = mysql_connect("127.0.0.1","root","luom1ng");
	if (!$con)
 	 {
 		 die('Could not connect: ' . mysql_error());
  	 }

	mysql_select_db("ksea", $con);

	$sql = sprintf("SELECT * FROM tb_image where name IS NULL");

	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result))
	{
		$imageurl = "http://www.ksea.com.cn/".$row['source_url'];
		$imageurl = cn_urlencode($imageurl);

		echo "**********start to get image ".$row['id']."\n";

		$dinfo = getImage($imageurl,"/Users/gypsai/Documents/productimages/",md5($imageurl),1);
		if($dinfo['error']!=0)
		{

			//打印日志
			$tm = date('Y-m-d H:i:s');
			$loginfo = "[".$tm."]".$imageurl."\n";
			printLog("./log",$loginfo,'a');
			print_r("image %d  get fail!\n",$row['id']);

		}else
		{

			print_r("Done.............\n");
			var_dump($info);
			print_r("Update DB :%d............\n",$row['id']);
			$info = array('filename'=>$dinfo['file_name'],'id'=>$row['id']);
			upImageDB($info);
			print_r("Done Update...............\n");

		}
	}


}


function cn_urlencode($url){
     //$pregstr = "/[\x{4e00}-\x{9fa5}]+/u";//UTF-8中文正则,包含的范围不够，譬如没有包含顿号
     $pregstr = "/[\x{2e80}-\x{fe4f}]+/u";//UTF-8中文正则
    if(preg_match_all($pregstr,$url,$matchArray)){//匹配中文，返回数组
        foreach($matchArray[0] as $key=>$val){
            $url=str_replace($val, urlencode($val), $url);//将转译替换中文
        }
        if(strpos($url,' ')){//若存在空格
            $url=str_replace(' ','%20',$url);
        }
    }
    return $url;
}


/** 
* 写文件 
* @param string $file 文件路径 
* @param string $str 写入内容 
* @param char $mode 写入模式 
*/ 
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



function parseImageURL($source_id)
{

	$filepath = $filepath = "/Users/gypsai/Documents/ksea/".$source_id.".html";

	$html = file_get_html($filepath);

	$text = file_get_contents($filepath);

	$html = str_get_html($text);

	$data = array();
	foreach ($html->find('[src*=productdetail]') as $ele ) {
	array_push($data, $ele->src);
	echo $ele->src;
	echo "\n";
  	}
	return $data;
}


function getSKUFromSID($source_id)
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

/*图片下载完成之后更新数据库*/

function upImageDB($info)
{

	$con = mysql_connect("127.0.0.1","root","luom1ng");
	if (!$con)
 	 {
 		 die('Could not connect: ' . mysql_error());
  	 }

	mysql_select_db("ksea", $con);

	$sql = sprintf("UPDATE tb_image SET name='%s' where id=%d",$info['filename'],$info['id']);

	//echo $sql;
	$result = mysql_query($sql);

	mysql_close($con);

}


/**
 * 抓取远程图片
 *
 * @param string $url 远程图片路径
 * @param string $filename 本地存储文件名
 */
function grabImage($url, $filename = '') {
    if($url == '') {
        return false; //如果 $url 为空则返回 false;
    }
    $ext_name = strrchr($url, '.'); //获取图片的扩展名
    if($ext_name != '.gif' && $ext_name != '.jpg'&& $ext_name != '.JPG' && $ext_name != '.bmp' && $ext_name != '.png') {
        return false; //格式不在允许的范围
    }
    if($filename == '') {
        $filename = time().$ext_name; //以时间戳另起名
    }else{
    	$filename = $filename.$ext_name;
    }
    //开始捕获
    ob_start();
    readfile($url);
    $img_data = ob_get_contents();
    ob_end_clean();
    $size = strlen($img_data);
    $local_file = fopen($filename , 'a');
    fwrite($local_file, $img_data);
    fclose($local_file);
    return $filename;
}

/*
*功能：php完美实现下载远程图片保存到本地
*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
*当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$save_dir='',$filename='',$type=0){
    if(trim($url)==''){
		return array('file_name'=>'','save_path'=>'','error'=>1);
	}
	if(trim($save_dir)==''){
		$save_dir='./';
	}
  //   if(trim($filename)==''){//保存文件名
  //       $ext=strrchr($url,'.');
  //       if($ext!='.gif'&&$ext!='.jpg'){
		// 	return array('file_name'=>'','save_path'=>'','error'=>3);
		// }
  //       $filename=time().$ext;
  //   }

	$ext = strrchr($url, '.');
	$filename = $filename.$ext;

	if(0!==strrpos($save_dir,'/')){
		$save_dir.='/';
	}
	//创建保存目录
	// if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
	// 	return array('file_name'=>'','save_path'=>'','error'=>5);
	// }
    //获取远程文件所采用的方法 
    if($type){
		$ch=curl_init();
		$this_header = array(
			"content-type: application/x-www-form-urlencoded; 
				charset=UTF-8"
		);
		$timeout=60;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$img=curl_exec($ch);
		curl_close($ch);
    }else{
	    ob_start(); 
	    readfile($url);
	    $img=ob_get_contents(); 
	    ob_end_clean(); 
    }
    echo strlen($img);
    //文件大小 
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
	unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}
?>