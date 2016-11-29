<?php
/**
 * 用于AJAX查询登陆状态的接口
 * @return 会员信息
 */
 
session_start();  
require("../config/common.inc.php");

if(isset($_GET['action']) && $_GET['action'] == 'select'){
	if(!empty($_SESSION['_userid'])){
		$userid = $_SESSION['_userid'];
		$memberinfo = get_memberinfo($userid);
		echo json_encode($memberinfo);
	}else{
		echo '';
	}
}
?>