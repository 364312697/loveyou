<?php
require('check.php');
$member = M('member');

$month_where = strtotime('-1 month').' AND '.time(); //最近30天
$week_where = strtotime('-1 week').' AND '.time(); //最近7天

$yesterday_where = strtotime(date("Y-m-d",strtotime("-1 day"))).' AND '.strtotime(date("Y-m-d")); //昨天0点到昨天24点
$today_where = strtotime(date("Y-m-d")).' AND '.time(); //今天0点到现在时间


$total = $member->total();
$month_total = $member->where("regdate BETWEEN $month_where")->total();
$week_total = $member->where("regdate BETWEEN $week_where")->total();
$yesterday_total = $member->where("regdate BETWEEN $yesterday_where")->total();
$today_total = $member->where("regdate BETWEEN $today_where")->total();

include('templets/member_count.htm');