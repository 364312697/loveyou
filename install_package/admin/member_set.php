<?php
require('check.php');

$data = get_sysinfo();

if(isset($_POST["dosubmit"])){
	
	foreach($_POST as $key => $value){
		M('otherconfig')->update(array('value'=>$value),array('varname'=>$key));		
	}
	delcache('sysinfo');	
	showmsg("操作成功！",'1');
}

include('templets/member_set.htm');
