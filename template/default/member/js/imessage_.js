function checktitle(){
 if($("#title").val()==''){
   addtip("title","请输入信件主题。"); 
 }else{
   yestip("title","");
 }
}
function checkcontent(){
 if($("#content").val()==''){
   addtip("content","请输入信件内容。"); 
 }else{
   yestip("content","");
 }
}
function checkform(){
  var post = true; 
  if($("#title").val()==''){
    addtip("title","请输入信件主题。"); 
	post = false;
  }
  if($("#content").val()==''){
    addtip("content","请输入信件内容。"); 
	post = false;
  }
  return post;
} 