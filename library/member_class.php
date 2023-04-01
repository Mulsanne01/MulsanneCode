<?php
class member_class extends user_class{
	public $sessionid = "uid";
	public $sessionshell = "shell";
	public $repass = "repass";	
	public function check() {
	  $uid = $this->cookie ? $_COOKIE[$this->sessionid] : $_SESSION[$this->sessionid];
	  $shell = $this->cookie ? $_COOKIE[$this->sessionshell] : $_SESSION[$this->sessionshell];
	  if(($uid==''||$shell=='')&&$this->verifyLgoin){
        $this->logout('?mod=member&act=login&url='.base64_encode(rewrite::response(geturl())),'请登陆后进行操作');
		return false;
	  }else{
		$user = is_array($row = $this->sql($uid));
	    $login = $user ? $shell == md5($row['username'].$row['password'].$row['salt']) : false;
		if(!$login&&$this->verifyLgoin){
		  $this->logout('?mod=member&act=login&url='.base64_encode(rewrite::response(geturl())),'请登陆后进行操作');
		  return false; 
		}else{
		  $this->keep($uid,$shell);
		  return $row;
		}
	  }
	}
	public function login($username,$password,$seccode) {
	  if($username=='') $this->logout('?mod=member&act=login&url='.$_GET['url']);
	  if($password=='') $this->logout('?mod=member&act=login&url='.$_GET['url']);
	  if(config::get("mustcode")&&$seccode=='') return '对不起，请输入安全验证码';
	  if(config::get("mustcode")&&$seccode!=$_SESSION['seccode'])  return '对不起，安全验证码不正确';
	  if(isMobile($username)){
	    if(!is_array($row = $this->sql($username,'userphone',"mcheck='1'"))) $this->logout('?mod=member&act=login&url='.$_GET['url']);		
	  }elseif(isEmail($username)){
	    if(!is_array($row = $this->sql($username,'email'))) $this->logout('?mod=member&act=login&url='.$_GET['url']);	
	  }else{
        if(!is_array($row = $this->sql($username,'username'))) return '对不起，用户名或密码不正确';
	  }
	 // if(!$row['canlogin'])  return '对不起，用户名或密码不正确';
	  if($this->password($password,$row[salt]) == $row[password]){//密码是否正确
		$this->keep($row[uid],md5($row[username].$row[password].$row[salt])); 
	    $this->logininfo($row);
		$location = $_GET['url'] ? base64_decode($_GET['url']) : 'member';
		location($location);
	  }else{
  	     return '对不起，用户名或密码不正确';	  
	  }  			
	}
	public function mobile_login($username,$password,$wchat='') {
	   if($username=='') return '您还没有输入用户名';
	   if($password=='') return '请输入您的登陆密码';
	   if(isMobile($username)){
	     if(!is_array($row = $this->sql($username,'userphone',"mcheck='1'"))) return '手机号码不存在或未认证';	
	   }elseif(isEmail($username)){
	     if(!is_array($row = $this->sql($username,'email'))) return '电子邮箱不存在';	
	   }else{
         if(!is_array($row = $this->sql($username,'username'))) return '你输入的用户名不存在';	 
	   }
	   if($this->password($password,$row[salt]) == $row[password]){//密码是否正确
		 $this->keep($row[uid],md5($row[uid].$row[password].$row[salt])); 
	     $this->logininfo($row);
		 if($wchat){
		   $user = $this->sql($wchat,'wechatid');
		   if(is_array($user)){
			 return '该会员已绑定微信';
		   }else{
		     $this->update(array('wechatid'=>$wchat),$row[uid]);
			 return '0';
		   }
		 }else{
		   $location = $_GET['url'] ? base64_decode($_GET['url']) : 'mobile';
		   location($location);
		 }
	   }else{
  	     $this->dropuser();
		 return '对不起，您的密码有误';			  
	   }  			
	} 
	public function autologin($uid) {
	  if(!is_array($row = $this->sql($uid))) $this->module->message('go_back','对不起，验证会员失败','0');	
	  $this->keep($row[uid],md5($row[username].$row[password].$row[salt])); 
	  $this->logininfo($row);
	  location('member');			
	}
	public function ajaxlogin($username,$password,$seccode) {
	  $username = sintrim($username);
	  $password = sintrim($password);
	  $seccode = sintrim($seccode);
	  if($username=='') return '用户名不能为空';
	  if($password=='') return '密码不能为空';
	  if(config::get("mustcode")&&$seccode=='') return '验证码不能为空';
	  if(config::get("mustcode")&&$seccode!=$_SESSION['seccode']) return '验证码不正确';	 
	  if(!is_array($row = $this->sql($username,'username'))) return '该用户不存在';	  
	  if(!$row['status']) return '该用户未通过审核';  
	  if($this->password($password,$row[salt]) == $row[password]){//密码是否正确
		$this->keep($row[uid],md5($row[username].$row[password].$row[salt])); 
	    $this->logininfo($row);
	    return $row;	 
	  }else{
  	    $this->dropuser();
		return '对不起，登录密码有误';			  
	  } 
	}	
	function purview(){
	  $member_purviews = $this->module->member['purviews'];
	  $purviews = explode(',',$member_purviews);
	  if(!$this->verifyLgoin) return true;
	  if(!in_array($this->purview,$purviews)) $this->module->message('go_back','对不起，你没有权限操作！','0');
	}
	function rn_purview($mod,$act,$get){
	  $member_purviews = $this->module->member['purviews'];
	  $purviews = explode(',',$member_purviews);
	  return in_array("{$mod}_{$act}_{$get}",$purviews);
	}
	function gt_purview($act){
	  $purviews = $this->module->member['purviews'];
	  return strstr($purviews,"_{$act}_") ? true : false;
	}
	public function relogin($password){  
     if($password=='') $this->module->message('go_back','请输入您的安全密码','0');
	 if($this->password($password,$this->module->member['salt']) == $this->module->member['repass']){
	   $this->keeprepass($this->module->member['uid']);
	 }else{
	   $this->module->message('go_back','您输入的安全密码有误','0');	
	 }		
	}
	function isrepass(){
		$repass = $this->cookie ? $_COOKIE[$this->repass] : $_SESSION[$this->repass];
		$uid = $this->cookie ? $_COOKIE[$this->sessionid] : $_SESSION[$this->sessionid];
		return $repass!=''&&$uid!=''&&$repass==$uid;
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
	function update($arr,$uid,$fields='uid'){
		$user = $this->sql($uid,$fields);
		if($arr['password']){
		   $salt = $user['salt'];
		   $arr['password'] = $this->password($arr['password'],$salt);
		}else{
		   unset($arr['password']);	
		}		
		if($arr['repass']){
		   $salt = $user['salt'];
		   $arr['repass'] = $this->password($arr['repass'],$salt);
		}else{
		   unset($arr['repass']);	
		}
		unset($arr['username']);
		$this->mysql->update("{$this->module->pre}user",$arr,"uid ='$uid'");
	}
	function insert($arr){
		$arr['salt'] = $this->salt();
        $arr['password'] = $this->password($arr['password'],$arr['salt']);
        $arr['repass'] = $this->password($arr['repass'],$arr['salt']);
		$arr['status'] = $arr['status']=='0' ? 0 : '1';
		$arr['regtime'] = time();
		$arr['regip'] = getip();
		$arr['groupid'] = $arr['groupid'] ? $arr['groupid'] : 1;
		if($this->checkusername($arr['username'])) $this->module->message('go_back','该用户已经存在','0');
        $this->mysql->insert("{$this->module->pre}user",$arr);
		$uid = $this->mysql->insertid();
		return $uid;
	}
	function checkusername($username){
         return is_array($this->sql($username,'username'));
	}
	function checkuserphone($userphone){
		 $value = $this->mysql->select_one("select uid from {$this->module->pre}user where userphone='{$userphone}' and mcheck='1'");
         return is_array($value);
	}
	function checkpassword($password,$pay=''){
		 $checkpassword = $pay=='' ? $this->module->member['password'] : $this->module->member['repass'];
		 return $this->password($password,$this->module->member['salt'])==$checkpassword;
	}		
	function checkemail($email){
		 $value = $this->mysql->select_one("select uid from {$this->module->pre}user where email='{$email}' and echeck='1'");
         return is_array($value);
	}	
	function authemail($email,$uid){
		 $arr = $this->mysql->select_one("select uid from {$this->module->pre}user where email='{$email}' and uid<>'{$uid}'");
         return is_array($arr) ? $arr['username'] : '';
	}	
	function logout($url,$message) {
	    $this->dropuser();
		location($url);
	}
	public function sql($val,$fields='uid',$where=''){
	   $pre = config::get("tablepre");
	   if($where) $where = "and ".$where;
	   $row = $this->mysql->select_one("select * from {$this->module->pre}user where {$fields}='{$val}' {$where}");
	   if(is_array($row)){
		 $usergroup = $this->mysql->select_one("select * from {$this->module->pre}usergroup where groupid='{$row['groupid']}'");
		 $customs = $this->mysql->select_one("select * from {$this->module->pre}customs where uid='{$row['uid']}'");
		 $row['serviceaddress'] = $customs['address'];
		 $row['servicename'] = $customs['name'];
		 $row['renumber'] = $this->mysql->counts("select uid from {$this->pre}user where referee='{$row['username']}'");
		 
		 $row['usergroup'] = $usergroup;
		 $row['groupname'] = $usergroup['groupname'];
		 $row['purviews'] = $usergroup['purviews'];
	   }
	   return $row;
	}	
	public function select_one($sql){
	   $row = $this->mysql->select_one($sql);
	   if(is_array($row)){
		 $usergroup = $this->mysql->select_one("select * from {$this->module->pre}usergroup where groupid='{$row['groupid']}'");
		 $customs = $this->mysql->select_one("select * from {$this->module->pre}customs where uid='{$row['uid']}'");
		 $row['serviceaddress'] = $customs['address'];
		 $row['servicename'] = $customs['name'];
		 $row['renumber'] = $this->mysql->counts("select uid from {$this->pre}user where referee='{$row['username']}'");
		 $row['usergroup'] = $usergroup;
		 $row['groupname'] = $usergroup['groupname'];
		 $row['purviews'] = $usergroup['purviews'];
	   }
	   return $row;
	}	
	public function getarr($sql){
	   $query = $this->mysql->query($sql);
	   while($row=$this->mysql->assoc($query)){
		 $usergroup = $this->mysql->select_one("select * from {$this->module->pre}usergroup where groupid='{$row['groupid']}'");
		 $customs = $this->mysql->select_one("select * from {$this->module->pre}customs where uid='{$row['uid']}'");
		 $row['serviceaddress'] = $customs['address'];
		 $row['servicename'] = $customs['name'];
		 $row['renumber'] = $this->mysql->counts("select uid from {$this->pre}user where referee='{$row['username']}'");
		 $row['usergroup'] = $usergroup;
		 $row['groupname'] = $usergroup['groupname'];
		 $row['purviews'] = $usergroup['purviews'];
		 $user[] = $row;
	   }
	   return $user;
	}	
	function getval($fields,$uid){
       return $this->mysql->value("{$this->module->pre}user",$fields,"uid ='$uid'");
	}
	
	private function keeprepass($uid){
       return $this->cookie ? $this->putcookie($this->repass,$uid,1200) : $_SESSION[$this->repass] = $uid;
	}
	
	private function droprepass(){
	   $this->dropcookie($this->repass);
	   $_SESSION[$this->repass] = "";			
	}
	
	private function keep($uid,$shell){
		if($this->cookie){
		   $this->putcookie($this->sessionid,$uid);
		   $this->putcookie($this->sessionshell,$shell);
		}else{
		   $_SESSION[$this->sessionid] = $uid;
		   $_SESSION[$this->sessionshell] = $shell;			
		}
	}
	
	function dropuser(){
  	   $this->dropcookie($this->sessionid);
	   $this->dropcookie($this->sessionshell);
	   $_SESSION[$this->sessionid] = "";
	   $_SESSION[$this->sessionshell] = "";	
	   $this->droprepass();
	}
}
?>