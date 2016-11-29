<?php 
require('include.inc.php');

$article = M('article');
$column = M('column');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$data = $article->where(array('id' => $id))->find(); 
if($data['username']!=$username || $data['display']==1 || $data['system']==1){
	showmsg("请勿非法操作！",3,"index.php");  //只能编辑自己发布的信息
}   

if(isset($_POST['dosubmit'])){
	
	if($_POST['title'] == '' || $_POST['content'] == '' || $_POST['catid'] == 0) showmsg("信息填写不完整！");
	
	//判断栏目是否禁止投稿
	$res = $column->field('id')->where(array('member_publish'=>1, 'id'=>$_POST['catid']))->find();
	if(!$res) showmsg("请勿非法操作！");
	
	$otherconfig = get_sysinfo();
	$dir = $column->field('dir')->where(array('id'=>$_POST['catid']))->find();					 
	$_POST['columnpath'] = $otherconfig['html_path'].$dir['dir'].'/';       //获取文章的存放路径
	
	
	$thumbnail  = $_FILES['thumbnail']['name']; 
	
	foreach($_POST as $_k=>$_v) {
		if($_k == 'content') {
			$_POST[$_k] = remove_xss(strip_tags($_v, '<p><a><br><img><ul><li><div>'));
		}else{
			$_POST[$_k] = new_html_special_chars(trim_script($_v));
		}
	}
	
	if($thumbnail != ''){						
		$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/thumbnail/'));	 //上传图片
		if($uppic->uploadFile('thumbnail')){
			$image = new image(YZMCMS_PATH."/uploads/thumbnail/");      //制作用户上传的缩略图
			$_POST['thumbnail'] = $otherconfig['wroot'].'uploads/thumbnail/'.$image->thumb($uppic->getNewFileName(), $otherconfig['pic_wid'], $otherconfig['pic_hei'], "th_");
			@unlink('../uploads/thumbnail/'.$uppic->getNewFileName());  //删除大图
		}else{
			showmsg($uppic->getErrormsg(),5);
		}					  
	}	
	
	$_POST['abstract'] = $_POST['abstract']!='' ? $_POST['abstract'] : cut_str(strip_tags($_POST['content']),70);				
	$_POST['ip'] = getip();				
	$_POST['system'] = '0';
	$_POST['status'] = '7'; //为文章置顶做准备
	$_POST['display'] = '0';
	$_POST['username'] = $username;	
	$_POST['nickname'] = $nickname;	
	
	unset($_POST['url'], $_POST['inputtime']);
	
	if($article->update($_POST,array('id' => $id))){
		showmsg("操作成功！",'1','manuscript.php');
	}else{
		showmsg("数据未修改！",'3','manuscript.php');
	}
}

$column_data = $column->field('id,title')->where(array('member_publish'=>1))->select(); //只查询允许投稿的栏目
$html = '<select id="sel" name="catid"><option value="0">请选择栏目...</option>';
foreach($column_data as $val){
	$str = $data['catid'] == $val['id'] ? 'selected' : '';
	$html .= '<option value="'.$val['id'].'" '.$str.'>'.$val['title'].'</option>';
}
$html .= '</select>';



//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-在线投稿';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 