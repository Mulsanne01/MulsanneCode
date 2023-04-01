function checkq1(){
 if($("#q1").val()==''){
   addtip("q1","请选择安全问题"); 
 }else{
   yestip("q1","");
 }
}
function checkq2(){
 if($("#q2").val()==''){
   addtip("q2","请选择安全问题"); 
 }else{
   yestip("q2","");
 }
}
function checkq3(){
 if($("#q3").val()==''){
   addtip("q3","请选择安全问题"); 
 }else{
   yestip("q3","");
 }
}

function checka1(){
 if($("#a1").val()==''){
   addtip("a1","请输入安全问题答案"); 
 }else{
   yestip("a1","");
 }
}
function checka2(){
 if($("#a2").val()==''){
   addtip("a2","请输入安全问题答案"); 
 }else{
   yestip("a2","");
 }
}
function checka3(){
 if($("#a3").val()==''){
   addtip("a3","请输入安全问题答案"); 
 }else{
   yestip("a3","");
 }
}

function checkform(){
  var post = true; 
  if($("#q1").val()==''){
    addtip("q1","请选择安全问题");
    post = false;
  }
  if($("#q2").val()==''){
    addtip("q2","请选择安全问题");
    post = false;
  }
  if($("#q3").val()==''){
    addtip("q3","请选择安全问题");
    post = false;
  }
  if($("#a1").val()==''){
    addtip("a1","请输入安全问题答案");
    post = false;
  }
  if($("#a2").val()==''){
    addtip("a2","请输入安全问题答案");
    post = false;
  }
  if($("#a3").val()==''){
    addtip("a3","请输入安全问题答案");
    post = false;
  }  
  if(post) listTable.memberfrom('设置密保','ajaxformbox','');
  return false;
} 

function checkform2(){
  var post = true; 
  if($("#a1").val()==''){
    addtip("a1","请输入安全问题答案");
    post = false;
  }
  if($("#a2").val()==''){
    addtip("a2","请输入安全问题答案");
    post = false;
  }
  if($("#a3").val()==''){
    addtip("a3","请输入安全问题答案");
    post = false;
  }  
  return post;
} 