<?php
/*
 *	文件名称：数据备份恢复类[MYSQLI版]
 *  用途: 用来备份MySQL数据和恢复数据
 *  作者：袁志蒙
 *	编写时间：2014.12.09
 *	最后修改时间：2016.05.08
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class dbmanage {
    public $db; 
    public $database; 
    public $sqldir; 
    private $ds = "\n";
    public $sqlContent = "";
    public $sqlEnd = ";";

    function __construct() {
        $this->host = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->database = DB_NAME;
        $this->charset = 'utf8';
        set_time_limit(0);
        @ob_end_flush();
        $this->db = @mysqli_connect ( $this->host, $this->username, $this->password ) or die( '<p class="dbDebug"><span class="err">Mysql Connect Error : </span>'.mysqli_error().'</p>');
        mysqli_select_db ( $this->db, $this->database ) or die('<p class="dbDebug"><span class="err">Mysql Connect Error:</span>'.mysqli_error().'</p>');
        mysqli_query ( $this->db, 'SET NAMES ' . $this->charset );
 
    }
	
    function getTables() {
        $res = mysqli_query ( $this->db,  "SHOW TABLES" );
        $tables = array ();
        while ( $row = mysqli_fetch_array ( $res ) ) {
            $tables [] = $row [0];
        }
        return $tables;
    }
	
    function backup($tablename = '', $dir = './backup/', $size = '2048') {
        if (! is_dir ( $dir )) {
            mkdir ( $dir, 0777, true ) or die ( '创建文件夹失败' );
        }
        $sql = '';
        if (! empty ( $tablename )) {
            if(@mysqli_num_rows(mysqli_query($this->db, "SHOW TABLES LIKE '".$tablename."'")) == 1) {
             } else {
                $this->_showMsg('表-<b>' . $tablename .'</b>-不存在，请检查！',true);
                die();
            }
            $this->_showMsg('正在备份表 <span class="imp">' . $tablename.'</span>');
            $sql = $this->_retrieve ();
            $sql .= $this->_insert_table_structure ( $tablename );
            $data = mysqli_query ( $this->db,  "select * from " . $tablename );
            $filename = date ( 'YmdHis' ) . "_" . $tablename;
            $num_fields = mysqli_num_fields ( $data );
            $p = 1;
            while ( $record = mysqli_fetch_array ( $data ) ) {
                $sql .= $this->_insert_record ( $tablename, $num_fields, $record );
                if (strlen ( $sql ) >= $size * 1024) {
                    $file = $filename . "_v" . $p . ".sql";
                    if ($this->_write_file ( $sql, $file, $dir )) {
                        $this->_showMsg("表-<b>" . $tablename . "</b>-卷-<b>" . $p . "</b>-数据备份完成,备份文件 [ <span class='imp'>" .$dir . $file ."</span> ]");
                    } else {
                        $this->_showMsg("备份表 -<b>" . $tablename . "</b>- 失败",true);
                        return false;
                    }
                    $p ++;
                    $sql = "";
                }
            }
            unset($data,$record);
            if ($sql != "") {
                $filename .= "_v" . $p . ".sql";
                if ($this->_write_file ( $sql, $filename, $dir )) {
                    $this->_showMsg( "表-<b>" . $tablename . "</b>-卷-<b>" . $p . "</b>-数据备份完成,备份文件 [ <span class='imp'>" .$dir . $filename ."</span> ]");
                } else {
                    $this->_showMsg("备份卷-<b>" . $p . "</b>-失败<br />");
                    return false;
                }
            }
            $this->_showMsg("恭喜您! <span class='imp'>备份成功</span>");
        } else {
            $this->_showMsg('正在备份...');
            if ($tables = mysqli_query ( $this->db,  "show table status from " . $this->database )) {
                $this->_showMsg("读取数据库结构成功！");
            } else {
                $this->_showMsg("读取数据库结构失败！");
                exit ( 0 );
            }
            $sql .= $this->_retrieve ();
            $filename = date ( 'YmdHis' ) . "_all";
            $tables = mysqli_query ( $this->db,  'SHOW TABLES' );
            $p = 1;
            while ( $table = mysqli_fetch_array ( $tables ) ) {
                $tablename = $table [0];
                $sql .= $this->_insert_table_structure ( $tablename );
                $data = mysqli_query ( $this->db,  "select * from " . $tablename );
                $num_fields = mysqli_num_fields ( $data );
 
                while ( $record = mysqli_fetch_array ( $data ) ) {
                    $sql .= $this->_insert_record ( $tablename, $num_fields, $record );
                    if (strlen ( $sql ) >= $size * 1000) {
 
                        $file = $filename . "_v" . $p . ".sql";
                        if ($this->_write_file ( $sql, $file, $dir )) {
                            $this->_showMsg("-卷-<b>" . $p . "</b>-数据备份完成,备份文件 [ <span class='imp'>".$dir.$file."</span> ]");
                        } else {
                            $this->_showMsg("卷-<b>" . $p . "</b>-备份失败!",true);
                            return false;
                        }
                        $p ++;
                        $sql = "";
                    }
                }
            }
            if ($sql != "") {
                $filename .= "_v" . $p . ".sql";
                if ($this->_write_file ( $sql, $filename, $dir )) {
                    $this->_showMsg("-卷-<b>" . $p . "</b>-数据备份完成,备份文件 [ <span class='imp'>".$dir.$filename."</span> ]");
                } else {
                    $this->_showMsg("卷-<b>" . $p . "</b>-备份失败",true);
                    return false;
                }
            }
            $this->_showMsg("恭喜您! <span class='imp'>备份成功</span>");
        }
    }
	
    private function _showMsg($msg,$err=false){
        $err = $err ? "<span class='err'>ERROR:</span>" : '' ;
        echo "<p class='dbDebug'>".$err . $msg."</p>";
        flush();
 
    }
	
    private function _retrieve() {
        $value = '';
        $value .= '--' . $this->ds;
        $value .= '-- MySQL database dump' . $this->ds;
        $value .= '-- Created by dbmanage class, Powered by YzmCMS. ' . $this->ds;
        $value .= '--' . $this->ds;
        $value .= '-- 主机: ' . $this->host . $this->ds;
        $value .= '-- 生成日期: ' . date ( 'Y' ) . ' 年  ' . date ( 'm' ) . ' 月 ' . date ( 'd' ) . ' 日 ' . date ( 'H:i' ) . $this->ds;
        $value .= '-- MySQL版本: ' . mysqli_get_server_info ($this->db) . $this->ds;
        $value .= '-- PHP 版本: ' . phpversion() . $this->ds;
        $value .= $this->ds;
        $value .= '--' . $this->ds;
        $value .= '-- 数据库: `' . $this->database . '`' . $this->ds;
        $value .= '--' . $this->ds . $this->ds;
        $value .= '-- -------------------------------------------------------';
        $value .= $this->ds . $this->ds;
        return $value;
    }

    private function _insert_table_structure($table) {
        $sql = '';
        $sql .= "--" . $this->ds;
        $sql .= "-- 表的结构" . $table . $this->ds;
        $sql .= "--" . $this->ds . $this->ds;
        $sql .= "DROP TABLE IF EXISTS `" . $table . '`' . $this->sqlEnd . $this->ds;
        $res = mysqli_query ( $this->db,  'SHOW CREATE TABLE `' . $table . '`' );
        $row = mysqli_fetch_array ( $res );
        $sql .= $row [1];
        $sql .= $this->sqlEnd . $this->ds;
        $sql .= $this->ds;
        $sql .= "--" . $this->ds;
        $sql .= "-- 转存表中的数据 " . $table . $this->ds;
        $sql .= "--" . $this->ds;
        $sql .= $this->ds;
        return $sql;
    }

    private function _insert_record($table, $num_fields, $record) {
        $insert = '';
        $comma = "";
        $insert .= "INSERT INTO `" . $table . "` VALUES(";
        for($i = 0; $i < $num_fields; $i ++) {
            $insert .= ($comma . "'" . mysqli_real_escape_string ($this->db, $record [$i] ) . "'");
            $comma = ",";
        }
        $insert .= ");" . $this->ds;
        return $insert;
    }
	
    private function _write_file($sql, $filename, $dir) {
        $dir = $dir ? $dir : './backup/';
        if (! is_dir ( $dir )) {
            mkdir ( $dir, 0777, true );
        }
        $re = true;
        if (! @$fp = fopen ( $dir . $filename, "w+" )) {
            $re = false;
            $this->_showMsg("打开sql文件失败！",true);
        }
        if (! @fwrite ( $fp, $sql )) {
            $re = false;
            $this->_showMsg("写入sql文件失败，请文件是否可写",true);
        }
        if (! @fclose ( $fp )) {
            $re = false;
            $this->_showMsg("关闭sql文件失败！",true);
        }
        return $re;
    }
	
    function restore($sqlfile) {
        if (! file_exists ( $sqlfile )) {
            $this->_showMsg("sql文件不存在！请检查",true);
            exit ();
        }
        $this->lock ( $this->database );
        $sqlpath = pathinfo ( $sqlfile );
        $this->sqldir = $sqlpath ['dirname'];
        $volume = explode ( "_v", $sqlfile );
        $volume_path = $volume [0];
        $this->_showMsg("请勿刷新或关闭浏览器，正在拼命处理中...");
        $this->_showMsg("<img src='images/loading.gif'>");

		
		if (empty ( $volume [1] )) {
            $this->_showMsg ( "正在导入sql：<span class='imp'>" . $sqlfile . '</span>');
            if ($this->_import ( $sqlfile )) {
                $this->_showMsg( "<script>$('#info').html(\"<strong>数据导入成功！</strong>\") </script>");
            } else {
                 $this->_showMsg("<script>$('#info').html(\"<strong>数据导入失败！</strong>\") </script>",true);
                exit ();
            }
        } else {
            $volume_id = explode ( ".sq", $volume [1] );
            $volume_id = intval ( $volume_id [0] );
            while ( $volume_id ) {
                $tmpfile = $volume_path . "_v" . $volume_id . ".sql";
                if (file_exists ( $tmpfile )) {
                    $this->_showMsg("正在导入分卷 $volume_id ：<span style='color:#f00;'>" . $tmpfile . '</span>');
                    if ($this->_import ( $tmpfile )) {
                        $this->_showMsg("导入分卷 $volume_id ：<span style='color:#f00;'>" . $tmpfile . '成功！</span>');
                    } else {
                        $volume_id = $volume_id ? $volume_id :1;
                        exit ( "导入分卷：<span style='color:#f00;'>" . $tmpfile . '</span>失败！可能是数据库结构已损坏！请尝试从分卷1开始导入' );
                    }
                } else {
                    $this->_showMsg( "<script>$('#info').html(\"<strong>此分卷备份全部导入成功！</strong>\") </script>");
                    return;
                }
                $volume_id ++;
            }
        }
    }

    private function _import($sqlfile) {
        $sqls = array ();
        $f = fopen ( $sqlfile, "rb" );
        $create_table = '';
        while ( ! feof ( $f ) ) {
            $line = fgets ( $f );
            if (! preg_match ( '/;/', $line ) || preg_match ( '/ENGINE=/', $line )) {
                $create_table .= $line;
                if (preg_match ( '/ENGINE=/', $create_table)) {
                    $this->_insert_into($create_table);
                    $create_table = '';
                }
                continue;
            }
            $this->_insert_into($line);
        }
        fclose ( $f );
        return true;
    }

    private function _insert_into($sql){
        if (! mysqli_query ( $this->db,  trim ( $sql ) )) {
            $this->msg .= mysqli_error ();
            return false;
        }
    }
    private function close() {
        mysqli_close ( $this->db );
    }

    private function lock($tablename, $op = "WRITE") {
        if (mysqli_query ( $this->db,  "lock tables " . $tablename . " " . $op ))
            return true;
        else
            return false;
    }

    private function unlock() {
        if (mysqli_query ( $this->db,  "unlock tables" ))
            return true;
        else
            return false;
    }

    function __destruct() {
        if($this->db){
            mysqli_query ( $this->db,  "unlock tables");
            mysqli_close ( $this->db );
        }
    }

}
