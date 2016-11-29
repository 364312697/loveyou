<?php
require('check.php');

if(!isset($_SESSION["ftpname"]))  showmsg("请登录文件管理器！", 3, "filelock.php");

$num = 0; //用来统计子目录和文件的个数
$dirname = $default_dir = '..'; //默认目录
$str = '';

if(isset($_GET['del'])){   
	$del = iconv('utf-8','gb2312',$_GET['del']);
	$result = @unlink ($del); 
    $dirname = dirname($del);   	
	if ($result == false) { 
		showmsg('删除文件失败！'); 
	}else { 
		showmsg('删除文件成功！', 1); 		
	}  
}else if(isset($_GET['readdir']) && $_GET['readdir']!=''){
	$dirname = iconv('utf-8','gb2312',$_GET['readdir']);	
}

$dir_handle = opendir($dirname); //用opendir打开目录
if($dir_handle === false) exit('Error:Open directory failed: invalid directory or refuse access!');

$arr_file = array();

$str = $dirname != $default_dir ? '<p><img src="images/dir.jpg"><a href="?readdir='.dirname(iconv('gb2312', 'utf-8', $dirname)).'">返回上一层</a></p>' : '';

$htmltables = '';

//将遍历的目录和文件名使用表格格式输出
$htmltables.= '<table cellspacing="0" cellpadding="0">';
$htmltables.= '<tr align="left" bgcolor="#3eafe0">';
$htmltables.= '<th>文件名</th><th>文件大小</th><th>文件类型</th><th>修改时间</th><th>操作</th></tr>';
 
//使用readdir循环读取目录里的内容
while($file = readdir($dir_handle)){
	if($file!="." && $file!="..") {           //去掉目录下当前目录和上一级目录 	
	    $dirFile = $dirname."/".$file;        //将将目录下的文件和当前目录连接起来，才能在程序中使用
		$num ++;
        if(is_dir($dirFile)){							
			$htmltables.= '<tr>';
			$htmltables.= '<td><img src="images/dir.jpg"><a href="?readdir='.iconv('gb2312','utf-8',$dirFile).'">'.iconv('gb2312','utf-8',$file).'</a></td>'; //显示目录名
			$htmltables.= '<td>'.sizecount(dirSize($dirFile)).'</td>'; //显示目录大小
			$htmltables.= '<td>dir</td>'; //显示文件类型
			$htmltables.= '<td>'.date("Y/m/d H:i:s",filemtime($dirFile)).'</td>'; //格式化显示文件修改时间
			$htmltables.= '<td><a href="?readdir='.iconv('gb2312','utf-8',$dirFile).'">进入</a></td>';  
			$htmltables.= '</tr>';
		}else{
			$arr_file[]=$file;
		}
	}
}

$img_arr = array('jpg', 'png', 'gif', 'jpeg');
//遍历文件
foreach($arr_file as $val){
	        $dirFile = $dirname."/".$val;        //将将目录下的文件和当前目录连接起来，才能在程序中使用
	        $name_img = in_array(fileext($val), $img_arr) ? 'img' : 'file';        //判断文件格式，加载不同的图片标识
			$htmltables.= '<tr>';
			$htmltables.= '<td><img src="images/'.$name_img.'.jpg">'.iconv('gb2312','utf-8',$val).'</td>'; //显示文件名
			$htmltables.= '<td>'.sizecount(filesize($dirFile)).'</td>'; //显示文件大小
			$htmltables.= '<td>'.$name_img.'</td>'; //显示文件类型
			$htmltables.= '<td>'.date("Y/m/d H:i:s",filemtime($dirFile)).'</td>'; //格式化显示文件修改时间
			$htmltables.= '<td><a href="'.get_browse_url().iconv('gb2312','utf-8',$dirFile).'" target="_blank">浏览</a> | <a href="?del='.iconv('gb2312','utf-8',$dirFile).'" onclick="return confirm(\'你确定要删除 '.iconv('gb2312','utf-8',$val).' 吗？\')">删除</a></td>'; 
			$htmltables.= '</tr>';	
}

$htmltables.= '<tr><td colspan="5">总计：在本目录下，子目录和文件数量共 <b>'.$num.'</b> 个</td></tr>';
$htmltables.= '</table>'; 
closedir($dir_handle); //关闭文件操作句柄


function dirSize($directory){
   $dir_size = 0; //用来累加各个文件大小
 
   if($dir_handle = @opendir($directory)){      //打开目录，并判断是否能成功打开
    while($filename = readdir($dir_handle)){     //循环遍历目录下的所有文件
        if($filename != "."&& $filename != ".."){     //一定要排除两个特殊的目录
            $subFile = $directory."/".$filename;     //将目录下的子文件和当前目录相连
            if(is_dir($subFile))                     //如果为目录
            $dir_size += dirSize($subFile);     //递归地调用自身函数，求子目录的大小
            if(is_file($subFile))                //如果是文件
            $dir_size += filesize($subFile);     //求出文件的大小并累加
        }
     }
    closedir($dir_handle);      //关闭文件资源
    return $dir_size;     //返回计算后的目录大小
  }
}

function get_browse_url(){
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	return $sys_protocal.str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}


include('templets/fileftp.htm');