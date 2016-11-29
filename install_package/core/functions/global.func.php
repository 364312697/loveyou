<?php
/**
 *	文件名称：global.func.php
 *  用途: 公共函数库
 *  作者：袁志蒙
 *	编写时间：2013.12.18
 *	最后修改时间：2016-06-01
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/
 
 
/**
 * 字符截取
 * @param $string 要截取的字符串
 * @param $sublen 截取长度
 * @param $dot 截取之后用什么表示
 * @param $start 开始位
 * @param $code 编码格式，支持UTF8/GBK
 */
function cut_str($string, $sublen, $dot = '...', $start = 0, $code = 'UTF-8') { 
	if($code == 'UTF-8'){ 
		$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
		preg_match_all($pa, $string, $t_string);
		if(count($t_string[0]) - $start > $sublen) 
			return join('', array_slice($t_string[0], $start, $sublen)).$dot; 
		return join('', array_slice($t_string[0], $start, $sublen)); 
	}else{ 
		$start = $start*2; 
		$sublen = $sublen*2; 
		$strlen = strlen($string); 
		$tmpstr = ''; 
		for($i=0; $i<$strlen; $i++){ 
			if($i>=$start && $i<($start+$sublen)){ 
				if(ord(substr($string, $i, 1))>129){ 
				    $tmpstr.= substr($string, $i, 2); 
				}else{ 
				    $tmpstr.= substr($string, $i, 1); 
				} 
			} 
			if(ord(substr($string, $i, 1))>129) $i++; 
		} 
		if(strlen($tmpstr)<$strlen ) $tmpstr.= ""; 
		return $tmpstr; 
	} 
}


/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2); 

	for ($i = 0; $i < sizeof($parm); $i++) { 
		$pattern = '/'; 
		for ($j = 0; $j < strlen($parm[$i]); $j++) { 
			if ($j > 0) { 
				$pattern .= '('; 
				$pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
				$pattern .= '|(&#0([9][10][13]);?)?'; 
				$pattern .= ')?'; 
			}
			$pattern .= $parm[$i][$j]; 
		}
		$pattern .= '/i';
		$string = preg_replace($pattern, ' ', $string); 
	}
	return $string;
}	


/**
 * 安全过滤函数
 *
 * $stringing 需要处理的字符串或数组
 * @return mixed
 */
function safe_str($string){
	if(!is_array($string)){
		$string = str_replace('"' , '&quot;' , $string);	
		$string = str_ireplace("and" , "&#97;nd" , $string);
		$string = str_ireplace("execute" , "&#101;xecute" , $string);
		$string = str_ireplace("update" , "&#117;pdate" , $string);
		$string = str_ireplace("count" , "&#99;ount" , $string);
		$string = str_ireplace("chr" , "&#99;hr" , $string);
		$string = str_ireplace("truncate" , "&#116;runcate" , $string);
		$string = str_ireplace("char" , "&#99;har" , $string);
		$string = str_ireplace("union" , "&#117;nion" , $string);
		$string = str_ireplace("declare" , "&#100;eclare" , $string);
		$string = str_ireplace("select" , "&#115;elect" , $string);
		$string = str_ireplace("create" , "&#99;reate" , $string);
		$string = str_ireplace("delete" , "&#100;elete" , $string);
		$string = str_ireplace("insert" , "&#105;nsert" , $string);
		$string = str_ireplace("iframe" , "&#105;frame" , $string);
		$string = str_ireplace("script" , "&#115;cript" , $string);
		$string = str_ireplace("eval" , "&#101;val" , $string);	
		return $string;		
	}
	
	foreach($string as $key => $val) $string[$key] = safe_str($val);
	return $string;	
}	


/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_str($_SERVER['PHP_SELF']) : safe_str($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_str($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_str($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_str($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}


/**
 * 获取请求ip
 * @return ip地址
 */
function getip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '127.0.0.1';
}


/**
 * 获取请求地区
 * @param $ip
 * @return 地区
 */
function get_address($ip){
	 if($ip == '127.0.0.1') return '本地地址';
	 $content = @file_get_contents("http://api.map.baidu.com/location/ip?ak=7IZ6fgGEGohCrRKUE9Rj4TSQ&ip={$ip}&coor=bd09ll");
	 $arr = json_decode($content,true);
	 $address = $arr['content']['address'];
	 if(!$address)  $address = '未知';
	 return $address;
 }

 
/**
 * 对用户的密码进行加密
 * @param $pass 字符串
 * @return string 字符串
 */
function password($pass) {
	return substr(md5($pass), 3, 26);
}



/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}


/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 6) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}


/**
* 创建订单号
*
* @return   string     字符串
*/
function create_tradenum(){
	return date('YmdHis').random(4);
}


/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}


/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}


/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
	if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,'utf-8');
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}

/**
 * 转义 javascript 代码标记
 *
 * @param $str
 * @return mixed
 */
 function trim_script($str) {
	if(is_array($str)){
		foreach ($str as $key => $val){
			$str[$key] = trim_script($val);
		}
 	}else{
 		$str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
		$str = str_replace ( 'javascript:', 'javascript：', $str );
 	}
	return $str;
}


/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
	$data = trim($data);
	if($data == '') return array();
	if(strpos($data, 'array')===0){
		@eval("\$array = $data;");
	}else{
		if(strpos($data, '{\\')===0) $data = stripslashes($data);
		$array=json_decode($data,true);
	}
	return $array;
}


/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '' || empty($data)) return '';
	
	if($isformdata) $data = new_stripslashes($data);
	if (version_compare(PHP_VERSION,'5.3.0','<')){
		return addslashes(json_encode($data));
	}else{
		return addslashes(json_encode($data,JSON_FORCE_OBJECT));
	}
}


/**
 * 判断email格式是否正确
 * @param $email
 */
function is_email($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}


/**
 * 判断手机格式是否正确
 * @param $mobile
 */
function is_mobile($mobile) {
	return preg_match('/1[34578]{1}\d{9}$/',$mobile);
}


/**
 * 检测输入中是否含有错误字符
 *
 * @param char $string 要检查的字符串名称
 * @return TRUE or FALSE
 */
function is_badword($string) {
	$badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
	foreach($badwords as $value){
		if(strpos($string, $value) !== false) {
			return true;
		}
	}
	return false;
}


/**
 * 检查用户名是否符合规定
 *
 * @param STRING $username 要检查的用户名
 * @return 	TRUE or FALSE
 */
function is_username($username) {
	$strlen = strlen($username);
	if(is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
		return false;
	} elseif ( 20 < $strlen || $strlen < 2 ) {
		return false;
	}
	return true;
}



/**
 * 检查密码长度是否符合规定
 *
 * @param STRING $password
 * @return 	TRUE or FALSE
 */
function is_password($password) {
	$strlen = strlen($password);
	if($strlen >= 6 && $strlen <= 20) return true;
	return false;
}


/**
 * 取得当前文件名
 *
 * @param $suffix 1为带后缀的文件名，0不带后缀
 * @return string
 */
function get_file_name($suffix = 0){
	$arr = explode('/',$_SERVER['PHP_SELF']);
	if($suffix == 0){
        $selfname = end($arr);		
        return substr($selfname,0,strrpos($selfname,'.'));
	}else{
		return end($arr);
	} 
}


/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}


/**
 * IE浏览器判断
 */

function is_ie() {
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)) return false;
	if(strpos($useragent, 'msie ') !== false) return true;
	return false;
}


/**
 * 判断字符串是否为utf8编码，英文和半角字符返回ture
 * @param $string
 * @return bool
 */
function is_utf8($string) {
	return preg_match('%^(?:
					[\x09\x0A\x0D\x20-\x7E] # ASCII
					| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
					| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
					| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
					| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
					)*$%xs', $string);
}



/**
 * 文件下载
 * @param $filepath 文件路径
 * @param $filename 文件名称
 */

function file_down($filepath, $filename = '') {
	if(!$filename) $filename = basename($filepath);
	if(is_ie()) $filename = rawurlencode($filename);
	$filetype = fileext($filename);
	$filesize = sprintf("%u", filesize($filepath));
	if(ob_get_length() !== false) @ob_end_clean();
	header('Pragma: public');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	header('Content-Transfer-Encoding: binary');
	header('Content-Encoding: none');
	header('Content-type: '.$filetype);
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Content-length: '.$filesize);
	readfile($filepath);
	exit;
}



/**
 * 输出自定义错误
 *
 * @param $errno 错误号
 * @param $errstr 错误描述
 * @param $errfile 报错文件地址
 * @param $errline 错误行号
 * @return string 错误提示
 */
function my_error_handler($errno, $errstr, $errfile, $errline) {
	if($errno==8) return '';
	if(DB_DEBUG == 1){
		$errfile = str_replace(YZMCMS_PATH,'',$errfile);
		echo '<div style="font-size:14px;text-align:left; border:1px solid #9cc9e0;line-height:25px; padding:5px 10px;color:#000;font-family:Arial, Helvetica,sans-serif;">
		<b> Error : </b> [ '. $errno.' ] '.$errstr .' <br /><b>Errno : </b>'. $errno .' <br /> <b>ErrorFile : </b> <span>'. $errfile.' on line:'.$errline.'</span>
		</div>';
	}else{
	    error_log('YzmCMS Error : '.date('m-d H:i:s').' | '.$errno.' | '.str_pad($errstr,30).' | '.$errfile.' | '.$errline."\r\n", 3, YZMCMS_PATH.'/error_log.php');		
	}
}


/**
* 传入日期格式或时间戳格式时间，返回与当前时间的差距，如1分钟前，2小时前，5月前，3年前等
* @param string or int $date 分两种日期格式"2015-09-12 14:16:12"或时间戳格式"1386743303"
* @param int $type
* @return string
*/
function format_time($date = 0, $type = 1) { //$type = 1为时间戳格式，$type = 2为date时间格式
    switch ($type) {
        case 1:
            //$data时间戳格式
            $second = time() - $date;
            $minute = floor($second / 60) ? floor($second / 60) : 1; 
            if ($minute >= 60 && $minute < (60 * 24)) { 
                $hour = floor($minute / 60); 
            } elseif ($minute >= (60 * 24) && $minute < (60 * 24 * 30)) { 
                $day = floor($minute / ( 60 * 24)); 
            } elseif ($minute >= (60 * 24 * 30) && $minute < (60 * 24 * 365)) { 
                $month = floor($minute / (60 * 24 * 30));
            } elseif ($minute >= (60 * 24 * 365)) { 
                $year = floor($minute / (60 * 24 * 365)); 
            }
            break;
            case 2:
            //$date为字符串格式 2013-06-06 19:16:12
            $date = strtotime($date);
            $second = time() - $date;
            $minute = floor($second / 60) ? floor($second / 60) : 1; 
            if ($minute >= 60 && $minute < (60 * 24)) { 
                $hour = floor($minute / 60); 
            } elseif ($minute >= (60 * 24) && $minute < (60 * 24 * 30)) { 
                $day = floor($minute / ( 60 * 24)); 
            } elseif ($minute >= (60 * 24 * 30) && $minute < (60 * 24 * 365)) { 
               $mont = floor($minute / (60 * 24 * 30)); 
            } elseif ($minute >= (60 * 24 * 365)) { 
               $year = floor($minute / (60 * 24 * 365)); 
            }
            break;
            default:
            break;
    }
    if (isset($year)) {
        return $year . '年前';
    } elseif (isset($month)) {
        return $month . '月前';
    } elseif (isset($day)) {
        return $day . '天前';
    } elseif (isset($hour)) {
        return $hour . '小时前';
    } elseif (isset($minute)) {
        return $minute . '分钟前';
    }
}	


/**
* 转换字节数为其他单位
* @param	string	$filesize	字节大小
* @return	string	返回大小
*/
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}


/**
 * 对数据进行编码转换
 * @param array/string $data       数组
 * @param string $input     需要转换的编码
 * @param string $output    转换后的编码
 */
function array_iconv($data, $input = 'gbk', $output = 'utf-8') {
	if (!is_array($data)) {
		return iconv($input, $output, $data);
	} else {
		foreach ($data as $key=>$val) {
			if(is_array($val)) {
				$data[$key] = array_iconv($val, $input, $output);
			} else {
				$data[$key] = iconv($input, $output, $val);
			}
		}
		return $data;
	}
}


/**
 * 生成sql语句，如果传入$in_cloumn 生成格式为 IN('a', 'b', 'c')
 * @param $data 条件数组或者字符串
 * @param $front 连接符
 * @param $in_column 字段名称
 * @return string
 */
function to_sqls($data, $front = ' AND ', $in_column = false) {
	if($in_column && is_array($data)) {
		$ids = '\''.implode('\',\'', $data).'\'';
		$sql = "$in_column IN ($ids)";
		return $sql;
	} else {
		if ($front == '') {
			$front = ' AND ';
		}
		if(is_array($data) && count($data) > 0) {
			$sql = '';
			foreach ($data as $key => $val) {
				$sql .= $sql ? " $front `$key` = '$val' " : " `$key` = '$val' ";
			}
			return $sql;
		} else {
			return $data;
		}
	}
}


/**
 * 获取父类下的所有子类
 * @param intval $catid 父栏目ID
 * @return string 
 */
function get_child($catid) {
	global $column; 
	$childstr = '';		
	if(!isset($column)) $column = M('column');		
	$res =$column->field('id')->where("FIND_IN_SET('$catid',path)")->select();
	foreach($res as $val){
		$childstr .= $val['id'].',';
	}
	return rtrim($childstr, ',');
}



/**
 * 根据catid获取子栏目数据的sql语句
 * @param intval $catid 父栏目ID
 * @return string sql语句
 */
function get_sql_catid($catid) {	
	if(!$sql_where = getcache($catid.'_where', 2, 'column')){
		$catid = intval($catid);
		if(!$catid) return '1=2';
		
		$str = get_child($catid);
		if($str){
			$sql_where = '`display`=1 AND `catid` IN('. $str .')';
		}else{
			$sql_where = '`display`=1 AND `catid` = '.$catid;
		}
		
		setcache($catid.'_where', $sql_where, 2, 'column');
	}	
	return $sql_where;
}


/**
 * 根据catid获取子栏目数据并带缩略图的sql语句
 * @param intval $catid 父栏目ID
 * @return string sql语句
 */
function get_sql_catid_thumb($catid) {	
	$sql_where = get_sql_catid($catid);	
	$sql_where .= " AND`thumbnail` != ''";
	return $sql_where;
}


/**
 * 用于创建对象  如：M('table_name');
 * @param $tabname	 表名
 * @return object
 */	
function M($tabname){
	return new db_mysqli(strtolower($tabname));	 
}


/**
 * 打印各种类型的数据，调试程序时使用。
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @return void|string
 */
function P($var, $echo=true){
	ob_start();
    var_dump($var);
    $output = ob_get_clean();
	if(!extension_loaded('xdebug')){
		$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
		$output = '<pre>' .  htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    }
	if($echo){
        echo $output;
        return null;
    }else
        return $output;
}


/**
 * 根据栏目ID获取栏目名称
 *
 * @param  int $catid
 * @return string
 */
function get_catname($catid){
	$catid = intval($catid);
    global $column; 		
	if(!isset($column)) $column = M('column');
	$data = $column->field('title')->where(array('id' => $catid))->find();
	if(!$data) return '';
    return $data['title']; 	
}



/**
 * 获取系统配置信息
 *
 * @param 
 * @return array
 */
function get_sysinfo(){
	if(!$sysinfo = getcache('sysinfo', 2)){
		$data = $data2 = array();
		$data = M('webinfo')->where(array('id' => 1))->find();
		$config = M('otherconfig')->select();
		foreach($config as $val){
			$data2[$val['varname']] = $val['value'];
		}
        $sysinfo = array_merge($data, $data2);
		setcache('sysinfo', $sysinfo, 2);
	}
    return $sysinfo; 	
}



/**
 * 获取内容中的图片
 * @param string $content 内容
 * @return string or false
 */
function match_img($content){
    preg_match('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/', $content, $match);
    return !empty($match) ? $match[1] : false; 
}



/**
 * 获取编辑器中的图片并制作成缩略图
 * @param string $content 文章内容
 * @param string $wroot 网站的网址
 * @param int $pic_wid 可选参数，图片缩略图的宽度
 * @param int $pic_hei 可选参数，图片缩略图的高度
 * @param string $prefix 可选参数，图片缩略图的前缀
 * @return string or false
 */
function get_thumbnail($content, $wroot, $pic_wid = 180, $pic_hei = 120, $prefix = 'th_'){
	$content = str_replace('\\','',$content);	
	$upfile = match_img($content);	
	if($upfile && strpos($upfile, $wroot)!==false){
		$arr = explode('/',$upfile);
		$total = count($arr);  
		$date = $arr[$total-2];					
		$filename = $arr[$total-1];					
		$root = rtrim($upfile,'/'.$arr[$total-2].'/'.$arr[$total-1]);
		$image = new image(YZMCMS_PATH."/uploads/".$date);    
		return $root.'/'.$date.'/'.$image->thumb($filename, $pic_wid, $pic_hei, $prefix);									
	}
    return false;	
}


/**
 * 获取远程图片并把它保存到本地, 确定您有把文件写入本地服务器的权限
 * @param string $content 文章内容
 * @param string $wroot 可选参数，本网站的网址
 * @param string $targeturl 可选参数，对方网站的网址，防止对方网站的图片使用"/upload/1.jpg"这样的情况
 * @return string $content 处理后的内容
 */
function grab_image($content, $wroot = '', $targeturl = ''){
	$content = str_replace('\\','',$content);
	preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/', $content, $img_array); 
    $img_array = isset($img_array[1]) ? array_unique($img_array[1]) : false;
	
	if($img_array) {
		$path = 'uploads/'.date( "Ymd" );
		$urlpath = $wroot.$path;
		$imgpath =  YZMCMS_PATH.'/'.$path;
		if(!is_dir($imgpath)) @mkdir($imgpath, 0777, true);
	}
	
	foreach($img_array as $key=>$value){
		$val = $value;		
		if(strpos($value, 'http') === false){
			if(!$targeturl) return false;
			$value = $targeturl.$value;
		}		
		$ext = strrchr($value, '.');
		if($ext!='.png' && $ext!='.jpg' && $ext!='.gif' && $ext!='.jpeg') return false;
		$imgname = date("YmdHis").rand(1,9999).$ext;
		$filename = $imgpath.'/'.$imgname;
		$urlname = $urlpath.'/'.$imgname;
		
		ob_start();
		readfile($value);
		$data = ob_get_contents();
		ob_end_clean();
		file_put_contents($filename, $data);
	 
		if(file_exists($filename)){                         
			$content = str_replace($val, $urlname, $content);
		}else{
			return false;
		}
	}
	return $content;        
}


/**
 * 写入缓存
 * @param $name 缓存名称
 * @param $data 缓存数据
 * @param $type 缓存类型[1为serialize序列化, 2为保存为可执行文件array]
 * @param $filepath 缓存目录
 * @param $timeout 过期时间
 */
function setcache($name, $data, $type = 1, $filepath = '', $timeout=0) {
    if($type != 1) cache_file::setCacheMode($type);
    if($filepath != '') 
		cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/'.$filepath);
	else
		cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/');
	return cache_file::set($name, $data, $timeout);
}


/**
 * 读取缓存
 * @param string $name 缓存名称
 * @param $type 缓存类型[1为serialize序列化, 2为保存为可执行文件array]
 * @param $filepath 缓存目录
 */
function getcache($name, $type = 1, $filepath = '') {
	if($type != 1) cache_file::setCacheMode($type);
    if($filepath != '') 
		cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/'.$filepath);
	else
		cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/');
	return cache_file::get($name);
}


/**
 * 删除缓存
 * @param string $name 缓存名称
 * @param $filepath 缓存目录
 * @param $flush 是否清空所有缓存
 */
function delcache($name, $filepath = '', $flush=false) {
    if($filepath != '') 
		cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/'.$filepath);
	else
		cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/');
	return !$flush ? cache_file::delete($name) : cache_file::flush();
}


/**
 * 获取用户所有信息
 * @param $userid 
 * @param $type 1为userid,其他为ussername
 * @return array or false
 */
function get_memberinfo($param, $type=1){
	if($type == 1){
		$userid = intval($param);
		if(!$userid) return false;
        $where = "b.userid=$userid";		
	}else{
		if(!$param) return false;
		$where = "yzmcms_member.username='$param'";
	}

	global $member;
	$member = isset($member) ? $member : M("member");
	$sysinfo = get_sysinfo();
	$memberinfo = $member->field('yzmcms_member.username,yzmcms_member.lastdate,yzmcms_member.loginnum,yzmcms_member.email,yzmcms_member.groupid,yzmcms_member.amount,yzmcms_member.point,yzmcms_member.status,yzmcms_member.vip,b.*')->join('yzmcms_member_detail b ON yzmcms_member.userid=b.userid')->where($where)->find();	
	$memberinfo['userpic'] ? $memberinfo['userpic'] : $sysinfo['wroot'].'member/templets/images/default.gif';
	unset($memberinfo['problem'], $memberinfo['answer']);	
	return $memberinfo;
}


/**
 * 获取用户头像
 * @param $user userid或者username
 * @param $type 1为根据userid查询，其他为根据username查询, 建议根据userid查询
 * @param default 如果用户头像为空，是否显示默认头像
 * @return string
 */
function get_memberavatar($user, $type=1, $default=true) {
	global $member_detail;
	$member_detail = isset($member_detail) ? $member_detail : M("member_detail");
	$sysinfo = get_sysinfo();
	if($type == 1){
		$res = $member_detail->field('userpic')->where(array('userid' => $user))->find();
	}else{
		$res = $member_detail->field('userpic')->join('yzmcms_member b ON yzmcms_member_detail.userid=b.userid')->where(array('username' => $user))->find();
	}	
	return $res['userpic'] ? $res['userpic'] : ($default ? $sysinfo['wroot'].'member/templets/images/default.gif' : '');
}


/**
 * 根据用户名获取用户ID
 * @param $username 用户名username
 * @return int
 */
function get_userid($username) {
	global $member;
	$member = isset($member) ? $member : M("member");
	$sysinfo = get_sysinfo();
	$res = $member->field('userid')->where(array('username' => $username))->find();	
	return $res ? $res['userid'] : 0;
}


/**
 * 根据用户ID获取粉丝数量
 * @param $userid
 * @return int
 */
function get_fans($userid) {
	$userid = intval($userid);	
	return M('member_follow')->where(array('followid' => $userid))->total();
}


/**
 * 检查并根据结果更新会员组
 *
 * @param array 会员的基本信息，必须包含[userid,point,groupid]
 */
function update_group($array){
	 if(!is_array($array)) return false;
	 $data = M('member_group')->field('groupid')->where(array('point>=' => $array['point']))->find();
	 if($data['groupid'] && $array['groupid'] != $data['groupid']){
		 return M('member')->update(array('groupid' => $data['groupid']), array('userid'=>$array['userid']));  	
	 } 		 
}


/**
 *  提示信息页面跳转
 *
 * @param     string  $msg      消息提示信息
 * @param     int     $limittime  限制时间
 * @param     string  $gourl    跳转地址
 * @return    void
 */
function showmsg($msg, $limittime=3, $gourl=''){
	
	$gourl = $gourl!='' ? $gourl : $_SERVER['HTTP_REFERER'];
    
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>	
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="'.$limittime.';URL='.$gourl.'" />
    <title>YzmCMS提示信息</title>
    <style>
	  *{padding:0;margin:0;}
	  body{background:#f8f8f8;font-family:"宋体"}
	  #msg{border:1px solid #1c77ac;width:500px;margin:150px auto 0px;padding:1px;line-height:30px;text-align:center;font-size:16px;background:#fff;}
	  #msgtit{height:30px;line-height:30px;color:#fff;background:#1c77ac;font-weight:bold;}
	  #msgbody{margin:20px 0;}
	  #info{font-weight:bold;margin-bottom:10px;}
	  #msgbody p{font-size:14px;color:#333;}
	</style>
</head>
<body>
    <div id="msg">        	
     <div id="msgtit">提示信息</div>
	 <div id="msgbody">
     <div id="info">'.$msg.'</div>
        <p>本页面将在<span style="color:red; font-weight:bold;margin:0 5px;">'.$limittime.'</span>秒后跳转...</p>
     </div>
    </div>
</body>
</html>';

    echo $html;
	exit;
}	


/**
 * 获取分类的select
 * @param $name     select的名称
 * @param $value    选中的id，用于修改
 * @param $root     顶级分类名称
 * @param $disabled 是否禁单页和外部链接
 * @return string
 */
function formselect($name="pid", $value="0", $root="≡ 一级栏目 ≡", $disabled=true){
	if($root == '') $root="≡ 一级栏目 ≡";
	$data = M('column')->field('id,pid,title,type,concat(path,",",id) as abspath')->order('abspath,id ASC')->select();
	$html='<select id="sel" name="'.$name.'">';
	$html.='<option value="0">'.$root.'</option>';
	foreach($data as $val){
			
		$str = $value != $val['id'] ? '' : ' selected="selected" ';
		if($disabled) $str .= $val['type'] == 0 ? '' : ' disabled="disabled" ';
		$html.='<option '.$str.'value="'.$val['id'].'">';

		$num=count(explode(",", $val["abspath"]))-2;
		$space=str_repeat("|&nbsp;&nbsp;&nbsp;&nbsp;",$num);	
		$name=$val["title"];
		$html.=$space."|-&nbsp;".$name;
		$html.='</option>';	
	}
	$html.='</select>';

	return $html;
}


/**
 * 模板调用
 *
 * @param $file_path
 * @return unknown_type
 */
function template($file){
	global $tem_style; 
	if(!isset($tem_style)){
		$sysinfo = get_sysinfo();
		$tem_style = $sysinfo['tem_style'];
	}
	$file_path = YZMCMS_PATH.'/templets/'.$tem_style.'/'.$file;
	if(!file_exists($file_path)) {
		echo '<p>YzmCMS ERROR：<span style="color:red;margin:0 5px">'.$file_path.'</span>模板不存在！<p>';
		exit;
	}
	return $file_path;
}


/**
 * 发送邮件    必须做好配置邮箱
 * @param $email    收件人邮箱
 * @param $title    邮件标题
 * @param $content     邮件内容
 * @param $mailtype    邮件内容类型
 * @return TRUE or FALSE
 */
function sendmail($email, $title = '', $content = '', $mailtype = 'HTML'){
	$sysinfo = get_sysinfo();
	if(empty($sysinfo['mail_pass']) || !is_email($email)) return false;
	$smtp = new smtp($sysinfo['mail_server'],$sysinfo['mail_port'],$sysinfo['mail_auth'],$sysinfo['mail_user'],$sysinfo['mail_pass']);
	$state = $smtp->sendmail($email, $sysinfo['mail_from'], $title.' - '.$sysinfo['wname'], $content, $mailtype);
	if($state == '') return false;
	return true;
}


/**
 * 用来生成html静态文档
 * @param $id
 * @return int
 */	
function make_html($id){
	$_GET['id'] = $id;
	require(YZMCMS_PATH.'/common/frontend.inc.php');
	ob_start();
	include(YZMCMS_PATH.'/article.php');
	$data = ob_get_contents();				
	ob_clean();									
	$mkdir_path = YZMCMS_PATH.'/'.$columnpath;
	if(!is_dir($mkdir_path)) mkdir($mkdir_path, 0755, true);
	return file_put_contents($mkdir_path.'/'.$id.'.html', $data);
}


/**
 * 自定义函数接口
 * 自动全局加载用户自定义的函数
 */
if(file_exists('extention.func.php')){
    require('extention.func.php');
}