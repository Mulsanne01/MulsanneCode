<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_member extends module_class{
	function main(){
	  $safepwd = $this->user->getarr("select * from {$this->pre}safepwd where uid='{$this->member['uid']}'");
	  if(!is_array($safepwd)){
		$this->message('?mod=member&act=user&type=safepwd','您还未设置密保，请立即设置','0');	  
	  }
	  $this->givemoney($this->member['uid']);	   
      $todaytime = untime(formattime(time(),'Y-m-d'));
      $yestodaytime = untime(formattime(time()-24*3600,'Y-m-d')); 
      $this->todaymoney = $this->getrecords("where uid='{$this->member['uid']}' and addtime='{$todaytime}'");
	  $this->yestodaymoney = $this->getrecords("where uid='{$this->member['uid']}' and addtime='{$yestodaytime}'");
	  $this->allmoney = $this->getrecords("where uid='{$this->member['uid']}'");
	  $query = $this->mysql->query("select * from {$this->pre}news order by id desc limit 5");
	  while($rs=$this->mysql->assoc($query)){
	    $rs['url'] = rewrite::request("?mod=member&act=notice&type=show&id=".$rs['id']);
		$rs['typename'] = $this->mysql->value("{$this->pre}newstype","typename","id='{$rs['typeid']}'");
	    $rs['addtime'] = formattime($rs['addtime']);
		$this->record[] = $rs;
	  }
    }	
	function treeform(){
	  $_GET['type'] = $_GET['type'] ? $_GET['type'] : "referee";
	  
	  if($_GET['type']=='status'){
	  
		if(submit()){
          if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
		    if(!is_array($user = $this->user->sql($_POST['username'],'username',"status='0'"))){
			  $json['error'] = "玩家不存在或已经激活";
		      echo json($json);
		      exit;
			}
	        if($_POST['service']){
	          if(!is_array($service = $this->user->select_one("select * from {$this->pre}user where username='{$_POST['service']}' and service='1'"))){
			    $json['error'] = "报单中心不存在";
		        echo json($json);
		        exit;
		      }
			  $reguser = $service['service'] ? $service['uid'] : '0';
			  $this->mysql->query("update {$this->pre}user set reguser='{$reguser}' where uid='{$user['uid']}'");
	        }
		    $json['error'] = $this->status($user['uid']);
		  }else{
            $json['error'] = "安全密码错误，请检查";
		  }
		  $json['message'] = "账户激活成功";
		  $json['url'] = Purl("?mod=member&act=treeform&type=record");
		  echo json($json);
		  exit;
		}
	  
	  }
	  if($_GET['type']=='fuser'){	

		  
		if($_GET['method']==''){
	    if(submit()){
		  if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']){
             $json['error'] = "安全密码不正确";
	         echo json($json);
	         exit;
		  }
		  if($_POST['uid']){
		     $uid = implode(',',$_POST['uid']);
			 $user = $this->user->getarr("select * from {$this->pre}user where uid in({$uid}) and parentid='{$this->member['uid']}'");
			 foreach($user as $u){
			   $n = floor($u['money']/100);
			   $this->up_money($u['uid'],$n*100,"-","合并到主账户");
			   $this->up_money($this->member['uid'],$n*100,"+","合并自副账户");
			 }
			 $arr['error'] = "0";
			 $arr['message'] = "合并成功";
			 $arr['url'] = Purl("?mod=member&act=user&type=fuser");
	         echo json($arr);
			 exit; 
		  }else{
             $json['error'] = "请选择要合并的账号";
	         echo json($json);
	         exit;
		  }
		}	  
	    if($_GET['re']=='ajax'){
		  if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']){
             $json['error'] = "安全密码不正确";
	         echo json($json);
	         exit;
		  }
		  if($_GET['do']=='hebing'){
			 $u = $this->user->select_one("select * from {$this->pre}user where uid='{$_GET['uid']}' and parentid='{$this->member['uid']}'");
			 if(!is_array($u)){
               $json['error'] = "该副账户不存在";
	           echo json($json);
	           exit; 
			 }
			 if($u['money']<100){
               $json['error'] = "副账户余额不足100";
	           echo json($json);
	           exit; 
			 }
			 $n = floor($u['money']/100);
			 $this->up_money($u['uid'],$n*100,"-","合并到主账户");
			 $this->up_money($this->member['uid'],$n*100,"+","合并自副账户");
			 $arr['error'] = "0";
			 $arr['message'] = "合并成功";
			 $arr['url'] = Purl("?mod=member&act=user&type=fuser");
	         echo json($arr);
			 exit;
		  }
		  if($_GET['do']=='remove'){
			 $this->mysql->query("update {$this->pre}user set parentid='0' where uid='{$_GET['id']}' and parentid='{$this->member['uid']}'");
			 $arr['error'] = 0;
			 $arr['url'] = Purl("?mod=member&act=user&type=fuser");
	         echo json($arr);
			 exit;
		  }
		  if($_GET['do']=='moneycheck'){
			 $user = $this->user->sql($_GET['uid']);
			 if(!is_array($user)){
               $json['error'] = "该账号不存在";
	           echo json($json);
	           exit; 
			 }else{
			   if($user['moneycheck']=='1'){
                 $json['error'] = "该账户正在分红不需要激活";
	             echo json($json);
	             exit;  
			   }else{
				 $money = $user['usergroup']['buymoney']; 
				 if($this->member['money']<$money){
                   $json['error'] = "金币不足激活分红";
	               echo json($json);
	               exit;   
				 }else{
		           $this->up_money($this->member['uid'],$money,"-","激活分红");
				   $moneytime = untime(formattime($user['moneytime'],"Y-m-d 00:00"))+(24*3600);
				   $this->mysql->query("update {$this->pre}user set `moneycheck`='1',maxmoney='0',moneytime='{$moneytime}' where uid ='{$user['uid']}'");
			       $arr['error'] = 0;
			       $arr['url'] = Purl("?mod=member&act=user&type=fuser");
	               echo json($arr);
			       exit; 
				 }
			   } 
			 }
		  }
		  exit;
	    } 		  
	      $where =  "where parentid='{$this->member['uid']}'";
		  if($_GET["time"]&&$_GET["timet"]){
		    $time = untime($_GET["time"]);  
		    $timet = untime($_GET["timet"]." 23:59"); 
		    $where .= " and opentime>='{$time}' and opentime<='{$timet}'";	
		    $this->time_str = $_GET["time"].",".$_GET["timet"];
	      }
	      $this->pagetotal = $this->mysql->counts("select uid from {$this->pre}user $where");
	      $this->pageclass($this->pagetotal);
	      $this->record = $this->user->getarr("select * from {$this->pre}user $where order by uid desc {$this->page->limit}");
		}else{

		  if($_POST['username']){
            if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
			  if($_POST['username']==$this->member['username']){
				$json['error'] = "不能添加自己为副账户";  
			  }else{
				if(!is_array($u = $this->user->sql($_POST['username'],'username'))){
				  $json['error'] = "副账户用户名不存在";
				}else{
				  if($this->user->password($_POST['repass'],$u['salt'])==$u['repass']){
					if($u['parentid']){
				      $json['error'] = "副账户已经绑定过了";
					}else{
				      $this->mysql->query("update {$this->pre}user set `parentid`='{$this->member['uid']}' where uid ='{$u['uid']}'");
					  $json['error'] = "0";
				    }  
				  }else{
				    $json['error'] = "副账户安全密码不正确";
				  }
				}
			  }
		    }else{
              $json['error'] = "主账号安全密码错误";
		    }
			$json['message'] = "副账户绑定成功";
		    $json['url'] = Purl("?mod=member&act=user&type=fuser");
		    echo json($json);
		    exit;
		  }
			
		}
		
		
	  }
	  
	  if($_GET['type']=='referee'){
		$referee = $_GET['referee'] ? $_GET['referee'] : $this->member['username'];
		$referee = $_GET['username'] ? $_GET['username'] : $referee;
  	    $this->myuser = $this->user->sql($referee,'username');		
		if(($this->member['uid']!=$this->myuser['uid'])&&!$_GET['referee']){
		   $__referee = explode(",",$this->myuser['__referee']);
		   if(!in_array($this->member['uid'],$__referee)) $this->message('?mod=member&act=treeform','该会员不存在或非你市场内会员','0');
		}	
	    $this->reuser = $this->user->getarr("select * from {$this->pre}user where referee='{$referee}' order by uid asc");
		if($_GET['referee']){
		  echo json($this->reuser);
		  exit;
		}
	  }
	  if($_GET['type']=='record'){
	    $where =  "where referee='{$this->member['username']}'";
		if($_GET["time"]&&$_GET["timet"]){
		  $time = untime($_GET["time"]);  
		  $timet = untime($_GET["timet"]." 23:59"); 
		  $where .= " and regtime>='{$time}' and regtime<='{$timet}'";	
		  $this->time_str = $_GET["time"].",".$_GET["timet"];
	    }
	    $this->pagetotal = $this->mysql->counts("select uid from {$this->pre}user $where");
	    $this->pageclass($this->pagetotal);
	    $this->record = $this->user->getarr("select * from {$this->pre}user $where order by uid desc limit {$this->page->start},{$this->page->size}");
	  }
    }
	function vocational(){
	  if($_GET['type']=='ajax'){
		if($_GET['do']=='delete'){
		  $json['error'] = 0;
          if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
			 if(is_array($user = $this->user->sql($_GET['uid']))&&$user['status']=='0'){
			   $this->mysql->delete("{$this->pre}user","uid='{$_GET['uid']}'");
			 }else{
			   $json['error'] = '会员已激活，禁止删除'; 
			 }
		  }else{
            $json['error'] = "安全密码错误，请检查";
		  }
	      echo json($json);
		  exit;
		}
        if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
		   $json['error'] = $this->status($_GET['uid']);
		}else{
           $json['error'] = "安全密码错误，请检查";
		}
		$json['url'] = Purl("?mod=member&act=treeform&type=record");
		echo json($json);
		exit;
	  }
	  if($this->member['status']=='0') $this->message('go_back','对不起，未激活会员不能进行操作','0');
	  if($_GET['type']=='register'){
		if($_GET['t1']&&$_GET['t2']&&$_GET['t3']){
			$username = decode($_GET['t1']);
			$password = decode($_GET['t2']);
			$repass = decode($_GET['t3']);
			$user = $this->user->sql($username,"username");
	        if($this->user->password($password,$user['salt'])==$user['password']&&$this->user->password($repass,$user['salt'])==$user['repass']){
			   $this->registeruser['username'] = $username;
			   $this->registeruser['password'] = $password;
			   $this->registeruser['repass'] = $repass;
	        }
		}
		if(submit()){
          if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
		    $json['error'] = $this->verifyinsertuser($_POST);
		  }else{
            $json['error'] = "安全密码错误，请检查";
		  }
		  $json['url'] = Purl("?mod=member&act=vocational&type=register&t1=".encode($_POST['username'])."&t2=".encode($_POST['password'])."&t3=".encode($_POST['_repass']));
		  echo json($json);
		  exit;
		}
		$username = 'c'.rand(11111111,99999999);
		while($this->user->checkusername($username)){
		  $username = 'c'.rand(11111111,99999999);
		}
		$this->autouser =  $username;
	  }
	  if($_GET['type']=='customs'){
	    $this->customs = $this->mysql->select_one("select * from {$this->pre}customs where uid='{$this->member['uid']}'");
	    if($_GET['re']=='ajax'){
		  $json['error'] = '0';
		  if(is_array($this->customs)){
			$json['error'] = '已经是报单中心或正在审核中';
			echo json($json);
			exit; 
		  }
          if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
		    $arr['name'] = $_GET['name'];
			$arr['address'] = $_GET['address'];
            if($arr['name']==''){
			  $json['error'] = '请填写报单中心名称';
			  echo json($json);
			  exit;
	        }
            if($arr['address']==''){
			  $json['error'] = '请填写报单中心所在地方';
			  echo json($json);
			  exit;
	        }
			$arr['addtime'] = time();
			$arr['checked'] = '0';
			$arr['uid'] = $this->member['uid'];
			$this->mysql->insert("{$this->pre}customs",$arr);
			$json['url'] = Purl("?mod=member&act=vocational&type=customs");
          }else{
            $json['error'] = "安全密码错误，请检查";
          }
	      echo json($json);
	      exit;
	    }
	  }
	  if($_GET['type']=='list'){
		 if($this->member['service']) {
	       $where =  "where reguser='{$this->member['uid']}'";
		   if($_GET["time"]&&$_GET["timet"]){
		     $time = untime($_GET["time"]);  
		     $timet = untime($_GET["timet"]." 23:59"); 
		     $where .= " and regtime>='{$time}' and regtime<='{$timet}'";	
		     $this->time_str = $_GET["time"].",".$_GET["timet"];
	       }
	       $this->pagetotal = $this->mysql->counts("select uid from {$this->pre}user $where");
	       $this->pageclass($this->pagetotal);
	       $this->record = $this->user->getarr("select * from {$this->pre}user $where order by uid desc limit {$this->page->start},{$this->page->size}");
		 }else{
		   $this->message('?mod=member&act=vocational&type=customs','你不是报单中心，请先申请。','0');	 
		 }
	  }
	}
	function capital(){
	  if($_GET['type']=='list'){
		$_GET['method'] = $_GET['method'] ? $_GET['method'] : "main";
		if($_GET['method']=='main'){
		   $where = "where uid='{$this->member['uid']}'";
		   if($_GET["time"]&&$_GET["timet"]){
		     $time = untime($_GET["time"]);  
		     $timet = untime($_GET["timet"]." 23:59"); 
		     $where .= " and addtime>='{$time}' and addtime<='{$timet}'";	
		     $this->time_str = $_GET["time"].",".$_GET["timet"];
	       }
		   if($_GET["content"]) $where .= " and content like '{$_GET[content]}%'";	  
		   $this->pagetotal = $this->mysql->counts("select id from {$this->pre}log $where and parentid='0'");
		   $this->pageclass($this->pagetotal);
		   $query = $this->mysql->query("select * from {$this->pre}log $where and parentid='0' order by id desc {$this->page->limit}");
	       while($rs=$this->mysql->assoc($query)){
		     $q = $this->mysql->query("select * from {$this->pre}log where parentid='{$rs['id']}'");
	         while($r=$this->mysql->assoc($q)){
			   $rs[$r['typeid']] = $r;
	         }
		     $rs[$rs['typeid']] = $rs;
	         $this->record[] = $rs;
	       }
           $todaytime = untime(formattime(time(),'Y-m-d'));
           $yestodaytime = untime(formattime(time()-24*3600,'Y-m-d')); 
           $this->todaymoney = $this->getrecords("where uid='{$this->member['uid']}' and addtime='{$todaytime}'");
	       $this->yestodaymoney = $this->getrecords("where uid='{$this->member['uid']}' and addtime='{$yestodaytime}'");
	       $this->allmoney = $this->getrecords("where uid='{$this->member['uid']}'");	  
		}
	    if($_GET['method']=='money'||$_GET['method']=='regmoney'||$_GET['method']=='shopmoney'||$_GET['method']=='balance'){
           switch ($_GET['method']){
             case 'money':
               $typeid = "1";
               break;
             case 'regmoney':
               $typeid = "2";
               break;
             case 'shopmoney':
               $typeid = "3";
               break;
             default:
               $typeid = "4";
           }
		   $where = "where uid='{$this->member['uid']}'";		   
		   $this->t = _time('addtime','and');
		   $where .= $this->t["where"];
		   if($_GET["content"]) $where .= " and content like '{$_GET[content]}%'";	 
	       $this->pagetotal = $this->mysql->counts("select id from {$this->pre}log $where  and typeid='{$typeid}'");
	       $this->pageclass($this->pagetotal);
	       $this->record = $this->mysql->getarr("select * from {$this->pre}log $where and typeid='{$typeid}' order by id desc limit {$this->page->start},{$this->page->size}");
		   
		   
		  $partarr = $this->getchatdate($this->t['timet'],$this->t['time']);
		  for($i=0;$i<=$partarr['step'];$i++){
			$parttime = dateadd($partarr['part'],$i,$partarr['time']);  
			$formattime = formattime(untime($parttime),$partarr['format']);
			$addtime = '"'.$formattime.'"';
			$addkey = untime($formattime);
			if(!in_array($addtime,$categories)){
			  $categories[$addkey] = $addtime;
			}
			$record = $this->getrecords("where FROM_UNIXTIME(addtime,'".$partarr['_format']."')='".$formattime."'");
			$inmoney[$addkey] = $record['inmoney'];
			$outmoney[$addkey] = $record['outmoney'];
		  }
		  $this->categories = implode(',',$categories);
		  $this->inmoney = implode(",",$inmoney);
		  $this->outmoney = implode(",",$outmoney);
		  $this->allmoney = $this->getrecords($where);
	    }	
	  }  
	  if($_GET['type']=='transfer'){
		 if($_GET['money']){
           $json['error'] = '0';
		   $money = $_GET['money'];
		   $repass = $_GET['repass'];
		   $username = $_GET['username'];
		   if(!$_GET['repass']){
		     $json['error'] = "请输入安全密码确认转账";
			 echo json($json);
			 exit; 
		   }
		   if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']&&$json['error']=='0'){
	         $json['error'] = "安全密码错误，请检查";
			 echo json($json);
			 exit; 
		   }
		   if($_GET['checkcode']==''){
	         $json['error'] = "请输入手机验证码";
			 echo json($json);
			 exit; 
		   }
		   if($this->member['msalt']!=$_GET['checkcode']){
	         $json['error'] = "手机验证码不正确";
			 echo json($json);
			 exit; 
		   }
	       if(isMobile($username)){
	         if(!is_array($touser = $this->user->sql($username,'userphone',"mcheck='1'"))){
			   $json['error'] = '手机号码不存在或未认证'; 
			   echo json($json);
			   exit; 
			 }
	       }elseif(isEmail($username)){
	         if(!is_array($touser = $this->user->sql($username,'email'))){
		  	   $json['error'] = '电子邮箱不存在'; 
			   echo json($json);
			   exit;
			 }	
	       }else{
             if(!is_array($touser = $this->user->sql($username,'username'))){
			   $json['error'] = '你输入的用户名不存在'; 
			   echo json($json);
			   exit; 
			 }
	       }
		   if(!$_GET['repass']) $json['error'] = "请输入安全密码确认转账";
		   if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']&&$json['error']=='0'){
	         $json['error'] = "安全密码错误，请检查";
		   }
		   if($money<0&&$json['error']=='0') $json['error'] = '转账金额不能小于0';  
		   if($this->member['money']<$money&&$json['error']=='0') $json['error'] = '转账超出账户余额';
		   if($json['error']=='0'){
			 $this->up_money($this->member['uid'],$money,"-","转账给".$touser['username']);
			 $this->records($this->member['uid'],'otherout',$money);
		     $this->up_money($touser['uid'],$money,"+",$this->member['username']."转账给你");
			 $this->records($touser['uid'],'otherin',$money);
			 $json['url'] = Purl("?mod=member&act=capital&type=list&method=money");
		     $arr['mcheck'] = 1;
			 $arr['mtime'] = 1;
			 $arr['msalt'] = '';
			 $arr['newmsalt'] = '';
		     $this->user->update($arr,$this->member[uid]);
		   }
		   echo json($json);
		   exit;
		 }
	  }	  
	  if($_GET['type']=='payment'){
	     if($_GET['re']=='ajax'){
		   if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']){
             $json['error'] = "安全密码不正确";
	         echo json($json);
	         exit;
		   }
           $order = $this->mysql->select_one("select * from {$this->pre}payorder where uid='{$this->member['uid']}' and checked='0' and id='{$_GET['id']}'");
		   if(is_array($order)){
			 $this->mysql->query("update {$this->pre}payorder set checked='1' where id='{$order['id']}'");
			 $arr['error'] = 0;
			 $arr['message'] = paycheck('1');
	         echo json($arr);
			 exit;
		   }else{
             $json['error'] = "获取订单失败";
	         echo json($json);
	         exit;  
		   }
	 	 }
		 if($_GET['method']==''){
		   $this->add = $this->mysql->select_one("select * from {$this->pre}money where id='1'");
		   $this->add['gmoney'] = $this->add['allmoney']+$this->add['money']+$this->add['paymoney'];
           if(submit()){
		     if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']){
                $json['error'] = "安全密码不正确";
	            echo json($json);
	            exit;
		     }
			 if(!(formattime(time(),'H')>=$this->paytime[0]&&formattime(time(),'H')<$this->paytime[1])){
                $json['error'] = "目前不是抢购时间";
	            echo json($json);
	            exit;
			 }
			 
			 if($this->add['allmoney']<1){
                $json['error'] = "金币已经完全卖光";
	            echo json($json);
	            exit;
			 }
			 
			 if(!is_numeric($_POST['money'])){
                $json['error'] = "抢购金额必须是数字";
	            echo json($json);
	            exit;
			 }
			 if($_POST['money']<100){
                $json['error'] = "抢购金额必须100以上";
	            echo json($json);
	            exit;
			 }
			 if($_POST['money']%100!=0){
                $json['error'] = "抢购金额必须是整百";
	            echo json($json);
	            exit;
			 }
			 $order = $this->mysql->select_one("select * from {$this->pre}payorder where uid='{$this->member['uid']}' and checked='0'");
			 if(is_array($order)){
			   $json['error'] = "有未汇款订单，不能再次抢购";
	           echo json($json);
	           exit;
		     }else{
		       $arr['orderid'] = makeorderid();
	           $arr['total_fee'] = $_POST['money'];
		       $arr['checked'] = 0;
		       $arr['uid'] = $this->member['uid'];
		       $arr['addtime'] = time();
		       $arr['paytype'] = "银行汇款";
		       $this->mysql->insert("{$this->pre}payorder",$arr);
			   $this->mysql->query("update {$this->pre}money set allmoney=allmoney-'{$_POST['money']}',paymoney=paymoney+'{$_POST['money']}' where id='1'");
			   $json['url'] = Purl("?mod=member&act=capital&type=payment&method=record");
               $json['message'] = "抢购成功，请尽快汇款";
			   $json['error'] = "0";
	           echo json($json);
	           exit;
			 }
		   }
		 }else{
		   $where = "where uid='{$this->member['uid']}'";
		   if($_GET["time"]&&$_GET["timet"]){
		     $time = untime($_GET["time"]);  
		     $timet = untime($_GET["timet"]." 23:59"); 
		     $where .= " and addtime>='{$time}' and addtime<='{$timet}'";	
		     $this->time_str = $_GET["time"].",".$_GET["timet"];
	       }
		   if($_GET["orderid"]) $where .= " and orderid like '{$_GET[orderid]}%'";
	       $this->pagetotal = $this->mysql->counts("select id from {$this->pre}payorder $where");
	       $this->pageclass($this->pagetotal);
	       $this->record = $this->mysql->getarr("select * from {$this->pre}payorder $where order by id desc limit {$this->page->start},{$this->page->size}");
		   $this->yespay = formatnum($this->mysql->sum("{$this->pre}payorder","total_fee","{$where} and checked='1'"));
		   $this->nopay = formatnum($this->mysql->sum("{$this->pre}payorder","total_fee","{$where} and checked='0'"));
	       $query = $this->mysql->query("select * from {$this->pre}payorder $where group by FROM_UNIXTIME(addtime,'%Y-%m-%d') order by addtime asc");
	       while($rs=$this->mysql->assoc($query)){
			 $addtime = '"'.formattime($rs['addtime'],'Y-m-d').'"';
			 $addkey = untime(formattime($rs['addtime'],'Y-m-d 00:00'));
			 if(!in_array($addtime,$categories)){
			   $categories[$addkey] = $addtime;
			   $buymoney[$addkey] = "0.00";
			   $outmoney[$addkey] = "0.00";
			 }
			 $wheretime = "and FROM_UNIXTIME(addtime,'%Y-%m-%d')='".formattime($rs['addtime'],'Y-m-d')."'";
			 $yespaymoney[$addkey] = formatnum($this->mysql->sum("{$this->pre}payorder","total_fee","{$where} and checked='1' {$wheretime}"));
			 $nopaymoney[$addkey] = formatnum($this->mysql->sum("{$this->pre}payorder","total_fee","{$where} and checked='0' {$wheretime}"));
	       }
		   $this->categories = implode(',',$categories);
		   $this->yespaymoney = implode(",",$yespaymoney);
		   $this->nopaymoney = implode(",",$nopaymoney);
		 }        
	  }
	  if($_GET['type']=='myatm'){
		 if($_GET['method']==''){
		   if($_GET['money']&&$_GET['bankid']){
			  $json['error'] = '0';
		      $money = $_GET['money'];
			  $repass = $_GET['repass'];
		      if(!$_GET['repass']){
		        $json['error'] = "请输入安全密码确认提现";
		      }
		      if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']&&$json['error']=='0'){
	            $json['error'] = "安全密码错误，请检查";
		      }
			  if(($money%config::get('atmmoney')!=0||$money<config::get('atmmoney'))&&$json['error']=='0'){
			    $json['error'] = '提现金额不能小于'+config::get('atmmoney')+'并且是'+config::get('atmmoney')+'的倍数';  
			  }
			  if($this->member['money']<$money&&$json['error']=='0'){
			    $json['error'] = '您提现金额超出账户余额';  
			  }
			  $bank = $this->mysql->one("select * from {$this->pre}atmbank where id='{$_GET['bankid']}' and uid='{$this->member[uid]}'");
			  if(!is_array($bank)&&$json['error']=='0'){
				$json['error'] = '请正确选择提现银行！';
			  }
			  if($json['error']=='0'){
		        $this->up_money($this->member['uid'],$money,"-","申请提现");	
		        $arr['uid'] = $this->member['uid'];
				$arr['orderid'] = makeorderid();
		        $arr['lognum'] = $money;
		        $arr['bankname'] = $bank['bankadd'].$bank['bankname'];
		        $arr['bankcard'] = $bank['bankcard'];
				$arr['truename'] = $bank['truename'];
		        $arr['addtime'] = time();
		        $arr['checked'] = 0;
                $this->mysql->insert("{$this->pre}atmlog",$arr); 
			    $this->record('atmmoney',$money);
			    $this->records($arr['uid'],'atmmoney',$money);
				$json['url'] = Purl("?mod=member&act=capital&type=myatm&method=record");
			  }
			  echo json($json);
			  exit;
		   }
           $this->bank = $this->mysql->getarr("select * from {$this->pre}atmbank where uid='{$this->member[uid]}'");
		 }else{
		   $where = "where uid='{$this->member['uid']}'";
		   if($_GET["time"]&&$_GET["timet"]){
		     $time = untime($_GET["time"]);  
		     $timet = untime($_GET["timet"]." 23:59"); 
		     $where .= " and addtime>='{$time}' and addtime<='{$timet}'";	
		     $this->time_str = $_GET["time"].",".$_GET["timet"];
	       }
		   if($_GET["orderid"]) $where .= " and orderid like '{$_GET[orderid]}%'";
	       $this->pagetotal = $this->mysql->counts("select id from {$this->pre}atmlog $where");
	       $this->pageclass($this->pagetotal);
	       $this->record = $this->mysql->getarr("select * from {$this->pre}atmlog $where order by id desc limit {$this->page->start},{$this->page->size}");
		   $this->yespay = formatnum($this->mysql->sum("{$this->pre}atmlog","lognum","{$where} and checked='1'"));
		   $this->nopay = formatnum($this->mysql->sum("{$this->pre}atmlog","lognum","{$where} and checked='0'"));
	       $query = $this->mysql->query("select * from {$this->pre}atmlog $where group by FROM_UNIXTIME(addtime,'%Y-%m-%d') order by addtime asc");
	       while($rs=$this->mysql->assoc($query)){
			 $addtime = '"'.formattime($rs['addtime'],'Y-m-d').'"';
			 $addkey = untime(formattime($rs['addtime'],'Y-m-d 00:00'));
			 if(!in_array($addtime,$categories)){
			   $categories[$addkey] = $addtime;
			   $buymoney[$addkey] = "0.00";
			   $outmoney[$addkey] = "0.00";
			 }
			 $wheretime = "and FROM_UNIXTIME(addtime,'%Y-%m-%d')='".formattime($rs['addtime'],'Y-m-d')."'";
			 $yespaymoney[$addkey] = formatnum($this->mysql->sum("{$this->pre}atmlog","lognum","{$where} and checked='1' {$wheretime}"));
			 $nopaymoney[$addkey] = formatnum($this->mysql->sum("{$this->pre}atmlog","lognum","{$where} and checked='0' {$wheretime}"));
	       }
		   $this->categories = implode(',',$categories);
		   $this->yespaymoney = implode(",",$yespaymoney);
		   $this->nopaymoney = implode(",",$nopaymoney);
		 }        
	  }
    }	
	function goods(){
	  if($_GET['type']=='order'){
	    if($_GET['id']){
	  	  $this->order = $this->mysql->select_one("select * from {$this->pre}order where id='{$_GET['id']}' order by id desc limit 1");
		  if(!is_array($this->order)) location("?mod=member&act=goods&type=order");
		  $this->order['goods'] = unserialize($this->order['goods']);
		  $this->order['delivery'] = unserialize($this->order['delivery']);
	    }else{
	      $where = "where uid='{$this->member['uid']}'";
	      if($_GET['method']){
		    $checked = '0';
		    if($_GET['method']=='yespay') $checked = '1';
		    if($_GET['method']=='yesdeal') $checked = '2';
		    if($_GET['method']=='backnow') $checked = '3';
		    if($_GET['method']=='backed') $checked = '4';
		    if($_GET['method']=='yessend') $checked = '5';
		    $where .= " and checked='{$checked}'";
	      }
		  if($_GET['orderid']) $where .= " and orderid like '{$_GET['orderid']}%'";
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];	
	      $this->pagetotal = $this->mysql->counts("select id from {$this->pre}order $where");
	      $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}order $where order by id desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
		    $rs['goods'] = unserialize($rs['goods']);
			$rs['addtime'] = formattime($rs['addtime']);
	        $this->order[] = $rs;
	      }
	    }
	  }
	  if($_GET['type']=='list'){
		if($_GET['re']=='ajax'){
		  if($_GET['do']=='remove'){
		    $this->mysql->delete("{$this->pre}delivery","id='{$_GET['id']}' and uid='{$this->member['uid']}'");
		    $json['error'] = 0;
	        echo json($json);
		    exit;
		  }
		}
		if(submit()){
          if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
			$json['error'] = $this->goodsorder($_POST);
			$json['message'] = '订单提交成功，请付款';
			if(is_numeric($json['error'])){				
			  $json['url'] = Purl('?mod=member&act=goods&type=order&id='.$json['error']); 
			  $json['error'] = '0';
			}
		  }else{
            $json['error'] = "安全密码错误，请检查";
		  }
		  echo json($json);		
		  exit;	
		}
		$this->delivery = $this->mysql->getarr("select * from {$this->pre}delivery where uid='{$this->member['uid']}'");
	    $this->pagetotal = $this->mysql->counts("select goods_id from {$this->pre}goods $where");
	    $this->pageclass($this->pagetotal);
	    $this->record = $this->sqlgoods("select * from {$this->pre}goods $where order by goods_id desc {$this->page->limit}");
	  }
	}
	function user(){
	  	
	  if($_GET['type']=='safepwd'){
	    $this->safepwd = $this->user->getarr("select * from {$this->pre}safepwd where uid='{$this->member['uid']}' order by id desc");
		$this->safepwdyes = is_array($this->safepwd) ? $_SESSION['safepwdyes'] : true;
		if(!$this->safepwdyes){
		  if(submit()){
		    $i = 1;
		    foreach($this->safepwd as $val){
			  if($val['answer']!=$_POST['a'.$i]){
			    $this->message('?mod=member&act=user&type=safepwd','对不起，密保安全答案有误');		
		  	  }
			  $i++;   
		    }
		    $_SESSION['safepwdyes'] = true;
			location('?mod=member&act=user&type=safepwd');
		  }
		}else{
		  if(submit()){
            if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
			  $this->mysql->query("delete from {$this->pre}safepwd where uid='{$this->member['uid']}'");
			  for($i=1;$i<4;$i++){
			    $arr['uid'] = $this->member['uid'];
			    $arr['question'] = $_POST['q'.$i];
			    $arr['answer'] = $_POST['a'.$i];
			    $this->mysql->insert("{$this->pre}safepwd",$arr);
			    $json['error'] = "0";
		  	  }
			  $_SESSION['safepwdyes'] = false;
		    }else{
              $json['error'] = "安全密码错误，请检查";
		    }
		    $json['message'] = "设置密保成功";
		    $json['url'] = Purl("?mod=member");
		    echo json($json);
		    exit;
		  }
		
		  $this->safeq = '[<><请选择安全问题...>][<您的生日是什么时间？><您的生日是什么时间？>][<您的爱人叫什么名字？><您的爱人叫什么名字？>][<您最喜欢的是什么？><您最喜欢的是什么？>][<您的父亲的姓名是什么？><您的父亲的姓名是什么？>][<您的母亲的姓名是什么？><您的母亲的姓名是什么？>][<您的小学名称叫什么名字？><您的小学名称叫什么名字？>]';
		}
		  
	  }

		
	  if(submit()){
		$this->submit = true;
	    if($_GET['type']=='profile'){
          $arr['truename'] = $_POST['truename'];
		  if($arr['truename']=='') $this->message('member_user','玩家姓名不能为空','0');
          $this->user->update($arr,$this->member[uid]);
          $this->message('member_user','基本信息修改成功','1');		
	    }
	    if($_GET['type']=='password'){
		  if($_GET['method']==''){
		     $arr['password'] = $_POST['password'];
		     if($_POST['oldpassword']=='') $this->message('go_back','对不起，请输入原始密码','0'); 
		     if(!$this->user->checkpassword($_POST['oldpassword'])) $this->message('go_back','对不起，原始密码不正确','0'); 
	 	     if($arr['password']=='') $this->message('go_back','对不起，请输入新的密码','0'); 
		     if($arr['password']!=$_POST['cpassword']) $this->message('go_back','对不起，两次输入密码不一致','0'); 
		     $this->user->update($arr,$this->member['uid']);
			 $this->user->dropuser();
		     $this->message('member_login','密码已修改，请重新登录');
		  }
		  if($_GET['method']=='paypasswd'){
		     $arr['repass'] = $_POST['password'];
		     if($_POST['oldpassword']=='') $this->message('go_back','对不起，请输入原始安全密码','0'); 
		     if(!$this->user->checkpassword($_POST['oldpassword'],1)) $this->message('go_back','对不起，原始安全密码不正确','0'); 
	 	     if($arr['repass']=='') $this->message('go_back','对不起，请输入新的安全密码','0'); 
		     if($arr['repass']!=$_POST['cpassword']) $this->message('go_back','对不起，两次输入密码不一致','0'); 
		     $this->user->update($arr,$this->member[uid]);
		     $this->message('member_user','恭喜您，安全密码修改成功');
 		  }  
	    }
	    if($_GET['type']=='authphone'){
		  if($this->member['mcheck']){
		    if($_POST['checkcode']=='') $this->message("go_back",'对不起，请输入原手机验证码。','0');
			if($_POST['phonecode']=='') $this->message("go_back",'对不起，请输入新手机验证码。','0');
            if($this->member['msalt']!=$_POST['checkcode']) $this->message("go_back",'对不起，原手机验证码有误。','0');
			if($this->member['newmsalt']!=$_POST['phonecode']) $this->message("go_back",'对不起，新手机验证码有误。','0');
		    if($this->member['newphone']!=$_POST['newphone']) $this->message("go_back",'对不起，数据出错。','0');
		    $arr['userphone'] = $_POST['newphone'];
			$arr['newphone'] = '';
			$arr['mtime'] = 1;
			$arr['msalt'] = '';
			$arr['newmsalt'] = '';
		    $this->user->update($arr,$this->member[uid]);
		    $this->message("?mod=member&act=user&type=authphone",'恭喜你，手机修改绑定成功');
		  }else{
		    if($_POST['checkcode']=='') $this->message("go_back",'对不起，请输入验证码。','0');
            if($this->member['msalt']!=$_POST['checkcode']) $this->message("go_back",'对不起，你输入的验证码有误。','0');
		    if($this->member['userphone']!=$_POST['userphone']) $this->message("go_back",'对不起，数据出错。','0');
		    $arr['mcheck'] = 1;
			$arr['mtime'] = 1;
			$arr['msalt'] = '';
			$arr['newmsalt'] = '';
		    $this->user->update($arr,$this->member[uid]);
		    $this->message("?mod=member&act=user&type=authphone",'恭喜你，手机绑定成功');
		  }
	    }
	    if($_GET['type']=='authemail'){
			if($this->member['echeck']) $this->message('go_back','已经完成认证，请勿重复认证！','0');
		    $salt = $this->user->salt();
			$this->user->update(array("email"=>$_POST['email']),$this->member['uid']);
			$arr['email'] = $_POST['email'] ? $_POST['email'] : $this->member['email'];
		    $arr['authemail'] = $this->user->password($salt,$this->member['salt']);
			if($this->user->checkemail($arr['email'])) $this->message('go_back','该邮箱已绑定，请更换其他邮箱！','0');				
            $this->mysql->update("{$this->pre}user",$arr,"uid='{$this->member[uid]}'");
		    $authemailurl = config::get("sitedomain").Purl("?mod=member&act=authemail&uid={$this->member[uid]}&salt={$salt}");
		    $this->emailurl = 'http://mail.'.substr($arr['email'],strpos($arr['email'],'@')+1);
	        $subject = config::get('sitename').'邮箱认证';		
		    $body = "<div style='line-height:1.5;font-size:14px;margin-bottom:15px;color:#4d4d4d;'>";
		    $body .= "<strong style='display:block;margin-bottom:15px;'>";
		    $body .= "亲爱的会员：{$this->member['username']} 您好！</strong><p>您已于 ".formattime(time())." 进行邮箱认证操作。</p></div>";
		    $body .= "<div style='margin-bottom:20px;'><strong style='display:block;margin-bottom:20px;font-size:14px;'>";
		    $body .= "<a target='_blank' style='color:#f60;' href='{$authemailurl}'>点此进行认证</a></strong>";
		    $body .= "<p style='color:#666;'><small style='display:block;font-size:12px;margin-bottom:5px;'>";
		    $body .= "如果上述文字点击无效，请把下面网页地址复制到浏览器地址栏中打开：  </small><span style='color:#666;'>";
		    $body .= "<a target='_blank' href='{$authemailurl}'> {$authemailurl} </a></span></p></div>";		
	        sendmail($arr['email'],$subject,$body,$this->member['username']); 
	    }		
	  }

	  
	  
    }
	
	
	
	function trading(){
	  if($_GET['type']=='ajax'){
		$json['error'] = '0';
        if($this->user->password($_GET['repass'],$this->member['salt'])==$this->member['repass']){
		   $trading = $this->mysql->select_one("select * from {$this->pre}trading where id='{$_GET['id']}' and checked<3");
		   if(is_array($trading)){
			 if($trading['checked']=='1'){
				if($this->member['uid']==$trading['uid']){					
					$json['error'] = "不能购买自己的挂单";
				}else{
					$t['bid'] = $this->member['uid'];
					$t['buytime'] = time();
					$t['checked'] = '2';
					$this->mysql->update("{$this->pre}trading",$t,"id='{$trading['id']}'");
					$json['html'] = tradcheck($t['checked']);
				}			
				
			 }else{
				if($this->member['uid']==$trading['uid']){					
					$t['checked'] = '3';
					$this->mysql->update("{$this->pre}trading",$t,"id='{$trading['id']}'");
					$this->up_money($trading['bid'],$trading['money']*0.95,"+","购买货币");
					$json['html'] = btradcheck($t['checked']);
				}else{
				  $json['error'] = "该挂单不是您的";
				}	 
			 }
		   }else{
		     $json['error'] = "该挂单不存在";
		   }
		}else{
           $json['error'] = "安全密码错误，请检查";
		}
		echo json($json);
		exit;
	  }
	  if($_GET['type']=='list'){
		if($_GET['method']=='record'){
		  $where = "where bid='{$this->member['uid']}'";
		  if($_GET["time"]&&$_GET["timet"]){
		    $time = untime($_GET["time"]);  
		    $timet = untime($_GET["timet"]." 23:59"); 
		    $where .= " and addtime>='{$time}' and addtime<='{$timet}'";	
		    $this->time_str = $_GET["time"].",".$_GET["timet"];
	      }
		  $this->pagetotal = $this->mysql->counts("select id from {$this->pre}trading $where and checked='2'");
		  $this->pageclass($this->pagetotal);
		  $query = $this->mysql->query("select * from {$this->pre}trading $where and checked='2' order by id desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
		    $rs['user'] = $this->user->sql($rs['uid']);
	        $this->record[] = $rs;
	      }	
	    }else{
		  $where = "where uid>'0'";
		  if($_GET["time"]&&$_GET["timet"]){
		    $time = untime($_GET["time"]);  
		    $timet = untime($_GET["timet"]." 23:59"); 
		    $where .= " and addtime>='{$time}' and addtime<='{$timet}'";	
		    $this->time_str = $_GET["time"].",".$_GET["timet"];
	      }
		  $this->pagetotal = $this->mysql->counts("select id from {$this->pre}trading $where and checked='1'");
		  $this->pageclass($this->pagetotal);
		  $query = $this->mysql->query("select * from {$this->pre}trading $where and checked='1' order by id desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
		    $rs['user'] = $this->user->sql($rs['uid']);
	        $this->record[] = $rs;
	      }	
		}
  	
	  }
	  if($_GET['type']=='addorder'){
		 if($_GET['method']==''){
		   if($_GET['money']){
			  $json['error'] = '0';
		      $money = $_GET['money'];
			  $repass = $_GET['repass'];
		      if(!$_GET['repass']){
		        $json['error'] = "请输入安全密码确认挂单";
		      }
		      if($this->user->password($_GET['repass'],$this->member['salt'])!=$this->member['repass']&&$json['error']=='0'){
	            $json['error'] = "安全密码错误，请检查";
		      }
			  if($this->member['money']<$money&&$json['error']=='0'){
			    $json['error'] = '您挂单金额超出账户余额';  
			  }
			  if($json['error']=='0'){
		        $this->up_money($this->member['uid'],$money,"-","挂单寄售");	
		        $arr['uid'] = $this->member['uid'];
		        $arr['money'] = $money;
		        $arr['bankname'] = $bank['bankadd'].$bank['bankname'];
		        $arr['bankcard'] = $bank['bankcard'];
				$arr['truename'] = $bank['truename'];
		        $arr['addtime'] = time();
                $this->mysql->insert("{$this->pre}trading",$arr); 
				$json['url'] = Purl("?mod=member&act=trading&type=addorder&method=record");
			  }
			  echo json($json);
			  exit;
		   }
		   $this->bank = $this->mysql->getarr("select * from {$this->pre}atmbank where uid='{$this->member[uid]}'");
		 }else{

		     $where = "where uid='{$this->member['uid']}'";
		     if($_GET["time"]&&$_GET["timet"]){
		       $time = untime($_GET["time"]);  
		       $timet = untime($_GET["timet"]." 23:59"); 
		       $where .= " and addtime>='{$time}' and addtime<='{$timet}'";	
		       $this->time_str = $_GET["time"].",".$_GET["timet"];
	         }
	         $this->pagetotal = $this->mysql->counts("select id from {$this->pre}trading $where");
	         $this->pageclass($this->pagetotal);
	         $this->record = $this->mysql->getarr("select * from {$this->pre}trading $where order by id desc  {$this->page->limit}");  
		   }
       
	  }	  
 

    }	
	
	
	
	
	function authemail(){
	  $uid = $_GET['uid'];
	  $salt = $_GET['salt'];
	  $user = $this->mysql->select_one("select * from {$this->pre}user where uid='{$uid}'");
	  $authemail = $this->user->password($salt,$user['salt']);
	  if($authemail!=$user['authemail']||$user['echeck']=='1') $this->message("?mod=member&act=user&type=authemail",'你的认证地址已经失效','0');
	  $arr['authemail'] = '';
      $arr['echeck'] = 1;
      $this->mysql->update("{$this->pre}user",$arr,"uid='{$user[uid]}'");
	  $this->message('?mod=member&act=user&type=authemail','恭喜你，邮箱认证成功');
	}
    function login(){
      if(is_array($this->member)) location('member');
      if(submit()){
		 $this->message = $this->user->login($_POST['username'],$_POST['password'],$_POST['seccode']);
	  }
    }	
    function logout(){
      $this->user->logout('member_login',"恭喜您，用户注销成功");	
    }
    function forgotpassword(){
      if(is_array($this->member)) location('member');
      $this->safeq = '[<><请选择安全问题...>][<您的生日是什么时间？><您的生日是什么时间？>][<您的爱人叫什么名字？><您的爱人叫什么名字？>][<您最喜欢的是什么？><您最喜欢的是什么？>][<您的父亲的姓名是什么？><您的父亲的姓名是什么？>][<您的母亲的姓名是什么？><您的母亲的姓名是什么？>][<您的小学名称叫什么名字？><您的小学名称叫什么名字？>]';
      if(submit()){
		$user = $this->user->sql($_POST['username'],"username");
		for($i=1;$i<4;$i++){
		  $safepwd = $this->user->select_one("select * from {$this->pre}safepwd where uid='{$user['uid']}' and question='".$_POST['q'.$i]."' limit 1");
		  if($safepwd['answer']!=$_POST['a'.$i]){
			$this->message('go_back','对不起，密保安全答案有误');		
		  }
		}
        $arr['password'] = $this->user->password($_POST['password'],$user['salt']);
		$arr['repass'] = $this->user->password($_POST['repass'],$user['salt']);
        $this->mysql->update("{$this->pre}user",$arr,"uid='{$user[uid]}'");
		$this->message('member_login','密码找回成功','1');
      }
    }	
	function resetpassword(){
      if(is_array($this->member)) location('member');
      $uid = $_GET['uid'];
      $salt = $_GET['salt'];
      $user = $this->mysql->select_one("select * from {$this->pre}user where uid='{$uid}'");
      $this->username = $user['username'];
      $forgotpassword = $this->user->password($salt,$user['salt']);
      if($forgotpassword!=$user['forgotpassword']) location('member_forgotpassword');
      if(submit()){
        $arr['forgotpassword'] = '';
        $arr['password'] = $this->user->password($_POST['password'],$user['salt']);
        $this->mysql->update("{$this->pre}user",$arr,"uid='{$user[uid]}'");
        $this->message('member_login','密码重置成功');
      }
    }
    function bankcard(){
	  $id = $_GET['id'];
	  if($_GET['do']=='del'){
		$this->mysql->delete("{$this->pre}atmbank","id='{$_GET['id']}' and uid='{$this->member['uid']}'");
		$arr['error'] = '0';
		echo json($arr);
		exit;
	  }elseif($_GET['do']=='list'){
		$query = $this->mysql->query("select * from {$this->pre}atmbank where uid='{$this->member['uid']}'");
	    while($rs=$this->mysql->assoc($query)){
		  $rs['bankimages'] = bankimages($rs['bankname']);
		  $arr[] = $rs;
	    }
		echo json($arr);
		exit;
	  }else{
        if(submit()){
		  $arr['truename'] = sintrim($_POST['truename']);
		  $arr['bankadd'] = sintrim($_POST['bankadd']);
		  $arr['bankname'] = sintrim($_POST['bankname']);
		  $arr['bankcard'] = sintrim($_POST['bankcard']);
		  $arr['uid'] = $this->member['uid'];
		  if($id){
            $this->mysql->update("{$this->pre}atmbank",$arr,"id='$id'"); 
		  }else{
            $this->mysql->insert("{$this->pre}atmbank",$arr); 
		  }
		  $this->js("parent.pagereload()");
		  exit;
	    } 
        $this->bank = $this->mysql->select_one("select * from {$this->pre}atmbank where uid='{$this->member['uid']}' and id='{$id}'");
	    if(!is_array($this->bank)&&$id) $this->message('go_back','对不起，该银行信息不存在','0');	
	  }
    }
	function imessage(){
	   $_GET['method'] = $_GET['method'] ? $_GET['method'] : 'sendfrom';
	   if($_GET['method']=='sendfrom'){
		  $where = "where uid='{$this->member['uid']}' and type='2' and isdel='0'";
		  if($_GET['content']) $where .= " and content like '%{$_GET['content']}%'";
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];	  
	      $this->pagetotal = $this->mysql->counts("select id from {$this->pre}message $where");
	      $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}message $where order by checked asc,id desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
			$rs['ico'] = $rs['checked'] ? '1' : '0';
			$rs['checked'] = $rs['checked']=='0' ? '未读' : '已读';
			$rs['addtime'] = formattime($rs['addtime']);
			$this->record[] = $rs;
	      }
	   }
	   if($_GET['method']=='sendto'){
		  $where = "where uid='{$this->member['uid']}' and type='1' and isdel='0'";
		  if($_GET['content']) $where .= " and content like '%{$_GET['content']}%'";
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];	  
	      $this->pagetotal = $this->mysql->counts("select id from {$this->pre}message $where");
	      $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}message $where order by checked asc,id desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
			$rs['ico'] = 'from';
			$rs['checked'] = $rs['checked']=='0' ? '未读' : '已读';
			$rs['addtime'] = formattime($rs['addtime']);
			$this->record[] = $rs;
	      }
	   }
	   if($_GET['method']=='send'){
          if(submit()){
		    $arr['title'] = $_POST['title'];
		    $arr['content'] = $_POST['content'];
			if($arr['title']=='') $this->message('go_back',"请填写信件主题");
			if($arr['content']=='') $this->message('go_back',"请填写信件内容");
		    $arr['uid'] = $this->member['uid'];
		    $arr['type'] = '1';
		    $arr['addtime'] = time();
            $this->mysql->insert("{$this->pre}message",$arr); 
		    $this->message('?mod=member&act=imessage&type=sendto','信件发送成功');
	      } 
	   }
	   if($_GET['method']=='ajax'){
          $message = $this->mysql->select_one("select * from {$this->pre}message where id='{$_GET['id']}' and uid='{$this->member['uid']}' and isdel='0'");
		  $this->mysql->query("update {$this->pre}message set checked='1' where id='{$_GET[id]}' and uid='{$this->member['uid']}' and type='2' and isdel='0'");
		  $message['error'] = '0';
		  $message['read'] = $this->mysql->counts("select id from {$this->pre}message where uid='{$this->member['uid']}' and checked='0' and type='2' and isdel='0'");
		  echo json($message);
		  exit;
	   }
	   if($_GET['re']=='ajax'){
		  if($_GET['do']=='remove'){
			 $this->mysql->query("update {$this->pre}message set isdel='1' where id='{$_GET[id]}' and uid='{$this->member['uid']}'");
		     $arr['error'] = 0;
	         echo json($arr);
		  }
		  exit;
	   }
	}
	function notice(){
	   $_GET['type'] = $_GET['type'] ? $_GET['type'] : 'list';
	   if($_GET['type']=='list'){
		  $where = "where id>'0'";
		  if($_GET['typeid']) $where .= " and typeid='{$_GET['typeid']}'";
		  if($_GET['content']) $where .= " and title like '%{$_GET['content']}%'";
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];	  
	      $this->pagetotal = $this->mysql->counts("select id from {$this->pre}news $where");
	      $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}news $where order by id desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
			$rs['url'] = rewrite::request("?mod=member&act=notice&type=show&id=".$rs['id']);
			$rs['typename'] = $this->mysql->value("{$this->pre}newstype","typename","id='{$rs['typeid']}'");
			$rs['addtime'] = formattime($rs['addtime']);
			$this->record[] = $rs;
	      }
	   }
	   if($_GET['type']=='show'){
		  $this->mysql->query("update {$this->pre}news set clicknumber=clicknumber+1 where id='{$_GET[id]}'");
	      $this->news = $this->mysql->select_one("select * from {$this->pre}news where id='{$_GET[id]}' ");  
	      if(!is_array($this->news)) $this->message('go_back','对不起，该信息不存在');
	      $this->newstype = $this->mysql->select_one("select * from {$this->pre}newstype where id='{$this->news['typeid']}'"); 
		  $_GET['typeid'] = $this->news['typeid'];
	   }
	}
	function about(){
	   $_GET['type'] = $_GET['type'] ? $_GET['type'] : 'show';
	   if($_GET['type']=='show'){
	      $where = $_GET['typeid'] ? "where typeid='{$_GET['typeid']}'" : (isNumber($_GET['id']) ? "where id='{$_GET[id]}'" : "where myurl='{$_GET[id]}'");
	      $this->about = $this->mysql->select_one("select * from {$this->pre}about $where limit 1");
	      if(!is_array($this->about)) errorpage();
	   }
	}
	function delivery(){
	  $id = $_GET['id'];
	  if(!is_array($this->member)) $this->ajaxAlert('对不起，没有登陆不能进行操作！');
	  $takenumber = $this->mysql->counts("select * from {$this->pre}delivery where uid='{$this->member['uid']}'");
	  if($takenumber>=5&&!$id) $this->ajaxAlert('对不起，最多可添加5个常用收货信息！');
	  $this->add = $this->mysql->select_one("select * from {$this->pre}delivery where id='$id' and uid='{$this->member['uid']}'");
	  if(!is_array($this->add)&&$id) $this->ajaxAlert('对不起，该收货信息不存在');
	  if(submit()){
		$this->error='ok';
		if($_POST['name']==''){
          $this->error='请填写收货人姓名';
        }elseif($_POST['address']==''){
          $this->error='请填写收货详细地址';	
		}elseif($_POST['mobile']==''){
          $this->error='请填写收收货人手机';	
		}
		if($this->error=='ok'){
		  $arr['name'] = $_POST['name'];
		  $arr['address'] = $_POST['address'];
		  $arr['mobile'] = $_POST['mobile'];
		  if($id){
		    $this->mysql->update("{$this->pre}delivery",$arr,"id='{$id}'");
		    $arr['id'] = $id;
		  }else{
		    $arr['uid'] = $this->member['uid'];
		    $this->mysql->insert("{$this->pre}delivery",$arr);
		    $arr['id'] = $this->mysql->insertid();
		  }
		  echo "<script type='text/javascript'>\r\n";  
		  echo 'parent.'.$_GET['refun'].'('.json($arr).');'."\r\n";
		  echo "</script>";
		  exit;	
		}
	  }
	}
}
?>