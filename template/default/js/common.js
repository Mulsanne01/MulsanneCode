$(document).ready(function() {//公用接口函数
  $.getScript(appdir + "placeholder.js");					   
  $(".pow-layer > .ngx > .fr").click(function(){
	$(this).prev('.pl-box').toggle(300);
  });
});