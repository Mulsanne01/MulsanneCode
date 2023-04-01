<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class controller_class{//模型
 function __construct($module,$action){ 
   $this->modules($module,$action);
 }	
 function modules($module,$action){
   $extfile = 'mod_'.$module;
   $modulepath = PATH.'library/modules/'.$extfile.'.php';
   if(!file_exists($modulepath)) error("Module '$module' file not found");
   require($modulepath);		
   if(!class_exists($extfile)) error("Module '$module' class not found");
   $this->module = new $extfile($module,$action);
   if(!method_exists($extfile,$action) === true) error("Module({$module})'s Action '$action' not found");
   if($action == 'template') error("Module({$module})'s Action '$action' is'not effective");
   if(@$this->module->$action()) error("Module({$module})'s Action '$action' is'not effective");
 }
 function display(){
   $this->module->template();
 }
}
?>