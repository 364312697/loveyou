/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : yzmweb

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2015-05-26 12:27:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yzm_admin
-- ----------------------------
DROP TABLE IF EXISTS `yzm_admin`;
CREATE TABLE `yzm_admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `usertype` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `pwd` char(32) NOT NULL DEFAULT '',
  `uname` varchar(30) NOT NULL DEFAULT '',
  `nickname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(30) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `addpeople` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_admin
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_admintype
-- ----------------------------
DROP TABLE IF EXISTS `yzm_admintype`;
CREATE TABLE `yzm_admintype` (
  `rank` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `typename` varchar(30) NOT NULL DEFAULT '',
  `description` varchar(90) NOT NULL DEFAULT '',
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `purviews` text,
  PRIMARY KEY (`rank`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_admintype
-- ----------------------------
INSERT INTO `yzm_admintype` VALUES ('1', '超级管理员', '超级管理员', '1', 'admin_allowall');
INSERT INTO `yzm_admintype` VALUES ('2', '总编', '总编', '1', 'manage_info,add_article,manage_publish,manage_column,img_table,manage_comment,manage_words,manage_link,readwords,add_column,add_link,edit_admin_public,edit_column,edit_article,edit_link,make_homehtml,make_articlehtml,make_maphtml,make_pagehtml,error,tools,search_take,manage_tag,manage_custom,manage_innerlink,user_log,add_tag,edit_tag,innerlink,singlepage_content');
INSERT INTO `yzm_admintype` VALUES ('3', '发布人员', '发布人员', '1', 'manage_info,add_article,img_table,edit_admin_public,edit_article,make_homehtml,make_articlehtml,make_maphtml,make_pagehtml,error,tools,user_log');

-- ----------------------------
-- Table structure for yzm_adminlog
-- ----------------------------
DROP TABLE IF EXISTS `yzm_adminlog`;
CREATE TABLE `yzm_adminlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(20) NOT NULL default '',
  `login_time` int(10) unsigned NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `address` varchar(30) NOT NULL default '',
  `pwd` varchar(30) NOT NULL default '',
  `result` tinyint(1) NOT NULL default '0' COMMENT '登录结果1为登录成功0为登录失败',
  `cause` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_adminlog
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_article
-- ----------------------------
DROP TABLE IF EXISTS `yzm_article`;
CREATE TABLE `yzm_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `catid` smallint(5) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `nickname` varchar(30) NOT NULL default '',
  `title` varchar(200) NOT NULL default '',
  `inputtime` int(10) unsigned NOT NULL default '0',
  `keyword` varchar(100) NOT NULL default '',
  `click` int(6) unsigned NOT NULL default '0',
  `tag` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `abstract` varchar(255) NOT NULL default '',
  `source` varchar(50) NOT NULL default '',
  `thumbnail` varchar(100) NOT NULL default '',
  `columnpath` varchar(30) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL default '',
  `status` varchar(12) NOT NULL default '' COMMENT '1置顶,2头条,3特荐,4推荐,5热点,6幻灯,7正常',
  `display` tinyint(1) NOT NULL default '1',
  `system` tinyint(3) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `index_catid` (`catid`),
  KEY `index_title` (`title`),
  KEY `index_time` (`inputtime`),
  KEY `index_thumbnail` (`thumbnail`),
  KEY `index_display` (`display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_article
-- ----------------------------


-- ----------------------------
-- Table structure for yzm_custom
-- ----------------------------
DROP TABLE IF EXISTS `yzm_custom`;
CREATE TABLE `yzm_custom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL DEFAULT '' COMMENT '变量名',
  `val` text NOT NULL COMMENT '变量值',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '变量描述',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_custom
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `yzm_guestbook`;
CREATE TABLE `yzm_guestbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT '' COMMENT '主题',
  `booktime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名字',
  `email` varchar(40) DEFAULT '' COMMENT '留言人电子邮箱',
  `phone` varchar(11) DEFAULT '' COMMENT '留言人电话',
  `qq` varchar(11) DEFAULT '' COMMENT '留言人qq',
  `address` varchar(100) DEFAULT '' COMMENT '留言人地址',
  `bookmsg` text COMMENT '内容',
  `ip` varchar(20) DEFAULT '' COMMENT 'ip地址',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否读过',
  `replyid` int(10) unsigned NOT NULL default '0' COMMENT '回复的id',
  PRIMARY KEY (`id`),
  KEY `index_booktime` (`booktime`),
  KEY `index_ischeck` (`ischeck`),
  KEY `index_isread` (`isread`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_guestbook
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_innerlink
-- ----------------------------
DROP TABLE IF EXISTS `yzm_innerlink`;
CREATE TABLE `yzm_innerlink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `val` varchar(30) NOT NULL DEFAULT '',
  `link` varchar(56) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_innerlink
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_link
-- ----------------------------
DROP TABLE IF EXISTS `yzm_link`;
CREATE TABLE `yzm_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weburl` varchar(60) NOT NULL DEFAULT '' COMMENT '网站URL',
  `webname` varchar(50) NOT NULL DEFAULT '' COMMENT '网站名称',
  `sortrank` smallint(5) NOT NULL DEFAULT '1' COMMENT '排序',
  `logo` varchar(90) DEFAULT '' COMMENT 'logo图片',
  `msg` varchar(150) DEFAULT '' COMMENT '网站概况',
  `email` varchar(40) DEFAULT '' COMMENT '邮箱',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_link
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_column
-- ----------------------------
DROP TABLE IF EXISTS `yzm_column`;
CREATE TABLE `yzm_column` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `pid` smallint(5) NOT NULL DEFAULT '0',
  `path` varchar(100) NOT NULL DEFAULT '',
  `dir` varchar(30) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ord` smallint(3) NOT NULL DEFAULT '0',
  `member_publish` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会员投稿',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `list_template` varchar(30) NOT NULL DEFAULT '',
  `article_template` varchar(30) NOT NULL DEFAULT '',
  `pclink` varchar(100) NOT NULL DEFAULT '',
  `moblink` varchar(80) NOT NULL DEFAULT '',
  `column_img` varchar(150) DEFAULT '',
  `seo_title` varchar(200) DEFAULT '',
  `seo_keywords` varchar(200) DEFAULT '',
  `seo_description` varchar(250) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `member_publish` (`member_publish`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_column
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_otherconfig
-- ----------------------------
DROP TABLE IF EXISTS `yzm_otherconfig`;
CREATE TABLE `yzm_otherconfig` (
  `aid` smallint(5) NOT NULL AUTO_INCREMENT,
  `varname` varchar(20) NOT NULL DEFAULT '',
  `info` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_otherconfig
-- ----------------------------
INSERT INTO `yzm_otherconfig` VALUES ('1', 'pic_wid', '缩略图宽', '180');
INSERT INTO `yzm_otherconfig` VALUES ('2', 'pic_hei', '缩略图高', '120');
INSERT INTO `yzm_otherconfig` VALUES ('3', 'html_path', '存放静态html目录', 'html/');
INSERT INTO `yzm_otherconfig` VALUES ('4', 'tem_style', '模板风格', 'default');
INSERT INTO `yzm_otherconfig` VALUES ('5', 'search_limit', '网站前端搜索时间限制', '3');
INSERT INTO `yzm_otherconfig` VALUES ('6', 'is_words', '是否开启前端留言功能', '0');
INSERT INTO `yzm_otherconfig` VALUES ('7', 'is_pathinfo', '列表页是否开启PATHINO模式', '1');
INSERT INTO `yzm_otherconfig` VALUES ('8', 'is_rewrite', '列表页是否开启REWRITE模式', '0');
INSERT INTO `yzm_otherconfig` VALUES ('9', 'article_html', '内容页是否生成html', '1');
INSERT INTO `yzm_otherconfig` VALUES ('10', 'mail_server', 'SMTP服务器', 'smtp.qq.com');
INSERT INTO `yzm_otherconfig` VALUES ('11', 'mail_port', 'SMTP服务器端口', '25');
INSERT INTO `yzm_otherconfig` VALUES ('12', 'mail_from', 'SMTP服务器的用户邮箱', '');
INSERT INTO `yzm_otherconfig` VALUES ('13', 'mail_auth', 'AUTH LOGIN验证', '1');
INSERT INTO `yzm_otherconfig` VALUES ('14', 'mail_user', 'SMTP服务器的用户帐号', '');
INSERT INTO `yzm_otherconfig` VALUES ('15', 'mail_pass', 'SMTP服务器的用户密码', '');
INSERT INTO `yzm_otherconfig` VALUES ('16', 'member_register', '是否开启会员注册', '0');
INSERT INTO `yzm_otherconfig` VALUES ('17', 'member_email', '新会员注册是否需要邮件验证', '0');
INSERT INTO `yzm_otherconfig` VALUES ('18', 'member_check', '新会员注册是否需要管理员审核', '1');
INSERT INTO `yzm_otherconfig` VALUES ('19', 'member_point', '新会员默认积分', '0');
INSERT INTO `yzm_otherconfig` VALUES ('20', 'member_yzm', '是否开启会员登录验证码', '1');
INSERT INTO `yzm_otherconfig` VALUES ('21', 'comment_tourist', '是否允许游客评论', '0');
INSERT INTO `yzm_otherconfig` VALUES ('22', 'comment_check', '是否开启评论审核', '1');
INSERT INTO `yzm_otherconfig` VALUES ('23', 'qq_app_id', 'App ID', '');
INSERT INTO `yzm_otherconfig` VALUES ('24', 'qq_app_key', 'App key', '');
INSERT INTO `yzm_otherconfig` VALUES ('25', 'login_point', '每日登陆奖励积分', '2');
INSERT INTO `yzm_otherconfig` VALUES ('26', 'comment_point', '发布评论奖励积分', '2');
INSERT INTO `yzm_otherconfig` VALUES ('27', 'publish_point', '投稿奖励积分', '3');
INSERT INTO `yzm_otherconfig` VALUES ('28', 'water_enable', '是否开启图片水印', '1');
INSERT INTO `yzm_otherconfig` VALUES ('29', 'water_img', '水印图片路径', 'mark.png');
INSERT INTO `yzm_otherconfig` VALUES ('30', 'water_pos', '水印的位置', '9');
INSERT INTO `yzm_otherconfig` VALUES ('31', 'default_email', '站点默认邮箱', '');

-- ----------------------------
-- Table structure for yzm_search
-- ----------------------------
DROP TABLE IF EXISTS `yzm_search`;
CREATE TABLE `yzm_search` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '搜索关键字',
  `cou` mediumint(8) NOT NULL DEFAULT '0' COMMENT '搜索次数',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后搜索时间',
  PRIMARY KEY (`aid`),
  KEY `index_cou` (`cou`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_search
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_singlepage
-- ----------------------------
DROP TABLE IF EXISTS `yzm_singlepage`;
CREATE TABLE `yzm_singlepage` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(160) NOT NULL DEFAULT '',
  `content` text,
  `keywords` varchar(90) NOT NULL DEFAULT '',
  `description` varchar(210) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(32) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '',
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_singlepage
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_webinfo
-- ----------------------------
DROP TABLE IF EXISTS `yzm_webinfo`;
CREATE TABLE `yzm_webinfo` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `wname` varchar(100) DEFAULT '' COMMENT '网站名称',
  `wdescription` varchar(255) DEFAULT '' COMMENT '网站描述',
  `wkeyword` varchar(255) DEFAULT '' COMMENT '网站关键字',
  `wcopyright` varchar(255) DEFAULT '' COMMENT '网站版权信息',
  `wfiling` varchar(100) DEFAULT '' COMMENT '网站备案号',
  `wroot` varchar(100) DEFAULT '' COMMENT '网站根网址',
  `wpath` varchar(30) DEFAULT '' COMMENT 'CMS安装目录',
  `wcode` text COMMENT '统计代码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_webinfo
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_tag
-- ----------------------------
DROP TABLE IF EXISTS `yzm_tag`;
CREATE TABLE `yzm_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(30) NOT NULL DEFAULT '',
  `catid` tinyint(5) unsigned NOT NULL DEFAULT '0',
  `tag_click` int(10) unsigned NOT NULL DEFAULT '0',
  `article_total` int(8) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tag_index` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_tag
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_comment
-- ----------------------------
DROP TABLE IF EXISTS `yzm_comment`;
CREATE TABLE `yzm_comment` (
  `articleid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` char(255) NOT NULL DEFAULT '',
  `url` char(255) NOT NULL DEFAULT '',
  `total` int(8) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `square` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `anti` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `neutral` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`articleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_comment
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_comment_data
-- ----------------------------
DROP TABLE IF EXISTS `yzm_comment_data`;
CREATE TABLE `yzm_comment_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `articleid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `userpic` varchar(100) NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评论状态{0:未审核,-1:未通过审核,1:通过审核}',
  `reply` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为回复',
  PRIMARY KEY (`id`),
  KEY `articleid` (`articleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_comment_data
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_favorite
-- ----------------------------
DROP TABLE IF EXISTS `yzm_favorite`;
CREATE TABLE `yzm_favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` char(100) NOT NULL DEFAULT '',
  `url` char(100) NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yzm_member
-- ----------------------------
DROP TABLE IF EXISTS `yzm_member`;
CREATE TABLE `yzm_member` (
  `userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `lastip` char(15) NOT NULL DEFAULT '',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `email` char(32) NOT NULL DEFAULT '',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金钱',
  `point` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核,1正常,2锁定,3拒绝',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `connectid` char(40) NOT NULL DEFAULT '',
  `fromlogin` char(10) NOT NULL DEFAULT '',
  `email_status` tinyint(1) unsigned NOT NULL DEFAULT '0',  
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`(20))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yzm_member_detail
-- ----------------------------
DROP TABLE IF EXISTS `yzm_member_detail`;
CREATE TABLE `yzm_member_detail` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `sex` varchar(6) NOT NULL default '',
  `realname` varchar(30) NOT NULL default '' COMMENT '真实姓名',
  `nickname` char(20) NOT NULL default '',
  `qq` char(11) NOT NULL default '',
  `mobile` char(11) NOT NULL default '',
  `phone` char(10) NOT NULL default '',
  `userpic` varchar(100) NOT NULL default '',
  `birthday` char(10) NOT NULL default '' COMMENT '生日',
  `industry` varchar(60) NOT NULL default '' COMMENT '行业',
  `area` varchar(60) NOT NULL default '',
  `motto` varchar(210) NOT NULL default '' COMMENT '个性签名',
  `introduce` text NOT NULL COMMENT '个人简介',
  `guest` int(10) unsigned NOT NULL DEFAULT '0',
  `problem` varchar(39) NOT NULL default '' COMMENT '安全问题',
  `answer` varchar(30) NOT NULL default '' COMMENT '答案',
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yzm_member_group
-- ----------------------------
DROP TABLE IF EXISTS `yzm_member_group`;
CREATE TABLE `yzm_member_group` (
  `groupid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(21) NOT NULL DEFAULT '',
  `point` smallint(6) unsigned NOT NULL DEFAULT '0',
  `icon` char(30) NOT NULL DEFAULT '' COMMENT '图标',
  `authority` char(12) NOT NULL DEFAULT '' COMMENT '1短消息,2发表评论,3发表文章',
  `description` char(100) NOT NULL DEFAULT '',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统内置',
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


INSERT INTO `yzm_member_group` VALUES ('1', '初来乍到', '0', 'icon1.png', '1', '初来乍到组', '1');
INSERT INTO `yzm_member_group` VALUES ('2', '新手上路', '50', 'icon2.png', '1,2', '新手上路组', '1');
INSERT INTO `yzm_member_group` VALUES ('3', '中级会员', '100', 'icon3.png', '1,2', '中级会员组', '1');
INSERT INTO `yzm_member_group` VALUES ('4', '高级会员', '200', 'icon4.png', '1,2,3', '高级会员组', '1');
INSERT INTO `yzm_member_group` VALUES ('5', '金牌会员', '300', 'icon5.png', '1,2,3', '金牌会员组', '1');


-- ----------------------------
-- Table structure for yzm_member_guest
-- ----------------------------
DROP TABLE IF EXISTS `yzm_member_guest`;
CREATE TABLE `yzm_member_guest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `space_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `guest_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `guest_name` varchar(30) NOT NULL DEFAULT '',
  `guest_pic` varchar(100) NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `space_id` (`space_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_member_guest
-- ----------------------------


-- ----------------------------
-- Table structure for yzm_member_event
-- ----------------------------
DROP TABLE IF EXISTS `yzm_member_event`;
CREATE TABLE `yzm_member_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `userevent` varchar(100) NOT NULL DEFAULT '',
  `articleid` int(10) unsigned NOT NULL DEFAULT '0',
  `eventtype` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为发布文章',
  `eventstatus` smallint(1) unsigned NOT NULL DEFAULT '0',
  `eventtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_member_event
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_member_follow
-- ----------------------------
DROP TABLE IF EXISTS `yzm_member_follow`;
CREATE TABLE `yzm_member_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `followid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '被关注者id',
  `followname` varchar(30) NOT NULL DEFAULT '' COMMENT '被关注者用户名',
  `followpic` varchar(100) NOT NULL DEFAULT '' COMMENT '被关注者头像',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_member_follow
-- ----------------------------


-- ----------------------------
-- Table structure for yzm_message
-- ----------------------------
DROP TABLE IF EXISTS `yzm_message`;
CREATE TABLE `yzm_message` (
  `messageid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_from` varchar(30) NOT NULL DEFAULT '' COMMENT '发件人',
  `send_to` varchar(30) NOT NULL DEFAULT '' COMMENT '收件人',
  `message_time` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` char(80) NOT NULL DEFAULT '' COMMENT '主题',
  `content` text NOT NULL,
  `replyid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复的id',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1正常0隐藏',
  `isread` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否读过',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统信息',
  PRIMARY KEY (`messageid`),
  KEY `replyid` (`replyid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yzm_message_data
-- ----------------------------
DROP TABLE IF EXISTS `yzm_message_data`;
CREATE TABLE `yzm_message_data` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_message_id` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '读过的信息ID',
  PRIMARY KEY (`id`),
  KEY `message` (`userid`,`group_message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yzm_message_group
-- ----------------------------
DROP TABLE IF EXISTS `yzm_message_group`;
CREATE TABLE `yzm_message_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组id',
  `subject` char(80) NOT NULL DEFAULT '',
  `content` text NOT NULL COMMENT '内容',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for yzm_pay
-- ----------------------------
DROP TABLE IF EXISTS `yzm_pay`;
CREATE TABLE `yzm_pay` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `trade_sn` char(18) NOT NULL DEFAULT '' COMMENT '订单号',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `money` char(8) NOT NULL DEFAULT '' COMMENT '金钱或积分的量',
  `creat_time` int(10) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(150) NOT NULL DEFAULT '' COMMENT '备注',
  `payment` varchar(30) NOT NULL DEFAULT '' COMMENT '支付方式',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1积分,2金钱',
  `ip` char(15) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1成功,0失败',
  `adminnote` char(20) NOT NULL DEFAULT '' COMMENT '如是后台操作,管理员姓名',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `trade_sn` (`trade_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_pay
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_payzm_spend
-- ----------------------------
DROP TABLE IF EXISTS `yzm_payzm_spend`;
CREATE TABLE `yzm_payzm_spend` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `trade_sn` char(18) NOT NULL DEFAULT '' COMMENT '订单号',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `money` char(8) NOT NULL DEFAULT '' COMMENT '金钱或积分的量',
  `creat_time` int(10) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(150) NOT NULL DEFAULT '' COMMENT '备注',
  `payment` varchar(30) NOT NULL DEFAULT '' COMMENT '消费方式',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1积分,2金钱',
  `ip` char(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `trade_sn` (`trade_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_payzm_spend
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_collection_content
-- ----------------------------
DROP TABLE IF EXISTS `yzm_collection_content`;
CREATE TABLE `yzm_collection_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nodeid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未采集,1:已采集,2:已导入',
  `url` char(255) NOT NULL DEFAULT '',
  `title` char(100) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nodeid` (`nodeid`),
  KEY `status` (`status`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_collection_content
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_collection_node
-- ----------------------------
DROP TABLE IF EXISTS `yzm_collection_node`;
CREATE TABLE `yzm_collection_node` (
  `nodeid` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '采集节点ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '节点名称',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后采集时间',
  `sourcecharset` varchar(8) NOT NULL DEFAULT '' COMMENT '采集点字符集',
  `sourcetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '网址类型:1序列网址,2单页',
  `urlpage` text NOT NULL COMMENT '采集地址',
  `pagesize_start` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '页码开始',
  `pagesize_end` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '页码结束',
  `par_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '每次增加数',
  `url_contain` char(100) NOT NULL DEFAULT '' COMMENT '网址中必须包含',
  `url_except` char(100) NOT NULL DEFAULT '' COMMENT '网址中不能包含',
  `url_start` char(100) NOT NULL DEFAULT '' COMMENT '网址开始',
  `url_end` char(100) NOT NULL DEFAULT '' COMMENT '网址结束',
  `title_rule` char(100) NOT NULL DEFAULT '' COMMENT '标题采集规则',
  `title_html_rule` text NOT NULL COMMENT '标题过滤规则',
  `time_rule` char(100) NOT NULL DEFAULT '' COMMENT '时间采集规则',
  `time_html_rule` text COMMENT '时间过滤规则',
  `content_rule` char(100) NOT NULL DEFAULT '' COMMENT '内容采集规则',
  `content_html_rule` text NOT NULL COMMENT '内容过滤规则',
  `down_attachment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否下载图片',
  `watermark` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '图片加水印',
  `coll_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '导入顺序',
  PRIMARY KEY (`nodeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_collection_node
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_model
-- ----------------------------
DROP TABLE IF EXISTS `yzm_model`;
CREATE TABLE `yzm_model` (
  `modelid` int(10) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL default '',
  `tablename` char(20) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `setting` text NOT NULL,
  `inputtime` int(10) unsigned NOT NULL default '0',
  `items` smallint(5) unsigned NOT NULL default '0',
  `disabled` tinyint(1) unsigned NOT NULL default '0',
  `sendmail` tinyint(1) unsigned NOT NULL default '0',  
  `allowvisitor` tinyint(1) unsigned NOT NULL default '0',
  `default_style` varchar(30) NOT NULL default '',
  `list_template` varchar(30) NOT NULL default '',
  `show_template` varchar(30) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`modelid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_model
-- ----------------------------

-- ----------------------------
-- Table structure for yzm_model_field
-- ----------------------------
DROP TABLE IF EXISTS `yzm_model_field`;
CREATE TABLE `yzm_model_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `tips` varchar(100) NOT NULL DEFAULT '',
  `css` varchar(30) NOT NULL DEFAULT '',
  `minlength` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(100) NOT NULL DEFAULT '',
  `errortips` varchar(100) NOT NULL DEFAULT '',
  `formtype` varchar(20) NOT NULL DEFAULT '',
  `defaultvalue` varchar(30) NOT NULL DEFAULT '',
  `setting` mediumtext NOT NULL,
  `isrequired` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isunique` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `modelid` (`modelid`,`disabled`),
  KEY `field` (`field`,`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_model_field
-- ----------------------------
INSERT INTO `yzm_model_field` VALUES ('1', '0', 'userid', '用户ID', '', '', '0', '0', '', '', 'hidden', '0', '', '0', '1', '1', '99', '0', '0');
INSERT INTO `yzm_model_field` VALUES ('2', '0', 'username', '用户名', '', '', '2', '30', '', '', 'hidden', '', '', '0', '1', '1', '99', '0', '0');
INSERT INTO `yzm_model_field` VALUES ('3', '0', 'ip', 'ip', '', '', '8', '15', '', '', 'hidden', '', '', '0', '1', '0', '99', '0', '0');
INSERT INTO `yzm_model_field` VALUES ('4', '0', 'inputtime', '提交时间', '', '', '10', '10', '', '', 'hidden', '0', '', '0', '1', '0', '99', '0', '0');

-- ----------------------------
-- Table structure for yzm_article_data
-- ----------------------------
DROP TABLE IF EXISTS `yzm_article_data`;
CREATE TABLE `yzm_article_data` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzm_article_data
-- ----------------------------