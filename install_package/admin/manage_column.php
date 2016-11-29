<?php
require('check.php');
$column = M('column');
$sysinfo = get_sysinfo();

if(isset($_GET["id"])){
	$id = intval($_GET["id"]);
	if($column->where(array('pid'=>$id))->total() > 0){
		 showmsg('分类删除失败：该分类下有子分类！',3,'manage_column.php');
	}elseif(M('article')->where(array('catid'=>$id))->total() > 0){
		 showmsg('分类删除失败：该分类下有文章！',3,'manage_column.php');
	}else{
        $res = $column->where(array('id'=>$id))->find();		
		if($column->delete(array('id'=>$id))){
			 $htmlpath='../'.$sysinfo['html_path'].$res['dir'].'/';
			 if(file_exists($htmlpath)) @rmdir($htmlpath);   //删除文件目录
			 M('singlepage')->delete(array('catid' => $id));    //删除单页数据
			 delcache('', 'column', true);
			 showmsg('分类删除成功！',1,'manage_column.php');
		}else{
			 showmsg('分类删除失败！',1,'manage_column.php');
		}		   
	}
}

if(isset($_POST["dosubmit"])){
	foreach($_POST['id'] as $key=>$val){
		$column->update(array('ord'=>$_POST['ord'][$key]),array('id'=>intval($val)));
	}
	delcache('', 'column', true);
	showmsg('操作成功！',1,'manage_column.php');
}

$big_menu = array(
	'栏目管理' => 'manage_column.php',
	'添加栏目' => 'add_column.php',
	'添加单页' => 'add_column.php?type=1',
	'添加外部链接' => 'add_column.php?type=2',
);
$this_menu = '栏目管理';

$data = $column->field('id,pid,title,concat(path,",",id) as abspath,type,ord,column_img,member_publish')->order('abspath ASC')->select();
	
include('templets/manage_column.htm');