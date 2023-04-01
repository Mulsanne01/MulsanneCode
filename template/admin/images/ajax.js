function moneyedit(){
	var content = $('#content').val();
	var rank_regmoney = $('#rank_money').val();
	var post = true;
	if(content==''){
	  addtip("content",'请务必填写变动原因！');
	  post = false;
	}
    if(rank_regmoney==0&&post){
	  addtip("content",'必须填写变动金额！');
      post = false;
    }
	if(post){
	  document.getElementById('subform').submit();
	}
} 
function checkbankcard(){
	var bankcard = document.getElementById('bankcard').value;
	if(bankcard==''){
	   document.getElementById('bankcardtip').innerHTML='开户地址不能为空';
	}else{
	   document.getElementById('bankcardtip').innerHTML='';
	}
}