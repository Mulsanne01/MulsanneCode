function changeusername(){
    $.getJSON(get_url("act=changeusername"),function(res){
      $("#username").val(res.username);
    }); 
}
function checkusername(){
 var username = $("#username").val();
 var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;
 if(username==''){
   addtip("username","请输入您要使用的用户名");
 }else if(username.length<4||username.length>20){
   addtip("username","用户名长度4-20个字符");
 }else if(isFirstNum(username)){
   addtip("username","用户名不能使用数字开头");
 }else if(!testUser.test(username)){
   addtip("username","仅可使用字母、数字、汉字和下划线");
 }else{ 
   $.getJSON(get_url("act=verifyusername&username="+encodeURIComponent(username)),function(res){
     if(res.error=='0'){
       addtip("username",'已注册过，请更换用户名');
     }else{
       yestip("username","用户名可用");
     }
   });        
 }	
}   
function checkuserphone(){
  var userphone = $("#userphone").val();
  if(userphone==''){															 
    addtip("userphone",'请输入您的手机号码');  
  }else if(!isPhone(userphone)){
    addtip("userphone",'请输入有效的手机号码');  
  }else{
    yestip("userphone",'');
  }
}
function checkemail(){
  var email = $("#email").val();
  if(email==''||isEmail(email)){															 
    yestip("email",'');
  }else{
    addtip("email",'请输入有效的邮箱地址');  
  }
}

function checkreferee(){
  var referee = $("#referee").val();
  if(referee==''){
    addtip("referee","对不起，请输入推荐会员");
  }else{
    $.getJSON(get_url("act=verifyusername&username="+encodeURIComponent(referee)),function(res){
      if(res.error=='0'){
        yestip("referee",res.truename);
      }else{
        addtip("referee",res.error);
      }
    }); 
  }
}

function check_referee(){
  var _referee = $("#_referee").val();
  if(_referee==''){
    addtip("_referee","对不起，请输入会员上线");
  }else{
    $.getJSON(get_url("act=verifyusername&username="+encodeURIComponent(_referee)),function(res){
      if(res.error=='0'){
        yestip("_referee",res.truename);
      }else{
        addtip("_referee",res.error);
      }
    }); 
  }
}
function checktruename(){
 if($("#truename").val()==''){
   addtip("truename","请填写玩家姓名。"); 
 }else{
   yestip("truename","");
 }
}

function checkpassword(){
 if($("#password").val()==''){
   addtip("password","至少6位的数字、字母组合。"); 
 }else if($("#password").val().length<6){
   addtip("password","密码长度最小六位！");
 }else{
   yestip("password","");
 }
}   
function checkrepass(){
  if($("#_repass").val()==""){
    addtip("_repass","请再次输入安全密码。");
  }else if($("#_repass").val().length<6){
    addtip("_repass","安全密码长度最小六位！");
  }else{
    yestip("_repass","");
  }
}
function checkservice(){
  var service = $("#service").val();
  if(service==''){															 
    removetip("service",'');
  }else{
    $.getJSON(get_url("act=verifyservice&username="+encodeURIComponent(service)),function(res){																 
      if(res.error=='0'){
	    yestip("service",res.truename);
      }else{
        addtip("service",res.error);
      }							  
    });   
  }
}

function checkgroupid(){
  var groupid = $("#groupid").val();
  if(groupid==''){
    addtip("groupid","对不起，请选择会员级别");
  }else{
    yestip("groupid",'');
	getbuymoney();
  }
}

function getbuymoney(){
  var groupid = $("#groupid").val();
  var mymoney = parseFloat($("#mymoney").val());

    $.ajax({url:get_url("act=getregmoney&groupid="+groupid),
	  type:'GET',
	  success:function(money){		
		if($("#nowopen").val()=='1'){
          yestip("nowopen",'激活会员将扣您'+money,'1');
		}else{ 
          if($("#nowopen").val()==''){
            addtip("nowopen","请选择是否现在激活");
          }else{
            yestip("nowopen",'');
          }
		}
      }
    }); 
}

function checknowopen(){
  var nowopen = $("#nowopen").val();
  if(nowopen==''){
    addtip("nowopen","请选择是否现在激活");
  }else{
	getbuymoney();
  }
}

function checkform(){
  var post = true; 
  var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;    
  if($("#username").val()==''){
    addtip("username","请输入您要使用的用户名");
    post = false;
  }
  if($("#username").val().length<4||$("#username").val().length>20){
    post = false;
  }
  if(isFirstNum($("#username").val())&&post){
    post = false;
  }
  if(!testUser.test($("#username").val())&&post){
    post = false;
  }
  if($("#usernametip").html().indexOf('已注册过') != -1&&post){
    post = false;
  }   
  
  if($("#truename").val()==''){
    addtip("truename","请填写玩家姓名。"); 
	post = false;
  } 
  if(!isPhone($("#userphone").val())){
    addtip("userphone","请输入有效的手机号码"); 
	post = false;
  }  
  if($("#userphone").val()==''){
    addtip("userphone","请输入您的手机号码"); 
	post = false;
  }  
  if($("#referee").val()==''){
	addtip("referee","对不起，请输入推荐会员");
    post = false;
  }   
  if($("#refereetip").html().indexOf('推荐会员不存在') != -1&&post){
    post = false;
  }   

  if($("#password").val()==''){
	addtip("password","对不起，请输入会员登陆密码");
    post = false;
  }  
  if($("#password").val().length<6){
    addtip("password","密码长度最小六位！");
    post = false;
  }  
  if($("#_repass").val()==''){
	addtip("_repass","对不起，请输入安全密码");
    post = false;
  } 
  if($("#_repass").val().length<6){
    addtip("_repass","安全密码长度最小六位！");
    post = false;
  } 
  
  if(post) listTable.register();
  return false;
} 