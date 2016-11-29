<?php
require('check.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$id) showmsg("非法参数！");

$collection_node = M('collection_node');
$collection_content = M('collection_content');
$data = $collection_node->where(array('nodeid' => $id))->find();
if(!$data) showmsg("该采集节点不存在！");
if($data['urlpage'] == '') showmsg("网址配置为空！");

//目标网址
if($data['sourcetype'] == 1){
	$url = array();
	for ($i = $data['pagesize_start']; $i <= $data['pagesize_end']; $i = $i + $data['par_num']) {
		$url[] = str_replace('(*)', $i, $data['urlpage']);
	}
}else{
	$url[0] = $data['urlpage'];
}



//定义采集列表区间
$url_start = $data['url_start'];
$url_end = $data['url_end'];

if($url_start=='' || $url_end=='') showmsg("列表区域配置为空！");
$i = $j = 0;

foreach($url as $v){
	$content = collection::get_content($v);
	$content = collection::get_sub_content($content, $url_start, $url_end);
	
	if(!$content) continue;
	
	if($data['sourcecharset'] == 'gbk') $content = array_iconv($content);
	$content = collection::get_all_url($content, $data['url_contain'], $data['url_except']);
    
    if(!empty($content['url'])) foreach($content['url'] as $k => $v){
		$r = $collection_content->field('url')->where(array('url' => $v))->find();
		if(!$r){
			$collection_content->insert(array('nodeid' => $data['nodeid'], 'status' => 0, 'url' => $v, 'title' => $content['title'][$k]));
			$j++;
		}else{
			$i++;
		} 
	}
	
}

showmsg("操作成功，共去除{$i}条重复数据，新增{$j}条数据！", 5); 