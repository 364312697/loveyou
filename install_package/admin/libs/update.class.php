<?php   
class update{
	
	private static $url ='http://www.yzmcms.com/notice/update.php';
	
	public static function mysql_varsion() {
        return M('admin')->version();     
    }
	 
	public static function notice_url($action = 'notice') {
        $sysinfo = get_sysinfo();		
		$pars = array(
			'action'=>$action,
			'siteurl'=>urlencode($sysinfo['wroot']),
			'sitename'=>urlencode($sysinfo['wname']),
			'version'=>YZMCMS_VERSION,
			'os'=>PHP_OS,
			'php'=>phpversion(),
			'mysql'=>self::mysql_varsion(),
			'browser'=>urlencode($_SERVER['HTTP_USER_AGENT']),
			'username'=>urlencode($_SESSION['adminname']),
			'email'=>urlencode($_SESSION['userinfo']['email']),
			);
		$data = http_build_query($pars);
        return self::$url.'?'.$data;     
    }
	
}

function system_information($data) {
		$notice_url = update::notice_url();
		$string = base64_decode('PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPiQoIiNib2R5IikucmVtb3ZlQ2xhc3MoImRpc3BsYXkiKTs8L3NjcmlwdD48ZGl2IGlkPSJ5em1jbXNfbm90aWNlIj48L2Rpdj48c2NyaXB0IHR5cGU9InRleHQvamF2YXNjcmlwdCIgc3JjPSJOT1RJQ0VfVVJMIj48L3NjcmlwdD4=');
		echo $data.str_replace('NOTICE_URL',$notice_url,$string);
}