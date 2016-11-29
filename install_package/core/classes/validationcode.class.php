<?php
/*
 *	文件名称：验证码类
 *  用途: 用来生成验证码
 *  作者：袁志蒙
 *	编写时间：2015.06.17
 *	最后修改时间：2015.06.18
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class ValidationCode {
	private $width;
	private $height;
	private $CheckCode;
  
	function __construct($width=100, $height=28){
	   $this->width=$width;
	   $this->height=$height;
	}

	//显示图像
	function showImage() {
		$im = imagecreate($this->width, $this->height);

		//imagecolorallocate($im, 14, 114, 180); // background color
		$red = imagecolorallocate($im, 255, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);

		$num1 = rand(1, 20);
		$num2 = rand(1, 20);

		
		$this->CheckCode = $num1 + $num2;

		$gray = imagecolorallocate($im, 118, 151, 199);
		$black = imagecolorallocate($im, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));

		//画背景
		imagefilledrectangle($im, 0, 0, 100,  $this->height, $black);
		//在画布上随机生成大量点，起干扰作用;
		for ($i = 0; $i < 80; $i++) {
			imagesetpixel($im, rand(0, $this->width), rand(0, $this->height), $gray);
		}

		imagestring($im, 5, 5, 5, $num1, $red);
		imagestring($im, 5, 30, 6, "+", $red);
		imagestring($im, 5, 45, 4, $num2, $red);
		imagestring($im, 5, 70, 5, "=", $red);
		imagestring($im, 5, 80, 4, "?", $white);

		header("Content-type: image/png");
		imagepng($im);
		imagedestroy($im);
	} 
	
	//获取输出的结果
	function getCheckCode(){
	   return $this->CheckCode;
	}	
}
?>