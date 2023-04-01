var starttime = endtime-nowtime;
$(function(){
  $(".payorderyes").click(function(){
	var _this = $(this);
	var id = _this.attr('aid');
    showhandle({
      html:'<table class="lotab"><tr><th><label>安全密码：</label></th><td><input id="repass" class="myinput repass" type="password"></td></tr></table>',
      width:320,
      height:136,
      id:'atmmymoney',
      title:'确认汇款'
    },function(){
	  $("#controlLoad").show();
	  removetip("atmmymoney");
      $.getJSON(get_path_url("?mod=member&act=capital&type=payment&re=ajax&id="+id+"&repass="+$("#repass").val()),function(res){
	    $("#controlLoad").hide();																		 
	    if(res.error=='0'){
	      hidebox('atmmymoney',true);
          Right('确认汇款成功',{},function(){
			_this.parent().html(res.message);
			Close();
		  });
	    }else{
		  addtip("atmmymoney",res.error);
	    }							  
      });
    });	
  });
  if(starttime>0){
    time_over();
    window.setInterval('time_over()',1000);
  }
});
function time_over(){
  starttime--;
  if(starttime>0){
    var date = parseInt(starttime/(60*60*24)).toFixed(0);
    var hour = parseInt((starttime-(date*60*60*24))/(60*60)).toFixed(0);
    var minute = parseInt((starttime-(date*60*60*24)-(hour*60*60))/60).toFixed(0);
    var second = parseInt(starttime-(date*60*60*24)-(hour*60*60)-(minute*60)).toFixed(0);
    var timestr = hour+"小时"+minute+"分"+second+"秒";
	$("#time_over").html(timestr);
  }else{
    $("#daoshihou").html('<div style="padding:25px; padding-left:46px;"><b class="text_red_line">对不起，抢购时间已经结束，请于'+paystarttime+':00前来抢购</b></div>');
  }  
}
function checkmoney(){
  var money =  parseInt($("#howmoney").val());
  if($("#howmoney").val()==''){
	addtip('howmoney','请输入抢购金额');
  }else if(isNaN(money)){
	addtip('howmoney','必须是有效的数字');
  }else if(money<100){
	addtip('howmoney','一次性最低抢购100');
  }else if(money%100!=0){
	addtip("howmoney","抢购金额必须是整百");
  }else{
	yestip('howmoney','');  
  }
}

function checkpaymoney(){
  var post = true;
  var money =  parseInt($("#howmoney").val());
  if($("#howmoney").val()==''){
	addtip('howmoney','请输入抢购金额');
	post = false; 
  }else if(isNaN(money)){
	addtip('howmoney','必须是有效的数字');
	post = false; 
  }else if(money<100){
	addtip('howmoney','一次性最低抢购100');
	post = false; 
  }else if(money%100!=0){
	addtip("howmoney","抢购金额必须是整百");
	post = false;
  }else{
	yestip('howmoney','');  
  }
  if(post) listTable.memberfrom('抢购金币','paymoney','');
  return false;  
} 
function cancelpayorder(id){
    $.getJSON(get_url('act=cancelpayorder&id='+id),function(res){																 
	  if(res.error=='0'){
		$("#payorder_"+id).remove();
	  }else{
		Wrong(res.error);
	  }							  
    });
}