<?php 
require('include.inc.php');

$userpic = $userpic!='' ? $userpic: 'templets/images/default.gif';
$email = $email!='' ? $email: '暂无';



//分配样式及加载模板
$filename = '';
$cssarr = array('index');
include("templets/index.html");
?> 