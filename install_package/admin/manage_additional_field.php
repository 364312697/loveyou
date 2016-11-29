<?php
require('check.php');
require('libs/sql.class.php');

$model_field = M('model_field');

if(isset($_GET['fieldid'])){
    $fieldid = intval($_GET['fieldid']);

	$model_data = $model_field->field('field')->where(array('fieldid' => $fieldid))->find();	
	sql::sql_del_field('article_data', $model_data['field']);  
	
    $model_field->delete(array('fieldid' => $fieldid));	
	delcache('', 'model', true);
	showmsg('操作成功！',1);	
}elseif(isset($_POST['dosubmit'])){
	foreach($_POST['listorder'] as $key => $val){
		$model_field->update(array('listorder'=>$val),array('fieldid'=>$key));
	}
	delcache('', 'model', true);
	showmsg('操作成功！',1);
}


//获取附加字段,type为1的是文章模型附加字段
$res = $model_field->where(array('type' => 1))->order('listorder ASC,field ASC')->limit('100')->select();

include('templets/manage_additional_field.htm');  