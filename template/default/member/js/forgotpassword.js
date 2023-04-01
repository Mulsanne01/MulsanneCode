function checkusername(){
 var username = $("#username").val();
 if(username==''){
   addtip("username","请输入玩家编号");
 }else{ 
   $.getJSON(get_url("act=verifyusername&username="+encodeURIComponent(username)),function(res){
     if(res.error=='0'){
	   yestip("username","");
     }else{
       addtip("username",'玩家编号有误');
     }
   });        
 }	
}

function checkq1(){
 if($("#q1").val()==''){
   addtip("q1","请选择安全问题"); 
 }else{
   yestip("q1","");
 }
}
function checkq2(){
 if($("#q2").val()==''){
   addtip("q2","请选择安全问题"); 
 }else{
   yestip("q2","");
 }
}
function checkq3(){
 if($("#q3").val()==''){
   addtip("q3","请选择安全问题"); 
 }else{
   yestip("q3","");
 }
}

function checka1(){
 if($("#a1").val()==''){
   addtip("a1","请输入安全问题答案"); 
 }else{
   yestip("a1","");
 }
}
function checka2(){
 if($("#a2").val()==''){
   addtip("a2","请输入安全问题答案"); 
 }else{
   yestip("a2","");
 }
}
function checka3(){
 if($("#a3").val()==''){
   addtip("a3","请输入安全问题答案"); 
 }else{
   yestip("a3","");
 }
}

function checkpassword(){
 if($("#password").val()==''){
   addtip("password","请输入登录密码。"); 
 }else if($("#password").val().length<6){
   addtip("password","密码长度最小六位！");
 }else{
   yestip("password","");
 }
}   
function checkrepass(){
  if($("#repass").val()==""){
    addtip("repass","请输入安全密码。");
  }else if($("#repass").val().length<6){
    addtip("repass","安全密码长度最小六位！");
  }else{
    yestip("repass","");
  }
}

function checkform(){
  var post = true; 
  
  if($("#username").val()==''){
    addtip("username","请输入玩家编号");
    post = false;
  }
  if($("#usernametip").html().indexOf('玩家编号有误') != -1&&post){
    post = false;
  }   
  
  
  if($("#q1").val()==''){
    addtip("q1","请选择安全问题");
    post = false;
  }
  if($("#q2").val()==''){
    addtip("q2","请选择安全问题");
    post = false;
  }
  if($("#q3").val()==''){
    addtip("q3","请选择安全问题");
    post = false;
  }
  if($("#a1").val()==''){
    addtip("a1","请输入安全问题答案");
    post = false;
  }
  if($("#a2").val()==''){
    addtip("a2","请输入安全问题答案");
    post = false;
  }
  if($("#a3").val()==''){
    addtip("a3","请输入安全问题答案");
    post = false;
  }  
  if($("#password").val().length<6){
    addtip("password","密码长度最小六位！");
    post = false;
  } 
  if($("#password").val()==''){
	addtip("password","请输入登陆密码");
    post = false;
  }  
 
  if($("#repass").val().length<6){
    addtip("repass","安全密码长度最小六位！");
    post = false;
  } 
  
  if($("#repass").val()==''){
	addtip("repass","请输入安全密码");
    post = false;
  } 
  return post;
} 
