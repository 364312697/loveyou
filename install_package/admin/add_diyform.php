<?php
require('check.php');
require('libs/sql.class.php');

$sysinfo = get_sysinfo();

if(isset($_POST['dosubmit'])){
	$model = M('model');
	
	//因为前端有ajax验证，这里就不验证那么多了
	if($_POST['name']=='' || $_POST['tablename']=='') showmsg('表单名称或表名不能为空！');  
	
	sql::sql_create($_POST['tablename']);
					
	$_POST['inputtime'] = time();
    $_POST['items']	= $_POST['type'] = 0;
	$model->insert($_POST,'1');
	
	showmsg("操作成功",'1','manage_diyform.php');
}

$tem_style = YZMCMS_PATH.'/templets/'.$sysinfo['tem_style'].'/config.php';
if(!file_exists($tem_style)) showmsg($tem_style."文件不存在，请检查！", 5);
$templets = require($tem_style);
$list_template = $templets['diyform_list_temp'];
$show_template = $templets['diyform_show_temp'];

include('templets/add_diyform.htm');