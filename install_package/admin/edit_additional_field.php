<?php
require('check.php');

$fieldid = isset($_GET['fieldid']) ? intval($_GET['fieldid']) : 0;

$model = M('model');
$model_field = M('model_field');

$data = $model_field->where(array('fieldid' => $fieldid))->find();
$setting = string2array($data['setting']);
$formtype_arr = array('select', 'radio', 'checkbox');

if(isset($_POST['dosubmit'])){
   
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
   delcache('', 'model', true);   
   showmsg('操作成功！', 1, 'manage_additional_field.php');
}
include('templets/edit_additional_field.htm');