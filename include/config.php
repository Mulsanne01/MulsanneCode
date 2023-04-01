<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
return array (
'dbhost'=>'localhost',	// 数据库服务器
'dbuser'=>'yanshi',	// 数据库用户名
'dbpassword'=>'z2806522',	// 数据库密码
'dbname'=>'yanshi',	// 数据库名
'tablepre'=>'sinbegin_', //数据库前缀
'dbconn'=>'conn',  //数据库连接标示
'pconnect'=>false,	//是否持久连接
'charset'=>'utf8', //数据库编码
"error"=>"1", //是否开启报错
"rebug"=>"1", //是否调试
'openfile'=>'1', //
'uploadpath'=>'upload', //附件路径
"filesize"=>"2048", //附件大小限制
"filetype"=>".gif|.jpg|.png|.jpeg", //附件扩展名
"filecount"=>"8", //批量上传个数
"mustcode"=>"1",
"rewrite"=>"0",
"sitepath"=>"/",
"cookiedomain"=>"dm.com",
"template_dir"=>"default",
"paytype"=>array('tenpaybank','alipay','tenpay','99bill','chinabank','yeepay','allinpay'),
"tenpaybank"=>array('1002','1003','1005','1020','1052','1001','1009','1027','1008','1006','1022','1004','1032','1010','1021','1025','1024','1028'),
"allinpaybank"=>array('allinpay_cmb','allinpay_icbc','allinpay_ccb','allinpay_abc','allinpay_cmbc','allinpay_spdb','allinpay_cgb','allinpay_cib','allinpay_ceb','allinpay_comm','allinpay_boc','allinpay_citic','allinpay_bos','allinpay_pingan','allinpay_psbc'),
"chinabank0"=>array('1025','3080','105','103','104','301','311','309','305','306','307','314','313','312','316','3230','324','302','310','326','342','335','336'),
"chinabank1"=>array('1027','308','1054','106','3112','3051','3121','3231','3241','303','3261','301','309','307','334'),
"cookie"=>"0", //{title=储存方式}{prompt=设置登录信息储存方式，一般cookie}{type=radio}{values=[<1><Cookie>][<0><Session>]}{option=}{unit=}
"managertime"=>"3600", //{title=后台失效}{prompt=以秒为单位，如一个小时为3600秒}{type=input}{values=}{option=class="skey"}{unit=}	
"membertime"=>"3600", //{title=前台失效}{prompt=以秒为单位，如一个小时为3600秒}{type=input}{values=}{option=class="skey"}{unit=}
"bankname"=>"工商银行,邮政银行,建设银行,农业银行,招商银行,中国银行,支付宝,财付通", //{title=提现银行}{prompt=提现支持银行列表，每个以","隔开}{type=textarea}{values=}{option=class="textarea1" cols="40" rows="5"}{unit=}
"pagetitle"=>"", //{title=网站标题}{prompt=网站首页标题，title标签}{type=input}{values=}{option=class="skey"}{unit=}
"keywords"=>"", //{title=网站关键}{prompt=每个之间以“,”隔开。}{type=input}{values=}{option=class="skey"}{unit=}
"description"=>"", //{title=网站描述}{prompt=搜索引擎抓取到的网站的描述。}{type=textarea}{values=}{option=class="textarea1" cols="40" rows="5"}{unit=}
"adminpagesize"=>"20", //{title=后台分页}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}
"pagesize"=>"10", //{title=前台分页}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}
"express"=>"顺丰快递,中通快递,圆通快递,邮政快递", //{title=发货快递}{prompt=发货快递，每个以“,”隔开。}{type=textarea}{values=}{option=class="textarea1" cols="40" rows="5"}{unit=}
"adminpagesize"=>"20", //{title=后台分页}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}
"siteicp"=>"", //{title=备案号码}{prompt=}{type=input}{values=}{option=class="skey""}{unit=}
"adminemail"=>"", //{title=管理邮箱}{prompt=错误接收邮箱，非常重要。}{type=input}{values=}{option=class="skey"}{unit=}
"siteaddress"=>"", //{title=公司地址}{prompt=}{type=input}{values=}{option=class="skey""}{unit=}
"sitephone"=>"", //{title=客服电话}{prompt=}{type=input}{values=}{option=class="skey""}{unit=}
//} 

//basic-基本设置{
"siteurl"=>"/", //{title=网站目录}{prompt=一般情况下不建议修改。}{type=input}{values=[<1><开启>][<0><关闭>]}{option=class="skey"}{unit=}
"sitename"=>"会员管理系统", //{title=网站名称}{prompt=网站的名称。}{type=input}{values=[<1><开启>][<0><关闭>]}{option=class="skey"}{unit=}
"sitedomain"=>"http://www.zhixiao360.com", //{title=网站域名}{prompt=网站的名称，前面带http://。}{type=input}{values=}{option=class="skey"}{unit=}
"closed"=>"0", //{title=关闭前台}{prompt=}{type=radio}{values=[<1><是>][<0><否>]}{option=}{unit=}
"closemsg"=>"升级维护中，预计1小时候可以访问。", //{title=关闭原因}{prompt=网站关闭提示信息。}{type=textarea}{values=}{option=class="textarea1" cols="40" rows="5"}{unit=}
"atmscale"=>"", //{title=综合税金}{prompt=提现将扣除综合税金如：10%，不收手续费请留空。}{type=input}{values=}{option=class="skey"}{unit=}
"paychecked"=>"支付宝：200113113@163.COM（户名：张三）<br>财付通：22654111（户名：张三）<br>农行卡：231312315645464（户名：张三）<br>工行卡：3211321231321（户名：张三）<br>联系电话：1512111516", //{title=汇款方式}{prompt=汇款方式每个以“< br >”隔开。}{type=textarea}{values=}{option=class="textarea1" cols="40" rows="5" style="width:360px;height:120px;"}{unit=}
"paytime"=>"9到22", //{title=交易时间}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}
"atmmoney"=>"300", //{title=提现倍数}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}
//}

//show-显示设置{

//}

//email-邮件设置{
"mailsend"=>"1", //{title=邮件发送方式}{prompt=}{type=select}{values=[<1><使用sendmail发送(推荐此方式)>][<0><使用socket连接服务器发送>]}{option=style="width:324px;"}{unit=}
"mailserver"=>"smtp.qq.com", //{title=SMTP服务器}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}	
"mailport"=>"587", //{title=SMTP端口}{prompt=默认为 25}{type=input}{values=}{option=class="skey" style="width:60px;"}{unit=}	
"mailauth"=>"1", //{title=要求身份验证}{prompt=}{type=radio}{values=[<1><是>][<0><否>]}{option=}{unit=}	
"mailfrom"=>"df", //{title=发信人地址}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}	
"mailusername"=>"sdfasdf", //{title=SMTP用户名}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}	
"mailpassword"=>"6866226", //{title=SMTP密码}{prompt=}{type=password}{values=}{option=class="skey"}{unit=}	
//}

//pay-支付配置{
"howpay"=>"chinabank", //{title=默认支付方式}{prompt=选择默认的支付方式}{type=radio}{values=[<alipay><支付宝>][<chinabank><网银在线>][<tenpay><财付通>][<tenpaybank><财付通网银直连>][<yeepay><易宝支付>][<allinpay><通联支付>][<99bill><快钱支付>]}{option=}{unit=}
"tenpayopen"=>"0", //{title=财付通开关}{prompt=是否支持财付通在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"tenpaybankopen"=>"0", //{title=网银直连开关}{prompt=是否支持财付通网银直连在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"tenpay"=>"1212721901", //{title=财付通商户号}{prompt=财付通商户号}{type=input}{values=}{option=class="skey"}{unit=}
"tenpaykey"=>"d53e4346d3cf269add0c57ea0c73be53", //{title=财付通密钥}{prompt=财付通密钥}{type=input}{values=}{option=class="skey"}{unit=}		

"alipayopen"=>"0", //{title=支付宝支付开关}{prompt=是否支持支付宝在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"seller_email"=>"adddn@163.com", //{title=支付宝帐户}{prompt=您登陆支付宝使用的账户}{type=input}{values=}{option=class="skey"}{unit=}	
"partner"=>"2088701941735344", //{title=合作者身份ID}{prompt=商家账户合作者身份ID}{type=input}{values=}{option=class="skey"}{unit=}
"alipaykey"=>"1j65ckl5a6hgbs5sx82zg9fwi84n22lx", //{title=交易安全校验码}{prompt=商家账户所使用的交易安全码}{type=input}{values=}{option=class="skey"}{unit=}

"chinabankopen"=>"1", //{title=网银在线开关}{prompt=是否支持网银在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"v_mid"=>"22788123494", //{title=网银在线ID}{prompt=您登陆网银在线使用的账户}{type=input}{values=}{option=class="skey"}{unit=}	
"chinabankkey"=>"67B899V9Vbb6.vgr68rjH.66", //{title=网银在线KEY}{prompt=网银在线KEY}{type=input}{values=}{option=class="skey"}{unit=}

"yeepayopen"=>"0", //{title=易宝支付开关}{prompt=是否支持易宝在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"yeepay"=>"10011825193dd", //{title=易宝商户号}{prompt=易宝商户号}{type=input}{values=}{option=class="skey"}{unit=}
"yeepaykey"=>"m28F8G34pSdd0267U1V49Lm9546Fl3YM4lsIA2ae59k5sfY20x80D4K5TnK4z3", //{title=易宝支付通密钥}{prompt=易宝支付通密钥}{type=input}{values=}{option=class="skey"}{unit=}

"allinpayopen"=>"0", //{title=通联支付开关}{prompt=是否支持通联在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"allinpay"=>"1091275511401001", //{title=通联商户号}{prompt=通联商户号}{type=input}{values=}{option=class="skey"}{unit=}
"allinpaykey"=>"12345678901", //{title=通联支付通密钥}{prompt=通联支付通密钥}{type=input}{values=}{option=class="skey"}{unit=}

"99billopen"=>"0", //{title=快钱支付开关}{prompt=是否支持快钱在线充值}{type=radio}{values=[<1><打开>][<0><关闭>]}{option=}{unit=}
"99bill"=>"10021143844301", //{title=快钱商户号}{prompt=快钱商户号}{type=input}{values=}{option=class="skey"}{unit=}
//}

//user-用户设置{


//}

//sms-短信配置{
"smsuname"=>"cloveyb", //{title=短信用户}{prompt=}{type=input}{values=}{option=class="skey"}{unit=}
"smspwd"=>"tkggjeso", //{title=短信密码}{prompt=}{type=password}{values=}{option=class="skey"}{unit=}	
"smsapi"=>"http://api.smsbao.com/", //{title=短信接口}{prompt=发送短信仅支持HTTP接口，接口填写方式请联系系统开发商。}{type=textarea}{values=}{option=class="textarea1" cols="40" rows="5"}{unit=}
//}

);
?>