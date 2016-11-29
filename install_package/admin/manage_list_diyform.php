<?php
require('check.php');

$modelid = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$modelid) showmsg('模型ID不能为空！',1);

$model = M('model');
$r = $model->field('tablename')->where(array('modelid' => $modelid))->find();
$tablename = M($r['tablename']);

if(isset($_POST["dosubmit"])){

	if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	$del_total = $tablename->delete($_POST['fx'], true);
	$model->update("`items`=`items`-$del_total", array('modelid'=>$modelid));
	showmsg('操作成功！',1);	
}

$total = $tablename->total();	

$page = new spage($total,10);
$start = $page->start_rows();
$res = $tablename->field('id,username,userid,ip,inputtime')->order('id DESC')->limit("$start,10")->select();


include('templets/manage_list_diyform.htm');  