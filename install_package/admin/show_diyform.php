<?php
require('check.php');

$modelid = isset($_GET['modelid']) ? intval($_GET['modelid']) : 0;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$modelid) showmsg('模型ID不能为空！',1);

$model = M('model');
$r = $model->field('tablename')->where(array('modelid' => $modelid))->find();
$tablename = M($r['tablename']);

$model_field = M('model_field');
$model_fieldres = $model_field->field('field,name,formtype')->where(array('modelid' => $modelid))->order('fieldid ASC')->select();

$data = $tablename->where(array('id' => $id))->find();


include('templets/show_diyform.htm');  