<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class view_class{
 function __construct(){//构造函数
   $_GET['mod'] = empty($_GET['mod']) ? 'index' : $_GET['mod'];
   $_GET['act'] = empty($_GET['act']) ? 'main'  : $_GET['act'];  
   if($_GET['mod']=='admin'){
	 if(empty($_GET['get'])){
	   $get = array_keys(admin_menu_left($_GET['act']));
       $_GET['get'] = $get[0];
       $_POST['get'] = $get[0];
	 }
	 if(empty($_GET['re'])){
	   $re = array_keys(admin_menu_small($_GET['act'],$_GET['get']));
       $_GET['re'] = $re[0];
	 }
   }
   if($_GET['mod']=='member'){
	 if(empty($_GET['type'])){
	   $type = array_keys(member_menu($_GET['act']));
       $_GET['type'] = $type[0];
	   $_POST['type'] = $type[0];
	 }
   }
   if(config::get('closed')&&$_GET['mod']!='admin'&&$_GET['mod']!='tools') exit(config::get('closemsg'));
   $this->controller($_GET['mod'],$_GET['act']);
 }
 function controller($module,$action){
   $this->ctl = new controller_class($module,$action);		
 }
 function display(){
   $this->ctl->display();
 }
 function __destruct(){
 }
}
?>