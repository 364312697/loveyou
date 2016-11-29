<?php
require('check.php');
$member = M('member');
$member_group = M('member_group');
$groupdata = $member_group->field('groupid,name')->select();

$where = '1 = 1';

if(isset($_POST['pldel_sub'])){  //删除

	if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	$where = to_sqls($_POST['fx'], '', 'a.userid');
	$member->query("DELETE a,b from yzmcms_member AS a LEFT JOIN yzmcms_member_detail AS b ON a.userid=b.userid WHERE $where"); 
	showmsg("操作成功！",1);
	
}else if(isset($_POST['lock'])){  //锁定

	if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	$where = to_sqls($_POST['fx'], '', 'userid');
	$member->query("UPDATE yzmcms_member SET `status`=2 WHERE $where"); 
	showmsg("锁定会员成功！",1);
	
}else if(isset($_POST['unlock'])){  //解锁

	if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	$where = to_sqls($_POST['fx'], '', 'userid');
	$member->query("UPDATE yzmcms_member SET `status`=1 WHERE $where"); 
	showmsg("解锁会员成功！",1);
	
}else if(isset($_GET['search'])){  //搜索
	 
	$status = isset($_GET["status"]) ? intval($_GET["status"]) : 99;
	$groupid = isset($_GET["groupid"]) ? intval($_GET["groupid"]) : 0;
	$searinfo = isset($_GET["searinfo"]) ? $_GET["searinfo"] : '';
    $searkey = isset($_GET["searkey"]) ? $_GET["searkey"] : '';
	
	if($searinfo != ''){
		if($searkey != 'userid')
		    $where .= ' AND '.$searkey.' LIKE \'%'.$searinfo.'%\'';
	    else
			$where .= ' AND a.userid = \''.$searinfo.'\'';
	}

	if(isset($_GET["start"]) && $_GET["start"] == ''){	
		$where .= ' AND regdate < '.strtotime($_GET["end"]);
	}else if(isset($_GET["start"]) && $_GET["start"] != '' && $_GET["end"] != ''){		
		$where .= ' AND regdate BETWEEN '.strtotime($_GET["start"]).' AND '.strtotime($_GET["end"]);
	}
	
	if($status != 99) {
		$where .= ' AND status = '.$status;
	}
	
	if($groupid) {
		$where .= ' AND groupid = '.$groupid;
	}
	
	//因为搜索功能涉及到两个表的字段，所以要重新计算total
     $total = $member->join('yzmcms_member_detail AS b ON yzmcms_member.userid = b.userid', 'LEFT')->where($where)->total();	
	
}else{
	
	$total = $member->total();
	
}

$page = new spage($total,10);
$start = $page->start_rows();
$res = $member->fetch_all($member->query("SELECT a.*,b.nickname FROM yzmcms_member AS a LEFT JOIN yzmcms_member_detail AS b ON a.userid = b.userid WHERE $where ORDER BY a.userid DESC LIMIT $start, 10"));	


include('templets/member_list.htm');