<?php
require('check.php');
$column = M('column');
	
$catid = isset($_GET["lastcatid"]) ? intval($_GET["lastcatid"]) : 0; //当用户继续发布文章时候，记住上次的文章分类  

$tag = M('tag');
$tagres = $tag->select();

$tagstr = '';
foreach($tagres as $val){
	 $tagstr .= '<label><input type="checkbox" name="tag[]" value="'.$val['id'].'" /> '.$val['tag'].'</label>';
}
$tagstr = $tagstr!='' ? $tagstr : '<a href="add_tag.php">点击添加</a>';

//获取附加字段,type为1的是文章模型附加字段
$article_data = getcache('article_data',2,'model');
if($article_data === false){
	$article_data = M('model_field')->where(array('type' => 1, 'disabled' => 0))->order('listorder ASC,field ASC')->limit('100')->select();
	setcache('article_data', $article_data, 2, 'model');
}

//缓存附加字段string
$article_data_str = getcache('article_data_str',2,'model');
if($article_data_str === false){

	$article_data_str = '';
	foreach($article_data as $val){
			$str = $val['isrequired'] ? '<b>*</b>' : '';
			$formtype = $val['formtype'];
			if($formtype == 'input'){
			   $article_data_str .= '<li class="clear"><label>'.$val['name'].$str.'</label>'.form::$formtype('name="'.$val['field'].'" style="width:518px"', $val['defaultvalue']).'<i class="tips">'.$val['tips'].'</i>
			   </li>';		   
			}elseif($formtype == 'textarea'){
			   $article_data_str .=  '<li class="clear"><label>'.$val['name'].$str.'</label>'.form::$formtype('name="'.$val['field'].'" class="abstract"', $val['defaultvalue']).'<i class="tips">'.$val['tips'].'</i>
			   </li>';		   
			}elseif($formtype == 'date'){
			   $article_data_str .=  '<li class="clear"><label>'.$val['name'].$str.'</label>'.form::date('name="'.$val['field'].'"', $val['defaultvalue']).'<i class="tips">'.$val['tips'].'</i></li>';
			}elseif($formtype == 'number'){
			   $article_data_str .=  '<li class="clear"><label>'.$val['name'].$str.'</label>'.form::input('name="'.$val['field'].'"', $val['defaultvalue']).'<i class="tips">'.$val['tips'].'</i></li>';	
			}elseif($formtype == 'checkbox'){
			   $arr = string2array($val['setting']);
				$article_data_str .=  '<li class="clear"><label>'.$val['name'].$str.'</label>'.form::$formtype($arr['options'], '', 'name="'.$val['field'].'[]"').'<i class="tips">'.$val['tips'].'</i></li>';	
			}else{
				$arr = string2array($val['setting']);
				$article_data_str .=  '<li class="clear"><label>'.$val['name'].$str.'</label>'.form::$formtype($arr['options'], '', 'name="'.$val['field'].'"').'<i class="tips">'.$val['tips'].'</i></li>';
			}
	}

	setcache('article_data_str', $article_data_str, 2, 'model');
}


if(isset($_POST['dosubmit'])){
	$article = M('article');
	$title  = $_POST['title'];
	$content  = $_POST['content'];
	if($title =='' || $content=='') showmsg("文章标题或内容不能为空");	
	if($_POST['catid'] == 0) showmsg("请选择文章栏目");
	
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
	if(isset($_POST['tag'])) foreach($_POST['tag'] as $val){					
		$tag->update('`article_total`=`article_total`+1',array('id'=>$val));   //更新TAG标签文档数量
	}
	$_POST['tag'] = isset($_POST['tag']) ? join(',',$_POST['tag']) : ''; 	
	$_POST['inputtime'] = strtotime($_POST['inputtime']);	
	$_POST['abstract'] = $_POST['abstract']!='' ? $_POST['abstract'] : cut_str(strip_tags($content),70);				
	$_POST['ip'] = getip();				
	$_POST['system'] = '1';
	$_POST['display'] = isset($_POST['display']) ? '1' : '0';
	$_POST['username'] = $_SESSION['adminname'];
	
	$lastid = $article->insert($_POST);
	
	if(empty($_POST['url'])){
		$url = $otherconfig['article_html'] ? $otherconfig['wroot'].$_POST['columnpath'].$lastid.'.html' : $otherconfig['wroot'].'article.php?id='.$lastid;
		$article->update(array('url'=>$url), array('id'=>$lastid));		
	}

	//如果存在附加字段，则添加数据
	if($article_data){
		foreach($article_data as $val){
            if($val['formtype'] == 'checkbox' && isset($_POST[$val['field']])){     //如果多选框，则获取多个选项值	    
				$_POST[$val['field']] = join(',', $_POST[$val['field']]);		
			}			
		}
		$_POST['id'] = $lastid;
		M('article_data')->insert($_POST);
	}
	
	if($otherconfig['article_html']){
		make_html($lastid);
		$r = $article->field('id')->where(array('id<'=>$lastid , 'display'=>1 , 'catid'=>$_POST['catid']))->order('id DESC')->find();
		if($r) make_html($r['id']);
	} 		
	echo "<script>location.href='transfer.php?id={$lastid}&lastcatid={$_POST['catid']}'; </script>";
	exit();	
	
}

include('templets/add_article.htm');