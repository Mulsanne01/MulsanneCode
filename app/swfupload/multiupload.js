/*!
 * MultiUpload for xheditor
 * @requires xhEditor
 * 
 * @author Yanis.Wang<yanis.wang@gmail.com>
 * @site http://xheditor.com/
 * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
 * 
 * @Version: 0.9.2 (build 100505)
 */
var swfu,selQueue=[];
function fileQueued(file){
	for(var i in selQueue)if(selQueue[i].name==file.name){
	  swfu.cancelUpload(file.id);
	  alert('这个文件已经上传过了');
	  return false;
	}//防止同名文件重复添加
	selQueue.push(file);
	if($('#uploadlist').html().trim()==''){
		cover = '封面';
	}else{
	   	cover = "&nbsp;";
	}
	var div = '<li id="show_'+file.id+'">';
	div += '<div class="displayimg" id="img_'+file.id+'"><img src="/app/swfupload/images/zoomloader.gif" style="width:16px;margin-top:20px;height:16px;"/></div>';
	div += '<a class="previous" href="javascript:void(0);"></a>';          
    div += '<span class="front-cover">'+cover+'</span>';
    div += '<a class="next" href="javascript:void(0);"></a>';
    div += '<a class="delete" href="javascript:void(0);"></a>';
	div += '<input type="hidden" name="thumb_list[]" class="thumb_list" id="thumb_list_'+file.id+'" value="" />';
    div += '</li>';
    $('#uploadlist').append(div);
}


function fileQueueError(file, errorCode, message)//队列添加失败
{
	var errorName='';
	switch (errorCode)
	{
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			errorName = "对不起，您还可以上传 "+(this.settings.file_upload_limit-truedelete)+" 个文件";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			errorName = "选择的文件超过了当前大小限制："+this.settings.file_size_limit;
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			errorName = "零大小文件";
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			errorName = "文件扩展名必需为："+this.settings.file_types_description+" ("+this.settings.file_types+")";
			break;
		default:
			errorName = "未知错误";
			break;
	}
	alert(errorName);
}


function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}


function uploadStart(file)//单文件上传开始
{
}
function uploadProgress(file, bytesLoaded, bytesTotal)//单文件上传进度
{
}
function uploadSuccess(file, serverData){//单文件上传成功
	var data=Object;
	try{eval("data=" + serverData);}catch(ex){};
	if(data.error==0){
	   addItem(data.url,data.trueurl,file);
	}else{
	   alert(data.message);
	   $('#show_'+file.id).remove();
	}
}
function uploadError(file, errorCode, message)//单文件上传错误
{
	return message;
}
function uploadComplete(file)//文件上传周期结束
{
	if(swfu.getStats().files_queued>0)swfu.startUpload();
	else uploadAllComplete();
}
function uploadAllComplete(){
   //Msg('上传完毕');
}
function addItem(url,path,file){
	$("#img_"+file.id).html('<img src="'+url+'"/>');
	$("#thumb_list_"+file.id).val(url);
}
$(document).ready(function(){
  $('#uploadPic ul li a').live('click', function () {										
    var o = $(this), pNode = o.parent(), v = $(this).attr('class');
    switch (v) {
      case 'delete':
		refNode = pNode['next']();
		if(pNode.find("span").html()=='封面'){
		   refNode.find("span").html("封面");
		}
		pNode.remove(); 
		break;
      case 'previous':
      case 'next':
        var refNode = pNode[v == 'previous' ? 'prev' : 'next']();
        if (refNode[0] == null) {return false;}
        setItemPosition(pNode.parent(), true);
        var nItemTop = refNode.css('margin-left'), refItemTop = pNode.css('margin-left');
        pNode[v == 'previous' ? 'after' : 'before'](refNode); //交换位置
        pNode.animate({ marginLeft: nItemTop }, 300);
        refNode.animate({ marginLeft: refItemTop }, 300, function () { setItemPosition(pNode.parent()); });
        break;
     }								  
   });
});
function setItemPosition(dvContainer, isAB) {
    var h = 0, eachItem;
    dvContainer.children().each(function () {
        eachItem = $(this);
	    var nItemTop = eachItem.css('margin-left');
	    if(nItemTop=='0px'&&!isAB){
		   eachItem.find("span").html("封面");
		}else{
		   eachItem.find("span").html("&nbsp;");
		}
        eachItem.css({ 'position': isAB ? 'absolute' : 'relative', 'margin-left': isAB ? h : '' });
        if (isAB) h += 87;
     });
}