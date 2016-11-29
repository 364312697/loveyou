<?php 
/**
 * 收藏url，必须登录
 * @param url 地址，需urlencode，防止乱码产生
 * @param title 标题，需urlencode，防止乱码产生
 * @return {1:成功;-1:未登录;-2:缺少参数}
 */

session_start(); 
require('../config/common.inc.php');

if(empty($_POST['title']) || empty($_POST['url'])) {
	exit('-2');	
} else {
	$title = $_POST['title'];
	$title = addslashes(urldecode($title));
	$title = new_html_special_chars($title);
	$url = safe_str(addslashes(urldecode($_POST['url'])));
}

//检查是否是否有存在已登录的用户
if(empty($_SESSION['_userid'])){
	exit(json_encode(array('status'=>-1)));
}

$data = array('title'=>$title, 'url'=>$url, 'inputtime'=>time(), 'userid'=>$_SESSION['_userid']);

$favorite = M('favorite');

//根据url判断是否已经收藏过。
$is_exists = $favorite->where(array('url'=>$url, 'userid'=>$_SESSION['_userid']))->find();
if(!$is_exists) {
	$favorite->insert($data);
}

exit(json_encode(array('status'=>1)));