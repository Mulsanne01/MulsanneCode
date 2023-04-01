<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>网银在线支付</title>
</head>
<body onLoad="javascript:document.chinabank.submit()">
<form method="post" name="chinabank" action="https://Pay3.chinabank.com.cn/PayGate">
  <input type="hidden" name="v_mid"         value="<?php echo $v_mid;?>">
  <input type="hidden" name="v_oid"         value="<?php echo $v_oid;?>">
  <input type="hidden" name="v_amount"      value="<?php echo $v_amount;?>">
  <input type="hidden" name="v_moneytype"   value="<?php echo $v_moneytype;?>">
  <input type="hidden" name="v_url"         value="<?php echo $v_url;?>">
  <input type="hidden" name="v_md5info"     value="<?php echo $v_md5info;?>">
  <input type="hidden" name="remark1"       value="<?php echo $remark1;?>">
  <input type="hidden" name="remark2"       value="<?php echo $remark2;?>">
</form>
</body>
</html>