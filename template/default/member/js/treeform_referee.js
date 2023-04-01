function clickject(obj){//无限级AJAX
	var _this = $(obj);
	var _floor = parseInt(_this.attr('floor'));
	var username = _this.attr('username');
	var classname = _this.attr('class');
	if(classname.indexOf('have_display')!=-1){
	  _this.parent().next().hide();
	  _this.removeClass(classname).addClass(classname.replace("have_display","have"));
	}else{
	  if(_this.attr('data')=='1'){
		_this.parent().next().show();  
	  }else{
        showLoading();
        $.getJSON(get_path_url("?mod=member&act=treeform&type=referee&referee="+username),function(res){
	      var div = _this.attr("yesend")=='1' ? '<div class="sub_0">' : '<div class="sub">';
          for(var i=0;i<res.length;i++) {
            var yesend = res.length-1>i ? "2" : "1";      
	        div += '<div class="node"><div class="title">';
		    if(res[i].renumber>0){
	          div += '<div class="click have_'+yesend+'" yesend="'+yesend+'" onClick="clickject(this);" floor="'+(_floor+1)+'" username="'+res[i].username+'"></div>';
		    }else{
	          div += '<div class="no_'+yesend+'" yesend="'+yesend+'" floor="'+(_floor+1)+'" username="'+res[i].username+'"></div>';
		    }
	        div += '<span>['+(_floor+1)+']['+res[i].username+']['+res[i].groupname+']';
			if(res[i].status=='1'){
			  div += '[已激活]';	
			}else{
			  div += '[<em buymoney="'+res[i]['usergroup']['buymoney']+'" uid="'+res[i]['uid']+'" class="nowopen">未激活，点击激活</em>]';
			}
			div += '</span>';
	        div += '</div></div>';	  
          }	
		  div += "</div>";
	      _this.parent().parent().append(div);
		  _this.attr('data','1');
	      Close();					  
        });
	  }
	  _this.removeClass(classname).addClass(classname.replace("have","have_display"));
	}
}