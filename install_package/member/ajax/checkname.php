<?php 
/**
 * 检查用户名
 * @param string $username	用户名
 * @return $status {-2:用户名不合法 ;-1:用户名已存在 ;1:成功}
 */
 
define('MEMBER_AJAX_PATH', dirname(dirname(str_replace("\\", '/', dirname(__FILE__)))));
require(MEMBER_AJAX_PATH.'/config/common.inc.php');

$username = isset($_POST['username']) && trim($_POST['username']) ? trim($_POST['username']) : exit('0');
if(!is_username($username)) exit('-2'); 
if(M('member')->where(array('username'=>$username))->find())  exit('-1'); 
exit('1'); 
?>