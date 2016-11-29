<?php 
if(!file_exists('index.html') || isset($_GET['upcache'])){ 
	require(str_replace("\\", '/', dirname(__FILE__)).'/common/frontend.inc.php');

	$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0; //获取栏目ID,网站头部用到

	//文章属性：1置顶,2头条,3特荐,4推荐,5热点,6幻灯
	
	//TAG标签
	$tag = M('tag');
    
    include template('index.htm');

}else{
	include("index.html");
}
?> 