<?php
require('check.php');
require('libs/sql.class.php');

$modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;
if(!$modelid) showmsg('模型ID不能为空！',1);

$model = M('model');
$model_field = M('model_field');

$model_data = $model->field('name,tablename')->where(array('modelid' => $modelid))->find();
if(!$model_data) showmsg('模型不存在！');

if(isset($_POST['dosubmit'])){
   
   $_POST['issystem'] = 0;	
   $_POST['modelid'] = $modelid;
   
   if(!empty($_POST['setting']['options'])){
	   $setting['options'] = explode(',', rtrim($_POST['setting']['options'], ',')); 
	   $_POST['setting'] = array2string($setting);
   }else{
	   unset($_POST['setting']);
   }
   
   
   if($_POST['minlength']){
	   $_POST['isrequired'] = 1;
   }

   if($_POST['formtype'] == 'textarea'){
	   sql::sql_add_field_text($model_data['tablename'], $_POST['field']);  
   }else if($_POST['formtype'] == 'number'){
	   sql::sql_add_field_int($model_data['tablename'], $_POST['field'], intval($_POST['defaultvalue']));  
   }else{
	   sql::sql_add_field($model_data['tablename'], $_POST['field'], $_POST['defaultvalue'], $_POST['maxlength']);  
   }
   
   
   $model_field->insert($_POST);    
   showmsg('操作成功！', 1, 'manage_diy_field.php?id='.$modelid);
}
include('templets/add_diy_field.htm');