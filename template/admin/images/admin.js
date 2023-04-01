function addClass(id,c,v){
  $("#"+id).removeClass();
  $("#"+id).addClass(c);
  $("#"+id).html(v);
}
function checkusername(){
 var username = $("#username").val();
 var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;
 if(username==''){
   addClass("usernametip","tipsno","您还没有输入用户名");
 }else if(username.length<4||username.length>20){
   addClass("usernametip","tipsno","用户名长度4-20个字符");
 }else if(isFirstNum(username)){
   addClass("usernametip","tipsno","用户名不能使用数字开头");
 }else if(!testUser.test(username)){
   addClass("usernametip","tipsno","仅可使用字母、数字、汉字和下划线");
 }else{
   $.getJSON(get_url("act=verifyusername&username="+encodeURIComponent(username)),function(res){
     if(res.error=='0'){
       addClass("usernametip","tipsno","对不起，该用户名已存在");
     }else{
       addClass("usernametip","tipsyes","&nbsp;");
     }
   });
 }	
}


function checkidcard(){
 var idcard = $("#idcard").val();
 if(idcard==''){
   addClass("idcardtip","tipsno","您还没有输入身份证号");
 }else{

         addClass("idcardtip","tipsyes","&nbsp;");

 }	
}

function checkgroupid(){
  var groupid = $("#groupid").val();
  if(groupid==''){
    addClass("groupidtip","tipsno","对不起，请选择会员级别");
  }else{
    addClass("groupidtip","tipsyes","&nbsp;");
  }
}

function checkbuytype(){
  var buytype = $("#buytype").val();
  if(buytype==''){
    addClass("buytypetip","tipsno","对不起，请选择投资类型");
  }else{
    if($("#buytype").val()=='1'){
      $("#groupidshow").hide();
	}else{  
      $("#groupidshow").show();
	} 
    addClass("buytypetip","tipsyes","&nbsp;");
  }
}

function checkpassword(){
	var password = $("#password").val();
	var strleng = password.length;
	if(strleng<6){
	   addClass("passwordtip","tipsno","对不起用户密码长度不得小于6位");
	}else{
	   addClass("passwordtip","tipsyes","&nbsp;");
	}
}

function checkreferee(){
  var referee = $("#referee").val();
  if(referee==''){
    addClass("refereetip","tipsno","对不起，请输入推荐会员");
  }else{
    addClass("refereetip","tipsyes","&nbsp;");
  }
}

function check_referee(){
  var _referee = $("#_referee").val();
  if(_referee==''){
    addClass("_refereetip","tipsno","对不起，请输入会员上线");
  }else{
    addClass("_refereetip","tipsyes","&nbsp;");
  }
}
  
function checkrepass(){
  if($("#repass").val()==""){
    addClass("repasstip","tipsno","请输入安全密码。");
  }else if($("#repass").val().length<6){
    addClass("repasstip","tipsno","安全密码长度最小六位！");
  }else{
    addClass("repasstip","tipsyes","&nbsp;");
  }
}
function checkposition(){
  if($('input[name="position"]:checked').val()==undefined){
    addClass("positiontip","tipsno","对不起，请选择所在位置");
  }else{
    addClass("positiontip","tipsyes","&nbsp;");
  }
}
function checknowopen(){
  var nowopen = $("#nowopen").val();
  if(nowopen==''){
    addClass("nowopentip","tipsno","请选择是否现在激活");
  }else{
    addClass("nowopentip","tipsyes","&nbsp;");
	checkgroupid();
  }
}

function inmember(){
  var post = true; 
  var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;    
  if($("#username").val()==''){
    addClass("usernametip","tipsno","您还没有输入用户名");
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
  if($("#idcard").val()==''){
    addClass("idcardtip","tipsno","您还没有输入身份证号");
    post = false;
  }

  if($("#nowopen").val()==''){
	addClass("nowopentip","tipsno","请选择是否现在激活");
    post = false;
  }   
  if($("#password").val()==''){
	addClass("passwordtip","tipsno","对不起，请输入会员登陆密码");
    post = false;
  }  
  if($("#password").val().length<6){
    addClass("passwordtip","tipsno","密码长度最小六位！");
    post = false;
  }  
  if($("#repass").val()==''){
	addClass("repasstip","tipsno","对不起，请输入安全密码");
    post = false;
  } 
  if($("#repass").val().length<6){
    addClass("repasstip","tipsno","安全密码长度最小六位！");
    post = false;
  }
  if($("#referee").val()==''){
	addClass("refereetip","tipsno","对不起，请输入推荐会员");
    post = false;
  } 
  if($("#_referee").val()==''){
	addClass("_refereetip","tipsno","对不起，请输入会员上线");
    post = false;
  } 
  if($('input[name="position"]:checked').val()==undefined){
	addClass("positiontip","tipsno","对不起，请选择所在位置");
    post = false;
  } 
  if(post) listTable.inmember();  
  return false;
} 
function addmember(){
  var post = true; 
  var testUser = /^[\u4E00-\u9FA5a-z0-9_]+$/i;    
  if($("#username").val()==''){
    addClass("usernametip","tipsno","您还没有输入用户名");
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
  if($("#idcard").val()==''){
    addClass("idcardtip","tipsno","您还没有输入身份证号");
    post = false;
  }
  if($("#nowopen").val()==''){
	addClass("nowopentip","tipsno","请选择是否现在激活");
    post = false;
  }   
  if($("#password").val()==''){
	addClass("passwordtip","tipsno","对不起，请输入会员登陆密码");
    post = false;
  }  
  if($("#password").val().length<6){
    addClass("passwordtip","tipsno","密码长度最小六位！");
    post = false;
  }  
  if($("#repass").val()==''){
	addClass("repasstip","tipsno","对不起，请输入安全密码");
    post = false;
  } 
  if($("#repass").val().length<6){
    addClass("repasstip","tipsno","安全密码长度最小六位！");
    post = false;
  }
  if(post) listTable.addmember(); 
  return false;
} 


function edituser(){
  var post = true;   
  if(post) listTable.ajaxform('编辑会员资料','editformbox',''); 
  return false;
} 

function atmsubmit(){
  var post = true;   
  if($('input[name="id[]"]:checked').val()==undefined){
	Wrong('请选择要处理的提现申请');
    post = false;
  }  
  if(post) listTable.ajaxform('批量确认付款','atmpost',''); 
  return false;
} 


function addlink(){
  var html = '<tr class="s_out" onmouseover="this.bgColor=\'#F5F5F5\';" onmouseout="this.bgColor=\'ffffff\';" bgcolor="#ffffff">';
  html += '<input type="hidden" name="id[]" value="">';
  html += '<td align="center">&nbsp;&nbsp;</td>';
  html += '<td align="left" style="padding-left:10px;">';
  html += '<input type="text" name="linkname[]" id="linkname[]" class="skey" style=\'width:130px;\'/>';
  html += '</td><td align="left" style="padding-left:10px;">';
  html += '<input type="text" name="linkurl[]" id="linkurl[]" value="http://" class="skey" style=\'width:250px;\'/>';
  html += '</td><td align="left" style="padding-left:10px;">';
  html += '<input type="text" name="linkorder[]" id="linkorder[]" value="0" class="skey" style=\'width:50px;\'/>';
  html += '</td><td align="center"><a onclick="$(this).parent().parent().remove();" href="javascript:;">移除</a></td></tr>';
  $("#type").append(html);
}
function addask(obj){
  var html = '<li>推荐 <input type="text" name="'+obj+'n[]" value=""/> ';
  html += '人可拿 <input type="text" name="'+obj+'f[]" value=""/> 代的 <input type="text" name="'+obj+'s[]" value=""/> <span onclick="$(this).parent().remove();">删除</span></li>';
  $("#"+obj+"ask").append(html);
}
function addtype(){
  var html = '<tr class="s_out" onmouseover="this.bgColor=\'#F5F5F5\';" onmouseout="this.bgColor=\'ffffff\';" bgcolor="#ffffff">';
  html += '<input type="hidden" name="id[]" value="">';
  html += '<td align="center">&nbsp;&nbsp;</td>';
  html += '<td align="left" style="padding-left:10px;">';
  html += '<input type="text" name="typename[]" id="typename[]" class="skey" style=\'width:130px;\'/>';
  html += '</td><td align="left" style="padding-left:10px;">';
  html += '<input type="text" name="typeorder[]" id="typeorder[]" class="skey" style=\'width:50px;\'/>';
  html += '</td><td align="center"><a onclick="$(this).parent().parent().remove();" href="javascript:;">移除</a></td></tr>';
  $("#type").append(html);
}
function addnewstype(){
  var html = '<tr class="s_out" onmouseover="this.bgColor=\'#F5F5F5\';" onmouseout="this.bgColor=\'ffffff\';" bgcolor="#ffffff">';
  html += '<input type="hidden" name="id[]" value="">';
  html += '<td align="center">&nbsp;&nbsp;</td>';
  html += '<td align="center"><input type="text" name="typename[]" id="typename[]" class="skey" style=\'width:130px;\'/></td>';
  html += '<td align="center"><input type="text" name="system[]" id="system[]" class="skey" style=\'width:50px;\'/></td>';
  html += '<td align="center"><input type="text" name="typeorder[]" id="typeorder[]" class="skey" style=\'width:50px;\'/></td>';
  html += '<td align="center"><a onclick="$(this).parent().parent().remove();" href="javascript:;">移除</a></td></tr>';
  $("#type").append(html);
}
$(function(){
 $(".noorderback").click(function(){
	var id = $(this).attr('orderid');
	var _this = $(this);
    showhandle({
      html:'<table class="lotab"><tr><th><label>拒绝原因：</label></th><td><textarea name="message" id="messagea" class="textarea"></textarea></td></tr><tr><th><label>管理密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
      width:320,
      height:210,
      id:'backmoney',
      title:'拒绝退款'
    },function(){
	  $("#controlLoad").show();
	  removetip("backmoney");																	 
      $.getJSON(listTable.url+'&repass='+$("#repass").val()+'&do=nobackmoney&id='+id+'&message='+encodeURIComponent($("#messagea").val()),function(res) {
	    $("#controlLoad").hide();	
        if(res.error == 0){
		  hidebox('backmoney',true);
		  location.href = location.href;
        }else{
	      addtip("backmoney",res.error);
        }											
      });							  
    });					
 });  
 $(".orderback").click(function(){
	var id = $(this).attr('orderid');
	var _this = $(this);
    showhandle({
      html:'<table class="lotab"><tr><th><label>管理密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
      width:320,
      height:130,
      id:'backmoney',
      title:'处理退款'
    },function(){
	  $("#controlLoad").show();
	  removetip("backmoney");
      $.getJSON(listTable.url+'&repass='+$("#repass").val()+'&do=backmoney&id='+id,function(res) {
	    $("#controlLoad").hide();
        if(res.error == 0){
		  hidebox('backmoney',true);
		  _this.parent().html('已退款');
        }else{
		  addtip("backmoney",res.error);
        }											
      });
    });					
 });
 $(".ordersend").click(function(){
	var id = $(this).attr('orderid');
	var _this = $(this);
    showhandle({
      html:'<table class="lotab"><tr><th><label>发货快递：</label></th><td><select name="express" id="express">'+$("#_express").html()+'</select></td></tr><tr><th><label>快递单号：</label></th><td><input id="expressnumber" class="myinput repass" type="text"></td></tr><tr><th><label>管理密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
      width:320,
      height:240,
      id:'ordersend',
      title:'确认发货'
    },function(){
	  $("#controlLoad").show();
	  removetip("ordersend");
      $.getJSON(listTable.url+'&repass='+$("#repass").val()+'&do=ordersend&id='+id+'&express='+encodeURIComponent($("#express").val())+'&expressnumber='+encodeURIComponent($("#expressnumber").val()),function(res) {
		$("#controlLoad").hide(); 
        if(res.error == 0){
		  hidebox('ordersend',true);
		  _this.parent().html('已发货');
        }else{
	      addtip("ordersend",res.error);
        }											
      });						  
    });					
 });
 $(".changemoney").click(function(){
	var uid = $(this).attr('uid');
	var username = $(this).attr('username');
	var money = $(this).attr('money');
	var balance = $(this).attr('balance');
	var shopmoney = $(this).attr('shopmoney');
    showhandle({
      html:'<table class="lotab lotab1"><tr><th><label>玩家编号：</label></th><td>'+username+'</td></tr><tr><th><label>变动原因：</label></th><td><textarea name="content" id="content" class="textarea"></textarea></td></tr><tr><th><label>账户金币：</label></th><td><select name="add_money" id="add_money" ><option value="1" selected="selected">充值</option><option value="0">扣款</option></select> <input name="rank_money" type="text" id="rank_money" value="0" class="myinput repass2" /> 余：'+money+'</td></tr><tr><th><label>管理密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
      width:350,
      height:245,
      id:'ordersend',
      title:'资金变动'
    },function(){
	  $("#controlLoad").show();
	  removetip("ordersend");
      $.getJSON(listTable.url+'&do=changemoney&uid='+uid+'&repass='+$("#repass").val()+'&rank_money='+$("#rank_money").val()+'&add_money='+$("#add_money").val()+'&content='+encodeURIComponent($("#content").val()),function(res) {
        $("#controlLoad").hide();																																														
        if(res.error == 0){
	      $("#money_"+uid).html(res.money);
		  hidebox('ordersend',true);
		  Right('恭喜你，资金变动成功！');
        }else{
		  addtip("ordersend",res.error);
        }											
      });						  
    });					
 });
 $('input:radio[name="sendtype"]').click(function(){ 
   $(this).parent().find('span').hide();										 
   if($(this).val()==1){ 
     $("#sendtype_1").show();
   }else if($(this).val()==2){
     $("#sendtype_2").show();
   }
 }); 
});
function select_tab(curr_tab){
  $("form[name='shopform'] > div").hide();
  $("#table_box_"+curr_tab).show();
  $("ul[name=menu1] > li").removeClass('selected');
  $('#li_'+curr_tab).addClass('selected');
}
function shopcheckForm(){
  var post = true;
  if($('#goods_name').val()==''&&post){
	Wrong('对不起，请输入产品名称');
	select_tab(1);
	post = false;  
  }
  if($('#shop_price').val()==''&&post){
	Wrong('对不起，请填写销售价格');
	select_tab(1);
	post = false;  
  }
  if($('#margin').val()==''&&post){
	Wrong('对不起，请填写奖励差额');
	select_tab(1);
	post = false;  
  }
  if($('#shop_price').val()==''&&post){
	Wrong('对不起，请填写产品剩余库存');
	select_tab(1);
	post = false;  
  }
  if(goods_desc_editor.isEmpty()&&post){
	Wrong('对不起，请输入商品详细介绍');
	select_tab(2);
	post = false;  
  }
  if($('.thumb_list').val()==undefined&&post){
	Wrong('对不起，请为商品上传至少一张图片!');
	select_tab(3);
	post = false;  
  }
  if(post) listTable.ajaxform(shoptitle,'shopform',''); 
  return false;
}
function configcheckForm(){
  var post = true;
  if(post) listTable.ajaxform('系统配置','configform',''); 
  return false;
}
function messagecheckForm(){
  var post = true;
  if($('#title').val()==''&&post){
	Wrong('对不起，请输入信件主题');
	post = false;  
  }
  if($('#content').val()==''&&post){
	Wrong('对不起，请填写信件内容');
	post = false;  
  }
  if(post) listTable.ajaxform('发送邮件','messageform',''); 
  return false;
}

function moneycheckForm(){
  var post = true;
  if($('#money').val()==''&&post){
	Wrong('对不起，剩余金币不能为空');
	post = false;  
  }
  if(post) listTable.ajaxform('更改金币余额','moneyform',''); 
  return false;
}


function managercheckForm(title){
  var post = true;
  if($('#username').val()==''&&post){
	Wrong('对不起，用户名不能为空');
	post = false;  
  }
  if($('#groupid').val()==''&&post){
	Wrong('对不起，请选择用户所属角色');
	post = false;  
  }
  if($('#password').val()==''&&post&&re=='add'){
	Wrong('对不起，密码不能为空');
	post = false;  
  }
  if(post) listTable.ajaxform(title,'managerform',''); 
  return false;
}
function groupcheckForm(title,message){
  var post = true;
  if($('#groupname').val()==''&&post){
	Wrong('对不起，'+message+'名称不能为空');
	post = false;  
  }
  if(post) listTable.ajaxform(title,'groupfrom',''); 
  return false;
}
function passwordcheckForm(){
  var post = true;
  if($('#username').val()==''&&post){
	Wrong('对不起，请填写要重置会员的玩家编号');
	post = false;  
  }
  if($('#password').val()==''&&$('#repass').val()==''&&post){
	Wrong('对不起，不能一个密码也不重置');
	post = false;  
  }  
  if(post) listTable.ajaxform('重置密码','passwordfrom',''); 
  return false;
}