<?php 
/**
 * 更新文章点击量
 * @param mid 文章ID
 * @return 点击量
 */
 
require("../config/common.inc.php");
if(isset($_GET['action']) && $_GET['action'] == 'dj'){
	$mid = intval($_GET['mid']);
	$article = M('article');
	$article->update('`click` = `click`+1', array('id' => $mid));
	$row = $article->field('click')->where(array('id' => $mid))->find();
	echo "document.write('".$row['click']."');";
}