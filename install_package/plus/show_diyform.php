<?php
require('../common/frontend.inc.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$model = M('model');
$r = $model->where(array('modelid'=>$id, 'disabled'=>0, 'type'=>0))->find();
if(!$r) showmsg('该表单不存在或已禁用！',3,$wroot);

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0; //获取栏目ID,网站头部用到

$title = $r['name'].'-'.$wname;

//获取当前位置
$location = '<a href="'.$wroot.'">首页</a> &gt; <a href="'.$wroot.'plus/list_diyform.php?modelid='.$id.'">'.$r['name'].'</a> &gt;内容页';

$data = M('model_field')->where(array('modelid'=>$id, 'disabled'=>0))->order('listorder ASC,field ASC')->select();

include template($r['show_template']);