<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_about extends module_class{//单页模型
	function main(){	
	   if(!is_array($this->member)) location('?mod=member&act=login&url='.base64_encode("?mod=about&act=main&id=".$_GET['id']));
	   $where = isNumber($_GET['id']) ? "where id='{$_GET[id]}'" : "where myurl='{$_GET[id]}'";
	   $this->about = $this->mysql->select_one("select * from {$this->pre}about $where");
	   if(!is_array($this->about)) errorpage();
	   $this->typename = $this->mysql->value("{$this->pre}abouttype",'typename',"id='{$this->about['typeid']}'");
	}
	function project(){
	   if(!is_array($this->member)) location('?mod=member&act=login&url='.base64_encode("?mod=about&act=project&id=".$_GET['id']));
	   $where = isNumber($_GET['id']) ? "where id='{$_GET[id]}'" : "where myurl='{$_GET[id]}'";
	   $this->about = $this->mysql->select_one("select * from {$this->pre}about $where");
	   if(!is_array($this->about)) errorpage();
	}
}
?>