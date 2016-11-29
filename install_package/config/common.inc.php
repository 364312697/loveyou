<?php
/*
+-----------------------------------------------------------------------
|	文件概要：全局公共文件
|	文件名称：common.inc.php
+-----------------------------------------------------------------------
*/

//输出页面字符集
header('content-type:text/html;charset=utf-8');

//设置为中国标准时间
date_default_timezone_set('PRC');             

define('CONFIG_PATH', str_replace("\\", '/', dirname(__FILE__)));

define('YZMCMS_PATH', dirname(CONFIG_PATH));        

require(CONFIG_PATH.'/version.php');
	
require(CONFIG_PATH.'/database.php');	

require(YZMCMS_PATH.'/core/functions/global.func.php');

if(version_compare(PHP_VERSION,'5.4.0','<')) {
    define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()? true : false);
}else{
    define('MAGIC_QUOTES_GPC',false);
}

//根据调试模式 输出错误报告
DB_DEBUG ? set_error_handler("my_error_handler") : error_reporting(E_ERROR | E_WARNING | E_PARSE);
//DB_DEBUG ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(E_ERROR | E_WARNING | E_PARSE);

//自动加载类库处理
function __autoload($classname){	
    $classname = preg_replace("/[^0-9a-z_]/i", '', $classname);
    if(class_exists($classname)){
        return true;
    }
    $classfile = $classname.'.class.php';
	if(is_file(YZMCMS_PATH.'/core/classes/'.$classfile)){
		require YZMCMS_PATH.'/core/classes/'.$classfile;
	}else{ 
		if(DB_DEBUG == 1){
			echo $classname.'类找不到';
			exit();
		}else{
			header ( "location:/404.html");
			exit();
		} 
	}
}

define('YZMCMS_SOFTNAME',base64_decode('WXptQ01T5YaF5a65566h55CG57O757uf'));