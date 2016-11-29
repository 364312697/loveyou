<?php
/**
 * @author         袁志蒙
 * @package        YzmCMS.Install
 * @copyright      Copyright (c) 2013 - 2016
 * @link           http://www.yzmcms.com
 * @notice		   本文件仅支持YzmCMS v2.1及以上版本，MYSQLI扩展安装
 * @lastmodify	   2016-02-23 
 */
 
function RunMagicQuotes(&$str){
	if(!get_magic_quotes_gpc()) {
		if( is_array($str) )
			foreach($str as $key => $val) $str[$key] = RunMagicQuotes($val);
		else
			$str = addslashes($str);
	}
	return $str;
}

function gdversion(){
	//没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
	if(!function_exists('phpinfo')){
		if(function_exists('imagecreate')) 
			return '2.0';
		else
			return 0;
	}else{
		ob_start();
		phpinfo(8);
		$module_info = ob_get_contents();
		ob_end_clean();
		if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches)) {  
		    $gdversion_h = $matches[1];  
		}else {  
		    $gdversion_h = 0; 
		}
		return $gdversion_h;
	}
}

function TestWrite($d){
	$tfile = '_yzm.txt';
	$d = preg_replace("#\/$#", '', $d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp){
		return false;
	}else{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs) 
			return true;
		else 
			return false;
	}
}

?>