<?php
require('check.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$id) showmsg("非法参数！");

$collection_node = M('collection_node');
$data = $collection_node->where(array('nodeid' => $id))->find();

if($data['urlpage'] == '') showmsg("网址配置为空！");

//目标网址
if($data['sourcetype'] == 1){
	$url = str_replace('(*)', $data['pagesize_start'], $data['urlpage']);
}else{
	$url = $data['urlpage'];
}

//定义采集列表区间
$url_start = $data['url_start'];
$url_end = $data['url_end'];

if($url_start=='' || $url_end=='') showmsg("列表区域配置为空！");

$content = collection::get_content($url);
$content = collection::get_sub_content($content, $url_start, $url_end);
if($content){
	
	if($data['sourcecharset'] == 'gbk') $content = array_iconv($content);
	$content = collection::get_all_url($content, $data['url_contain'], $data['url_except']);

	//获取第一篇文章地址来测试
	$articleurl = isset($content['url'][0]) ? $content['url'][0] : '';
	if(!empty($articleurl)){
		
		$config = array(
		   'title_rule' => collection::myexp('[内容]', $data['title_rule']),
		   'title_html_rule' => collection::myexp('[|]', $data['title_html_rule']),
		   
		   'time_rule' => collection::myexp('[内容]', $data['time_rule']),
		   'time_html_rule' => collection::myexp('[|]', $data['time_html_rule']),
		   
		   'content_rule' => collection::myexp('[内容]', $data['content_rule']),
		   'content_html_rule' => collection::myexp('[|]', $data['content_html_rule']),
		);

		$article = collection::get_content($articleurl);
		$article = collection::get_filter_html($article, $config);
		if($data['sourcecharset'] == 'gbk') $article = array_iconv($article);	
	}else{
		$article = '列表规则错误！';
	}	

}else{
	$article = '列表规则错误！';
}


include('templets/collection_test.htm');  