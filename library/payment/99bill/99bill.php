<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>快钱支付</title>
</head>
<body onLoad="document.allinpay.submit();">
	<form name="allinpay" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">      
				<input type="hidden" name="inputCharset" value="<?PHP echo $inputCharset; ?>" />
				<input type="hidden" name="pageUrl" value="<?PHP echo $pageUrl; ?>" />
				<input type="hidden" name="bgUrl" value="<?PHP echo $bgUrl; ?>" />
				<input type="hidden" name="version" value="<?PHP echo $version; ?>" />
				<input type="hidden" name="language" value="<?PHP echo $language; ?>" />
				<input type="hidden" name="signType" value="<?PHP echo $signType; ?>" />
				<input type="hidden" name="signMsg" value="<?PHP echo $signMsg; ?>" />
				<input type="hidden" name="merchantAcctId" value="<?PHP echo $merchantAcctId; ?>" />
				<input type="hidden" name="payerName" value="<?PHP echo $payerName; ?>" />
				<input type="hidden" name="payerContactType" value="<?PHP echo $payerContactType; ?>" />
				<input type="hidden" name="payerContact" value="<?PHP echo $payerContact; ?>" />
				<input type="hidden" name="orderId" value="<?PHP echo $orderId; ?>" />
				<input type="hidden" name="orderAmount" value="<?PHP echo $orderAmount; ?>" />
				<input type="hidden" name="orderTime" value="<?PHP echo $orderTime; ?>" />
				<input type="hidden" name="productName" value="<?PHP echo $productName; ?>" />
				<input type="hidden" name="productNum" value="<?PHP echo $productNum; ?>" />
				<input type="hidden" name="productId" value="<?PHP echo $productId; ?>" />
				<input type="hidden" name="productDesc" value="<?PHP echo $productDesc; ?>" />
				<input type="hidden" name="ext1" value="<?PHP echo $ext1; ?>" />
				<input type="hidden" name="ext2" value="<?PHP echo $ext2; ?>" />
				<input type="hidden" name="payType" value="<?PHP echo $payType; ?>" />
				<input type="hidden" name="bankId" value="<?PHP echo $bankId; ?>" />
				<input type="hidden" name="redoFlag" value="<?PHP echo $redoFlag; ?>" />
				<input type="hidden" name="pid" value="<?PHP echo $pid; ?>" />
	</form>
</body>
</html>