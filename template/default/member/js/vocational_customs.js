function checkname(){
 if($("#name").val()==''){
   addtip("name","请填写报单中心名称。"); 
 }else{
   yestip("name","");
 }
}
function checkaddress(){
 if($("#address").val()==''){
   addtip("address","请填写报单中心所在地方。"); 
 }else{
   yestip("address","");
 }
}
function checkform(){
  var post = true;   
  if($("#name").val()==''){
    addtip("name","请填写报单中心名称。"); 
	post = false;
  }
  if($("#address").val()==''){
    addtip("address","请填写报单中心所在地方。"); 
	post = false;
  }
  if(post) listTable.customs($("#name").val(),$("#address").val());
} 