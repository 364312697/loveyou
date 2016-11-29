<?php
require('check.php');

$userid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data1= M('member')->where(array('userid'=>$userid))->find();
$data2= M('member_detail')->where(array('userid'=>$userid))->find();
$data3 = M('member_group')->field('name')->where(array('groupid'=>$data1['groupid']))->find();
$data1['groupname'] = $data3['name'];

$data = array_merge($data1, $data2);


include('templets/member_show.htm');