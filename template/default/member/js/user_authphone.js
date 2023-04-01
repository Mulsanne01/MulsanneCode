var resendtime;
var sel;
$(document).ready(function() {
  $.getScript(appdir + "/placeholder.js");
  $("#bind_mobile_btn").click(function(){
	var l = 1;
	var _this = $(this);
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
    if(isPhone($("#userphone").val())){
      $.getJSON(get_url("act=getphonecode&type=authphone&userphone="+$("#userphone").val()),function(res){
		if(res.error=='0'){														   
	      _this.attr("disabled",true);
	      _this.addClass("smsbut-dis");
	      _this.val("重新获取(120)");
          resendtime = 120;
	      bindmobile();	
          clearInterval(ld);
		}else{
          _this.attr("disabled",false);
          _this.val("获取验证码");
		  _this.removeClass("smsbut-dis");
          clearInterval(ld);
		  addtip("userphone",res.error);	
		}
      });
	}else{
      $(this).attr("disabled",false);
      $(this).val("获取验证码");
      $(this).removeClass("smsbut-dis");
      clearInterval(ld);
	  addtip("userphone","请输入正确的11位手机号码");
	}
  });
  $("#mobile_btn").click(function(){
    if(isPhone($("#newphone").val())){
      $.getJSON(get_url("act=getphonecode&type=authphone&userphone="+$("#newphone").val()+"&double=1"),function(res){
		if(res.error=='0'){														   
	      $(this).attr("disabled",true);
	      $(this).addClass("smsbut-dis");
	      $(this).val("重新获取(120)");
          resendtime = 120;
	      bindmobile();	
		}else{
		  addtip("newphone",res.error);	
		}
      });
	  removetip("newphone");
	}else{
	  addtip("newphone","请输入正确的11位手机号码");
	}
  });
  resendtime = $("#resendtime").val();
  bindmobile();
});
function bindmobile(){
  var mobile_btn_id = $("#user_mobile_btn").val();
  if(resendtime < 0){
	resendtime = 0;
  }else{
    $("#"+mobile_btn_id).attr("disabled",true);
    $("#"+mobile_btn_id).val("重新获取("+resendtime+")");
    $("#"+mobile_btn_id).addClass("smsbut-dis");
	showtip("userphone","已发送，2分后可重新获取。");  
	showtip("mobile_btn","已发送，2分后可重新获取。");	
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
function checkcheckcode(){
  var checkcode = $("#checkcode").val();
  if(checkcode=='手机接收到的验证码'||checkcode=='原手机验证码') checkcode = '';
  if(checkcode==""){
    if($("#checkcode").val()!='手机接收到的验证码'&&$("#checkcode").val()!='原手机验证码') addtip("checkcode","请输入验证码。");
  }else{
    $.getJSON(get_url("act=verifyphonecode&code="+checkcode),
	   function(res){
		 if(res.error=='1'){														   
	       yestip("checkcode","");	
		 }else{
		   addtip("checkcode","验证码不正确。");	
		 }
       }
	);
  }
}
function checkphonecode(){
  var phonecode = $("#phonecode").val()=='新手机验证码' ? '' : $("#phonecode").val();
  if(phonecode==""){
    if($("#phonecode").val()!='新手机验证码') addtip("phonecode","请输入验证码。");
  }else{	  
    $.getJSON(get_url("act=verifyphonecode&phone=new&code="+phonecode),
	   function(res){
		 if(res.error=='1'){														   
	       yestip("phonecode","");	
		 }else{
		   addtip("phonecode","验证码不正确。");	
		 }
       }
	);
  }
}
function checkform(){
  var post = true; 
  if(!isPhone($("#userphone").val())){
    addtip("userphone","请输入正确的11位手机号码");
	post = false;
  }
  if($("#checkcode").val()==''||$("#checkcode").val()=='手机接收到的验证码'){
    addtip("checkcode","请输入验证码。");
    post = false;
  }
  if($("#checkcodetip").html().indexOf('验证码不正确')!=-1&&post){
    post = false;
  }
  return post;  
} 
function doubleform(){
  var post = true; 
  if(!isPhone($("#newphone").val())){
    addtip("newphone","请输入正确的11位手机号码");
	post = false;
  }else{
    if($("#checkcode").val()==''||$("#checkcode").val()=='手机接收到的验证码'||$("#checkcode").val()=='原手机验证码'){
      addtip("checkcode","请输入验证码。");
      post = false;
    }
    if($("#checkcodetip").html().indexOf('验证码不正确')!=-1&&post){
      post = false;
    }
    if($("#phonecode").val()==''||$("#checkcode").val()=='新手机验证码'){
      addtip("phonecode","请输入验证码。");
      post = false;
    }
    if($("#phonecodetip").html().indexOf('验证码不正确')!=-1&&post){
      post = false;
    }
  }
  return post;  
} 