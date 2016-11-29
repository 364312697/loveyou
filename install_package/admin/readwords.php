<?php
require('check.php');

$guestbook = M('guestbook');
if(isset($_GET["id"])){
	$id = intval($_GET["id"]);
	$guestbook->update(array('isread'=>'1'),array('id'=>$id));
	$gues = $guestbook->where(array('id'=>$id))->find();
}
		
if(isset($_POST['submit']) && $_POST['bookmsg']!=''){		
	if($guestbook->insert($_POST,'1'))
	 showmsg("回复成功！",'1',"manage_words.php");
    else
     showmsg("回复失败！",'3');		
}
		
if(isset($_POST['subche'])){		
	$guestbook->update($_POST,array('id'=>$id));
	showmsg("设置成功！",'1',"manage_words.php");
}

$res = $guestbook->field('booktime,bookmsg')->where(array('replyid'=>$id))->select();

include('templets/readwords.htm');