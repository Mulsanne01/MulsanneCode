<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_pay extends module_class{
 function main(){
 }
 function allinpay(){
            require_once(PATH."library/payment/allinpay/php_rsa.php");
            require_once(PATH."library/payment/allinpay/php_sax.php");
            $merchantId=$_POST["merchantId"];
            $version=$_POST['version'];
            $language=$_POST['language'];
            $signType=$_POST['signType'];
            $payType=$_POST['payType'];
            $issuerId=$_POST['issuerId'];
            $paymentOrderId=$_POST['paymentOrderId'];
            $orderNo=$_POST['orderNo'];
            $orderDatetime=$_POST['orderDatetime'];
            $orderAmount=$_POST['orderAmount'];
            $payDatetime=$_POST['payDatetime'];
            $payAmount=$_POST['payAmount'];
            $ext1=$_POST['ext1'];
            $ext2=$_POST['ext2'];
            $payResult=$_POST['payResult'];
            $errorCode=$_POST['errorCode'];
            $returnDatetime=$_POST['returnDatetime'];
            $signMsg=$_POST["signMsg"];
			
            $bufSignSrc="";
            if($merchantId != "")
            $bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
            if($version != "")
            $bufSignSrc=$bufSignSrc."version=".$version."&";		
            if($language != "")
            $bufSignSrc=$bufSignSrc."language=".$language."&";		
            if($signType != "")
            $bufSignSrc=$bufSignSrc."signType=".$signType."&";		
            if($payType != "")
            $bufSignSrc=$bufSignSrc."payType=".$payType."&";
            if($issuerId != "")
            $bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
            if($paymentOrderId != "")
            $bufSignSrc=$bufSignSrc."paymentOrderId=".$paymentOrderId."&";
            if($orderNo != "")
            $bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
            if($orderDatetime != "")
            $bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
            if($orderAmount != "")
            $bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
            if($payDatetime != "")
            $bufSignSrc=$bufSignSrc."payDatetime=".$payDatetime."&";
            if($payAmount != "")
            $bufSignSrc=$bufSignSrc."payAmount=".$payAmount."&";
            if($ext1 != "")
            $bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
            if($ext2 != "")
            $bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
            if($payResult != "")
            $bufSignSrc=$bufSignSrc."payResult=".$payResult."&";
            if($errorCode != "")
            $bufSignSrc=$bufSignSrc."errorCode=".$errorCode."&";
            if($returnDatetime != "")
            $bufSignSrc=$bufSignSrc."returnDatetime=".$returnDatetime;
            $key = initPublicKey(PATH."library/payment/allinpay/publickey.txt");
            $keylength = 1024;
            $verify_result = rsa_verify($bufSignSrc,$signMsg, $key[0], $key[1], $keylength,"sha1");
	        $pay_result = $verify_result and $payResult == 1;
	        if($pay_result){
			   $orderAmount = $orderAmount/100;
		       $order =  $this->mysql->select_one("select * from {$this->pre}payorder where orderid='{$orderNo}' and checked=0");
			   if(is_array($order)){
			     $uid = $order['uid'];
                 $this->mysql->update("{$this->pre}payorder",array('checked'=>1),"orderid='{$orderNo}' and uid='{$uid}'");
                 $this->up_money($uid,$orderAmount,"+","金币抢购{$orderAmount}元现金，订单号{$orderNo}");
                 $this->record('paymoney',$orderAmount);
                 $this->records($order['uid'],'paymoney',$orderAmount);
                 $this->message('?mod=member&act=capital&type=payment&method=record','恭喜您，支付成功！');
				 exit;
			   }else{
				 $url = $_GET['orderid'] ? "?mod=member&act=order" : "?mod=member&act=capital&type=payment&method=record";
                 $this->message($url,'该支付已处理请进入查看！','0'); 
				 exit;
			   }	
	        }else{
			  $this->message('?mod=member','对不起，支付失败请联系管理员！','0');
			}
			exit;
 }
 function chinabank(){
           $key = config::get("chinabankkey");
           $v_oid = trim($_POST['v_oid']);       // 商户发送的v_oid定单编号   
           $v_pmode = trim($_POST['v_pmode']);    // 支付方式（字符串）   
           $v_pstatus =trim($_POST['v_pstatus']);   //  支付状态 ：20（支付成功）；30（支付失败）
           $v_pstring =trim($_POST['v_pstring']);   // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
           $v_amount  =trim($_POST['v_amount']);     // 订单实际支付金额
           $v_moneytype  =trim($_POST['v_moneytype']); //订单实际支付币种    
           $remark1   =trim($_POST['remark1' ]);      //备注字段1
           $remark2   =trim($_POST['remark2' ]);     //备注字段2
           $v_md5str  =trim($_POST['v_md5str' ]);   //拼凑后的MD5校验值                   
           $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
           if($v_md5str==$md5string){
	          if($v_pstatus=="20"){
		       $order =  $this->mysql->select_one("select * from {$this->pre}payorder where orderid='{$v_oid}' and checked=0");				   
			   if(is_array($order)){
			     $uid = $order['uid'];
                 $this->mysql->update("{$this->pre}payorder",array('checked'=>1),"orderid='{$v_oid}' and uid='{$uid}'");
                 $this->up_money($uid,$v_amount,"+","金币抢购{$orderAmount}元现金，订单号{$v_oid}");
				 $this->record('paymoney',$v_amount);
				 $this->records($order['uid'],'paymoney',$v_amount);
                 $this->message('?mod=member&act=capital&type=payment&method=record','恭喜您，支付成功！');
				 exit;
			   }else{
				 $url = $_GET['orderid'] ? "?mod=member&act=order" : "?mod=member&act=capital&type=payment&method=record";
                 $this->message($url,'该支付已处理请进入查看！','0'); 
				 exit;
			   }	
	          }else{
		        $this->message('?mod=member','对不起，支付失败请联系管理员！','0');
	          }
           }else{
			  $this->message('?mod=member','对不起，校验失败,数据可疑！','0');
           }
 }
}
?>