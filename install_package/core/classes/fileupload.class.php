<?php
/*
 *	文件名称：文件上传类
 *  用途: 用来上传文件
 *  作者：袁志蒙
 *	编写时间：2014.12.09
 *	最后修改时间：2014.12.09
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class fileupload {
	private $filepath='./uploads/';     //指定上传文件保存的路径
	private $allowtype=array('gif', 'jpg', 'png', 'jpeg');  //充许上传文件的类型
	private $maxsize=2048000;  //允上传文件的最大值 2M
	private $israndname=true;  //是否随机重命名， true随机， false不随机，使用原文件名
	private $originName;   //源文件名称
	private $tmpFileName;   //临时文件名
	private $fileType;  //文件类型
	private $fileSize;  //文件大小
	private $newFileName; //新文件名
	private $errorNum=0;  //错误号
	private $errorMess=""; //用来提供错误报告



	//用于对上传文件初使化
	//1. 指定上传路径， 2，充许的类型， 3，限制大小， 4，是否使用随机文件名称
	//让用户可以不用按位置传参数，后面参数给值不用将前几个参数也提供值
	function __construct($options=array()){
		foreach($options as $key=>$val){
			$key=strtolower($key);
			//查看用户参数中数组的下标是否和成员属性名相同
			if(!in_array($key,get_class_vars(get_class($this)))){
				continue;
			}
			$this->setOption($key, $val);
		}
	}



	private function getError(){
		$str='上传文件<span style="color:red">'.$this->originName.'</span>时出错：';
		switch($this->errorNum){
			case 4: $str .= "没有文件被上传"; break;
			case 3: $str .= "文件只被部分上传"; break;
			case 2: $str .= "上传文件超过了HTML表单中MAX_FILE_SIZE选项指定的值"; break;
			case 1: $str .= "上传文件超过了php.ini 中upload_max_filesize选项的值"; break;
			case -1: $str .= "末充许的类型"; break;
			case -2: $str .= "文件过大，上传文件不能超过{$this->maxsize}个字节"; break;
			case -3: $str .= "上传失败"; break;
			case -4: $str .= "建立存放上传文件目录失败，请重新指定上传目录"; break;
			case -5: $str .= "必须指定上传文件的路径"; break;
			default: $str .=  "末知错误";
		}
		return $str;
	}

	//用来检查文件上传路径
	private function checkFilePath(){
		if(empty($this->filepath)) {
			$this->setOption('errorNum', -5);
			return false;
		}

		if(!file_exists($this->filepath) || !is_writable($this->filepath)){
			if(!@mkdir($this->filepath, 0755,true)){
				$this->setOption('errorNum', -4);
				return false;
			}
		}
		return true;
	}
	
	//用来检查文件上传的大小
	private function checkFileSize() {
		if($this->fileSize > $this->maxsize){
			$this->setOPtion('errorNum', '-2');
			return false;
		}else{
			return true;
		}
	}

	//用于检查文件上传类型
	private function checkFileType() {
		if(in_array(strtolower($this->fileType), $this->allowtype)) {
			return true;
		}else{
			$this->setOption('errorNum', -1);
			return false;
		}
	}
	
	//设置上传后的文件名称
	private function setNewFileName(){
		if($this->israndname){
			$this->setOption('newFileName', $this->proRandName());
		} else {
			$this->setOption('newFileName', $this->originName);
		}
	}

	//设置随机文件名称
	private function proRandName(){
		$fileName=date("YmdHis").rand(100,999);
		return $fileName.'.'.$this->fileType;
	}

	private function setOption($key, $val){
		$this->$key=$val;
	}
	
	//用来上传一个文件
	function uploadFile($fileField){
		$return=true;
		//检查文件上传路径
		if(!$this->checkFilePath()){
			$this->errorMess=$this->getError();
			return false;
		}

	    if(empty($_FILES)){
			$this->errorMess='FILES undefined';
			return false;
		} 
		
		$name=$_FILES[$fileField]['name'];
		$tmp_name=$_FILES[$fileField]['tmp_name'];
		$size=$_FILES[$fileField]['size'];
		$error=$_FILES[$fileField]['error'];

		if($this->setFiles($name, $tmp_name, $size, $error)){
			if($this->checkFileType() && $this->checkFileSize()){
				$this->setNewFileName();
				if($this->copyFile()){
					return true;
				}else{
					$return=false;
				}					
			}else{
				$return=false;
			}	
		}else{
			$return=false;
		}

		if(!$return)
			$this->errorMess=$this->getError();

		return $return;
		
	}

	private function copyFile(){
		if(!$this->errorNum){
			$filepath=rtrim($this->filepath, '/').'/';
			$filepath.=$this->newFileName;

			if(@move_uploaded_file($this->tmpFileName, $filepath))	{
				return true;
			}else{
				$this->setOption('errorNum', -3);
				return false;
			}
				
		}else{
			return false;
		}
	}

	//设置和$_FILES有关的内容
	private function setFiles($name="", $tmp_name='', $size=0, $error=0){	
		$this->setOption('errorNum', $error);			
		if($error){
			return false;
		}
		$this->setOption('originName', $name);
		$this->setOption('tmpFileName', $tmp_name);
		$arrStr=explode('.', $name); 
		$this->setOption('fileType', strtolower($arrStr[count($arrStr)-1]));
		$this->setOption('fileSize', $size);	
		return true;
	}	

	//用于获取上传后文件的大小
	public function getNewFileSize(){
		return $this->fileSize;
	}
	
	//用于获取上传后文件的文件名
	public function getNewFileName(){
		return $this->newFileName;
	}
	
	//上传如果失败，则调用这个方法，就可以查看错误报告
	public function getErrorMsg() {
		return $this->errorMess;
	}
}
