<?php
require('check.php');

$article = M('article');
$res = $article->field('id,title')->limit('0,9')->order("id DESC")->select();


$recount = $article->total();
$display = $article->where(array('display'=>'0'))->total();
$xitong = $article->where(array('system'=>'1'))->total(); //系统发布
$zhiding = $article->where("FIND_IN_SET('1',status)")->total(); //置顶
$toutiao = $article->where("FIND_IN_SET('2',status)")->total(); //头条
$tejian = $article->where("FIND_IN_SET('3',status)")->total(); //特荐
$tuijian = $article->where("FIND_IN_SET('4',status)")->total(); //推荐
$redian = $article->where("FIND_IN_SET('5',status)")->total(); //热点
$huandeng = $article->where("FIND_IN_SET('6',status)")->total(); //幻灯

$column = M('column')->field('id')->total(); //全部栏目
$admin =  M('admin')->field('id')->total();
$guestbook =  M('guestbook')->field('id')->where(array('replyid'=>'0'))->total();

include ('templets/default.htm');