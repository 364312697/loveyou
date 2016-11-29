<?php 
/**
 * 检查电子邮箱
 * @param string $email	电子邮箱
 * @return $status {-2:电子邮箱格式不正确 ;-1:电子邮箱已存在 ;1:成功}
 */
 
define('MEMBER_AJAX_PATH', dirname(dirname(str_replace("\\", '/', dirname(__FILE__)))));
require(MEMBER_AJAX_PATH.'/config/common.inc.php');

$email = isset($_POST['email']) && trim($_POST['email']) ? trim($_POST['email']) : exit('0');
if(!is_email($email)) exit('-2'); 
if(M('member')->where(array('email'=>$email))->find())  exit('-1'); 
exit('1'); 
?>