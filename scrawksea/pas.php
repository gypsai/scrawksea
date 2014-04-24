<?php
require('simple_html_dom.php');

$url = "http://www.ksea.com.cn/products/product-10706.html";
$html = file_get_html($url);

echo $html->find('div[class=pdtname clear]',0);

// foreach($html->find('div[class=pdtname clear]') as $ele)
//    {
// 	echo $ele->plaintext."\n";

//     }


echo $html->find('span[id=productno]',0)->plaintext;

// foreach($html->find('img[width=700]') as $element)
//     {	

// 	print_r($element."\n");

//     }


    ?>
