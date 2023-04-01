$(document).ready(function(){
  $("#opcardbutton").click(function(){
	var post = true;
    var howmoney = parseFloat($("#howmoney").val());
    var mymoney = parseFloat($("#mymoney").val());
	var method = $("#method").val();
	if($("#howmoney").val()==''){
	   addtip("howmoney","对不起，请输入转换金额");
	   post = false;
	}else if(!isNum(howmoney)){
	   addtip("howmoney","对不起，转换金额必须是数字");
	   post = false;
	}else if(howmoney<0.01){
	   addtip("howmoney","转换金额不能小于0");
	   post = false;
	}else if(mymoney<howmoney){
	   addtip("howmoney","对不起，可转换金额不足");
	   post = false;
	}else{
	   yestip("howmoney","");
	}
	if(post){
      showhandle({
        html:'<table class="lotab"><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
        width:320,
        height:136,
        id:'atmmymoney',
        title:'资金转换'
      },function(){
	    $("#controlLoad").show();
	    removetip("atmmymoney");
        $.getJSON(get_path_url("?mod=member&act=capital&type=change&money="+howmoney+"&method="+method+"&repass="+$("#repass").val()),function(res){
	      $("#controlLoad").hide();																		 
	      if(res.error=='0'){
			hidebox('atmmymoney',true);
            Right('资金转换成功。',{},function(){
			  location.href = res.url;
			});
	      }else{
		    addtip("atmmymoney",res.error);
	      }							  
        });
      });	
	}
  });
});
function checkmoney(){
	var howmoney = parseFloat($("#howmoney").val());
	var mymoney = parseFloat($("#mymoney").val());
	if($("#howmoney").val()==''){
	   addtip("howmoney","对不起，请输入转换金额");
	}else if(!isNum(howmoney)){
	   addtip("howmoney","对不起，转换金额必须是数字");
	}else if(howmoney<0.01){
	   addtip("howmoney","转换金额不能小于0");
	}else if(mymoney<howmoney){
	   addtip("howmoney","对不起，可转换金额不足");
	}else{
	   yestip("howmoney","");
	}
}