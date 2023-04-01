function backmoney(id){
  showhandle({
    html:'<table class="lotab"><tr><th><label>退款原因：</label></th><td><textarea name="message" id="message" class="textarea"></textarea></td></tr><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
    width:320,
    height:230,
    id:'backmoney',
    title:'申请退款'
  },function(){
	$("#controlLoad").show();
	removetip("backmoney");
    $.getJSON(get_url('act=backmoney&repass='+$("#repass").val()+'&id='+id+'&message='+encodeURIComponent($("#message").val())),function(res){
	  $("#controlLoad").hide();																		 
	  if(res.error=='0'){
		hidebox('backmoney',true);
		$("#check_user_"+id).html('<span class="ordtin">退款中</span>');
		location.href = location.href;
	  }else{
		addtip("backmoney",res.error);
	  }							  
    });
  });
}

function cancelorder(id){
  showhandle({
    html:'<table class="lotab"><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
    width:320,
    height:130,
    id:'cancelorder',
    title:'取消订单'
  },function(){
	$("#controlLoad").show();
	removetip("cancelorder");
    $.getJSON(get_url('act=cancelorder&repass='+$("#repass").val()+'&id='+id),function(res){
	  $("#controlLoad").hide();																		 
	  if(res.error=='0'){
		hidebox('cancelorder',true);
		location.reload();
	  }else{
		addtip("cancelorder",res.error);
	  }							  
    });
  });
}

function yeshave(id){
  showhandle({
    html:'<table class="lotab"><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
    width:320,
    height:130,
    id:'yeshave',
    title:'确认收货'
  },function(){
	$("#controlLoad").show();
	removetip("yeshave");
    $.getJSON(get_url('act=yeshave&repass='+$("#repass").val()+'&id='+id),function(res){
	  $("#controlLoad").hide();																		 
	  if(res.error=='0'){
		hidebox('yeshave',true);
		$("#check_user_"+id).html('<span class="ordtin">已成交</span>');
	  }else{
		addtip("yeshave",res.error);
	  }							  
    });
  });
}
              