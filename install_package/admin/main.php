<?php
require('check.php');
include('libs/update.class.php');

$adminlog = M('adminlog');
$res = $adminlog->field('login_time,ip')->where(array('user'=>$_SESSION['adminname'],'result'=>'1'))->order('id DESC')->limit('0,2')->select();



ob_start();
include('templets/main.htm');
$data = ob_get_contents();
ob_end_clean();
system_information($data);
