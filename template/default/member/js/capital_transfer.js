var resendtime;
var sel;
$(document).ready(function(){
  $.getScript(appdir + "/placeholder.js");	  		   
  $("#opcardbutton").click(function(){
	var post = true;
    var howmoney = parseFloat($("#howmoney").val());
    var mymoney = parseFloat($("#mymoney").val());
	var userphone = $("#userphone").val();
    if($("#username").val()==''||$("#username").val()=='用户名/电子邮箱/绑定手机'){
      addtip("username","请输入用户名/电子邮箱/绑定手机");
      post = false;
    }
    if($("#usernametip").html().indexOf('对不起') != -1){
      post = false;
    }  
	if($("#howmoney").val()==''){
	   addtip("howmoney","对不起，请输入转账金额");
	   post = false;
	}else if(!isNum(howmoney)){
	   addtip("howmoney","对不起，转账金额必须是数字");
	   post = false;
	}else if(howmoney%100!=0||howmoney<100){
	   addtip("howmoney","转账金额不能小于100并且是整百");
	}else if(mymoney<howmoney){
	   addtip("howmoney","对不起，可转账金额不足");
	   post = false;
	}else{
	   yestip("howmoney","");
	}
	if(post){
      showhandle({
        html:'<table class="lotab"><tr><th><label>绑定手机：</label></th><td><span class="dis-input phonerepass">'+userphone+'</span></td></tr><tr><th><label>验 证 码：</label></th><td><input id="checkcode" class="myinput phonecheckcode" type="input"><input class="smsbut phonecheckcode" style="font-size:12px;" type="button" id="bind_mobile_btn" onclick="bind_mobile_btn(this)" value="获取验证码"  /></td></tr><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput phonerepass" type="password"></td></tr></table>',
        width:400,
        height:256,
        id:'atmmymoney',
        title:'金币转账'
      },function(){
	    $("#controlLoad").show();
	    removetip("atmmymoney");
        $.getJSON(get_path_url("?mod=member&act=capital&type=transfer&username="+encodeURIComponent($("#username").val())+"&money="+howmoney+"&repass="+$("#repass").val()+"&checkcode="+$("#checkcode").val()),function(res){
	      $("#controlLoad").hide();																		 
	      if(res.error=='0'){
			hidebox('atmmymoney',true);
            Right('金币转账成功。',{},function(){
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


function bind_mobile_btn(obj){
	var l = 1;
	var _this = $(obj);
	_this.attr("disabled",true);
	_this.addClass("smsbut-dis");
	_this.val("获取中");	
    var ld = setInterval(function(){
	  var ltext = l==0 ? "获取中" : (l==1 ? "获取中·" : (l==2 ? "获取中··" : "获取中···"));
	  _this.attr("disabled",true);
	  _this.addClass("smsbut-dis");
	  _this.val(ltext);							   
	  l++;
	  if(l>3) l=0;
    },1000);
    $.getJSON(get_url("act=getphonecode&type=transfer"),function(res){												   
	  _this.attr("disabled",true);
	  _this.addClass("smsbut-dis");
	  _this.val("重新获取(120)");
      resendtime = 120;
	  bindmobile();	
      clearInterval(ld);
    });
}

function bindmobile(){
  var mobile_btn_id = "bind_mobile_btn";
  if(resendtime < 0){
	resendtime = 0;
  }else{
    $("#"+mobile_btn_id).attr("disabled",true);
    $("#"+mobile_btn_id).val("重新获取("+resendtime+")");
    $("#"+mobile_btn_id).addClass("smsbut-dis");
  }
  if(resendtime>=0){
    sel = setInterval(function(){
      if(resendtime==0){
         $("#"+mobile_btn_id).attr("disabled",false);
         $("#"+mobile_btn_id).val("获取验证码");
		 $("#"+mobile_btn_id).removeClass("smsbut-dis");
         clearInterval(sel);
         return;
      }else{
         resendtime--;
         $("#"+mobile_btn_id).attr("disabled",true);
         $("#"+mobile_btn_id).val("重新获取("+resendtime+")");
		 $("#"+mobile_btn_id).addClass("smsbut-dis");
	  }
    },1000);
  }
}    

function checkmoney(){
	var howmoney = parseFloat($("#howmoney").val());
	var mymoney = parseFloat($("#mymoney").val());
	if($("#howmoney").val()==''){
	   addtip("howmoney","对不起，请输入转账金额");
	}else if(!isNum(howmoney)){
	   addtip("howmoney","对不起，转账金额必须是数字");
	}else if(howmoney%100!=0||howmoney<100){
	   addtip("howmoney","转账金额不能小于100并且是整百");
	}else if(mymoney<howmoney){
	   addtip("howmoney","对不起，可转账金额不足");
	}else{
	   yestip("howmoney","");
	}
}
function checkusername(){
  var show = true,username = $("#username").val();
  if(username=='用户名/电子邮箱/绑定手机'){
    username='';
    show = false;
  }
  if(username==''&&show){
    addtip("username","请输入用户名/电子邮箱/绑定手机");
  }else if(show){
    $.getJSON(get_url("act=verifyusername&transfer=1&username="+encodeURIComponent(username)),function(res){
      if(res.error=='0'){
        yestip("username",res.truename);
      }else{
        addtip("username",res.error);
      }
	});
  }
}