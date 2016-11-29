<?php
require('../common/frontend.inc.php');

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0; //获取栏目ID,网站头部用到

$title = '留言反馈';
$wkeyword = $wname.'留言反馈,'.$wname.'留言板';
$wdescription = $wname.'留言反馈,'.$wname.'留言板,留言板';

//获取当前位置
$location = '<a href="'.$wroot.'">首页</a> &gt; 留言反馈';

$guestbook = M('guestbook');

include template('guestbook.htm');