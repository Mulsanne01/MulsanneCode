<?php
abstract class user_class{
    function __construct($module){
        $this->cookie = config::get('cookie');
        $this->mysql = $module->mysql;
		$this->module = $module;
		$this->pre = config::get("tablepre");
		$this->startpurview();
    }	
	function startpurview(){
		$this->purviews = $this->mysql->getarr("select * from {$this->pre}purviews order by id desc");
		if($this->module->module=='admin'){
		  $this->purview = "{$_GET['mod']}_{$_GET['act']}_{$_GET['get']}";
		}else{
		  $this->purview = "{$_GET['mod']}_{$_GET['act']}_{$_GET['type']}";	
		}
		$purviews = localPre($this->purviews,'purviews');
		$this->verifyLgoin = in_array($this->purview,$purviews) ? true : false;		
	}	
	//双重写入
    public function putsecook($key,$val){
	  $_SESSION[$key] = $val;
	  $this->putcookie($key,$val,3600);
    }
	//双重销毁
    public function dropsecook($key){
	  $_SESSION[$key] = '';
	  $this->dropcookie($key);
    }
	//写入cookie
    public function putcookie($key,$value,$keeptime=0){
		$keeptime = $keeptime==0 ? 60*60 : $keeptime;
	    setcookie($key,$value,time()+$keeptime,config::get('sitepath'),config::get("cookiedomain"));
    }	
	//销毁cookie
    public function dropcookie($key){
	    setcookie($key,"",time()-1,config::get('sitepath'),config::get("cookiedomain"));
    }	
	//密码加密
	public function password($password,$salt) {
		return md5(md5($password).$salt);
	}
	//密码后缀
	public function salt() {
		return substr(uniqid(rand()), -6);
	}
}
?>