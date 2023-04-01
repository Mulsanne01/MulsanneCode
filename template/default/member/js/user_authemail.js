$(document).ready(function() {
  $.getScript(appdir + "/placeholder.js");
});
function getemail(){
  showtip("email","请输入常用邮箱，用于登录和找回密码。");
}
function checkemail(){
  var email = $("#email").val();
  if(email==''){
    addtip("email","请输入常用邮箱，用于登录和找回密码。");	
  }else{
    if(!isEmail(email)){
      addtip("email","您输入的邮箱格式错误");	  
    }else{
      $.getJSON(get_url("act=verifyemail&email="+email),function(res){
        if(res.error=='0'){
          addtip("email",'该邮箱已绑定，请更换其他邮箱');
        }else{
          yestip("email","邮箱可用");
        }
      });
	}
  }
}
function checkseccode(){
 var seccode = $("#seccode").val();
 var show = true;
 if(seccode=='请输入验证码') seccode='';
 if((seccode.length!=4||isinCn(seccode))){
   if($("#seccode").val()!='请输入验证码') addtip("seccode","请输入验证码，4个非中文字符！");
 }else{
   $.ajax({url:get_url('&act=verifyseccode&seccode='+seccode),
	 type:'GET',
	 success:function(res){
       if(res=='yes'){
         yestip("seccode",'');
       }else{
         addtip("seccode","您输入的验证码有误");
       }										  
     }
   });
 }
}
function checkform(){
  var post = true; 
  var seccode = $("#seccode").val();
  if(seccode.length!=4||isinCn(seccode)){
    addtip("seccode","请输入验证码，4个非中文字符！");
	post = false;
  }
  if($("#seccodetip").html().indexOf('您输入的验证码有误')!=-1&&post){
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