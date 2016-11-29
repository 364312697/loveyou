<?php
require('check.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$id) showmsg("非法参数！");

$collection_content = M('collection_content');
$sysinfo = get_sysinfo();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$total = isset($_GET['total']) ? intval($_GET['total']) : 0;

if(!$total) $total = $collection_content->field('id,url')->where(array('nodeid' => $id, 'status' => 0))->total();
$total_page = ceil($total/2);

$list = $collection_content->field('id,url')->where(array('nodeid' => $id, 'status' => 0))->order('id DESC')->limit('2')->select();

if(empty($list)) showmsg("没有找到网址列表，请先进行网址采集！");

$collection_node = M('collection_node');
$data = $collection_node->field('sourcecharset,down_attachment,watermark,title_rule,title_html_rule,time_rule,time_html_rule,content_rule,content_html_rule')->where(array('nodeid' => $id))->find();

$config = array(
   'title_rule' => collection::myexp('[内容]', $data['title_rule']),
   'title_html_rule' => collection::myexp('[|]', $data['title_html_rule']),
   
   'time_rule' => collection::myexp('[内容]', $data['time_rule']),
   'time_html_rule' => collection::myexp('[|]', $data['time_html_rule']),
   
   'content_rule' => collection::myexp('[内容]', $data['content_rule']),
   'content_html_rule' => collection::myexp('[|]', $data['content_html_rule']),
);

$i = 0;
foreach($list as $v){
	$article = collection::get_content($v['url']);
	if($data['sourcecharset'] == 'gbk') $article = array_iconv($article);	
	$article = collection::get_filter_html($article, $config);
	if($data['down_attachment']) $article['content'] = grab_image($article['content'], $sysinfo['wroot'], collection::$url);
	$collection_content->update(array('status'=>1, 'data'=>array2string($article)), array('id'=>$v['id']));
    $i++;	
}


if($total_page > $page){
	$html_message =  '采集正在进行中，采集进度:'.($i+($page-1)*2).'/'.$total.'<script type="text/javascript">location.href="?id='.$id.'&page='.($page+1).'&total='.$total.'"</script>';
	include('templets/message.htm');
}else{
	$collection_node->update(array('lastdate' => time()), array('nodeid'=>$id));
	showmsg("采集完成！", 3, 'collection_list.php?status=1&search=1');
}