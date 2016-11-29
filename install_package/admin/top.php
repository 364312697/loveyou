<?php
require('check.php');
$guestbook = M('guestbook');
$res = $guestbook->field('id')->where(array('replyid'=>'0','isread'=>'0'))->total();	

include('templets/top.htm');