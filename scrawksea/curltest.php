<?php 

//$imgurl = "http://www.ksea.com.cn/upload/productdetail/%E6%97%B6%E5%B0%9A%E7%99%BE%E8%B4%A7/F2%E6%97%B6%E5%B0%9A%E5%90%8D%E4%BB%95%E9%A6%86/%E7%94%B7%E9%9E%8B/%E6%9D%B0%E7%89%B9%E6%B3%A2%E5%A3%AB/13-1.jpg";
$imgurl = "http://www.ksea.com.cn//upload/productdetail/%E6%97%B6%E5%B0%9A%E7%99%BE%E8%B4%A7/F1%E6%97%B6%E5%B0%9A%E7%B2%BE%E5%93%81%E9%A6%86/%E5%8D%A1%E7%89%87%E6%9C%BA/%E4%BD%B3%E8%83%BD/IXUS%20115%20HS/IMG_9893.JPG";


getImage($imgurl,"./",md5($imgurl),1);

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
	if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
		return array('file_name'=>'','save_path'=>'','error'=>5);
	}
    //获取远程文件所采用的方法 
    if($type){
		$ch=curl_init();
		$timeout=30;
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
    //echo strlen($img);
    //文件大小 
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
	unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}

?>