$(document).ready(function() {
 showtip("name","请输入收货人姓名");
 showtip("province","请选择你所在的行政区域");
 showtip("address","请输入详细的收货地址");
 showtip("mobile","请填写收货人的电话");
});
function checkname(){
 var name = $("#name").val();
 if(name==''){
   addtip("name","请输入收货人姓名");
 }else{
   yestip("name",'收货人姓名验证通过');
 }
}
function checkprovince(){
 if($("#province").val()==''||$("#city").val()==''||$("#county").val()==''){
   addtip("province","请选择你所在的行政区域");
 }else{
   yestip("province",'所在的行政区域验证通过');
 }
}
function checkaddress(){
 var address = $("#address").val();
 if(address==''){
   addtip("address","请输入详细的收货地址");
 }else{
   yestip("address",'详细的收货地址验证通过');
 }
}
function checkmobile(){
 var mobile = $("#mobile").val();
 if(mobile==''){
   addtip("mobile","请填写收货人的电话");
 }else{
   yestip("mobile",'收货人电话验证通过');
 }
}
function checkform(){
 var post = true; 
 if($("#name").val()==''){
   addtip("name","请输入收货人姓名");
   post = false;
 } 
 if($("#province").val()==''||$("#city").val()==''||$("#county").val()==''){
   addtip("province","请选择你所在的行政区域");
   post = false;
 } 
 if($("#address").val()==''){
   addtip("address","请输入详细的收货地址");
   post = false;
 } 
 if($("#mobile").val()==''){
   addtip("mobile","请填写收货人的电话");
   post = false;
 } 
 return post;  
} 