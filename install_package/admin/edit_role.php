<?php
require('check.php');

$admintype = M('admintype');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id == 1) showmsg("超级管理员权限不能修改！", 3, 'manage_role.php');

if(!isset($_POST['dosubmit'])){	
	
	$data = $admintype->where(array('rank' => $id))->find();
	
	
	$menu1 = array(
		'manage_info' => '信息管理',
		'add_article' => '发布信息',
		'manage_publish' => '投稿管理',
		'manage_column' => '栏目管理',
		'manage_comment' => '评论管理',
		'manage_words' => '留言反馈',
		'manage_link' => '友情链接',
		'manage_additional_field' => '附加字段',
        'img_table' => '图文资讯',		
		'readwords' => '查看/回复留言',  //非菜单权限
		'add_column' => '添加栏目',  //非菜单权限
		'add_link' => '添加友情链接',  //非菜单权限
		'edit_column' => '修改栏目',  //非菜单权限
		'edit_article' => '修改文章',  //非菜单权限
		'edit_link' => '修改友情链接',  //非菜单权限
		'singlepage_content' => '添加/修改单页内容',  //非菜单权限				
		'add_additional_field' => '添加附加字段',  //非菜单权限		
		'edit_additional_field' => '修改附加字段',  //非菜单权限
        'del_all_article' => '删除任意文档',  //非菜单权限		
	);

	$menu2 = array(
		'make_homehtml' => '生成首页HTML',
		'make_articlehtml' => '生成文档HTML',
		'make_pagehtml' => '生成单页HTML',
		'make_maphtml' => '生成网站地图',
	);


	$menu3 = array(
		'add_collection' => '添加采集节点',
		'manage_collection' => '采集节点管理',
		'collection_list' => '采集列表',
		'collection_test' => '采集测试', //非菜单权限
		'collection_list_url' => '采集网址', //非菜单权限
		'collection_content' => '采集内容', //非菜单权限
		'edit_collection' => '修改采集节点', //非菜单权限
		'collection_content_import' => '导入采集内容', //非菜单权限
	);


	$menu4 = array(
		'member_list' => '会员管理',
		'member_check' => '审核会员',
		'member_add' => '添加会员',
		'member_point' => '积分记录',
		'member_notice' => '消息通知',
		'member_count' => '会员统计',
		'member_group' => '会员组管理',
		'member_set' => '会员中心设置',
		'member_show' => '查看会员信息', //非菜单权限
		'member_add' => '添加会员', //非菜单权限
		'member_edit' => '修改会员', //非菜单权限
		'member_group_add' => '添加会员组', //非菜单权限
		'member_group_edit' => '修改会员组', //非菜单权限
	);

	$menu5 = array(
		'manage_admin' => '管理员管理',
		'manage_role' => '角色管理',
		'edit_admin_public' => '修改个人信息',			
		'webinfo' => '网站基本设置',
		'other_config' => '网站附加设置',
		'email' => 'SMTP邮箱配置',
		'error' => '404页面设置',
		'add_admin' => '添加管理员', //非菜单权限
		'add_role' => '添加角色',  //非菜单权限
		'edit_admin' => '修改管理员',  //非菜单权限
		'edit_role' => '修改角色',  //非菜单权限
	);

	$menu6 = array(
		'tools' => '常用工具',
		'manage_innerlink' => '内链功能',
		'filelock' => '文件管理',
		'user_log' => '登录日志',
		'send_email' => '发送邮件',
		'manage_custom' => '自定义变量',			
		'manage_diyform' => '自定义表单',			
		'search_take' => '前端搜索统计',
		'manage_tag' => 'TAG标签管理',
		'add_tag' => '添加TAG标签',  //非菜单权限
		'edit_tag' => '修改TAG标签',  //非菜单权限
		'innerlink' => '添加/修改内链',  //非菜单权限
		'custom' => '添加/修改自定义变量',  //非菜单权限
		'add_diyform' => '添加自定义表单',  //非菜单权限
		'edit_diyform' => '修改自定义表单',  //非菜单权限
		'add_diy_field' => '添加字段',  //非菜单权限
		'edit_diy_field' => '修改字段',  //非菜单权限
		'manage_diy_field' => '字段管理',  //非菜单权限
		'manage_list_diyform' => '信息列表',  //非菜单权限
		'show_diyform' => '信息查看',  //非菜单权限
		
	);

	$menu7 = array(
		'data_backups' => '数据备份',
		'data_recover' => '数据恢复',
	);
	
	
	$purview_arr = explode(',', $data['purviews']);
	
	
}else{
	if(empty($_POST['typename'])) showmsg("角色名称不能为空！");
	
	if(isset($_POST['purviews']))
	$_POST['purviews'] = join(',', $_POST['purviews']);

	$r = $admintype->update($_POST, array('rank' => $id));
	if($r){
		delcache($id.'_menu', 'menu');
		showmsg("操作成功！", 1, 'manage_role.php');		
	}else{
		showmsg("数据未修改！", 3, 'manage_role.php');
	}

}


include('templets/edit_role.htm');