<?php 
/**
 *	文件名称：cache_file.class.php
 *  用途: 缓存文件类,对数据库的数据进行文件缓存
 *  作者：袁志蒙
 *	编写时间：2016.04.05
 *	最后修改时间：2016.04.06
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class cache_file{ 
  
    private static $_instance = null;
    protected $_options = array();
     
	 
    /**
     * 构造函数
     */
    protected function __construct(){
		//缓存默认配置
        $this->_options = array(
			'cache_dir'   => YZMCMS_PATH.'/common/cache/',    //缓存文件目录
			'suffix'      => '.cache.php',  //缓存文件后缀
			'mode'        => '1',           //缓存格式：mode 1 为serialize序列化, mode 2 为保存为可执行文件array
		);
    }
	
	 
    /**
     * 得到本类实例
     * 
     * @return Ambiguous
     */
    public static function getInstance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    } 
 
 
    /**
     * 得到缓存信息
     * 
     * @param string $id
     * @return boolean|array
     */
    public static function get($id){
        $instance = self::getInstance();
         
        //缓存文件不存在
        if(!$instance->has($id)){
            return false;
        }
         
        $file = $instance->_file($id);
         
        $data = $instance->_fileGetContents($file);
         
        if($data['expire'] == 0 || time() < $data['expire']){
            return $data['contents'];
        }
        return false;
    }
     
    /**
     * 设置一个缓存
     * 
     * @param string $id   缓存id
     * @param array  $data 缓存内容
     * @param int    $cacheLife 缓存生命 默认为0无限生命
     */
    public static function set($id, $data, $cacheLife = 0){
        $instance = self::getInstance();
         
        $time = time();
        $cache  = array();
        $cache['contents'] = $data;
        $cache['expire']   = $cacheLife === 0 ? 0 : $time + $cacheLife;
        $cache['mtime']    = $time;
        
		if(!is_dir($instance->_options['cache_dir'])) {
			mkdir($instance->_options['cache_dir'], 0777, true);
	    }
		
        $file = $instance->_file($id);
         
        return $instance->_filePutContents($file, $cache);
    }
     
    /**
     * 清除一条缓存
     * 
     * @param string cache id    
     * @return void
     */  
    public static function delete($id){
        $instance = self::getInstance();
         
        if(!$instance->has($id)){
            return false;
        }
        $file = $instance->_file($id);
        //删除该缓存
        return unlink($file);
    }
     
    /**
     * 判断缓存是否存在
     * 
     * @param string $id cache_id
     * @return boolean true 缓存存在 false 缓存不存在
     */
    public static function has($id){
        $instance = self::getInstance();
        $file     = $instance->_file($id);
         
        if(!file_exists($file)){
            return false;
        }
        return true;
    }
     
    /**
     * 通过缓存id得到缓存信息路径
     * @param string $id
     * @return string 缓存文件路径
     */
    protected function _file($id){
        $instance  = self::getInstance();
        $fileNmae  = $instance->_idToFileName($id);
        return $instance->_options['cache_dir'] . $fileNmae;
    }   
     
    /**
     * 通过id得到缓存信息存储文件名
     * 
     * @param  $id
     * @return string 缓存文件名
     */
    protected function _idToFileName($id){
        $instance  = self::getInstance();
        return $id . $instance->_options['suffix'];
    }
     
  
    /**
     * 通过filename得到缓存id
     * 
     * @param  $id
     * @return string 缓存id
     */
    protected function _fileNameToId($fileName){
        $instance  = self::getInstance();
        return str_replace($instance->_options['suffix'], '', $fileName);
    }

	
    /**
     * 把数据写入文件
     * 
     * @param string $file 文件名称
     * @param array  $contents 数据内容
     * @return int | false 
     */
    protected function _filePutContents($file, $contents){
        if($this->_options['mode'] == 1){
            $contents = serialize($contents);
        }else{
            $contents = "<?php\nreturn ".var_export($contents, true).";\n?>";
        }
		
		$FileSize = file_put_contents($file, $contents, LOCK_EX);
        return $FileSize ? $FileSize : false;
    }
     
    /**
     * 从文件得到数据
     * 
     * @param  sring $file
     * @return boolean|array
     */
    protected function _fileGetContents($file){
        if(!file_exists($file)){
            return false;
        }
         
        if($this->_options['mode'] == 1){
            return unserialize(file_get_contents($file));
        }else{
            return @require $file;
        }
    }
     
     
    /**
     * 设置缓存路径
     * 
     * @param string $path
     * @return self
     */
    public static function setCacheDir($path){
        $instance  = self::getInstance();
		
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
		
        if (!is_writable($path)) {
            exit('cache_file: 路径 "'.$path.'" 不可写');
        }
     
        $path = rtrim($path,'/') . '/';
        $instance->_options['cache_dir'] = $path;
         
        return $instance;
    }
     
    /**
     * 设置缓存文件后缀
     * 
     * @param srting $suffix
     * @return self
     */
    public static function setCachePrefix($suffix){
        $instance  = self::getInstance();
        $instance->_options['suffix'] = $suffix;
        return $instance;
    }
     
    /**
     * 设置缓存存储类型
     * 
     * @param int $mode
     * @return self
     */
    public static function setCacheMode($mode = 1){
        $instance  = self::getInstance();
        if($mode == 1){
            $instance->_options['mode'] = 1;
        }else{
            $instance->_options['mode'] = 2;
        }
         
        return $instance;
    }
     
    /**
     * 删除所有缓存
     * @return boolean
     */
    public static function flush(){
        $instance  = self::getInstance();
        $glob = glob($instance->_options['cache_dir'] . '*' . $instance->_options['suffix']);
         
        if(empty($glob)){
            return false;
        }
         
        foreach ($glob as $v){
			$id =  $instance->_fileNameToId(basename($v));
            $instance->delete($id);
        }
        return true;
    }
}