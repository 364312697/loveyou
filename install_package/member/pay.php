<?php 
require('include.inc.php');
$pay = M('pay');

$total = $pay->where(array('userid' => $userid))->total(); 

$page = new spage($total,15);
$start = $page->start_rows();
$data = $pay->limit("$start,15")->order('id DESC')->select(); 




//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-入账记录';
$cssarr = array('index');
include("templets/$filename.html");
?> 