<?php
require('check.php');

$sysinfo = get_sysinfo();	

if(isset($_POST["sub"])){
	
	$_POST['html_path'] = rtrim($_POST['html_path'],'/').'/';
	
	foreach($_POST as $key => $value){
		M('otherconfig')->update(array('value'=>$value),array('varname'=>$key));		
	}
	
	//更新栏目URL
	$column = M('column');
	$res = $column->field('id,dir')->select();
    if($_POST['is_pathinfo']){
		$str = $_POST['is_rewrite'] ? '' : 'list.php/';
		foreach($res as $val){
		  $column->update(array('pclink'=>$sysinfo['wroot'].$str.$val['dir'].'/'), array('id'=>$val['id'], 'type'=>0));
	    }
	}else{
		foreach($res as $val){
		  $column->update(array('pclink'=>$sysinfo['wroot'].'list.php?cid='.$val['id']), array('id'=>$val['id'], 'type'=>0));
	    }
	}
	
	if($sysinfo['is_pathinfo']!=$_POST['is_pathinfo'] || $sysinfo['is_rewrite']!=$_POST['is_rewrite']) delcache('', 'column', true);
    delcache('sysinfo');	
	showmsg("更新站点配置成功",'1');
}
	
$template_list = array();
$list = glob(YZMCMS_PATH.'/templets'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
 
foreach($list as $v){	 
    $template_list[] = $dirname = basename($v);
}

$html = '<select name="tem_style" class="select3">';
foreach($template_list as $v){
	$str = $sysinfo["tem_style"]==$v ? ' selected ' : '';
	$html .= ' <option value="'.$v.'" '.$str.'>'.$v.'</option> ';
}
$html .= '</select>';

include ('templets/other_config.htm');	
