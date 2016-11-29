<?php
require('../common/frontend.inc.php');

$modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;

$model = M('model');
$r = $model->where(array('modelid'=>$modelid, 'disabled'=>0, 'type'=>0))->find();
if(!$r) showmsg('该表单不存在或已禁用！',3,$wroot);
$tablename = M($r['tablename']);

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0; //获取栏目ID,网站头部用到

$title = $r['name'].'-'.$wname;

//获取当前位置
$location = '<a href="'.$wroot.'">首页</a> &gt;'.$r['name'];

$_GET['cpage'] = isset($_GET['cpage']) ? intval($_GET['cpage']) : 1;
$total = $tablename->total();	

$page = new spage($total,10);
$start = $page->start_rows();
$data = $tablename->field('id,username,inputtime')->order('id DESC')->limit("$start,10")->select();

include template($r['list_template']);