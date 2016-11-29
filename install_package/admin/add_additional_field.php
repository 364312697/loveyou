<?php
require('check.php');
require('libs/sql.class.php');

$model_field = M('model_field');

if(isset($_POST['dosubmit'])){
   
   $_POST['type'] = 1;	//文章附加字段
   $_POST['issystem'] = 0;	
   
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
	   sql::sql_add_field_text('article_data', $_POST['field']);  
   }else if($_POST['formtype'] == 'number'){
	   sql::sql_add_field_int('article_data', $_POST['field'], intval($_POST['defaultvalue']));  
   }else{
	   sql::sql_add_field('article_data', $_POST['field'], $_POST['defaultvalue'], $_POST['maxlength']);  
   }
   
   
   $model_field->insert($_POST); 
   delcache('', 'model', true);   
   showmsg('操作成功！', 1, 'manage_additional_field.php');
}
include('templets/add_additional_field.htm');