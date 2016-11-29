<?php
require('check.php');


$guestbook = M('guestbook');

/* 批量删除 */
if(isset($_POST["pldel_sub"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	$del_num = count($_POST["fx"]);
	for($i = 0 ; $i < $del_num ; $i++){ 						
	  $guestbook->delete(array('id' => $_POST["fx"][$i]));				 
	  $guestbook->delete(array('replyid' => $_POST["fx"][$i]));				 
	}
	showmsg('操作成功！',1);
}


$total = $guestbook->where(array('replyid'=>'0'))->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $guestbook->limit("$start,10")->order('id DESC')->select();
include('templets/manage_words.htm');