<?php require('check.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>请选择你的后续操作</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="main.php">首页</a></li>
    <li><a href="#">操作成功</a></li>
    </ul>
    </div>
	<div class="transfer">
	<h6>操作成功！</h6>
    <p><strong>请选择你的后续操作：</strong><a href="add_article.php?lastcatid=<?php echo $_GET['lastcatid']?>">继续发布</a>|<a href="manage_info.php">管理文章</a>|<a href="../article.php?id=<?php echo $_GET['id']?>" target="blank">动态浏览</a>|<a href="make_articlehtml.php">更新文档</a></p>
	</div>
</body>
</html>
