<?php
    include ('../../../config/common.inc.php');   //加载核心文件
	$sysinfo = get_sysinfo();
	
	error_reporting( E_ERROR | E_WARNING );
	
    include "Uploader.class.php";
    //上传配置
    $config = array(
        "water_enable" => $sysinfo['water_enable'] ,                      //水印开关
        "water_pos" => $sysinfo['water_pos'] ,                            //水印的位置
        "water_img" => YZMCMS_PATH.'/common/images/water/'.$sysinfo['water_img'] ,           //水印图片
        "savePath" => "uploads/" ,             //存储文件夹
        "maxSize" => 2048 ,                   //允许的文件最大尺寸，单位KB
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )  //允许的文件格式
    );
    //上传文件目录
    $Path = "../../../uploads/";

    //背景保存在临时目录中
    $config[ "savePath" ] = $Path;
    $up = new Uploader( "upfile" , $config );
    $type = $_REQUEST['type'];
    $callback=$_GET['callback'];

    $info = $up->getFileInfo();

    /**
     * 返回数据
     */
    if($callback) {
        echo '<script>'.$callback.'('.json_encode($info).')</script>';
    } else {
        echo json_encode($info);
    }
