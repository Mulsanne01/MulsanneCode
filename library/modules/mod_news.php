<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_news extends module_class{
	function main(){
	   if(!is_array($this->member)) location('?mod=member&act=login&url='.base64_encode("?mod=news&act=main&typeid=".$_GET['typeid']));
       $typeid = $_GET['typeid'];
	   if(!$typeid) $this->message('go_back','对不起，该信息不存在');
	   $where = "where id>'0' ";
	   if($typeid) $where .= "and typeid='{$typeid}'";
	   $this->newstype = $this->mysql->select_one("select * from {$this->pre}newstype where id='{$_GET['typeid']}'"); 
	   if(!is_array($this->newstype)) $this->message('go_back','对不起，该信息不存在');
	   $this->pagetotal = $this->mysql->counts("select * from {$this->pre}news $where");
	   $this->pageclass($this->pagetotal);
	   $this->news = $this->mysql->getarr("select * from {$this->pre}news $where order by addtime desc limit ".$this->page->start.",".$this->page->size);  
    }
	
 	function show(){
	   if(!is_array($this->member)) location('?mod=member&act=login&url='.base64_encode("?mod=news&act=show&id=".$_GET['id']));
	   $this->news = $this->mysql->select_one("select * from {$this->pre}news where id='{$_GET[id]}' ");  
	   if(!is_array($this->news))  $this->message('go_back','对不起，该信息不存在');
	   $this->newstype = $this->mysql->select_one("select * from {$this->pre}newstype where id='{$this->news['typeid']}'"); 
	}	
}
?>