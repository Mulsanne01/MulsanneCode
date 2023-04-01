<?php
/**
 * 功能: 根据条件建立分类缓存减少类别使用
 * 创建日期:Thu May 31 15:55:11 CST 2007
 */

class cache{
    var $filename = ""; //文件名
	var $cachetype = 0;
    function __construct($cachePath,$cacheName,$cachetype=0){
      makeDirectory(PATH."./template/cache/$cachePath/");
	  $this->filename = PATH."./template/cache/$cachePath/".$cacheName;
	  $this->cachetype = $cachetype;
    }
    //获取数据
    function getcache(){
	  $fp = @fopen($this->filename,'r');	
	  $res = @fread($fp,filesize($this->filename));
	  fclose($fp);
      return $this->cachetype ? $res : unserialize($res);
    }
    //建立文件
    function setcache($res){
	  $res = $this->cachetype ? $res : serialize($res);
      $fp = fopen($this->filename,'w');
	  flock($fp,2);
	  fwrite($fp,$res);
	  fclose($fp);
      return $res;
    }
	//文件创建时间
	function filetime(){
	  return @filemtime($this->filename);		
	}
	//建立目录
    function makeDirectory($directoryName){
      $temp = $directoryName;
      if(!is_dir($temp)){
        $oldmask = umask(0);
        if(!mkdir($temp, 0777)) exit("不能建立目录 $temp");
        umask($oldmask);
      }
      return $temp;
    }
}
?>