$(document).ready(function() {
 if(message!='') Wrong(message);
});

function checkform(){
 var post = true; 
 if($("#username").val()==''){
   Wrong("请输入您的用户名");
   post = false;
 } 
 if($("#password").val()==''&&post){
   Wrong("请输入您的登陆密码");
   post = false;
 } 
 if($("#seccode").val()==''&&post){
   Wrong("请输入安全验证码");
   post = false;
 }
 return post;  
} 