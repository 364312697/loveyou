<?php
/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
class Uploader
{
    private $fileField;            //文件域名
    private $file;                 //文件上传对象
    private $config;               //配置信息
    private $oriName;              //原始文件名
    private $fileName;             //新文件名
    private $fullName;             //完整文件名,即从当前配置目录开始的URL
    private $fileSize;             //文件大小
    private $fileType;             //文件类型
    private $stateInfo;            //上传状态信息,
    private $stateMap = array(    //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS" ,                //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制" ,
        "文件大小超出 MAX_FILE_SIZE 限制" ,
        "文件未被完整上传" ,
        "没有文件被上传" ,
        "上传文件为空" ,
        "POST" => "文件大小超出 post_max_size 限制" ,
        "SIZE" => "文件大小超出网站限制" ,
        "TYPE" => "不允许的文件类型" ,
        "DIR" => "目录创建失败" ,
        "IO" => "输入输出错误" ,
        "UNKNOWN" => "未知错误" ,
        "MOVE" => "文件保存时出错",
        "DIR_ERROR" => "创建目录失败",
        "ORIGINAL_NOT_EXIST" => "要加水印的图片不存在",   //添加水印图片错误提示
        "WATERMARK_ERROR" => "添加水印时发生错误",   //添加水印图片错误提示
    );

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config  配置项
     * @param bool $base64  是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct( $fileField , $config , $base64 = false )
    {
        $this->fileField = $fileField;
        $this->config = $config;
        $this->stateInfo = $this->stateMap[ 0 ];
        $this->upFile( $base64 );
    }

    /**
     * 上传文件的主处理方法
     * @param $base64
     * @return mixed
     */
    private function upFile( $base64 )
    {
        //处理base64上传
        if ( "base64" == $base64 ) {
            $content = $_POST[ $this->fileField ];
            $this->base64ToImage( $content );
            return;
        }

        //处理普通上传
        $file = $this->file = $_FILES[ $this->fileField ];
        if ( !$file ) {
            $this->stateInfo = $this->getStateInfo( 'POST' );
            return;
        }
        if ( $this->file[ 'error' ] ) {
            $this->stateInfo = $this->getStateInfo( $file[ 'error' ] );
            return;
        }
        if ( !is_uploaded_file( $file[ 'tmp_name' ] ) ) {
            $this->stateInfo = $this->getStateInfo( "UNKNOWN" );
            return;
        }

        $this->oriName = $file[ 'name' ];
        $this->fileSize = $file[ 'size' ];
        $this->fileType = $this->getFileExt();

        if ( !$this->checkSize() ) {
            $this->stateInfo = $this->getStateInfo( "SIZE" );
            return;
        }
        if ( !$this->checkType() ) {
            $this->stateInfo = $this->getStateInfo( "TYPE" );
            return;
        }

        $folder = $this->getFolder();

        if ( $folder === false ) {
            $this->stateInfo = $this->getStateInfo( "DIR_ERROR" );
            return;
        }

        $this->fullName = $folder . '/' . $this->getName();
        		
		
        if ( $this->stateInfo == $this->stateMap[ 0 ] ) {
            if ( !move_uploaded_file( $file[ "tmp_name" ] , $this->fullName ) ) {
                $this->stateInfo = $this->getStateInfo( "MOVE" );
            }
        }
		
		//为上传的图片添加水印
		if($this->config[ "water_enable" ]){
			$this->imageWaterMark($this->fullName, $this->config[ "water_pos" ], $this->config[ "water_img" ]);
		}
		
		
    }

    /**
     * 处理base64编码的图片上传
     * @param $base64Data
     * @return mixed
     */
    private function base64ToImage( $base64Data )
    {
        $img = base64_decode( $base64Data );
        $this->fileName = time() . rand( 1 , 10000 ) . ".png";
        $this->fullName = $this->getFolder() . '/' . $this->fileName;
        if ( !file_put_contents( $this->fullName , $img ) ) {
            $this->stateInfo = $this->getStateInfo( "IO" );
            return;
        }
        $this->oriName = "";
        $this->fileSize = strlen( $img );
        $this->fileType = ".png";
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {
        return array(
            "originalName" => $this->oriName ,
            "name" => $this->fileName ,
            "url" => $this->fullName ,
            "size" => $this->fileSize ,
            "type" => $this->fileType ,
            "state" => $this->stateInfo
        );
    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo( $errCode )
    {
        return !$this->stateMap[ $errCode ] ? $this->stateMap[ "UNKNOWN" ] : $this->stateMap[ $errCode ];
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getName()
    {
        return $this->fileName = time() . rand( 1 , 10000 ) . $this->getFileExt();
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        return in_array( $this->getFileExt() , $this->config[ "allowFiles" ] );
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= ( $this->config[ "maxSize" ] * 1024 );
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower( strrchr( $this->file[ "name" ] , '.' ) );
    }

    /**
     * 按照日期自动创建存储文件夹
     * @return string
     */
    private function getFolder()
    {
        $pathStr = $this->config[ "savePath" ];
        if ( strrchr( $pathStr , "/" ) != "/" ) {
            $pathStr .= "/";
        }
        $pathStr .= date( "Ymd" );
        if ( !file_exists( $pathStr ) ) {
            if ( !mkdir( $pathStr , 0777 , true ) ) {
                return false;
            }
        }
        return $pathStr;
    }
	

	/*
	* 功能：图片水印 (水印支持图片或文字)
	* 作者：袁志蒙
	* 时间：2016-04-08
	* 参数：
	* $groundImage 背景图片，即需要加水印的图片，暂只支持GIF,JPG,PNG格式；
	* $waterPos 水印位置，有10种状态，0为随机位置；
	* 1为顶端居左，2为顶端居中，3为顶端居右；
	* 4为中部居左，5为中部居中，6为中部居右；
	* 7为底端居左，8为底端居中，9为底端居右；
	* $waterImage 图片水印，即作为水印的图片，暂只支持GIF,JPG,PNG格式；
	* $waterText 文字水印，即把文字作为为水印，支持ASCII码，不支持中文；
	* $textFont  文字大小，值为1、2、3、4或5，默认为5；
	* $textColor 文字颜色，值为十六进制颜色值，默认为#FF0000(红色)；
	*
	* 注意：Support GD 2.0，Support FreeType、GIF Read、GIF Create、JPG 、PNG
	* $waterImage 和 $waterText 最好不要同时使用，选其中之一即可，优先使用 $waterImage。
	* 当$waterImage有效时，参数$waterString、$stringFont、$stringColor均不生效。
	* 加水印后的图片的文件名和 $groundImage 一样。
	*/
	private function imageWaterMark($groundImage, $waterPos = 0, $waterImage = '', $waterText = 'yzmcms', $textFont = 5, $textColor='#FF0000'){
		$isWaterImage = FALSE;
		$formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。";
		//读取水印文件
		if(!empty($waterImage) && file_exists($waterImage))
		{
			$isWaterImage = TRUE;
			$water_info = getimagesize($waterImage);
			$water_w = $water_info[0];//取得水印图片的宽
			$water_h = $water_info[1];//取得水印图片的高 
			switch($water_info[2])//取得水印图片的格式
			{
				case 1:$water_im = imagecreatefromgif($waterImage);break;
				case 2:$water_im = imagecreatefromjpeg($waterImage);break;
				case 3:$water_im = imagecreatefrompng($waterImage);break;
				//default:die($formatMsg);
				default: $this->stateInfo = $this->getStateInfo( "WATERMARK_ERROR" );
			}
		}
		//读取背景图片
		if(!empty($groundImage) && file_exists($groundImage)){
			$ground_info = getimagesize($groundImage);
			$ground_w = $ground_info[0];//取得背景图片的宽
			$ground_h = $ground_info[1];//取得背景图片的高
			switch($ground_info[2])//取得背景图片的格式
			{
				case 1:$ground_im = imagecreatefromgif($groundImage);break;
				case 2:$ground_im = imagecreatefromjpeg($groundImage);break;
				case 3:$ground_im = imagecreatefrompng($groundImage);break;
				//default:die($formatMsg);
				default: $this->stateInfo = $this->getStateInfo( "WATERMARK_ERROR" );
			}
		}else{
			//die("需要加水印的图片不存在！");
			$this->stateInfo = $this->getStateInfo( "ORIGINAL_NOT_EXIST" );
			return false;
		}
		//水印位置
		if($isWaterImage)//图片水印
		{
			$w = $water_w;
			$h = $water_h;
			$label = "图片的";
		}
		else//文字水印
		{
			$temp = imagettfbbox(ceil($textFont*3), 0, YZMCMS_PATH."/common/libs/font/elephant.ttf", $waterText);//取得使用 TrueType 字体的文本的范围
			$w = $temp[2] - $temp[6];
			$h = $temp[3] - $temp[7];
			unset($temp);
			$label = "文字区域";
		}
		if( ($ground_w<$w) || ($ground_h<$h) ){
			//echo "需要加水印的图片的长度或宽度比水印".$label."还小，无法生成水印！";
			//$this->stateInfo = $this->getStateInfo( "WATERMARK_ERROR" ); 
			return;  //直接返回，放弃添加水印
		}
		switch($waterPos)
		{
			case 0://随机
				$posX = rand(0,($ground_w - $w));
				$posY = rand(0,($ground_h - $h));
				break;
			case 1://1为顶端居左
				$posX = 0;
				$posY = 0;
				break;
			case 2://2为顶端居中
				$posX = ($ground_w - $w) / 2;
				$posY = 0;
				break;
			case 3://3为顶端居右
				$posX = $ground_w - $w;
				$posY = 0;
				break;
			case 4://4为中部居左
				$posX = 0;
				$posY = ($ground_h - $h) / 2;
				break;
			case 5://5为中部居中
				$posX = ($ground_w - $w) / 2;
				$posY = ($ground_h - $h) / 2;
				break;
			case 6://6为中部居右
				$posX = $ground_w - $w;
				$posY = ($ground_h - $h) / 2;
				break;
			case 7://7为底端居左
				$posX = 0;
				$posY = $ground_h - $h;
				break;
			case 8://8为底端居中
				$posX = ($ground_w - $w) / 2;
				$posY = $ground_h - $h;
				break;
			case 9://9为底端居右
				$posX = $ground_w - $w - 10;   // -10 是距离右侧10px 可以自己调节
				$posY = $ground_h - $h - 10;   // -10 是距离底部10px 可以自己调节
				break;
			default://随机
				$posX = rand(0,($ground_w - $w));
				$posY = rand(0,($ground_h - $h));
				break;
		}
		//设定图像的混色模式
		imagealphablending($ground_im, true);
		if($isWaterImage)//图片水印
		{
			imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);//拷贝水印到目标文件 
		}
		else//文字水印
		{
			if( !empty($textColor) && (strlen($textColor)==7) )
			{
				$R = hexdec(substr($textColor,1,2));
				$G = hexdec(substr($textColor,3,2));
				$B = hexdec(substr($textColor,5));
			}else{
				//die("水印文字颜色格式不正确！");
				$this->stateInfo = $this->getStateInfo( "WATERMARK_ERROR" );
			}
			imagestring ( $ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B)); 
		}
		//生成水印后的图片
		@unlink($groundImage);
		switch($ground_info[2])//取得背景图片的格式
		{
			case 1:imagegif($ground_im,$groundImage);break;
			case 2:imagejpeg($ground_im,$groundImage);break;
			case 3:imagepng($ground_im,$groundImage);break;
			default:die($errorMsg);
		}
		//释放内存
		if(isset($water_info)) unset($water_info);
		if(isset($water_im)) imagedestroy($water_im);
		unset($ground_info);
		imagedestroy($ground_im);
	}
}