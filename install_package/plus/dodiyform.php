<?php
session_start();

require('../config/common.inc.php');

if(!isset($_POST['dosubmit'])) showmsg('非法访问！');
	
$modelid = isset($_POST['modelid']) ? intval($_POST['modelid']) : 0;
if(!$modelid) showmsg('该表单不存在或已禁用！');

$model = M('model');
$r = $model->where(array('modelid'=>$modelid, 'disabled'=>0, 'type'=>0))->find();
if(!$r) showmsg('该表单不存在或已禁用！');

if(!$r['allowvisitor'] && empty($_SESSION['_userid'])) showmsg('请登录会员！');

if($_SESSION['code'] != strtolower($_POST['code'])) showmsg("验证码错误！");

$model_field = M('model_field');
$tablename = M($r['tablename']);
$model_data = $model_field->where(array('modelid'=>$modelid, 'disabled'=>0))->select();
$sysinfo = get_sysinfo();

foreach($model_data as $val){
	if($val['isrequired']){     //判断是否必填	
	    $val['errortips'] = $val['errortips'] ? $val['errortips'] : $val['name'].'必填！';
		if(!isset($_POST[$val['field']]) || $_POST[$val['field']] === '') showmsg($val['errortips']);		
	}
	
	if($val['isunique']  && isset($_POST[$val['field']])){     //判断值是否唯一	
		if($tablename->where(array($val['field']=>$_POST[$val['field']]))->total()) 	
		showmsg($val['name'].'已存在！'); 	
	}
	
	if($val['formtype'] == 'file' && $_FILES[$val['field']]['name']){     //如果有图片上传字段	    		
		$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/diyform/'.$r['tablename']));	
		if($uppic->uploadFile($val['field'])){
			$_POST[$val['field']] = $sysinfo['wroot'].'uploads/diyform/'.$r['tablename'].'/'.$uppic->getNewFileName();
		}else{
			showmsg($uppic->getErrormsg(),5);
		}
	}elseif(($val['formtype'] == 'select' || $val['formtype'] == 'radio') && isset($_POST[$val['field']])){     //如果是选项字段，则获取选项值	
	    $arr = string2array($val['setting']);
        $_POST[$val['field']] = $arr['options'][$_POST[$val['field']]];		
	}elseif($val['formtype'] == 'checkbox' && isset($_POST[$val['field']])){     //如果多选框，则获取多个选项值	    
		$chestr = '';
	    $arr = isset($arr) ? $arr : string2array($val['setting']);
	    foreach($_POST[$val['field']] as $v){
			$chestr .= $arr['options'][$v].',';
		}
		$_POST[$val['field']] = rtrim($chestr, ',');		
	}
	
}

$_POST['userid'] = isset($_SESSION['_userid']) ? $_SESSION['_userid'] : 0;
$_POST['username'] = isset($_SESSION['_username']) ? $_SESSION['_username'] : '';
$_POST['ip'] = getip();
$_POST['inputtime'] = time();
$tablename->insert($_POST, 1);
$model->update('`items`=`items`+1', array('modelid'=>$modelid));

//发送邮件通知
if($r['sendmail']){
    sendmail($sysinfo['default_email'], '表单提醒：“'.$r['name'].'”有新信息', '您的网站有新信息提交，<a href="'.$sysinfo['wroot'].'">请查看</a>！<br> <b>'.$sysinfo['wname'].'</b>');	
}


showmsg('操作成功，感谢您的参与！'); 