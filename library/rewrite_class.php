<?php
class rewrite{//解析伪静态	
 function parameter(){
   if(!config::get("rewrite")) return false;
   if(!isset($_GET['parameter'])) return false;
   $parameter = explode("/",$_GET['parameter']);
   unset($_GET['parameter']);
   foreach($parameter as $key=>$val){
     $arr = explode('-',$val);
     if($arr[0]) $_GET[$arr[0]] = $arr[1];		
   }
   $request = geturl();
   $strpos = strpos($request,'?');
   if($strpos){//这个是干什么用的
     $request = substr($request,$strpos+1);
     $request = explode("&",$request);
     foreach($request as $key=>$val){
       $arr = explode('=',$val);
       if($arr[0]){
         if($arr[0]=='notify_time') $arr[1]=str_replace("+"," ",$arr[1]);
         $_GET[$arr[0]] = rawurldecode($arr[1]);		
       }
     }
   }
 }
 function response($url){
   if(!config::get("rewrite")){
	  $arr = explode("?",$url);
	  return "?".$arr[1];
   }else{
      $arr = explode("/",$url);
	  $request = '';
	  unset($arr[0]);
	  foreach($arr as $key=>$val){
		 if(strpos($val,'-')===false){
			if($key==1) $request .= "?mod=".$val; 
			if($key==2) $request .= "&act=".$val;
		 }else{
			$array = explode('-',$val);
		    $request .= "&{$array[0]}={$array[1]}";	 
		 } 
	  }
	  return $request;
   }
 }
 function request($request=''){
   $request = strpos($request,'?')===false ? $request : config::get("siteurl").'index.php'.$request;
   if($_GET['mod']=='admin') return $request;
   //if(!config::get("rewrite")) return str_replace("&act=main",'',str_replace("?mod=index",'',$request));
   if(!config::get("rewrite")) return $request;
   $request = substr($request,strpos($request,'?')+1);
   $arr = explode("&",$request);
   $prefix = '';
   foreach($arr as $key=>$val){
	 $array = explode("=",$val);
     if(in_array($array[0],array('mod','act'))){
       if($array[0]=='mod'){
         if($array[1]!='index') $prefix .= '/'.$array[1];
       }else{
         if($array[1]!='main') $prefix .= '/'.$array[1];
       }
     }else{
       $prefix .= '/'.$array[0].'-'.$array[1];
     }
   }
   return $prefix ? $prefix : config::get("siteurl");
 } 
}
?>