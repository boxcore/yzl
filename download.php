<?php
$url = $_GET['url'];
$file_name = $_GET['name']?$_GET['name']:time();
if( isset($url) && !empty($url) ){
	header('Content-type: application/image/pjpeg');//输出的类型  
	//下载显示的名字 
	header('Content-Disposition: attachment; filename="'.$file_name.'.jpg"'); 
	readfile("$url"); 
exit(); 
}