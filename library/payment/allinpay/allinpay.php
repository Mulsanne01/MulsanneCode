<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通联支付</title>
</head>
<body onLoad="document.allinpay.submit();">
	<form name="allinpay" action="https://service.allinpay.com/gateway/index.do" method="post">
		<input type="hidden" name="inputCharset" id="inputCharset" value="<?=$inputCharset?>" />
		<input type="hidden" name="pickupUrl" id="pickupUrl" value="<?=$pickupUrl?>"/>
		<input type="hidden" name="receiveUrl" id="receiveUrl" value="<?=$receiveUrl?>" />
		<input type="hidden" name="version" id="version" value="<?=$version ?>"/>
		<input type="hidden" name="language" id="language" value="<?=$language ?>" />
		<input type="hidden" name="signType" id="signType" value="<?=$signType?>"/>
		<input type="hidden" name="merchantId" id="merchantId" value="<?=$merchantId?>" />
		<input type="hidden" name="payerName" id="payerName" value="<?=$payerName?>"/>
		<input type="hidden" name="payerEmail" id="payerEmail" value="<?=$payerEmail?>" />
		<input type="hidden" name="payerTelephone" id="payerTelephone" value="<?=$payerTelephone ?>" />
		<input type="hidden" name="payerIDCard" id="payerIDCard" value="<?=$payerIDCard ?>" />
		<input type="hidden" name="pid" id="pid" value="<?=$pid?>"/>
		<input type="hidden" name="orderNo" id="orderNo" value="<?=$orderNo?>" />
		<input type="hidden" name="orderAmount" id="orderAmount" value="<?=$orderAmount ?>"/>
		<input type="hidden" name="orderCurrency" id="orderCurrency" value="<?=$orderCurrency?>" />
		<input type="hidden" name="orderDatetime" id="orderDatetime" value="<?=$orderDatetime?>" />
		<input type="hidden" name="orderExpireDatetime" id="orderExpireDatetime" value="<?=$orderExpireDatetime ?>"/>
		<input type="hidden" name="productName" id="productName" value="<?=$productName?>" />
		<input type="hidden" name="productPrice" id="productPrice" value="<?=$productPrice?>" />
		<input type="hidden" name="productNum" id="productNum" value="<?=$productNum ?>"/>
		<input type="hidden" name="productId" id="productId" value="<?=$productId?>" />
		<input type="hidden" name="productDescription" id="productDescription" value="<?=$productDescription?>" />
		<input type="hidden" name="ext1" id="ext1" value="<?=$ext1?>" />
		<input type="hidden" name="ext2" id="ext2" value="<?=$ext2?>" />
		<input type="hidden" name="payType" value="<?=$payType?>" />
		<input type="hidden" name="issuerId" value="<?=$issuerId?>" />
		<input type="hidden" name="pan" value="<?=$pan?>" />
		<input type="hidden" name="signMsg" id="signMsg" value="<?=$signMsg ?>" />	
	</form>
</body>
</html>