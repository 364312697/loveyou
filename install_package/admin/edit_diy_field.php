<?php
require('check.php');

$modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;
$fieldid = isset($_GET['fieldid']) ? intval($_GET['fieldid']) : 0;
if(!$modelid) showmsg('模型ID不能为空！',1);

$model = M('model');
$model_field = M('model_field');

$model_data = $model->field('name,tablename')->where(array('modelid' => $modelid))->find();
if(!$model_data) showmsg('模型不存在！');

$data = $model_field->where(array('fieldid' => $fieldid))->find();
$setting = string2array($data['setting']);
$formtype_arr = array('select', 'radio', 'checkbox');

if(isset($_POST['dosubmit'])){
   
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
   
   //为了简单化，这里就不修改表字段了
   
   $model_field->update($_POST, array('fieldid' => $fieldid));    
   showmsg('操作成功！', 1, 'manage_diy_field.php?id='.$modelid);
}
include('templets/edit_diy_field.htm');