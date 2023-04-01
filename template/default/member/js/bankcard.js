$(document).ready(function() {
 showtip("truename","请输入银行卡开户名");
 showtip("bankname","请选择银行卡开户银行");
 showtip("bankadd","请输入银行卡开户地址");
 showtip("bankcard","请输入银行卡卡号");
});
function checktruename(){
 var truename = $("#truename").val();
 if(truename==''){
   addtip("truename","请输入银行卡开户名");
 }else{
   yestip("truename",'');
 }
}
function checkbankname(){
 var bankname = $("#bankname").val();
 if(bankname==''){
   addtip("bankname","请选择银行卡开户银行");
 }else{
   yestip("bankname",'');
 }
}
function checkbankadd(){
 var bankadd = $("#bankadd").val();
 if(bankadd==''){
   addtip("bankadd","请选择银行卡开户地址");
 }else{
   yestip("bankadd",'');
 }
}
function checkbankcard(){
 var bankcard = $("#bankcard").val();
 if(bankcard==''){
   addtip("bankcard","请输入银行卡卡号");
 }else{
   yestip("bankcard",'');
 }
}
function checkform(){
 var post = true; 
 if($("#truename").val()==''){
   addtip("truename","请输入银行卡开户名");
   post = false;
 } 
 if($("#bankname").val()==''){
   addtip("bankname","请选择银行卡开户银行");
   post = false;
 } 
 if($("#bankadd").val()==''){
   addtip("bankadd","请选择银行卡开户地址");
   post = false;
 } 
 if($("#bankcard").val()==''){
   addtip("bankcard","请输入银行卡卡号");
   post = false;
 } 
 return post;  
} 