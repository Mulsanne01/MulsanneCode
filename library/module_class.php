<?php
if (!defined('ROOT')) exit('Can\'t Access !');
abstract class module_class{
    function __construct($module,$action){
     $this->module = $module;
     $this->action = $action;
     $this->pre = config::get('tablepre');
     $this->pagesize = $this->module=='admin' ? config::get("adminpagesize") : config::get("pagesize");
     $this->mysql = new mysql_class($this);
     if($module=='admin') $this->check_manager();
     $this->check_member();
     $this->upload_config();
	 $this->paytime = explode("到",config::get("paytime"));
	 $this->read = $this->mysql->counts("select id from {$this->pre}message where uid='{$this->member['uid']}' and checked='0' and type='2'");
    }	
    function check_member(){
     $this->user = new member_class($this);
     if($this->module!='admin'){
      $this->member = $this->user->check();
      $this->user->purview();
     }
     return true;
    }
    function check_manager(){
     $this->manager_class = new manager_class($this);
     $this->manager = $this->manager_class->check();
     $this->getReturn();
     $this->manager_class->purview();
     return true;
    }
    function getReturn(){
     if($_POST['get']){
      if($this->manager_class->rn_purview($this->module,$this->action,$_GET['get'])===false){
       $get = array_keys(admin_menu_left($_GET['act']));
       foreach($get as $val){
        if($this->manager_class->rn_purview($this->module,$this->action,$val)){
	     $_GET['get'] = $val;
	     break;
	    }
       }
       $re = array_keys(admin_menu_small($_GET['act'],$_GET['get']));
       $_GET['re'] = $re[0];
       $this->manager_class->startpurview();
      }
      unset($_POST['get']);
     }
     return true;
    }
    function upload_config(){
      $ftype = str_replace('.','*.',config::get('filetype'));
      $ftype = str_replace('|',';',$ftype);
      $fsize = config::get('filesize')/1024;
      $this->ftype = '图片('.$ftype.')';
      $this->fsize = "{$fsize}MB";
      $this->fcount = config::get('filecount');
      return true;
    }
  	function getupload($path,$thumb='',$fileInput='imgFile'){
      $filetype = explode("|",str_replace('.','',config::get('filetype')));
      $filesize = config::get('filesize')*1024;
      $uploadpath = PATH.config::get('uploadpath').$path;
      $filepath = config::get("sitepath").config::get('uploadpath').$path;
      $upload = new upload($uploadpath,$filetype,$filesize,0,$fileInput);
      $upload->setThumb($thumb,$thumbPrefix,$thumbWidth,$thumbHeight,$delete);
      $file = $upload->getfile();  
      if($file['0']['saveName']){
        $filesize = @getimagesize($uploadpath.$file['0']['saveName']);
        if($thumb){
	      $array = explode("#",$thumb);
          $getthumb = new ImageResize(); 
          $getthumb->load($uploadpath.$file['0']['saveName']);
	      foreach($array as $key=>$val){
	        $value = explode("|",$val);
	        $getthumb->resize($value[0]);
	        if($value[1]){
	          $savefile = $uploadpath.$file['0']['saveName']."_".$value[1].".".$getthumb->get_type($uploadpath.$file['0']['saveName']);
	        }else{
	          $savefile = $uploadpath.$file['0']['saveName']; 
	        }
	        $destroy = $key>=count($array)-1 ? true : false;
            $getthumb->save($savefile,$destroy);
	      }   
        }
        $return['file'] = $filepath.$file['0']['saveName'];
	    $return['truefile'] = $upload->returninfo['name'];
	    $return['width'] = $filesize[0];
	    $return['height'] = $filesize[1];
      }else{
	    $return['errno'] = $upload->errno;
  	    $return['errmsg'] = $upload->errmsg();   
      }
      return $return;
    }
	function getgoods($number,$where='',$order='order by goods_id desc',$namesort='0'){
	  $where = "and ".$where;
	  $query = $this->mysql->query("select * from {$this->pre}goods where ischeck='1' {$where} {$order} limit 0,$number");
	  while($rs=$this->mysql->assoc($query)){
        $goods_thumb = array_filter(explode(',',$rs['goods_thumb']));
	    $rs['goods_thumbs'] = array_filter(explode(',',$rs['goods_thumb']));
	    $rs['goods_thumb'] = get_img($rs['goods_thumbs'][0]);
		$rs['goods_prod'] = get_img($rs['goods_thumbs'][0],"prod");
		$rs['goods_img'] = $rs['goods_thumbs'][0];
		$rs['url'] = rewrite::request("?mod=member&act=goods&type=show&id=".$rs['goods_id']);
		$rs['market_price'] = formatnum($rs['market_price'],2,1);
		$rs['shop_price'] = formatnum($rs['shop_price'],2,1);
		if($namesort>'0') $rs['goods_name'] = msubstr($rs['goods_name'],0,$namesort);
		$goods[] = $rs;
	  }
      return $number=='1' ? $goods[0] : $goods;
	}	
	function sqlgoods($sql,$namesort='0'){
      $query = $this->mysql->query($sql);
      while($rs=$this->mysql->assoc($query)){
		$goods_thumb = array_filter(explode(',',$rs['goods_thumb']));
		$rs['goods_thumbs'] = array_filter(explode(',',$rs['goods_thumb']));
		$rs['goods_thumb'] = get_img($rs['goods_thumbs'][0]);
		$rs['goods_prod'] = get_img($rs['goods_thumbs'][0],"prod");
		$rs['goods_img'] = $rs['goods_thumbs'][0];
		$rs['url'] = rewrite::request("?mod=member&act=goods&type=show&id=".$rs['goods_id']);
		$rs['market_price'] = formatnum($rs['market_price'],2,1);
		$rs['shop_price'] = formatnum($rs['shop_price'],2,1);
		if($namesort>'0') $rs['goods_name'] = msubstr($rs['goods_name'],0,$namesort);
		$goods[] = $rs;
      }
      return $goods;
	}	
	function goodsorder($arr,$uid=''){
	  $uid = $uid=='' ? $this->member['uid'] : $uid;
	  $user = $this->user->sql($uid);
	  $delivery = $this->mysql->select_one("select * from {$this->pre}delivery where id='{$arr['takeid']}' and uid='{$uid}'");
	  if(!is_array($delivery)) return '请正确选择您的收货地址';
	  foreach($arr['goodsid'] as $key=>$val){
	    if($arr['number'][$key]>0){
		  $goods[$key] = $this->mysql->select_one("select goods_id,goods_name,shop_price,margin,stock from {$this->pre}goods where goods_id='{$val}'"); 
		  $goods[$key]['number'] = $arr['number'][$key]; 
		  $goods[$key]['money'] = $goods[$key]['shop_price']*$arr['number'][$key];
		  $goods[$key]['price'] = $goods[$key]['money']*$user['usergroup']['rebate'];
		  $goods[$key]['margin'] = $goods[$key]['margin']*$arr['number'][$key];
		  $money += $goods[$key]['money'];
		  $margin += $goods[$key]['margin'];
		  $price += $goods[$key]['price'];
		}	
	  }
	  $order['orderid'] = makeorderid();
	  $order['message'] = "";
	  $order['goods'] = serialize($goods);
	  $order['uid'] = $uid;
	  $order['checked'] = 0;
	  $order['delivery'] = serialize($delivery);
	  $order['money'] = $money;
	  $order['margin'] = $margin;
	  $order['price'] = $price;
	  $order['addtime'] = time();
      $this->mysql->insert("{$this->pre}order",$order);
	  $order['id'] = $this->mysql->insertid();
	  return $order['id'];
	}
	function turnorder($order,$check){
		if($check=='1'){
		  if($order['checked']=='0'){
			$submit = true;
			$user = $this->user->sql($order['uid']);	
			if($user['shopmoney']<$order['price']){
			  $submit = false;
			  return '购股币不足'.$order['price'].'元。';	
			}			
			if($submit){
		      $parentid = $this->up_shopmoney($order['uid'],$order['price'],"-","<a href=\"javascript:ogo(".$order['id'].");\" title=\"订单号{$order['orderid']},点击查看详情\">订购产品</a>");
		      $arr['checked'] = '2';
			  $this->mysql->update("{$this->pre}order",$arr,"id='{$order['id']}'");
			  $this->goodsstore($order['id']);
		  	}
		  }else{
			if($order['checked']=='3'){
			  if(strlen($order['messagea'])<100){
		        $arr['checked'] = '1';
			    $arr['message'] = $order['message']."A：".$order['messagea']." ".formattime(time())."<br>";
			    $this->mysql->update("{$this->pre}order",$arr,"id='{$order['id']}'");
			  }else{
			    return '简单概括就行，别写这么长';   
			  }
		  	}else{
			  return '订单状态有误';  
			}
		  }
	    }elseif($check=='2'){
		  if($order['checked']=='5'){
		    $arr['checked'] = '2';
		    $this->mysql->update("{$this->pre}order",$arr,"id='{$order['id']}'");
			$this->goodsstore($order['id']);
		  }else{
		    return '订单状态有误';
	      }
	    }elseif($check=='3'){
		  if($order['checked']=='1'){
		   if(strlen($order['messageq'])<100){
			  $arr['checked'] = '3';
 		      $arr['message'] = $order['message']."Q：".$order['messageq']." ".formattime(time())."<br>";
		      $this->mysql->update("{$this->pre}order",$arr,"id='{$order['id']}'");
		    }else{
			  return '简单概括就行，别写这么长';   
		    }
		  }else{
		    return "该订单目前状态不能退款";
		  }  
	    }elseif($check=='4'){
		  if($order['checked']=='3'){
			$arr['checked'] = '4';
		    $this->mysql->update("{$this->pre}order",$arr,"id='{$order['id']}'");
		    $parentid = $this->up_shopmoney($order['uid'],$order['price'],"+","<a href=\"javascript:ogo(".$order['id'].");\" title=\"订单号{$order['orderid']},点击查看详情\">订单退款</a>");
		  }else{
		    return "该订单目前状态不能退款";
		  } 
	    }elseif($check=='5'){
		  if($order['checked']=='1'){
			$arr['checked'] = '5';
			$arr['express'] = $order['express'];
			$arr['expressnumber'] = $order['expressnumber'];
			$arr['message'] = $_POST['message'];
			$arr['ftime'] = time();
		    $this->mysql->update("{$this->pre}order",$arr,"id='{$order['id']}'");
	      }else{
		    return "该订单目前状态不能发货";
		  } 
	    }
		return '0';
	}
	function getnews($classid,$num,$length='',$time=''){
		$number = 0;
		$where = $classid=='0' ? "" : "where typeid='$classid'";
	    $query = $this->mysql->query("select * from {$this->pre}news $where order by addtime desc limit 0,$num");
	    while($rs=$this->mysql->assoc($query)){
		  $rs['url'] = rewrite::request("?mod=news&act=show&id=".$rs['id']);
		  $rs['addtime'] = formattime($rs['addtime'],$time);
	      $rs['pics'] = getpic($rs['content']);
	      $rs['text'] = msubstr(noHtml($rs['content']),0,46);
		  $rs['title'] = $length=='' ? $rs['title'] : msubstr($rs['title'],0,$length);
		  $number ++;
		  $rs['number'] = $number;
		  $news[] = $rs;
	    }
		return $news;
	}
	function getnewstype($number,$where=''){
	    $query = $this->mysql->query("select * from {$this->pre}newstype {$where} order by typeorder asc limit 0,{$number}");
	    while($rs=$this->mysql->assoc($query)){
		   $newstype[] = $rs;
	    }
		return $newstype;
	}	
	function get_nav($id){
	    $nav = $this->mysql->getarr("select * from {$this->pre}nav where `type`='{$id}' order by ord asc");
		return $nav;
	}
	function records($uid,$how,$money,$addtime=""){
		$addtime = $addtime ? $addtime : untime(formattime(time(),"Y-m-d 00:00"));
	    $records = $this->mysql->select_one("select * from {$this->pre}records where addtime='{$addtime}' and uid='{$uid}'");
        $arr[$how] = $records[$how]+$money;
		if(is_array($records)){
		  $this->mysql->update("{$this->pre}records",$arr,"id='{$records['id']}'");
		}else{
		  $arr['addtime'] = $addtime;
		  $arr['uid'] = $uid;
		  $this->mysql->insert("{$this->pre}records",$arr); 
		}
	}
	function record($how,$money,$addtime=""){
		$addtime = $addtime ? $addtime : untime(formattime(time(),"Y-m-d 00:00"));
	    $record = $this->mysql->select_one("select * from {$this->pre}record where addtime='{$addtime}'");
		if(is_array($how)){
		  $arr = $how;
		}else{
		  $arr[$how] = $record[$how]+$money;
		}        
		if(is_array($record)){
		  $this->mysql->update("{$this->pre}record",$arr,"id='{$record['id']}'");
		}else{
		  $arr['addtime'] = $addtime;
		  $this->mysql->insert("{$this->pre}record",$arr); 
		}
	}
	function getrecord($where=''){
		$arr['_paymoney'] = '_paymoney';
		$arr['paymoney'] = 'paymoney';
		$arr['allpaymoney'] = '_paymoney+paymoney';
		$arr['money'] = 'money';
		$arr['_money'] = '_money';
		$arr['buymoney'] = 'buymoney+upgroup+ordermoney';
		$arr['refereemoney'] = 'refereemoney';
		$arr['floormoney'] = 'floormoney';
		$arr['__money'] = '__money';
		$arr['leadmoney'] = 'leadmoney';
		$arr['regmoney'] = 'regmoney';
		$arr['atmmoney'] = 'atmmoney';
		$arr['atmmoneyed'] = 'atmmoneyed';
		$arr['atmmoneyno'] = 'atmmoney-atmmoneyed';
		$arr['outmoney'] = 'money+_money+refereemoney+floormoney+__money+leadmoney+regmoney';
		$arr['margin'] = '(buymoney+upgroup)-(money+_money+refereemoney+floormoney+__money+leadmoney+regmoney)';
		return $this->mysql->getrecord("{$this->pre}record",$arr,$where);		
	}
	function getrecords($where=''){
		$arr['_paymoney'] = '_paymoney';
		$arr['paymoney'] = 'paymoney';
		$arr['allpaymoney'] = '_paymoney+paymoney';
		$arr['money'] = 'money';
		$arr['_money'] = '_money';
		$arr['buymoney'] = 'buymoney+upgroup+ordermoney';
		$arr['refereemoney'] = 'refereemoney';
		$arr['floormoney'] = 'floormoney';
		$arr['__money'] = '__money';
		$arr['leadmoney'] = 'leadmoney';
		$arr['regmoney'] = 'regmoney';
		$arr['atmmoney'] = 'atmmoney';
		$arr['atmmoneyed'] = 'atmmoneyed';
		$arr['atmmoneyno'] = 'atmmoney-atmmoneyed';
		$arr['inmoney'] = 'money+_money+refereemoney+floormoney+__money+leadmoney+regmoney+otherin';
		$arr['outmoney'] = 'buymoney+upgroup+otherout';
		$arr['margin'] = '(money+_money+refereemoney+floormoney+__money+leadmoney+regmoney+otherin)-(buymoney+upgroup+otherout)';
		return $this->mysql->getrecord("{$this->pre}records",$arr,$where);		
	}
	function getchatdate($timet,$time){
		$arr['time'] = $time ? $time : $this->mysql->value("{$this->pre}log","addtime","id>'0' order by id asc limit 1"); 
		$arr['timet'] = $timet ? $timet : time();
		$interval = $arr['timet'] - $arr['time'];
        if($interval>3600*24*30*24){
		  $arr['part'] = 'y';
		  $arr['format'] = "Y";
		  $arr['_format'] = "%Y";
		}elseif($interval>3600*24*30){
		  $arr['part'] = 'm';
		  $arr['format'] = "Y-m";
		  $arr['_format'] = "%Y-%m";
		}else{
		  $arr['part'] = 'd';
		  $arr['format'] = "Y-m-d";
		  $arr['_format'] = "%Y-%m-%d";
		}
		$arr['step'] = datediff($arr['part'],$arr['timet'],$arr['time']);
		return $arr;
	}
	function verifyinsertuser($val,$admin=''){
		$arr['username'] = $val['username'];
		$arr['password'] = $val['password'];
	    $arr['truename'] = $val['truename'];
		$arr['userphone'] = $val['userphone'];
	    $arr['groupid'] = $val['groupid'];
		$arr['repass'] = $val['_repass'];
		$arr['referee'] = $val['referee'];	
		$arr['usertype'] = $val['usertype'] ? $val['usertype'] : '0';	
		$arr['parentid'] = $val['parentid'] ? $val['parentid'] : '0';
		$arr['parentusername'] = $val['parentusername'] ? $val['parentusername'] : '';
		$arr['status'] = '0';
        if($arr['username']=='') return '玩家编号不能为空';
        if($this->user->checkusername($arr['username'])) return '该玩家编号已经存在';
	    if($arr['password']=='') return '登录密码不能为空';
	    if($arr['repass']=='') return '安全密码不能为空';
        if($val['nowopen']=='') return '请选择是否正式激活';
		$status = '0';
		if(!$val['getmember']){
		  if(!is_array($referee = $this->user->sql($arr['referee'],'username',"status='1'"))) return '推荐会员不存在或非正式会员';
	      if($val['service']){
	        if(!is_array($service = $this->user->select_one("select * from {$this->pre}user where username='{$val['service']}' and service='1'"))){
			   return '报单中心不存在';
		    }
	      }
		  $arr['reguser'] = $service['service'] ? $service['uid'] : '0';
		  $status = '1';
		}
        $uid = $this->user->insert($arr);
		if($val['nowopen']) return $this->status($uid,$status,$admin);
		return '0';
	}
	function status($uid,$status='1',$admin=''){
		$error = '0';
	    $user = $this->user->sql($uid);
		if(!is_array($user)) return '对不起，该会员信息不存在';
		if($user['status']=='1') return '对不起，该会员已经激活';
		if($status=='1'){
		  $money = $user['usergroup']['buymoney'];
		  if($admin==''){
		    if(is_array($this->member)){
              if($this->member['money']<$money) return "金币不足{$money}！";  
		      $this->up_money($this->member['uid'],$money,"-","激活会员");
		    }else{
			  return '非法操作';	
		    }
		  }	
		  $this->mysql->query("update {$this->pre}user set `status`='1',opentime='".time()."' where uid ='{$uid}'");
		  $this->record('buymoney',$money);	
		  

		  		
		  //{推荐奖
	      if(is_array($referee = $this->user->sql($user['referee'],'username'))){			  
		    $refereemoney = getval($referee['usergroup']['refereemoney'],'0',$money);			  
			if($refereemoney>0){
			  $this->up_money($referee['uid'],$refereemoney,"+","推荐奖",'0','',getval($referee['usergroup']['shopmoney'],'0',$refereemoney));
			  $this->records($referee['uid'],'refereemoney',$refereemoney);
			  $this->record('refereemoney',$refereemoney);
		    }
	      }
		  
		  //报单奖励
	      if(is_array($service = $this->user->select_one("select * from {$this->pre}user where uid='{$user['reguser']}' and service='1'"))){
			$regmoney = getval($service['usergroup']['regmoney'],"",$money);
			if($regmoney){
			  $this->up_money($service['uid'],$regmoney,"+","报单奖励",'0','',getval($service['usergroup']['shopmoney'],'0',$regmoney));
			  $this->records($service['uid'],'regmoney',$regmoney);
			  $this->record('regmoney',$regmoney);
			}
		  } 
		  
	    }else{
		  if($admin==''){
		    $this->mysql->query("update {$this->pre}user set `status`='1',opentime='".time()."' where uid ='{$uid}'"); 
		  }else{
		    $money = $user['usergroup']['buymoney'];  
			$this->record('buymoney',$money);	
			$this->mysql->query("update {$this->pre}user set `status`='1',opentime='".time()."' where uid ='{$uid}'");
		  }
	    }
		return '0';
	}
	
	function floormoney($referee,$money){
		$floor = 1;
	    while(is_array($referee = $this->user->sql($referee['referee'],'username'))){	
		  if($referee['usergroup']['floors']>=$floor){	
		    $floormoney = getval($referee['usergroup']['floormoney'],"",$money);
		    if($floormoney>0){
			  $this->up_money($referee['uid'],$floormoney,"+","1",'0','',getval($referee['usergroup']['shopmoney'],'0',$floormoney));
		      $this->records($referee['uid'],'floormoney',$floormoney);
			  $this->record('floormoney',$floormoney);
		    }
		  }
	      $floor++;
	    }
		return true;
	}
	
	
	function leadmoney($referee,$money){
		$floor = 1;
	    while(is_array($referee = $this->user->sql($referee['referee'],'username'))){	
		  if($referee['usergroup']['floors']>=$floor){	
		    $leadmoney = getval($referee['usergroup']['leadmoney'],"",$money);
		    if($leadmoney>0){
			  $this->up_money($referee['uid'],$leadmoney,"+","领导奖",'0','',getval($referee['usergroup']['shopmoney'],'0',$leadmoney));
		      $this->records($referee['uid'],'leadmoney',$leadmoney);
			  $this->record('leadmoney',$leadmoney);
		    }
		  }
	      $floor++;
	    }
		return true;
	}
	
	function upgroup($uid,$groupid){
		$error = '0';
	    $user = $this->user->sql($uid);
		$usergroup = $this->mysql->select_one("select * from {$this->pre}usergroup where groupid='{$groupid}'");
		if(!is_array($user)) return '对不起，该会员信息不存在';
		if(!is_array($usergroup)) return '对不起，非法的会员级别';
		$money = $usergroup['buymoney']-$this->member['usergroup']['buymoney'];
		if(is_array($this->member)){
          if($this->member['money']<$money) return "金币不足{$money}，请充值！";  
		  $this->up_money($this->member['uid'],$money,"-","会员升级");
		}else{
	      return '非法操作';	
		}
		$this->record('buymoney',$money);			  		
		//{推荐奖
	    if(is_array($referee = $this->user->sql($user['referee'],'username'))){
		  $refereemoney = getval($referee['usergroup']['refereemoney'],'0',$money);			  
	      if($refereemoney>0){
			$this->up_money($referee['uid'],$refereemoney,"+","推荐奖",'0','',getval($referee['usergroup']['shopmoney'],'0',$refereemoney));
			$this->records($referee['uid'],'refereemoney',$refereemoney);
			$this->record('refereemoney',$refereemoney);
		  }
	    }
		return '0';
	}	
	function givemoney($uid){
	   $user = $this->user->sql($uid);
	   $maxmoney = $user['maxmoney'];
	   $regtime = untime(formattime($user['regtime'],"Y-m-d 00:00"));
	   $moneytime = untime(formattime($user['moneytime'],"Y-m-d 00:00"));
	   $nowtime = untime(formattime(time(),"Y-m-d 00:00"));	   
	   if(($moneytime<$nowtime)&&($nowtime>$regtime)){
		  $moneytime = $moneytime ? $moneytime+(24*3600) : $regtime+(24*3600);
		  $number = ($nowtime-$moneytime)/(24*3600);
		  for($i=0;$i<=$number;$i++){
		    $money = $user['usergroup']['money'];
			if($user['usergroup']['maxmoney']-$maxmoney<$money) $money = $user['usergroup']['maxmoney']-$maxmoney;
		    $time = $moneytime+(24*3600)*$i;
			if($maxmoney+$money>=$user['usergroup']['maxmoney']) $clock = ",moneycheck='0'";
			$this->mysql->query("update {$this->pre}user set `moneytime`='{$time}',maxmoney=`maxmoney`+'{$money}',allmaxmoney=`allmaxmoney`+'{$money}'{$clock} where uid='{$user['uid']}'"); 
			if($money>0){
			  $this->records($uid,'money',$money);
			  $this->record('money',$money);
			  $balance = getval($user['usergroup']['atmscale'],'0',$money);
			  $money = $money-$balance;
			  $parentid = $this->up_money($user['uid'],$money,"+","静态分红",'0','',getval($user['usergroup']['shopmoney'],'0',$money));
			  $this->up_balance($user['uid'],$balance,"+","静态分红","",$parentid);	   
			  $this->leadmoney($user,$money);
			}
		    $maxmoney += $money;
		  }		
       }
	   return $maxmoney;
	}
	function goodsstore($id){
		$error = '0';
		$order = $this->mysql->select_one("select * from {$this->pre}order where id='{$id}' limit 1");
	    $user = $this->user->sql($order['uid']);
		$money = $order['price'];
	    $this->records($order['uid'],'ordermoney',$order['price']);
		$this->record('ordermoney',$order['price']);
		if($error=='0'){	

		}
		return $error;
	}
    function getselect($table,$id,$name,$where='',$order='',$value=''){
	    $arr = $this->mysql->getarr("select `{$id}`,{$name} as selectname from {$this->pre}{$table} {$where} {$order}");
	    return $value.formval($arr,$id,'selectname');  
    }
	function getabout($typeid=''){
		$number = 0;
		if($typeid!='') $where = "where typeid='{$typeid}'";
	    $query = $this->mysql->query("select * from {$this->pre}about $where order by id asc");
	    while($rs=$this->mysql->assoc($query)){
		   $id = $rs['myurl'] ? $rs['myurl'] : $rs['id'];
		   $rs['url'] = rewrite::request("?mod=about&act=show&id=".$id);
		   $rs['memberurl'] = rewrite::request("?mod=member&act=about&id=".$id);
		   $number ++;
		   $rs['number'] = $number;
		   $about[] = $rs;
	    }
		return $about;
	}	
	function getabouttype($number,$where=''){
	    $query = $this->mysql->query("select * from {$this->pre}abouttype {$where} order by typeorder asc limit 0,{$number}");
	    while($rs=$this->mysql->assoc($query)){
		   $abouttype[] = $rs;
	    }
		return $abouttype;
	}	
	function getusergoup($number='',$where=''){
		if($number) $limit = "limit 0,{$number}";
	    $query = $this->mysql->query("select * from {$this->pre}usergroup {$where} order by groupid asc {$limit}");
	    while($rs=$this->mysql->assoc($query)){
		   $group[] = $rs;
	    }
		return $group;
	}
	function up_money($uid,$money,$action,$message,$parentid=0,$addtime="",$shopmoney=0){
	   $user = $this->user->sql($uid);
	   $money = formatnum($money-$shopmoney);
	   if(is_array($user)&&$money>0){
	     $arr['money'] = $action=='+' ? $user['money']+$money : $user['money']-$money;
         $this->mysql->update("{$this->pre}user",$arr,"uid='{$user[uid]}'");
	     $log['uid'] = $user['uid'];
	     $log['typeid'] = 1;
		 $log['parentid'] = $parentid;
	     $log['addtime'] = $addtime ? $addtime : time();
	     $log['content'] = $message;
	     $log['lognum'] = $action.$money;
	     $log['balance'] = $arr['money'];
         $id = $this->mysql->insert("{$this->pre}log",$log);
		 if($shopmoney>0){
		   $mymoney = $this->up_shopmoney($uid,$shopmoney,$action,$message,$id); 
		   if($mymoney>=$user['usergroup']['rebate']){
			 $this->up_shopmoney($uid,$user['usergroup']['rebate'],"-","生成账号"); 
			 $u['username'] = 'F'.rand(10000,99999).$uid;
			 while($this->user->checkusername($u['username'])){
			   $u['username'] = 'F'.rand(10000,99999).$uid;
			 }
			 $u['password'] = rand(1000000,9999999);
			 $u['_repass'] = rand(1000000,9999999);
			 $u['nowopen'] = '1';
			 $u['parentid'] = $user['parentid'] ? $user['parentid'] : $uid;
			 $u['usertype'] = '1';
			 $u['nocheck'] = '1';
			 $u['getmember'] = '1';
			 $u['groupid'] = $user['groupid'];
			 $u['parentusername'] = $user['username'];
			 $this->verifyinsertuser($u);
		   }
		 }
	   }
	   return $id;
	}	
	function up_regmoney($uid,$money,$action,$message,$parentid=0){
	   $user = $this->user->sql($uid);
	   $money = formatnum($money);
	   if(is_array($user)&&$money>0){
	     $arr['regmoney'] = $action=='+' ? $user['regmoney']+$money : $user['regmoney']-$money;
         $this->mysql->update("{$this->pre}user",$arr,"uid='{$user[uid]}'");
	     $log['uid'] = $user['uid'];
	     $log['typeid'] = 2;
		 $log['parentid'] = $parentid;
	     $log['addtime'] = time();
	     $log['content'] = $message;
	     $log['lognum'] = $action.$money;
	     $log['balance'] = $arr['regmoney'];
         $id = $this->mysql->insert("{$this->pre}log",$log);
	   }
	   return $id;
	}
	function up_shopmoney($uid,$money,$action,$message,$parentid=0){
	   $user = $this->user->sql($uid);
	   $money = formatnum($money);
	   if(is_array($user)&&$money>0){
	     $arr['shopmoney'] = $action=='+' ? $user['shopmoney']+$money : $user['shopmoney']-$money;
         $this->mysql->update("{$this->pre}user",$arr,"uid='{$user[uid]}'");
	     $log['uid'] = $user['uid'];
	     $log['typeid'] = 3;
		 $log['parentid'] = $parentid;
	     $log['addtime'] = time();
	     $log['content'] = $message;
	     $log['lognum'] = $action.$money;
	     $log['balance'] = $arr['shopmoney'];
         $id = $this->mysql->insert("{$this->pre}log",$log);
	   }
	   return $log['balance'];
	}
	function up_balance($uid,$money,$action,$message,$addtime="",$parentid=0){
	   $user = $this->user->sql($uid);
	   $money = formatnum($money);
	   if(is_array($user)&&$money>0){
	     $arr['balance'] = $action=='+' ? $user['balance']+$money : $user['balance']-$money;
         $this->mysql->update("{$this->pre}user",$arr,"uid='{$user['uid']}'");
	     $log['uid'] = $user['uid'];
	     $log['typeid'] = 4;
		 $log['parentid'] = $parentid;
	     $log['addtime'] = $addtime ? $addtime : time();
	     $log['content'] = $message;
	     $log['lognum'] = $action.$money;
	     $log['balance'] = $arr['balance'];
         $id = $this->mysql->insert("{$this->pre}log",$log);
	   }
	   return $id;
	}	
	function pageclass($count,$pagesize='',$endpre=''){
		$this->pagesize = $this->module=='admin' ? config::get("adminpagesize") : config::get("pagesize");
		if($pagesize!='') $this->pagesize = $pagesize;
		$this->page = new page_class($this->pagesize,0,$count);
	    $this->pageid = $this->page->page;
		$this->showpage = $this->page->showpage($endpre);
		$this->newpage = $this->page->newpage($endpre);
		$this->minipage = $this->page->minipage($endpre);
		return true;
	}
    function message($url,$message,$right='1'){
       $this->message = language($message);
       $thisurl = Purl($url); 
       $this->jcode = $thisurl=='-1' ? "javascript:history.go(-1);" : "javascript:location.href='$thisurl';";
       $this->scode = $thisurl=='-1' ? "history.go(-1)" : "location.href='$thisurl'";	
       $this->msgRight = $right;
       $this->openmessage = 1;
       $this->template();
       return true;
    }
	function js($function){
	   $javascript = "<script language=\"javascript\">";
	   $javascript .= "$function";
	   $javascript .= "</script>";
	   echo $javascript;
	   exit;
	}
	function ajaxAlert($message){
       $message = language($message);
	   $javascript = "<script language=\"javascript\">";
	   $javascript .= "parent.Wrong('{$message}');";
	   $javascript .= "</script>";
	   echo $javascript;
	   exit;
	}	
    function template($temp='',$pre=''){
       $module = $this->module;
       $action = $this->action;
       $template_dir = $module=='admin' ? 'admin' : config::get("template_dir");
       $this->hempdir = config::get('sitepath')."template/{$template_dir}/";
       $template_dir = $module=='member' ? $template_dir.'/member' : $template_dir;
	   $template_dir = $module=='mobile' ? $template_dir.'/mobile' : $template_dir;
       $this->tempdir = config::get('sitepath')."template/{$template_dir}/";
       $this->appdir = config::get('sitepath')."app/";
       if($this->openmessage){
        $action = 'message';
        $module = $module=='admin' ? 'admin' : 'index';
        if($this->module=='member') $module='member';
		if($this->module=='mobile') $module='mobile';
       }
       if($temp){
        $this->tempdir .= "$temp/$pre/";
        $template_dir .= "/$temp/$pre";
       }
       include template($module.'_'.$action,$template_dir,'cache',"$temp/$pre");
       exit();
     }
}
?>