<?php
/**
 * 输出验证码
 * www.yzmcms.com
 */

session_start();
require('../core/classes/validationcode.class.php');
$code = new ValidationCode(90, 30, 4);
$code->showImage();   //输出到页面中供 注册或登录使用
$_SESSION['code'] = strtolower($code->getCheckCode());  //将验证码保存到服务器中