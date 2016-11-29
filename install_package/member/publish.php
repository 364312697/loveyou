<?php 
require('include.inc.php');
$column = M('column');

if(strpos($member_group_info['authority'], '3') === false) showmsg("你没有权限投稿，请升级会员组！", 3, 'index.php'); //首先检查会员有没有发布文章的权限

if(isset($_POST['dosubmit'])){
	
	if($_POST['title'] == '' || $_POST['content'] == '') showmsg("信息填写不完整！");
	
	//判断栏目是否禁止投稿
	$res = $column->field('id')->where(array('member_publish'=>1, 'id'=>intval($_POST['catid'])))->find();
	if(!$res) showmsg("请勿非法操作！");
	
    $article = M('article');
	$otherconfig = get_sysinfo();
	$dir = M('column')->field('dir')->where(array('id'=>intval($_POST['catid'])))->find();					 
	$_POST['columnpath'] = $otherconfig['html_path'].$dir['dir'].'/';       //获取文章的存放路径
	
	
	$thumbnail  = $_FILES['thumbnail']['name']; 
	
	foreach($_POST as $_k=>$_v) {
		if($_k == 'content') {
			$_POST[$_k] = remove_xss(strip_tags($_v, '<p><a><br><img><ul><li><div>'));
		}else{
			$_POST[$_k] = new_html_special_chars(trim_script($_v));
		}
	}	
	
	if($thumbnail){						
		$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/thumbnail/'));
		if($uppic->uploadFile('thumbnail')){
			$image = new image(YZMCMS_PATH.'/uploads/thumbnail/'); 
			$_POST['thumbnail'] = $otherconfig['wroot'].'uploads/thumbnail/'.$image->thumb($uppic->getNewFileName(), $otherconfig['pic_wid'], $otherconfig['pic_hei'], "th_");
			@unlink(YZMCMS_PATH.'/uploads/thumbnail/'.$uppic->getNewFileName());  //删除大图
		}else{
			showmsg($uppic->getErrormsg(),5);
		}					  
	}	
	
	$_POST['abstract'] = $_POST['abstract']!='' ? $_POST['abstract'] : cut_str(strip_tags($_POST['content']),70);
	$_POST['inputtime'] = time();				
	$_POST['ip'] = getip();				
	$_POST['system'] = '0';
	$_POST['status'] = '7'; //为文章置顶做准备
	$_POST['display'] = '0';
	$_POST['username'] = $username;	
	$_POST['nickname'] = $nickname;	
	
	$lastid = $article->insert($_POST);
				
	$url = $otherconfig['article_html'] ? $otherconfig['wroot'].$_POST['columnpath'].$lastid.'.html' : $otherconfig['wroot'].'article.php?id='.$lastid;
	$article->update(array('url'=>$url),array('id'=>$lastid));
	
	//投稿奖励积分
    if($otherconfig['publish_point'] > 0){
		$member->update('`point`=`point`+'.$otherconfig['publish_point'], array('userid'=>$userid)); 
		M('pay')->insert(array('trade_sn'=>create_tradenum(),'userid'=>$userid,'username'=>$username,'money'=>$otherconfig['publish_point'],'creat_time'=>time(),'msg'=>'投稿奖励','payment'=>'自动获取','type'=>'1','ip'=>getip(),'status'=>'1'));
		$member_arr = array();
		$member_arr['userid'] = $userid;
		$member_arr['point'] = $point += $otherconfig['publish_point'];	
		$member_arr['groupid'] = $groupid;
		update_group($member_arr);	//检查更新会员组		
	}
	
	//添加会员动态
	M('member_event')->insert(array('userid'=>$userid,'username'=>$username,'userevent'=>'发布了新文章《<a href="'.$url.'" target="_blank">'.$_POST['title'].'</a>》','articleid'=>$lastid,'eventtype'=>'1','eventstatus'=>'0','eventtime'=>time()));
	
	showmsg("发布成功，等待管理员审核！",1,'manuscript.php');
}

$column_data = $column->field('id,title')->where(array('member_publish'=>1))->select(); //只查询允许投稿的栏目
$html = '<select id="sel" name="catid"><option value="0">请选择栏目...</option>';
foreach($column_data as $val){
	$html .= '<option value="'.$val['id'].'">'.$val['title'].'</option>';
}
$html .= '</select>';


//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-在线投稿';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 