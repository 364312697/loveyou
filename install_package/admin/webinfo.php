<?php
require('check.php');

$re = get_sysinfo();

if(isset($_POST["sub"])){
		$_POST['wroot'] = rtrim($_POST['wroot'],'/').'/';
		$_POST['wpath'] = rtrim($_POST['wpath'],'/').'/';
        M('webinfo')->update($_POST,array('id'=>"1"));
		delcache('sysinfo');
		showmsg("更新站点配置成功",'1');
}
include('templets/webinfo.htm');
