<?php 
require('check.php');

//如果是超级管理员
if($usertype == '1'){
	$menustr = '<dd>
		<div class="title">
		<span><img src="images/left.gif" /></span>信息管理
		</div>
			<ul class="menuson">
			<li class="active"><cite></cite><a href="main.php" target="rightFrame">系统首页</a><i></i></li>
			<li><cite></cite><a href="manage_info.php" target="rightFrame">信息管理</a><i></i></li>
			<li><cite></cite><a href="add_article.php" target="rightFrame">发布信息</a><i></i></li>
			<li><cite></cite><a href="manage_publish.php" target="rightFrame">投稿管理</a><i></i></li>
			<li><cite></cite><a href="manage_column.php" target="rightFrame">栏目管理</a><i></i></li>		
			<li><cite></cite><a href="manage_comment.php" target="rightFrame">评论管理</a><i></i></li>
			<li><cite></cite><a href="manage_words.php" target="rightFrame">留言反馈</a><i></i></li>
			<li><cite></cite><a href="manage_link.php" target="rightFrame">友情链接</a><i></i></li>
			<li><cite></cite><a href="manage_additional_field.php" target="rightFrame">附加字段</a><i></i></li>						
			</ul>    
		</dd>
		
		<dd>
		<div class="title">
		<span><img src="images/left.gif" /></span>生成静态
		</div>
			<ul class="menuson">
			<li><cite></cite><a href="make_homehtml.php" target="rightFrame">生成首页HTML</a><i></i></li>
			<li><cite></cite><a href="make_articlehtml.php" target="rightFrame">生成文档HTML</a><i></i></li>
			<li><cite></cite><a href="make_pagehtml.php" target="rightFrame">生成单页HTML</a><i></i></li>
			<li><cite></cite><a href="make_maphtml.php" target="rightFrame">生成网站地图</a><i></i></li>
			</ul>     
		</dd> 
		
		<dd>
		<div class="title">
		<span><img src="images/left.gif" /></span>采集管理
		</div>
			<ul class="menuson">
			<li><cite></cite><a href="add_collection.php" target="rightFrame">添加采集节点</a><i></i></li>
			<li><cite></cite><a href="manage_collection.php" target="rightFrame">采集节点管理</a><i></i></li>
			<li><cite></cite><a href="collection_list.php" target="rightFrame">采集列表</a><i></i></li>
			</ul>     
		</dd> 
		
		<dd>
		<div class="title">
		<span><img src="images/left.gif" /></span>会员中心
		</div>
			<ul class="menuson">
			<li><cite></cite><a href="member_list.php" target="rightFrame">会员管理</a><i></i></li>
			<li><cite></cite><a href="member_check.php" target="rightFrame">审核会员</a><i></i></li>
			<li><cite></cite><a href="member_add.php" target="rightFrame">添加会员</a><i></i></li>
			<li><cite></cite><a href="member_point.php" target="rightFrame">积分记录</a><i></i></li>
			<li><cite></cite><a href="member_notice.php" target="rightFrame">消息通知</a><i></i></li>
			<li><cite></cite><a href="member_count.php" target="rightFrame">会员统计</a><i></i></li>
			<li><cite></cite><a href="member_group.php" target="rightFrame">会员组管理</a><i></i></li>
			<li><cite></cite><a href="member_set.php" target="rightFrame">会员中心设置</a><i></i></li>
			</ul>     
		</dd>
		
		<dd>
		<div class="title">
		<span><img src="images/left.gif" /></span>其他设置
		</div>
			<ul class="menuson">			
			<li><cite></cite><a href="manage_admin.php" target="rightFrame">管理员管理</a><i></i></li>
			<li><cite></cite><a href="manage_role.php" target="rightFrame">角色管理</a><i></i></li>
			<li><cite></cite><a href="edit_admin_public.php" target="rightFrame">修改个人信息</a><i></i></li>			
			<li><cite></cite><a href="webinfo.php" target="rightFrame">网站基本设置</a><i></i></li>
			<li><cite></cite><a href="other_config.php" target="rightFrame">网站附加设置</a><i></i></li>
			<li><cite></cite><a href="email.php" target="rightFrame">SMTP邮箱配置</a><i></i></li>
			<li><cite></cite><a href="error.php" target="rightFrame">404页面设置</a><i></i></li>
			</ul>     
		</dd> 
		
		<dd><div class="title"><span><img src="images/left.gif" /></span>常用工具</div>
		<ul class="menuson">
			<li><cite></cite><a href="tools.php" target="rightFrame">常用工具</a><i></i></li>
			<li><cite></cite><a href="manage_innerlink.php" target="rightFrame">内链功能</a><i></i></li>
			<li><cite></cite><a href="filelock.php" target="rightFrame">文件管理</a><i></i></li>
			<li><cite></cite><a href="user_log.php" target="rightFrame">登录日志</a><i></i></li>
			<li><cite></cite><a href="send_email.php" target="rightFrame">发送邮件</a><i></i></li>
			<li><cite></cite><a href="manage_custom.php" target="rightFrame">自定义变量</a><i></i></li>
			<li><cite></cite><a href="manage_diyform.php" target="rightFrame">自定义表单</a><i></i></li>			
			<li><cite></cite><a href="search_take.php" target="rightFrame">前端搜索统计</a><i></i></li>
			<li><cite></cite><a href="manage_tag.php" target="rightFrame">TAG标签管理</a><i></i></li>
		</ul>    
		</dd>  
			
		<dd><div class="title"><span><img src="images/left.gif"/></span>数据备份与恢复</div>
		<ul class="menuson">
			<li><cite></cite><a href="data_backups.php" target="rightFrame">数据备份</a><i></i></li>
			<li><cite></cite><a href="data_recover.php" target="rightFrame">数据恢复</a><i></i></li>
		</ul>   
		</dd>   
		<dd><div class="title"><span><img src="images/left.gif"/></span>关于我们</div>
		<ul class="menuson">
			<li><cite></cite><a href="about.php" target="rightFrame">关于</a><i></i></li>
		</ul>   
		</dd>';
	
}else{
	
	//获取缓存内的菜单列表
	if(!$menustr = getcache($usertype.'_menu', 2, 'menu')){
		
		$r = M('admintype')->field('purviews')->where(array('rank' => $usertype))->find(); //这里从数据库查而不是从SESSION获取是因为权限要及时更新
		$_SESSION['userinfo']['purviews'] = $r['purviews'];

		$purview_arr = explode(',', $r['purviews']);
		
		$menustr1 = $menustr2 = $menustr3 = $menustr4 = $menustr5 = $menustr6 = $menustr7 = $menubox2 = $menubox3 = $menubox4 = $menubox5 = $menubox6 = $menubox7 = '';
		
		$menu1 = array(
			'manage_info' => '信息管理',
			'add_article' => '发布信息',
			'manage_publish' => '投稿管理',
			'manage_column' => '栏目管理',
			'manage_comment' => '评论管理',
			'manage_words' => '留言反馈',
			'manage_link' => '友情链接',
			'manage_additional_field' => '附加字段',			
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
		);

		$menu5 = array(			
			'manage_admin' => '管理员管理',
			'manage_role' => '角色管理',
			'edit_admin_public' => '修改个人信息',			
			'webinfo' => '网站基本设置',
			'other_config' => '网站附加设置',
			'email' => 'SMTP邮箱配置',
			'error' => '404页面设置',
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
		);

		$menu7 = array(
			'data_backups' => '数据备份',
			'data_recover' => '数据恢复',
		);
		
		
		foreach($menu1 as $key => $val){
			 $menustr1 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		foreach($menu2 as $key => $val){
			 $menustr2 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		foreach($menu3 as $key => $val){
			 $menustr3 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		foreach($menu4 as $key => $val){
			 $menustr4 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		foreach($menu5 as $key => $val){
			 $menustr5 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		foreach($menu6 as $key => $val){
			 $menustr6 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		foreach($menu7 as $key => $val){
			 $menustr7 .= in_array($key, $purview_arr) ? ' <li><cite></cite><a href="'.$key.'.php" target="rightFrame">'.$val.'</a><i></i></li>' : '';	
		}
		
		
		//菜单显示框
		if($menustr2 != ''){
			$menubox2 = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>生成静态
			</div>
				<ul class="menuson">
				'.$menustr2.'
				</ul>     
			</dd>';
		}
		
		if($menustr3 != ''){
			$menubox3 = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>采集管理
			</div>
				<ul class="menuson">
				'.$menustr3.'
				</ul>     
			</dd>';
		}
		
		if($menustr4 != ''){
			$menubox4 = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>会员中心
			</div>
				<ul class="menuson">
				'.$menustr4.'
				</ul>     
			</dd>';
		}
		
		if($menustr5 != ''){
			$menubox5 = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>其他设置
			</div>
				<ul class="menuson">
				'.$menustr5.'
				</ul>     
			</dd>';
		}
		
		if($menustr6 != ''){
			$menubox6 = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>常用工具
			</div>
				<ul class="menuson">
				'.$menustr6.'
				</ul>     
			</dd>';
		}
		
		if($menustr7 != ''){
			$menubox7 = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>数据备份与恢复
			</div>
				<ul class="menuson">
				'.$menustr7.'
				</ul>     
			</dd>';
		}
		
		
		$menustr = '<dd>
			<div class="title">
			<span><img src="images/left.gif" /></span>信息管理
			</div>
				<ul class="menuson">
				<li class="active"><cite></cite><a href="main.php" target="rightFrame">系统首页</a><i></i></li>
				'.$menustr1.'
				</ul>    
			</dd>
			'.$menubox2.$menubox3.$menubox4.$menubox5.$menubox6.$menubox7.'   
			<dd><div class="title"><span><img src="images/left.gif"/></span>关于我们</div>
			<ul class="menuson">
				<li><cite></cite><a href="about.php" target="rightFrame">关于</a><i></i></li>
			</ul>   
			</dd>';
			
		setcache($usertype.'_menu', $menustr, 2, 'menu');	
	
	}

}

include('templets/left.htm');