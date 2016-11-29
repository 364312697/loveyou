<?php
require('check.php');
require('libs/sql.class.php');

$model = M('model');

if(isset($_GET["id"])){
	$id = intval($_GET["id"]);
	$r = $model->field('tablename')->where(array('modelid'=>$id))->find();
	if($r) sql::sql_delete($r['tablename']);
	
	$model->delete(array('modelid'=>$id)); //删除model信息
	M('model_field')->delete(array('modelid'=>$id)); //删除字段
	showmsg("操作成功！",1);
}


$total = $model->where(array('type' => 0))->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $model->limit("$start,10")->order('modelid DESC')->select();
include('templets/manage_diyform.htm');
