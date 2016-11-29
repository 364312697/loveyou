<?php 
require('include.inc.php');
$pay_spend = M('pay_spend');

$total = $pay_spend->where(array('userid' => $userid))->total(); 

$page = new spage($total,15);
$start = $page->start_rows();
$data = $pay_spend->limit("$start,15")->order('id DESC')->select(); 




//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-消费记录';
$cssarr = array('index');
include("templets/$filename.html");
?> 