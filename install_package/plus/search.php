<?php 
require('../common/frontend.inc.php');

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0; //获取栏目ID,网站头部用到

$_GET['cpage'] = isset($_GET['cpage']) ? intval($_GET['cpage']) : 1;

//搜索框搜索
if(isset($_GET["search"])){	
	$time_lock = 'time_lock.inc';
	$s = $search_limit;	//后台限制的时间	
	$keyword = str_replace('%', '', new_html_special_chars(strip_tags(trim($_GET["q"]))));
	$nowtime = time();	
	
	$locktime = file_exists($time_lock) ? file_get_contents($time_lock) : 0;
	$retime = ($locktime+$s) < $nowtime ? true : false;	   
	if(!$retime){
		showmsg('管理员设定搜索时间间隔为'.$s.'秒，请稍后再试！');
	}elseif(strlen($keyword) <= 2 || strlen($keyword) >= 30){
		showmsg('你输入的字符过长或过短！', 1, 'search.php');
	}else{
        //把用户搜索的关键字存入数据库，方便管理员统计数据		
		$search = M('search');
		$recount  = $search->field('aid,keyword')->where(array('keyword'=>$keyword))->find();	
		if($recount){
			$search->update("cou = cou+1, lasttime = $nowtime", array('aid'=>$recount['aid']));  //更新搜索关键字库
		}else{
			$search->insert(array('keyword'=>$keyword, 'cou'=>1, 'lasttime'=>$nowtime));							  
		}
		
		file_put_contents($time_lock, $nowtime);

        //搜索 where 条件
		$where = "`title` LIKE '%$keyword%' AND `display` = 1";					
	}
	
}elseif(isset($_GET["tag"])){        //TAG标签搜索
	$keyword = str_replace('%', '', new_html_special_chars(strip_tags(trim($_GET["tag"]))));
	$tag = M('tag');	   
	$res = $tag->where(array('tag'=>$keyword))->find();
	if(!$res) showmsg("TAG标签不存在，请检查！");
	$tagid = $res['id'];
	$tag->update('`tag_click`=`tag_click`+1', array('tag'=>$keyword));   //更新TAG标签点击数量
	
    //搜索 where 条件
	$where = "FIND_IN_SET('$tagid',`tag`)";	

}else{
	$keyword = '';
	$where = "1=2";		//如果啥也没搜，那就不让他显示任何内容了
}


$title = "搜索结果";
$wkeyword = $keyword;
$wdescription = $keyword.'的搜索结果，'.$wname;

include template('search.htm');