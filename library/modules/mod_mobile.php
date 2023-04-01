<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_mobile extends module_class{
	function main(){
	   $this->footnav = 'main';
    }	
	function news(){
       $this->footnav = 'main';
	   $this->about = $this->mysql->select_one("select * from {$this->pre}about where myurl='contact'");
    }	
	function contact(){
       $this->footnav = 'main';
	   $this->about = $this->mysql->select_one("select * from {$this->pre}about where myurl='contact'");
    }	
	function about(){
       $this->footnav = 'main';
	   $this->about = $this->mysql->select_one("select * from {$this->pre}about where myurl='aboutus'");
    }		
	function store(){
      $this->footnav = 'user';
	}
	function balance(){
      $this->footnav = 'user';
	}
	function address(){
      $this->footnav = 'user';
	  if(submit()){
        $arr['truename'] = $_POST['truename'];
		$arr['address'] = $_POST['address'];
        $this->user->update($arr,$this->member[uid]);
        $this->message('mobile_user','常用送餐地址修改成功');		
	  }
    }
	function profile(){
      $this->footnav = 'user';
	  if(submit()){
         $arr['truename'] = $_POST['truename'];
         $this->user->update($arr,$this->member[uid]);
         $this->message('mobile_user','会员资料修改成功');		
	  }
    }
	function guestbook(){
       $this->footnav = 'user';
       if(submit()){
		 if($_POST['message']=='') $this->message("go_back",'对不起，请填写反馈内容');
		 $arr['message'] = $_POST['message'];
		 $arr['addtime'] = time();
		 $arr['uid'] = $this->member['uid'];
         $this->mysql->insert("{$this->pre}guestbook",$arr);
		 $this->message('?mod=mobile&act=user',"意见反馈成功，谢谢您的支持！");
	   }       
    }	
	function order(){
	  $this->footnav = 'user';
    }
	function user(){
      $this->footnav = 'user';
    }
    function login(){
	  $this->footnav = 'user';
      if(submit()){
		echo $this->user->mobile_login($_POST['username'],$_POST['password']);
		exit;
	  }
    }	
    function register(){
	  $this->footnav = 'user';
      if(submit()){
        $arr['username'] = sintrim($_POST['username']);
        $arr['password'] = sintrim($_POST['password']);
        if($arr['username']=='') $this->message("go_back",'用户名不能为空','0');
        if(isEmail($arr['username'])) $this->message("go_back","用户名不能使用邮箱",'0');
        if(isFirstNum($arr['username'])) $this->message("go_back","用户名不能数字开头",'0');
        if(strlen($arr['username'])>20) $this->message("go_back","用户名长度不能大于20",'0');
        if(strlen($arr['username'])<4) $this->message("go_back","用户名长度不能小于4",'0');
        if($this->user->checkusername($arr['username'])) $this->message("go_back",'用户名已经存在','0');
        if($arr['password']=='') $this->message("go_back",'登录密码不能为空','0');
        if(strlen($arr['password'])<6) $this->message("go_back",'密码长度不能小于6','0');
        if($_POST['cpassword']=='') $this->message("go_back",'重复密码不能为空','0');
        if($_POST['cpassword']!=$arr['password']) $this->message("go_back",'两次输入密码不一致','0');
        $uid = $this->user->insert($arr);
		$this->user->mobile_login($arr['username'],$arr['password']);
        $this->message('mobile','恭喜你，注册成功');
      }
    }
    function logout(){
      $this->user->logout('mobile_login',"恭喜您，用户注销成功");	
    }
    function scratchcard(){
	  $this->footnav = 'user';
		
    }
    function rotary(){
	  $this->footnav = 'user';
		
    }
	function wchat(){
	   if($_GET['wechat']==1){
		  require_once(PATH."library/wechat.class.php"); 
		  $wechat = new wechat();		  
		  $wechattoken = $this->mysql->select_one("select * from {$this->pre}wechattoken where token='{$wechat->chatarr['fromUsername']}'");
		  if(!is_array($wechattoken)){
			$insert['token'] = $wechat->chatarr['fromUsername'];
			$this->mysql->insert("{$this->pre}wechattoken",$insert);
		  }
		  if($wechat->chatarr['MsgType']=='event'){
			 if($wechat->chatarr['Event']=='subscribe'){
				$type = 'text';
				$arr = "回复下列数字惊喜无限
1:会员卡
2:订餐记录
3:幸运大转盘
4:刮刮卡
5:礼品兑换
6:关于我们"; 
			 }
			 if($wechat->chatarr['Event']=='CLICK'){
				if($wechat->chatarr['EventKey']=='food'){
				  $type = 'news';
				  $arr = array(
				    config::get("sitename").'在线订餐,点击进入开始选择你喜欢的餐品,'.config::get("sitedomain").config::get("siteurl").'images/food.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=food&token='.$wechat->chatarr['base64_from'])
				  );
				}
				if($wechat->chatarr['EventKey']=='aboutus'){
				  $aboutus = $this->mysql->select_one("select * from {$this->pre}about where myurl='aboutus'");
				  $type = 'news';
				  $arr = array(
				    config::get("sitename").'关于我们,'.msubstr(noHtml($aboutus['content']),0,200).','.config::get("sitedomain").config::get("siteurl").'images/aboutus.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=about&token='.$wechat->chatarr['base64_from'])
				  );
				}
				if($wechat->chatarr['EventKey']=='order'){
				  $type = 'news';
				  $arr = array(
				    '您的订餐记录,点击查看您的订餐记录,,'.config::get("sitedomain").Purl('?mod=mobile&act=order&token='.$wechat->chatarr['base64_from'])
				  );
				}
				if($wechat->chatarr['EventKey']=='mycard'){
				  $type = 'news';
				  $arr = array(
				    config::get("sitename").'会员卡,点击进入查看您的会员卡,'.config::get("sitedomain").config::get("siteurl").'images/mycard.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=user&token='.$wechat->chatarr['base64_from'])
				  );
				}
				if($wechat->chatarr['EventKey']=='gift'){
				  $type = 'news';
				  $arr = array(
				    config::get("sitename").'积分换礼品,众多精美礼品等您来换,'.config::get("sitedomain").config::get("siteurl").'images/gift.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=gift&token='.$wechat->chatarr['base64_from'])
				  );
				}
				if($wechat->chatarr['EventKey']=='rotary'){
				  $type = 'news';
				  $arr = array(
				    config::get("sitename").'幸运大转盘,使用会员积分即可参与大转盘抽奖活动，超级大奖等您来拿,'.config::get("sitedomain").config::get("siteurl").'images/rotary.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=rotary&token='.$wechat->chatarr['base64_from'])
				  );
				}
				if($wechat->chatarr['EventKey']=='scratchcard'){
				  $type = 'news';
				  $arr = array(
				    config::get("sitename").'刮刮卡,积分刮刮乐，大奖轻松得,'.config::get("sitedomain").config::get("siteurl").'images/scratchcard.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=scratchcard&token='.$wechat->chatarr['base64_from'])
				  );
				}
			 }
		  }else{
			 if($wechat->chatarr['Content']=='会员卡'||$wechat->chatarr['Content']=='1'){
				$type = 'news';
				$arr = array(
				  config::get("sitename").'会员卡,点击进入查看您的会员卡,'.config::get("sitedomain").config::get("siteurl").'images/mycard.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=user&token='.$wechat->chatarr['base64_from'])
			    );
			 }elseif($wechat->chatarr['Content']=='订餐记录'||$wechat->chatarr['Content']=='2'){
				$type = 'news';
				$arr = array(
				  '您的订餐记录,点击查看您的订餐记录,,'.config::get("sitedomain").Purl('?mod=mobile&act=order&token='.$wechat->chatarr['base64_from'])
				);
			 }elseif($wechat->chatarr['Content']=='大转盘'||$wechat->chatarr['Content']=='幸运大转盘'||$wechat->chatarr['Content']=='3'){
				$type = 'news';
				$arr = array(
				  config::get("sitename").'幸运大转盘,使用会员积分即可参与大转盘抽奖活动，超级大奖等您,'.config::get("sitedomain").config::get("siteurl").'images/rotary.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=rotary&token='.$wechat->chatarr['base64_from'])
				);
			 }elseif($wechat->chatarr['Content']=='刮刮卡'||$wechat->chatarr['Content']=='4'){
				$type = 'news';
				$arr = array(
				  config::get("sitename").'刮刮卡,积分刮刮乐，大奖轻松得,'.config::get("sitedomain").config::get("siteurl").'images/scratchcard.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=scratchcard&token='.$wechat->chatarr['base64_from'])
				);
			 }elseif($wechat->chatarr['Content']=='礼品兑换'||$wechat->chatarr['Content']=='5'){
				$type = 'news';
				$arr = array(
				  config::get("sitename").'积分换礼品,众多精美礼品等您来换,'.config::get("sitedomain").config::get("siteurl").'images/gift.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=gift&token='.$wechat->chatarr['base64_from'])
				);
			 }elseif($wechat->chatarr['Content']=='关于我们'||$wechat->chatarr['Content']=='6'){
				$aboutus = $this->mysql->select_one("select * from {$this->pre}about where myurl='aboutus'");
			    $type = 'news';
				$arr = array(
				  config::get("sitename").'关于我们,'.msubstr(noHtml($aboutus['content']),0,200).','.config::get("sitedomain").config::get("siteurl").'images/aboutus.jpg,'.config::get("sitedomain").Purl('?mod=mobile&act=about&token='.$wechat->chatarr['base64_from'])
				);
			 }else{
				$type = 'text';
				$arr = "回复下列数字惊喜无限
1:会员卡
2:订餐记录
3:幸运大转盘
4:刮刮卡
5:礼品兑换
6:关于我们"; 
			 }
		  }
          $wechat->response($arr,$type);
	   }else{
		  $this->footnav = 'user';
          if(submit()){
			if($_GET['login']){
              $message = $this->user->mobile_login($_POST['username'],$_POST['password'],$_SESSION['token']);
			  if($message=='0'){
			    $this->message(base64_decode($_GET['url']),'登陆并激活微信会员成功');
			  }else{
			    $this->message("go_back",$message,'0');
			  }
			}else{
              $arr['username'] = $_POST['username'];
              $arr['password'] = $_POST['password'];
			  $arr['wechatid'] = $_SESSION['token'];
              if($arr['username']=='') $this->message("go_back",'用户名不能为空','0');
              if(isEmail($arr['username'])) $this->message("go_back","用户名不能使用邮箱",'0');
              if(isFirstNum($arr['username'])) $this->message("go_back","用户名不能数字开头",'0');
              if(strlen($arr['username'])>20) $this->message("go_back","用户名长度不能大于20",'0');
              if(strlen($arr['username'])<4) $this->message("go_back","用户名长度不能小于4",'0');
              if($this->user->checkusername($arr['username'])) $this->message("go_back",'用户名已经存在','0');
              if($arr['password']=='') $this->message("go_back",'登录密码不能为空','0');
              if(strlen($arr['password'])<6) $this->message("go_back",'密码长度不能小于6','0');
              if($_POST['cpassword']=='') $this->message("go_back",'重复密码不能为空','0');
              if($_POST['cpassword']!=$arr['password']) $this->message("go_back",'两次输入密码不一致','0');
              $this->user->insert($arr);
              $this->message('mobile','注册并激活微信会员成功');
			}
	      }
	   }
	}
}
?>