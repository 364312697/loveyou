<?php
require('check.php');

if(isset($_GET['clear'])){
    
	cache_file::setCacheDir(YZMCMS_PATH.'/common/cache/');
	cache_file::flush();
	$dir = glob(YZMCMS_PATH.'/common/cache/*', GLOB_ONLYDIR);
	foreach($dir as $v){
		cache_file::setCacheDir($v);
		cache_file::flush();
	}
	
    $html_message = '清除缓存成功！';

    include('templets/message.htm');	
			
}
?>