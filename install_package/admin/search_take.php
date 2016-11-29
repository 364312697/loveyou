<?php
require('check.php');

$search = M('search');

  
 /* 批量删除 */
if(isset($_POST["pldel_sub"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $search->delete(array('aid' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);
	
}


$total = $search->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $search->limit("$start,10")->order("cou DESC,lasttime DESC")->select();			

include('templets/search_take.htm');			