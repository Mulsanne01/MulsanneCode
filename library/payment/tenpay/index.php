<?php
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("classes/PayRequestHandler.class.php");

/* 商户号 */
$bargainor_id = "1210936901";

/* 密钥 */
$key = "93854588e49b6691e735ad70fbd9262e";

/* 返回处理地址 */
$return_url = "http://localhost/tenpay/return_url.php";

//date_default_timezone_set(PRC);
$strDate = date("Ymd");
$strTime = date("His");

//4位随机数
$randNum = rand(1000, 9999);

//10位序列号,可以自行调整。
$strReq = $strTime . $randNum;

/* 商家订单号,长度若超过32位，取前32位。财付通只记录商家订单号，不保证唯一。 */
$sp_billno = $strReq;

/* 财付通交易单号，规则为：10位商户号+8位时间（YYYYmmdd)+10位流水号 */
$transaction_id = $bargainor_id . $strDate . $strReq;

/* 商品价格（包含运费），以分为单位 */
$total_fee = "1";

/* 商品名称 */
$desc = "订单号：" . $transaction_id;

/* 创建支付请求对象 */
$reqHandler = new PayRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);

//----------------------------------------
//设置支付参数
//----------------------------------------
$reqHandler->setParameter("bargainor_id", $bargainor_id);			//商户号
$reqHandler->setParameter("sp_billno", $sp_billno);					//商户订单号
$reqHandler->setParameter("transaction_id", $transaction_id);		//财付通交易单号
$reqHandler->setParameter("total_fee", $total_fee);					//商品总金额,以分为单位
$reqHandler->setParameter("return_url", $return_url);				//返回处理地址
$reqHandler->setParameter("desc", "订单号：" . $transaction_id);	//商品名称
$reqHandler->setParameter("bank_type", "1002");	//商品名称

//用户ip,测试环境时不要加这个ip参数，正式环境再加此参数
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);

//请求的URL
$reqUrl = $reqHandler->getRequestURL();

//debug信息
//$debugInfo = $reqHandler->getDebugInfo();

//echo "<br/>" . $reqUrl . "<br/>";
//echo "<br/>" . $debugInfo . "<br/>";

//重定向到财付通支付
//$reqHandler->doSend();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>财付通</title>
<style type="text/css">


body { margin: 0px; background-color:#FFFFFF; }
 .font_14{font-family:"Verdana","宋体";font-size:14px;color:#404040;line-height:20px; font-weight:bold;}
.font_15{font-family:"Verdana","宋体";font-size:12px;color:#404040;line-height:20px; font-weight:bold;}
.font_16{font-family:"Verdana","宋体";font-size:12px;color:#595959;line-height:20px; font-weight:100; }
</style>
</head>

<body>
<table width="865" border="0" cellpadding="0" cellspacing="0" align="center">
   <tr>
    <td height="10" colspan="2"></td>  
  </tr>
  <tr>
    <td colspan="2" >
	     <TABLE style="FONT-SIZE: 12px" cellSpacing=1 cellPadding=3 width=762 align=center bgColor=#D8D9DB border=0   >					  
			<tr>
				<td width="754" height="36" style="background:#F1F3F2"  >&nbsp;<img src="images/logo.gif"   />为您提供以下网上支付服务<span style=" color:#868686">（财付通是腾讯旗下第三方支付平台）</span></td>
			</tr>
			<tr>
				<td height="36" style="background:#FFFFFF;  padding-left:20px ">
						<br /><input name="" type="radio" value="" style="vertical-align:top" />&nbsp;<img src="images/10.gif"  /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images//17.gif" /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/2.gif" /><input name="" type="radio" value=""  style="vertical-align:top"  />&nbsp;<img src="images/1.gif" /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/12.gif" /><br />
<br />
<input name="" type="radio" value="" style="vertical-align:top" />&nbsp;<img src="images/8.gif"  /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/27.gif" /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/11.gif" /><input name="" type="radio" value=""  style="vertical-align:top"  />&nbsp;<img src="images/3.gif" /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/4.gif" />
<br />
<input name="" type="radio" value="" style="vertical-align:top" />&nbsp;<img src="images/7.gif"  /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/6.gif" /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/18.gif" /><input name="" type="radio" value=""  style="vertical-align:top"  />&nbsp;<img src="images/16.gif" /><input name="" type="radio" value="" style="vertical-align:top"  />&nbsp;<img src="images/5.gif" />
<br />
<input name="" type="radio" value=""  />&nbsp;&nbsp;财付通账户支付<span style=" color:#868686">（财付通账户余额支付，一点通支付）</span><br /><br /> </td>
			</tr>
        </TABLE>
	</td>  
  </tr>
  <tr>
    <td height="20" colspan="2"></td>  
  </tr>
  
</table>

</body>
</html>
