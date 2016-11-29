<?php
require('check.php');
$column = M('column');	
$article = M('article');	

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = $article->where(array('id'=>$id))->find(); 


$tag = M('tag');
$tagres = $tag->select();
$tagstr = '';
foreach($tagres as $val){
	 $str = strpos($res['tag'],$val['id']) === false ? '' : 'checked="checked"';
	 $tagstr .= '<label><input type="checkbox" name="tag[]" value="'.$val['id'].'" '.$str.' /> '.$val['tag'].'</label>';
}

$tagstr = $tagstr!='' ? $tagstr : '<a href="add_tag.php">点击添加</a>';


//获取附加字段,type为1的是文章模型附加字段
$article_data = getcache('article_data',2,'model');
if($article_data === false){
	$article_data = M('model_field')->where(array('type' => 1, 'disabled' => 0))->order('listorder ASC,field ASC')->limit('100')->select();
	setcache('article_data', $article_data, 2, 'model');
}

if($article_data){
	$article_data_model = M('article_data');
	$res_data = $article_data_model->where(array('id'=>$id))->find();
}


if(isset($_POST['dosubmit'])){
	$title  = $_POST['title'];
	$content  = $_POST['content'];
	if($title =='' || $content=='') showmsg("文章标题或内容不能为空");	
	if($_POST['catid'] == 0) showmsg("请选择文章栏目");
	unset($_POST['username'],$_POST['system']);
	
	$otherconfig = get_sysinfo();			 				
	$dir = $column->field('dir')->where(array('id'=>$_POST['catid']))->find();				 
	$_POST['columnpath'] = $otherconfig['html_path'].$dir['dir'].'/';       //获取文章的存放路径
	$_POST['status'] = isset($_POST['status']) ? join(',',$_POST['status']) : 7; //为文章的置顶功能做准备
	
    if(isset($_POST['grab_img'])) $_POST['content'] = grab_image($content, $otherconfig['wroot']);
	
	if($_POST['thumbnail'] == '' && isset($_POST['auto_thum'])){
		$r = get_thumbnail($_POST['content'], $otherconfig['wroot'], $otherconfig['pic_wid'], $otherconfig['pic_hei']);
		if($r) $_POST['thumbnail'] = $r;		   		
	}
	
	$_POST['title'] = htmlspecialchars($_POST['title']);
	$_POST['tag'] = isset($_POST['tag']) ? join(',',$_POST['tag']) : ''; 
	$_POST['inputtime'] = strtotime($_POST['inputtime']);	
	$_POST['abstract'] = $_POST['abstract']!='' ? $_POST['abstract'] : cut_str(strip_tags($content),70);						
	$_POST['display'] = isset($_POST['display']) ? '1' : '0';

	if(empty($_POST['url'])){
		$_POST['url'] = $otherconfig['article_html'] ? $otherconfig['wroot'].$_POST['columnpath'].$id.'.html' : $otherconfig['wroot'].'article.php?id='.$id;
	}	
	
    $res_article = 0;
	$res_article = $article->update($_POST,array('id'=>$id));
	
	//如果存在附加字段，则更新数据
	if($article_data){
		foreach($article_data as $val){
			if($val['formtype'] == 'checkbox' && isset($_POST[$val['field']])){     //如果多选框，则获取多个选项值	    
				$_POST[$val['field']] = join(',', $_POST[$val['field']]);		
			}			
		}
		if($article_data_model->update($_POST, array('id' => $id))) $res_article = 1;
	}		
	
	if($res_article){
		if($otherconfig['article_html']) make_html($id);
		if($res['thumbnail'] && $res['thumbnail']!=$_POST['thumbnail'] && strpos($res['thumbnail'],'uploads/')){
			@unlink(str_replace($otherconfig['wroot'],YZMCMS_PATH.'/',$res['thumbnail']));
		}	
		showmsg("操作成功！",'1','manage_info.php');		
	}else{
		showmsg("数据未修改！",'3','manage_info.php');
	}


}
include('templets/edit_article.htm');