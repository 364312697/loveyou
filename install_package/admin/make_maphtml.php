<?php
require('check.php');
if(isset($_GET['dosubmit'])){
	$res = M('article')->field('url')->where(array('display'=>'1'))->select();
	$webinfo = get_sysinfo();
	$str = $webinfo['wroot']."\r\n";
	$filename = 'sitemap.txt';
	
	foreach($res as $val){
		$str .= $val['url']." \r\n";
	}
    
	$strlen = file_put_contents('../'.$filename, $str);
  
	if($strlen){
		$html_message = '<p>生成文件'.$filename.'成功！大小：'.sizecount($strlen).'</p><a href="../'.$filename.'" target="_blank" class="blue">点击浏览...</a>';
	}else{
		$html_message = '<p style="color:red;">生成文件'.$filename.'失败，请检查是否有写入权限！</p>';
	}
	include('templets/message.htm');
}else{
    include('templets/make_maphtml.htm');	
}