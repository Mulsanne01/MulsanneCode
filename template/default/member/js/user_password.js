var paypasswdtip = paypasswdtip==null ? "" : paypasswdtip;
function getoldpassword(){
  showtip("oldpassword","请输入原始"+paypasswdtip+"密码。");
}      
function checkoldpassword(){
 if($("#oldpassword").val()==''){
   addtip("oldpassword","请输入原始"+paypasswdtip+"密码。"); 
 }else{
   yestip("oldpassword","");
 }
}
function getpassword(){
  showtip("password","至少6位的数字、字母组合，区分大小写。");
  $(".grade-pwd").show();
  $(".grade-pwd").parent().parent().height(72);
}      
function checkpassword(){
 if($("#password").val()==''){
   addtip("password","至少6位的数字、字母组合，区分大小写。"); 
 }else if($("#password").val().length<6){
   addtip("password",paypasswdtip+"密码长度最小六位！");
 }else if($(".grade-text").text() == "弱"){
   addtip("password","强度不够，请用字母数字等混合输入。");
 }else{
   yestip("password","");
   checkcpassword();
 }
}
function getcpassword(){
  showtip("cpassword","请再次输入新的"+paypasswdtip+"密码。");
}    
function checkcpassword(){
  var cpassword = $("#cpassword").val();
  var password = $("#password").val();
  if(cpassword==""){
    addtip("cpassword","请再次输入新的"+paypasswdtip+"密码。");
  }else if(cpassword!=password){
    addtip("cpassword","两次输入密码不一致。");
  }else{
    yestip("cpassword","");
  }
}
function checkform(){
  var post = true; 
  if($("#oldpassword").val()==''){
    addtip("oldpassword","请输入原始"+paypasswdtip+"密码。");
    post = false;
  }
  if($("#password").val()==''){
    addtip("password","至少6位的数字、字母组合，区分大小写。");
    post = false;
  }
  if($("#password").val().length<6&&post){
	post = false;
  }
  if($(".grade-text").text() == "弱"){
	post = false;
  }
  if($("#cpassword").val()==""){
    addtip("cpassword","请再次输入新的"+paypasswdtip+"密码。");
	post = false;
  }
  if($("#cpassword").val()!=$("#password").val()&&post){
	post = false;
  }
  return post;  
} 