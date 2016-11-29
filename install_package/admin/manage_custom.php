<?php
require('check.php');

$custom = M('custom');


/* 批量删除 */
if(isset($_POST["pldel_sub"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $custom->delete(array('id' => $_POST['fx'][$i]));				 
	}
	delcache('custom');
	showmsg('操作成功！',1);

}			  
			  
$total = $custom->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $custom->limit("$start,10")->order('id DESC')->select();			  		  
	
include('templets/manage_custom.htm');	