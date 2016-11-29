<?php
require('check.php');
if(isset($_GET['dosubmit'])){
	ob_start();
	include('../index.php');
	$data = ob_get_contents();				
	ob_clean();
	$strlen = file_put_contents('../index.html', $data);
	if($strlen){
		$html_message = '<p>生成首页index.html成功！大小：'.sizecount($strlen).'</p><a href="../index.html" target="_blank" class="blue">点击浏览...</a>';
	}else{
		$html_message = '<p style="color:red;">生成首页index.html失败，请检查是否有写入权限！</p>';
	}
	include('templets/message.htm');
}else{
    include('templets/make_homehtml.htm');	
}
