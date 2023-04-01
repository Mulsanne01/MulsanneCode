$(function(){//表单
  $("#menu li a").click(function(){
     var checkElement = $(this).next();
     if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
	   return false;
     }
     if((checkElement.is('ul'))&&(!checkElement.is(':visible'))) {
       $('#menu ul:visible').hide();
       checkElement.show();
       return false;
     }
  });
  $(".nowopen").click(function(){
	var uid = $(this).attr('uid');
	var buymoney = $(this).attr('buymoney'); 
	listTable.status($(this),uid);
  })
  
  $(".nowdelete").click(function(){
	var uid = $(this).attr('uid');
	listTable.removeuser(uid,'确定要删除该账号吗？'); 
  })
  
  
  $(".trading").click(function(){
	var aid = $(this).attr('aid');
	var check = $(this).attr('check');
	listTable.trading($(this),aid,check);
  })
  $(".moneycheck,._moneycheck").click(function(){
	var uid = $(this).attr('uid');
	var money = $(this).attr('money');
	listTable.moneycheck(uid,'激活分红需扣除'+money+"金币");
  })
});