$(document).ready(function(){
  $(".fuhebing").click(function(){
	var uid = $(this).attr('uid');
	var post = true;
	if(post){
      showhandle({
        html:'<table class="lotab"><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
        width:320,
        height:136,
        id:'atmmymoney',
        title:'合并余额到主账户'
      },function(){
	    $("#controlLoad").show();
	    removetip("atmmymoney");
        $.getJSON(get_path_url("?mod=member&act=user&type=fuser&re=ajax&do=hebing&uid="+uid+"&repass="+$("#repass").val()),function(res){
	      $("#controlLoad").hide();																		 
	      if(res.error=='0'){
			hidebox('atmmymoney',true);
            Right('余额已经合并成功。',{},function(){
			  location.href = res.url;
			});
	      }else{
		    addtip("atmmymoney",res.error);
	      }							  
        });
      });	
	}
  })
});
function checkusername(){
 var username = $("#username").val();
 var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;
 if(username==''){
   addtip("username","请输入副账号用户名");
 }else{ 
   $.getJSON(get_url("act=verifyusername&username="+encodeURIComponent(username)),function(res){
     if(res.error=='0'){
	   yestip("username","");
     }else{
       addtip("username",'该用户名不存在');
     }
   });        
 }	
}  
function checkrepass(){
 if($("#repass").val()==''){
   addtip("repass","请输入要绑定的副账号安全密码。"); 
 }else{
   yestip("repass","");
 }
}
function checkform(){
  var post = true; 
  if($("#repass").val()==""){
    addtip("repass","请输入要绑定的副账号安全密码。");
	post = false;
  }
  if($("#username").val()==''){
    addtip("username","请输入副账号用户名");
	post = false;
  }
  if($("#usernametip").html().indexOf('该用户名不存在') != -1&&post){
    post = false;
  } 
  if(post) listTable.memberfrom('添加副账号','adduserfrom','');
  return false;
} 
function _checkusermoney(){
  listTable.memberfrom('批量合并账户余额','checkusermoney','');
  return false;
} 
