<?php
require('check.php');
require('libs/sql.class.php');

$model_field = M('model_field');

$modelid = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$modelid) showmsg('模型ID不能为空！',1);

if(isset($_GET['fieldid'])){
    $fieldid = intval($_GET['fieldid']);

	$model_data = $model_field->field('field,tablename')->join('`yzmcms_model` ON yzmcms_model_field.modelid = yzmcms_model.modelid')->where(array('fieldid' => $fieldid))->find();
	if(!$model_data) showmsg('模型不存在！');	
	sql::sql_del_field($model_data['tablename'], $model_data['field']);  
	
    $model_field->delete(array('fieldid' => $fieldid));	
	showmsg('操作成功！',1);	
}elseif(isset($_POST['dosubmit']) && isset($_POST['listorder'])){
	foreach($_POST['listorder'] as $key => $val){
		$model_field->update(array('listorder'=>$val),array('fieldid'=>$key));
	}
	showmsg('操作成功！',1);
}



$res = $model_field->where(array('modelid' => 0, 'type' => 0),array('modelid' => $modelid))->order('listorder ASC,field ASC')->limit('100')->select();
include('templets/manage_diy_field.htm');  