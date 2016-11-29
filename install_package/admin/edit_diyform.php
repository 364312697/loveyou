<?php
require('check.php');

$sysinfo = get_sysinfo();

$model = M('model');
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$data = $model->where(array('modelid'=>$id))->find();
		
if(isset($_POST['dosubmit'])){	
	
	//因为前端有ajax验证，这里就不验证那么多了
	if($_POST['name']=='') showmsg('表单名称不能为空！');  
	unset($_POST['tablename']);
	
	if($model->update($_POST, array('modelid'=>$id), 1)){
		showmsg("操作成功",'1','manage_diyform.php');
	}else{
		showmsg("数据未修改！",'3','manage_diyform.php');
	}
	
}

$tem_style = YZMCMS_PATH.'/templets/'.$sysinfo['tem_style'].'/config.php';
if(!file_exists($tem_style)) showmsg($tem_style."文件不存在，请检查！", 5);
$templets = require($tem_style);
$list_template = $templets['diyform_list_temp'];
$show_template = $templets['diyform_show_temp'];
	
include('templets/edit_diyform.htm');