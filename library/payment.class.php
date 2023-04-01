<?php
class payment_class{
    function __construct($module,$money,$orderid=''){
        $this->mysql = $module->mysql;
		$this->module = $module;
		$this->pre = $module->pre;
		$this->member = $module->member;
		$this->paymoney = $money;
		$this->orderurl = $orderid=='' ? '' : "&orderid=".$orderid;
    }
	function alipay(){
        $aliapy_config['partner'] = config::get("partner");
        $aliapy_config['key'] = config::get("alipaykey");
        $aliapy_config['seller_email'] = config::get("seller_email");
        $aliapy_config['return_url'] = config::get("sitedomain").Purl('?mod=pay&act=alipay'.$this->orderurl);
        $aliapy_config['notify_url'] = config::get("sitedomain").Purl('?mod=pay&act=alipay'.$this->orderurl);
        $aliapy_config['sign_type'] = 'MD5';
        $aliapy_config['input_charset'] = 'utf-8';
        $aliapy_config['transport'] = 'http';
        require_once(PATH."library/payment/alipay/lib/alipay_service.class.php");
        $out_trade_no = makeorderid();
        $subject = config::get('sitename')."金币抢购";
        $body = config::get('sitename')."金币抢购";
        $total_fee = formatnum($this->paymoney);
        $paymethod = '';
        $defaultbank = '';
        $anti_phishing_key = '';
        $exter_invoke_ip = '';
        $show_url = config::get("sitedomain");
        $extra_common_param = '';
        $royalty_type = "";
        $royalty_parameters = "";
		$arr['orderid'] = $out_trade_no;
	    $arr['total_fee'] = $total_fee;
		$arr['checked'] = 0;
		$arr['uid'] = $this->member['uid'];
		$arr['addtime'] = time();
		$arr['paytype'] = "支付宝";
		$this->mysql->insert("{$this->pre}payorder",$arr);
        $parameter = array(
		  "service"=>"create_direct_pay_by_user",
		  "payment_type"=>"1",		
		  "partner"=>trim($aliapy_config['partner']),
		  "_input_charset"=>trim(strtolower($aliapy_config['input_charset'])),
          "seller_email"=>trim($aliapy_config['seller_email']),
          "return_url"=>trim($aliapy_config['return_url']),
          "notify_url"=>trim($aliapy_config['notify_url']),		
		  "out_trade_no"=> $out_trade_no,
		  "subject"=>$subject,
		  "body"=>$body,
		  "total_fee"=>$total_fee,		
		  "paymethod"=>$paymethod,
		  "defaultbank"=>$defaultbank,		
		  "anti_phishing_key"=>$anti_phishing_key,
		  "exter_invoke_ip"=>$exter_invoke_ip,		
		  "show_url"=>$show_url,
		  "extra_common_param"=>$extra_common_param,		
		  "royalty_type"=>$royalty_type,
		  "royalty_parameters"=>$royalty_parameters
        );
        $alipayService = new AlipayService($aliapy_config);
        $html_text = $alipayService->create_direct_pay_by_user($parameter);
        echo $html_text;	 	
	}
	function tenpay(){
        require_once (PATH."library/payment/tenpay/classes/PayRequestHandler.class.php");
        $bargainor_id = config::get("tenpay");;
        $tenpaykey = config::get("tenpaykey");
        $return_url = config::get("sitedomain").Purl('?mod=pay&act=tenpay'.$this->orderurl);
        $strDate = date("Ymd");
        $strTime = date("His");
        $randNum = rand(1000, 9999);
        $strReq = $strTime . $randNum;
        $sp_billno = $strReq;
        $transaction_id = $bargainor_id . $strDate . $strReq;
        $total_fee = formatnum($this->paymoney);
        $desc = Sitize("金币抢购");
		$arr['orderid'] = $strDate . $strReq;
		$arr['total_fee'] = $total_fee;
	    $arr['checked'] = 0;
		$arr['uid'] = $this->member['uid'];
		$arr['addtime'] = time();
		$arr['paytype'] =  $_POST['paytype']=='tenpay' ? "财付通" : "网银直充";
		$this->mysql->insert("{$this->pre}payorder",$arr);
        $reqHandler = new PayRequestHandler();
        $reqHandler->init();
        $reqHandler->setKey($tenpaykey);
        $reqHandler->setParameter("bargainor_id",$bargainor_id);//商户号
        $reqHandler->setParameter("sp_billno",$sp_billno);//商户订单号
        $reqHandler->setParameter("transaction_id",$transaction_id);//财付通交易单号
        $reqHandler->setParameter("total_fee",$total_fee*100);//商品总金额,以分为单位
        $reqHandler->setParameter("return_url", $return_url);//返回处理地址
        $reqHandler->setParameter("desc",$desc);//商品名称
        if($_GET['paytype']!='tenpay') $reqHandler->setParameter("bank_type",$_POST['paytype']);
        $reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
        $reqUrl = $reqHandler->getRequestURL(); 
        $javascript = "<script language=\"javascript\">";
        $javascript .= "location.href= '{$reqUrl}';";
        $javascript .= "</script>";
        echo $javascript;
        exit;
    }
	function chinabank(){
	    $v_mid = config::get("v_mid");
	    $key   = config::get("chinabankkey"); 
	    $v_url = config::get("sitedomain").Purl('?mod=pay&act=chinabank'.$this->orderurl);
	    $v_oid = makeorderid(); 
	    $v_amount = formatnum($this->paymoney);            
        $v_moneytype = "CNY";
	    $text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;
        $v_md5info = strtoupper(md5($text));
	    $remark1 = config::get('sitename')."金币抢购";	
	    $remark2 = config::get('sitename')."金币抢购";
		$arr['orderid'] = $v_oid;
		$arr['total_fee'] = $v_amount;
		$arr['checked'] = 0;
		$arr['uid'] = $this->member['uid'];
	    $arr['addtime'] = time();
		$arr['paytype'] = "网银在线";
		$this->mysql->insert("{$this->pre}payorder",$arr);
        require_once(PATH."library/payment/chinabank/bank_lib.php");
        exit();	
	}
	function yeepay(){
        require_once(PATH."library/payment/yeepay/yeepayCommon.php");		
        $p2_Order = makeorderid();
        $p3_Amt = formatnum($this->paymoney);
        $p4_Cur = "CNY";
        $p5_Pid =  iconv("GB2312","UTF-8","金币抢购");
        $p6_Pcat	= $p5_Pid;
        $p7_Pdesc = $p5_Pid;
        $p8_Url = config::get("sitedomain").Purl('?mod=pay&act=yeepay'.$this->orderurl);
        $pa_MP  = $this->member['uid'];
        $pd_FrpId = "";
        $pr_NeedResponse	= "1";
        $hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
		$arr['orderid'] = $p2_Order;
	    $arr['total_fee'] = $p3_Amt;
		$arr['checked'] = 0;
		$arr['uid'] = $this->member['uid'];
		$arr['addtime'] = time();
		$arr['paytype'] = "易宝支付";
		$this->mysql->insert("{$this->pre}payorder",$arr);
        require_once(PATH."library/payment/yeepay/yeepay.php");
        exit();	 	 
    }
	function bill99(){
	    $merchantAcctId = config::get("99bill");
	    $inputCharset = "1";
      	$pageUrl = "";
	    $bgUrl = config::get("sitedomain")."/library/payment/99bill/recieve.php";
	    $version =  "v2.0";
        $language =  "1";
        $signType =  "4";
        $payerName= ""; 
        $payerContactType =  "1";
        $payerContact = $this->member['email'];
        $orderId = makeorderid();
		$money = $this->paymoney;
	    $orderAmount = formatnum($money*100);
        $orderTime = date("YmdHis");
        $productName= iconv("GB2312","UTF-8","金币抢购"); 
        $productNum = "";
        $productId = "";
        $productDesc = "";
        $ext1 = "";
        $ext2 = "";
        $payType = "00";
        $bankId = "";
        $redoFlag = "";
        $pid = "";
        $kq_all_para=kq_ck_null($inputCharset,'inputCharset');
	    $kq_all_para.=kq_ck_null($pageUrl,"pageUrl");
	    $kq_all_para.=kq_ck_null($bgUrl,'bgUrl');
        $kq_all_para.=kq_ck_null($version,'version');
        $kq_all_para.=kq_ck_null($language,'language');
        $kq_all_para.=kq_ck_null($signType,'signType');
        $kq_all_para.=kq_ck_null($merchantAcctId,'merchantAcctId');
        $kq_all_para.=kq_ck_null($payerName,'payerName');
        $kq_all_para.=kq_ck_null($payerContactType,'payerContactType');
        $kq_all_para.=kq_ck_null($payerContact,'payerContact');
        $kq_all_para.=kq_ck_null($orderId,'orderId');
        $kq_all_para.=kq_ck_null($orderAmount,'orderAmount');
        $kq_all_para.=kq_ck_null($orderTime,'orderTime');
        $kq_all_para.=kq_ck_null($productName,'productName');
        $kq_all_para.=kq_ck_null($productNum,'productNum');
        $kq_all_para.=kq_ck_null($productId,'productId');
        $kq_all_para.=kq_ck_null($productDesc,'productDesc');
        $kq_all_para.=kq_ck_null($ext1,'ext1');
        $kq_all_para.=kq_ck_null($ext2,'ext2');
        $kq_all_para.=kq_ck_null($payType,'payType');
        $kq_all_para.=kq_ck_null($bankId,'bankId');
        $kq_all_para.=kq_ck_null($redoFlag,'redoFlag');
        $kq_all_para.=kq_ck_null($pid,'pid');
        $kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);
        $fp = fopen(PATH."library/payment/99bill/99bill-rsa.pem", "r");
	    $priv_key = fread($fp, 8192);
	    fclose($fp);
	    $pkeyid = openssl_get_privatekey($priv_key);
        openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);
        openssl_free_key($pkeyid);
        $signMsg = base64_encode($signMsg);
	    $arr['orderid'] = $orderId;
		$arr['total_fee'] = $money;
		$arr['checked'] = 0;
		$arr['uid'] = $this->member['uid'];
		$arr['addtime'] = time();
	    $arr['paytype'] = "快钱支付";
		$this->mysql->insert("{$this->pre}payorder",$arr);
        require_once(PATH."library/payment/99bill/99bill.php");
        exit();	 	 
	}
	function allinpay(){
	    $serverUrl="";
	    $inputCharset=1;
	    $pickupUrl = config::get("sitedomain").Purl('?mod=pay&act=allinpay'.$this->orderurl);
	    $receiveUrl = config::get("sitedomain").Purl('?mod=pay&act=allinpay'.$this->orderurl);
	    $version = "v1.0";
	    $language = 1;
	    $signType = 1;
	    $merchantId = config::get("allinpay");
	    $payerName = "";
	    $payerEmail = $this->member['email'];	
	    $payerTelephone = "";
	    $payerIDCard = "";
	    $pid = "";
	    $orderNo = makeorderid();
		$money = $this->paymoney;
	    $orderAmount = $money*100;
	    $orderDatetime = date('Ymdhis');
	    $orderCurrency = "0";
	    $orderExpireDatetime = "";
	    $productName = config::get('sitename')."金币抢购";
	    $productId = $this->member['uid'];
	    $productPrice = "";
	    $productNum = "1";
	    $productDescription = "";
	    $ext1 = "";
	    $ext2 = "";
	    $payType = $_POST['payType']; //payType   不能为空，必须放在表单中提交。
	    $issuerId = $_POST['issuerId']; //issueId 直联时不为空，必须放在表单中提交。
	    $pan = "";	
	    $key = config::get("allinpaykey");
	    $bufSignSrc=""; 
	    if($inputCharset != "")
	    $bufSignSrc=$bufSignSrc."inputCharset=".$inputCharset."&";		
	    if($pickupUrl != "")
	    $bufSignSrc=$bufSignSrc."pickupUrl=".$pickupUrl."&";		
	    if($receiveUrl != "")
	    $bufSignSrc=$bufSignSrc."receiveUrl=".$receiveUrl."&";		
	    if($version != "")
	    $bufSignSrc=$bufSignSrc."version=".$version."&";		
	    if($language != "")
	    $bufSignSrc=$bufSignSrc."language=".$language."&";		
	    if($signType != "")
	    $bufSignSrc=$bufSignSrc."signType=".$signType."&";		
	    if($merchantId != "")
	    $bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
	    if($payerName != "")
	    $bufSignSrc=$bufSignSrc."payerName=".$payerName."&";		
	    if($payerEmail != "")
	    $bufSignSrc=$bufSignSrc."payerEmail=".$payerEmail."&";		
	    if($payerTelephone != "")
	    $bufSignSrc=$bufSignSrc."payerTelephone=".$payerTelephone."&";			
	    if($payerIDCard != "")
	    $bufSignSrc=$bufSignSrc."payerIDCard=".$payerIDCard."&";			
	    if($pid != "")
	    $bufSignSrc=$bufSignSrc."pid=".$pid."&";		
	    if($orderNo != "")
	    $bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
	    if($orderAmount != "")
	    $bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
	    if($orderCurrency != "")
	    $bufSignSrc=$bufSignSrc."orderCurrency=".$orderCurrency."&";
	    if($orderDatetime != "")
	    $bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
	    if($orderExpireDatetime != "")
	    $bufSignSrc=$bufSignSrc."orderExpireDatetime=".$orderExpireDatetime."&";
	    if($productName != "")
	    $bufSignSrc=$bufSignSrc."productName=".$productName."&";
	    if($productPrice != "")
	    $bufSignSrc=$bufSignSrc."productPrice=".$productPrice."&";
	    if($productNum != "")
	    $bufSignSrc=$bufSignSrc."productNum=".$productNum."&";
	    if($productId != "")
	    $bufSignSrc=$bufSignSrc."productId=".$productId."&";
	    if($productDescription != "")
	    $bufSignSrc=$bufSignSrc."productDescription=".$productDescription."&";
	    if($ext1 != "")
	    $bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
	    if($ext2 != "")
	    $bufSignSrc=$bufSignSrc."ext2=".$ext2."&";	
	    if($payType != "")
	    $bufSignSrc=$bufSignSrc."payType=".$payType."&";		
	    if($issuerId != "")
	    $bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
	    if($pan != "")
	    $bufSignSrc=$bufSignSrc."pan=".$pan."&";	
	    $bufSignSrc=$bufSignSrc."key=".$key; //key为MD5密钥，密钥是在通联支付网关会员服务网站上设置。
	    $signMsg = strtoupper(md5($bufSignSrc));
        $arr['orderid'] = $orderNo;
		$arr['total_fee'] = $money;
		$arr['checked'] = 0;
		$arr['uid'] = $this->member['uid'];
		$arr['addtime'] = time();
		$arr['paytype'] = $issuerId=='' ? "通联支付" : '网银直冲';
		$this->mysql->insert("{$this->pre}payorder",$arr);
        require_once(PATH."library/payment/allinpay/allinpay.php");
        exit();	 	 
    }
}
?>