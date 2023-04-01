function checkusername(){
   var username = $("#username").val();
   if(username==''){
     addtip("username","请输入要激活的玩家编号");
   }else{
     $.getJSON(get_url("act=verifystatususer&username="+encodeURIComponent(username)),function(res){
       if(res.error=='0'){
	     yestip("username",res.truename);
       }else{
         addtip("username",res.error);
       }	
     }); 
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

function checkform(){
  var post = true; 
  var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;    
  if($("#username").val()==''){
    addtip("username","请输入要激活的玩家编号");
    post = false;
  }

  if($("#usernametip").html().indexOf('玩家不存在或已经激活') != -1&&post){
    post = false;
  }   
  
  if(post) listTable.memberfrom('确定要激活会员，将扣除你'+$('#buymoney').val(),'ajaxformbox','');
  return false;
} 