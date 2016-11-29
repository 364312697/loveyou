<?php
require('check.php');
$pay = M('pay');

$where = '1 = 1';
if(isset($_GET['search'])){
	
    $username = isset($_GET["username"]) ? $_GET["username"] : '';
	
	if($username != ''){
		$where .= ' AND username LIKE \'%'.$username.'%\'';
	}
		
}
$total = $pay->where($where)->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $pay->limit("$start,10")->order("id DESC")->select();

//批量删除
if(isset($_POST["pldel_sub"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $pay->delete(array('id' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);

}

include('templets/member_point.htm');