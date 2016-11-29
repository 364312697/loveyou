<?php
/*
 *	文件名称：form类
 *  用途: 用于用户自定义表单，显示form表单的元素
 *  作者：袁志蒙
 *	编写时间：2016.05.31
 *	最后修改时间：2016.06.08
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class form {


	/**
	 * input 
	 * @param $str 属性  如：name="myform"
	 * @param $value 默认值 如：YzmCMS
	 * @param $type  类型,默认text 如：password
	 * @param $required  是否为必填项 默认false
	 * @param $id 如：input
	 * @param $width  宽度 如：100
	 */
	public static function input($str = '', $value = '', $type = 'text', $required=false, $id='', $width = 0) {
		$string = '<input class="input_text" ';
		if($width) $string .= ' style="width:'.$width.'px" ';
		if($required) $string .= ' required="required" ';
		if($id) $string .= ' id="'.$id.'" ';
		$string .= $str.' type="'.$type.'" value="'.$value.'">';
		return $string;
	}
	
	

	/**
	 * textarea
	 * @param $str 属性  如：name="myform"
	 * @param $value 默认值 如：YzmCMS
	 * @param $required  是否为必填项 默认false
	 * @param $id 如：mytextarea
	 * @param $style 样式 如：height:50px;width:100px
	 */
	public static function textarea($str = '', $value = '', $required=false, $id='', $style = '') {
		$string = '<textarea '.$str;
		if($style) $string .= ' style="'.$style.'" ';
		if($required) $string .= ' required="required" ';
		if($id) $string .= ' id="'.$id.'"';
		$string .= '>'.$value.'</textarea>';
		return $string;
	}


	
	/**
	 * 下拉选择框
	 * @param $array 一维数组 如：array('1'=>'交易成功', '2'=>'交易失败', '3'=>'交易结果未知');
	 * @param $id 默认选中值 如：1
	 * @param $str 属性  如：name="myform"
	 * @param $defaultvalue 是否增加默认值 如：请选择交易 
	 */
	public static function select($array = array(), $id = 0, $str = '', $default_option = '') {
		$string = '<select '.$str.'>';
		$default_selected = (empty($id) && $default_option) ? 'selected' : '';
		if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
		if(!is_array($array) || count($array)== 0) return false;
		$ids = array();
		if(isset($id)) $ids = explode(',', $id);
		foreach($array as $key=>$value) {
			$selected = in_array($key, $ids) ? 'selected' : '';
			$string .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		$string .= '</select>';
		return $string;
	}
	
	/**
	 * 复选框
	 * 
	 * @param $array 一维数组 如：array('1'=>'交易成功', '2'=>'交易失败', '3'=>'交易结果未知');
	 * @param $id 默认选中值，多个用 '逗号'分割 如：'1,2'
	 * @param $str 属性 如：name="myform"
	 * @param $defaultvalue 是否增加默认值 默认值为 -99
	 * @param $width 宽度 如：100
	 */
	public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '') {
		$string = '';
		$id = trim($id);
		if($id != '') $id = strpos($id, ',') ? explode(',', $id) : array($id);
		if($defaultvalue) $string .= '<input type="hidden" '.$str.' value="-99">';
		$i = 1;
		foreach($array as $key=>$value) {
			$key = trim($key);
			$checked = ($id && in_array($key, $id)) ? 'checked' : '';
			$string .= '<label class="option_label"';
			if($width) $string .= ' style="width:'.$width.'px" ';
			$string .= '><input type="checkbox" '.$str.' id="'.$field.'_'.$i.'" '.$checked.' value="'.new_html_special_chars($key).'"> '.new_html_special_chars($value);
			$string .= '</label>';
			$i++;
		}
		return $string;
	}

	/**
	 * 单选框
	 * 
	 * @param $array 一维数组 如：array('1'=>'交易成功', '2'=>'交易失败', '3'=>'交易结果未知');
	 * @param $id 默认选中值 如：1
	 * @param $str 属性 如：name="myform"
	 * @param $width 宽度 如：100
	 */
	public static function radio($array = array(), $id = 0, $str = '', $width = 0, $field = '') {
		$string = '';
		foreach($array as $key=>$value) {
			$checked = trim($id)==trim($key) ? 'checked' : '';
			$string .= '<label class="option_label"';
			if($width) $string .= ' style="width:'.$width.'px" ';
			$string .= '><input type="radio" '.$str.' id="'.$field.'_'.new_html_special_chars($key).'" '.$checked.' value="'.$key.'"> '.$value;
			$string .= '</label>';
		}
		return $string;
	}

	
	/**
	 * 验证码
	 * @param string $id   验证码ID
	 */
	public static function code($id = 'code') {
		$sysinfo = get_sysinfo();
		return '<img src="'.$sysinfo['wroot'].'plus/code.php" id="'.$id.'" onclick="this.src=\''.$sysinfo['wroot'].'plus/code.php?\'+Math.random()" style="cursor:pointer;" title="看不清？点击更换">';
	}
	
	
	/**
	 * 日期时间控件
	 * 
	 * @param $str 属性  如：name="myform"
	 * @param $value 默认值
	 * @param $isdatetime 是否显示时分秒
	 * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
	 */
	public static function date($str, $value = '', $isdatetime = 0, $loadjs = 0) {		
		$string = '';
		if($loadjs || !defined('CALENDAR_INIT')) {
			define('CALENDAR_INIT', 1);
			$sysinfo = get_sysinfo();
			$string .= '<script type="text/javascript" src="'.$sysinfo['wroot'].'common/js/laydate.js"></script>';
		}
		
		$string .= '<input class="laydate-icon"  value="'.$value.'" '.$str.' onClick="laydate(';
	    if($isdatetime) $string .= '{istime: true, format: \'YYYY-MM-DD hh:mm:ss\'}';
	    $string .= ')">';
		
		return $string;
	}
}

?>