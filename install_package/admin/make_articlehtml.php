<?php
require('check.php');

$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;

if(isset($_GET['dosubmit'])){				
						
	$article = M('article');
	
	$where = '`display` = 1';	
	
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
	$startid = $_GET['startid'] ? intval($_GET['startid']) : 1;
	$endid = $_GET['endid'] ? intval($_GET['endid']) : 0;
	
	if(!$catid){	
		if($startid) $where .= " AND id >= $startid";
		if($endid) $where .= " AND id <= $endid";
	}else{
		$where .= " AND catid = $catid";
	}
			
	$result = $article->field('id')->where($where)->limit((($page-1)*10).',10')->select(); 
	if(!$total) $total = $article->field('id')->where($where)->total();
	$total_page = ceil($total/10);
	
	$i = 0;
	foreach($result as $v){
        $len = make_html($v['id']);
		$i++;	
	}
    if(!$len) showmsg('生成文件失败，请检查文件权限！', 3, 'make_articlehtml.php');
	
	if($total_page > $page){
		$html_message =  '生成文档正在进行中，进度:'.($i+($page-1)*10).'/'.$total.'<script type="text/javascript">location.href="?startid='.$startid.'&endid='.$endid.'&catid='.$catid.'&page='.($page+1).'&total='.$total.'&dosubmit=1"</script>';
		include('templets/message.htm');
	}else{
		$html_message = '生成文件成功，共'.$total.'个文档';
		showmsg('生成文件成功，共'.$total.'个文档', 2, 'make_articlehtml.php?catid='.$catid.'&startid='.$startid.'&endid='.$endid);
	}	

    
				
}else{
	$column = M('column');
	$sysinfo = get_sysinfo();
	$htmlpath = $sysinfo['html_path'];
    include('templets/make_articlehtml.htm');	
}
?>