$(document).ready(function(){
  if(atmscale!='') showtip("money","提现扣除"+atmscale+"的所得税以及汇款手续费");
  $("#opcardbutton").click(function(){
	var post = true;
    var howmoney = parseInt($("#howmoney").val());
    var mymoney = parseInt($("#mymoney").val());
    var atmbank = $("#atmbank").val();
	if($("#howmoney").val()==''){
	   addtip("howmoney","对不起，请输入提现金额");
	   post = false;
	}else if(!isNum(howmoney)){
	   addtip("howmoney","对不起，提现金额必须是数字");
	   post = false;
	}else if(howmoney%atmmoney!=0||howmoney<atmmoney){
	   addtip("howmoney","提现金额不能小于"+atmmoney+"并且是"+atmmoney+"的倍数");
	   post = false;
	}else if(mymoney<howmoney){
	   addtip("howmoney","对不起，可提现金额不足");
	   post = false;
	}else{
	   yestip("howmoney","");
	}
	if(atmbank==''){
	   addtip("cash","对不起，请选择提现银行");
	   post = false;
    }else{
	   removetip("cash");
	}
	if(post){
      showhandle({
        html:'<table class="lotab"><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
        width:320,
        height:136,
        id:'atmmymoney',
        title:'申请提现'
      },function(){
	    $("#controlLoad").show();
	    removetip("atmmymoney");
        $.getJSON(get_path_url("?mod=member&act=capital&type=myatm&bankid="+atmbank+"&money="+howmoney+"&repass="+$("#repass").val()),function(res){
	      $("#controlLoad").hide();																		 
	      if(res.error=='0'){
			hidebox('atmmymoney',true);
            Right('提现已处理成功。',{},function(){
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
function locatmrecord(url){
  location.href = url;
}
function addbank(){
  sinbox('添加银行',get_path_url("?mod=member&act=bankcard&do=add"),480,350);
}
function editbank(id){
  sinbox('修改银行',get_path_url("?mod=member&act=bankcard&do=edit&id="+id),480,350);
}
function delbank(id){
  showAsk({Msg:"确定要删除该银行卡吗"},function(){
    $.ajax({url:get_path_url("?mod=member&act=bankcard&do=del&id="+id),
      type:'GET',
      dataType:"json",
      success:function(res){ 
	    pagereload();
      }
    });																														 
  },function(){
	Close();  
  });
}
function pagereload(){
  showLoading();
  $.getJSON(get_path_url("?mod=member&act=bankcard&do=list"),function(res){
	if(res==null){
	  $("#banklist").html('');	
	  $("#hasSetBanks").show();	
	  $("#banks").show();
	  $("#bankcount").html(5);
	}else{
	  if(res.length<5){
	    $("#banks").show();
	    $("#bankcount").html(5-res.length);
	  }else{
	    $("#banks").hide();
	  }
	  $("#hasSetBanks").hide();	
	  var div = '';
      for(var i=0;i<res.length;i++) {
	    div += '<tr><td onclick="changebank(\''+res[i].id+'\')" style="cursor:pointer;"><input name="bankid" id="bankid_'+res[i].id+'" type="radio" value="'+res[i].id+'" /> <img src="'+hempdir+'images/chinabank0/'+res[i].bankimages+'"> '+res[i].bankname+'</td>';
	    div += '<td>'+res[i].truename+'</td><td>'+res[i].bankcard+'</td><td>'+res[i].bankadd+'</td>';
	    div += '<td><a href="javascript:editbank(\''+res[i].id+'\');">编辑</a> <a href="javascript:delbank(\''+res[i].id+'\');">删除</a></td>';
	    div += '</tr>';	  
      }	
	  $("#banklist").html(div);
	}
	$("#atmbank").val('');
	Close();					  
  });
}
function checkmoney(){
	var howmoney = parseInt($("#howmoney").val());
	var mymoney = parseInt($("#mymoney").val());
	if($("#howmoney").val()==''){
	   addtip("howmoney","对不起，请输入提现金额");
	}else if(!isNum(howmoney)){
	   addtip("howmoney","对不起，提现金额必须是数字");
	}else if(howmoney%atmmoney!=0||howmoney<atmmoney){
	   addtip("howmoney","提现金额不能小于"+atmmoney+"并且是"+atmmoney+"的倍数");
	}else if(mymoney<howmoney){
	   addtip("howmoney","对不起，可提现金额不足");
	}else{
	   yestip("howmoney","");
	}
}
function changebank(id){
  $("#bankid_"+id).attr('checked',true);
  $("#atmbank").val(id);
  removetip("cash");
}