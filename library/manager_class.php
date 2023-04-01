<?php
class manager_class extends user_class{
	private $sessionid = "manageruid";
	private $sessionshell = "managershell";
	public function check() {
	  $uid = $this->cookie ? $_COOKIE[$this->sessionid] : $_SESSION[$this->sessionid];
	  $shell = $this->cookie ? $_COOKIE[$this->sessionshell] : $_SESSION[$this->sessionshell]; 
	  if(($uid==''||$shell=='')&&$this->verifyLgoin){
        $this->logout('admin_login');
		return false;
	  }else{
		$user = is_array($row = $this->sql($uid));
	    $login = $user ? $shell == md5($row['username'] . $row['password'] . $row['salt']) : false;
		if(!$login&&$this->verifyLgoin){
		  $this->logout('admin_login');
		  return false; 
		}else{
		  $this->keep($uid,$shell);
		  return $row;
		}
	  }
	}	
	
	public function login($username,$password,$seccode,$admin=false) {
	  $username = sintrim($username);
	  $password = sintrim($password);
	  $seccode = sintrim($seccode);
	  if($username=='') $this->module->message('go_back','您还没有输入用户名');
	  if($password=='') $this->module->message('go_back','请输入您的登陆密码');
	  if(config::get("mustcode")&&$seccode=='') $this->module->message('go_back','对不起，验证码不能为空');
	  if(config::get("mustcode")&&$seccode!=$_SESSION['seccode'])	$this->module->message('go_back','对不起，验证码不正确');	 
	  if(!is_array($row = $this->sql($username,'username'))){
		$this->module->message('go_back','对不起，该用户不存在');	 
	  }
	  if($this->password($password,$row[salt]) == $row[password]){//密码是否正确
		$this->keep($row[uid],md5($row[username].$row[password].$row[salt])); 
		$this->logininfo($row);
	    location('admin');
	  }else{
 		$this->drop();
		$this->module->message('go_back','对不起，您的密码有误');
	  }   	   			
	}
	
	function purview(){
		$manager_purviews = $this->module->manager['purviews'];
	    $purviews = explode(',',$manager_purviews);
		if(!$this->verifyLgoin) return true;
		if($manager_purviews!='adminall'&&!in_array($this->purview,$purviews)){
		   $this->module->message('go_back','对不起，你没有权限操作！');
		}
	}
	
	function rn_purview($mod,$act,$get){
		$manager_purviews = $this->module->manager['purviews'];
	    $purviews = explode(',',$manager_purviews);
		if($manager_purviews!='adminall'&&!in_array("{$mod}_{$act}_{$get}",$purviews)){
		   return false;
		}else{
		   return true;
		}
	}
	function gt_purview($act){
		$manager_purviews = $this->module->manager['purviews'];
		if($manager_purviews=='adminall'||strstr($manager_purviews,"_{$act}_")){
		   return true;
		}else{
		   return false;
		}
	}
	
	function logininfo($row){
	    $arr['lastip'] = getip();
		$arr['lasttime'] = time();
		$arr['loginnum'] = $row['loginnum']+1;
		$this->update($arr,$row['uid']);
	}
	
	
	function loginlog($arr){
		$arr['type'] = 0;
		$arr['uid'] = $arr['uid'];
		$arr['addtime'] = time();
        $this->mysql->insert("{$this->module->pre}log",$arr);
	}
	
	function update($arr,$uid){
		if($arr['password']){
		   $salt = $this->mysql->value("{$this->module->pre}manager",'salt',"uid ='$uid'");
		   $arr['password'] = $this->password($arr['password'],$salt);
		}else{
		   unset($arr['password']);	
		}		
		unset($arr['username']);
		$this->mysql->update("{$this->module->pre}manager",$arr,"uid ='$uid'");
	}
	
	function insert($arr){
		$arr['salt'] = $this->salt();
        $arr['password'] = $this->password($arr['password'],$arr['salt']);
		$arr['loginnum'] = 0;
		$arr['lasttime'] = time();
		$arr['lastip'] = getip();
		$arr['groupid'] = $arr['groupid'] ? $arr['groupid'] : 4;
		if($this->checkusername($arr['username'])) $this->module->message('go_back','对不起，该用户已经存在。');
        $this->mysql->insert("{$this->module->pre}manager",$arr);
    }
	
	public function sql($val,$fields='uid'){
	   $row = $this->mysql->select_one("select * from {$this->pre}manager where {$fields}='{$val}'");
	   if(is_array($row)){
		 $group = $this->mysql->select_one("select * from {$this->pre}group where groupid='{$row['groupid']}'");
		 $row['groupname'] = $group['groupname'];
		 $row['purviews'] = $group['purviews'];
	   }
	   return $row;
	}
	


	function checkusername($username){
         return is_array($this->sql($username,'username'));
	}
	

	
	function logout($url){
	   $this->drop();
	   location($url);
	}	
	
	
	private function keep($uid,$shell){
		if($this->cookie){
		   $this->putcookie($this->sessionid,$uid,config::get('managertime'));
		   $this->putcookie($this->sessionshell,$shell,config::get('managertime'));
		}else{
		   $_SESSION[$this->sessionid] = $uid;
		   $_SESSION[$this->sessionshell] = $shell;			
		}
	}
	
	function drop(){
  	   $this->dropcookie($this->sessionid);
	   $this->dropcookie($this->sessionshell);
	   $_SESSION[$this->sessionid] = "";
	   $_SESSION[$this->sessionshell] = "";
	}
}
?>