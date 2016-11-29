<?php
require('check.php');

$column = M('column');
$webconfig = get_sysinfo();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = isset($_GET['type']) ? intval($_GET['type']) : 0;

if(isset($_POST['dosubmit'])){	
	$title = $_POST["title"];
	$dir = isset($_POST["dir"]) ? $_POST["dir"] : '';
    $pid = $_POST["pid"];
	$column_img = $_FILES['column_img']['name'];			
	if($title=='')  showmsg("栏目名称不能为空！");
	
	$htmlpath='../'.$webconfig['html_path'].$dir.'/';
	if(!file_exists($htmlpath)){	
		$path = @mkdir($htmlpath,0755,true);  
		if(!$path) showmsg("创建栏目目录失败，请检查是否有写入权限！",5);
	}
	if($pid=='0') {
		$_POST["path"] = '0';
	}else{
		$data = $column->field('path')->where(array('id'=>$pid))->find();
		$_POST["path"] = $data["path"].','.$pid;
	}		
	if($column_img){					  
		$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/column/'));				  
		if($uppic->uploadFile('column_img')){
			$_POST["column_img"] = 'uploads/column/'.$uppic->getNewFileName();
		}else{
			showmsg($uppic->getErrormsg(),5);
		}						
	}
	
	
	$_POST["type"] = $type;				
	$id = $column->insert($_POST,'1');
	
	
	if($type == 0){   //普通类型
		$str = $webconfig['is_rewrite'] ? '' : 'list.php/';
	    $column->update(array('pclink'=>$webconfig['wroot'].$str.$_POST["dir"].'/'),array('id'=>$id)); //更新电脑版URL
	}else if($type == 1){   //单页类型
		$arr = array();
		$arr["catid"] = $id;					
		$arr["addtime"] = time();					
		$arr["path"] = $_POST["dir"];					
		$arr["template"] = $_POST["list_template"];								
		$arr["keywords"] = $_POST["seo_keywords"];				
		$arr["description"] = $_POST["seo_description"];					
		M('singlepage')->insert($arr);  //插入单页数据
		
		
		$str = $_POST['is_html'] ? $webconfig['html_path'] : ($webconfig['is_rewrite'] ? '' : 'list.php/');
		$column->update(array('pclink'=>$webconfig['wroot'].$str.$_POST["dir"].'/'),array('id'=>$id)); //更新电脑版URL
	}
	
	
	//更新手机版URL
	if(empty($_POST["moblink"])){
		$column->update(array('moblink'=>'list.php?cid='.$id),array('id'=>$id));
	}
	
	delcache('', 'column', true);
	
	if($id){					
		showmsg("操作成功！",'1','manage_column.php');					
	}else{
		showmsg("操作失败！",'3');
    }

}


$tem_style = YZMCMS_PATH.'/templets/'.$webconfig['tem_style'].'/config.php';
if(!file_exists($tem_style)) showmsg($tem_style."文件不存在，请检查！", 5);
$templets = require($tem_style);
$list_temp = $templets['list_temp'];
$article_temp = $templets['article_temp'];
$singlepage_temp = $templets['singlepage_temp'];


$big_menu = array(
	'栏目管理' => 'manage_column.php',
	'添加栏目' => 'add_column.php',
	'添加单页' => 'add_column.php?type=1',
	'添加外部链接' => 'add_column.php?type=2',
);

if($type == 0){
	$this_menu = '添加栏目';
	include('templets/add_column.htm');
}else if($type == 1){
	$this_menu = '添加单页';
	include('templets/add_singlepage.htm');
}else{
	$this_menu = '添加外部链接';
	include('templets/add_abroad_link.htm');
}