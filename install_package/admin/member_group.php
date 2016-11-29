<?php
require('check.php');
$member_group = M('member_group');



$res = $member_group->select();


if(isset($_POST["sub"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $member_group->delete(array('groupid' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);

}


include('templets/member_group.htm');