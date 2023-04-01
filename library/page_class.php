<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class page_class{
  function __construct($size,$start,$total){
     $this->size = $size;
     $this->start = $start;
     $this->total = $total;
	 $this->url = config::get("rewrite")&&$_GET['mod']!='admin' ? preg_replace('#/page-(\d+)#','',geturl()) : preg_replace('#&page=(\d+)#','',geturl());
	 $this->url = config::get("rewrite")&&$this->url=='/' ? "/index" : $this->url;
	 if(empty($_GET['page'])){
	   if($this->start == 0){
         $this->page = $this->start + 1;   //设定为1
       }
	 }else{
	   $this->page = $_GET['page'];   //获得用户提交的页数
       $this->start = ($this->page - 1) * $this->size;   //获得开始显示的记录编号
	 }
	 
     if($this->page % $this->size == 0){
       $this->CounterStart = $this->page - ($this->size - 1);
     }else{
       $this->CounterStart = $this->page - ($this->page % $this->size) + 1;
     }
     //显示页码的最大值
     
	 
     if($this->total % $this->size == 0){
       $this->MaxPage = $this->total / $this->size;
     }else{
       $this->MaxPage = ceil($this->total / $this->size);
     }
	 $this->CounterEnd = $this->CounterStart + ($this->MaxPage - 1);
	 $this->limit = " limit ".$this->start.",".$this->size;
  }
  
  function getNextpage(){	
    if($this->page < $this->MaxPage){
      $NextPage = $this->page + 1;
      return $this->url.rewrite::request("&page=".$NextPage);
   }else{
	  return 1;
   }
  }
  
  function showpage($endpre=''){
	 $endpre = $endpre ? "#{$endpre}" : "";
	 if($this->size>=$this->total) return false;
	 $showpage='';
     if($this->page != 1){
        $PrevStart = $this->page - 1;
        $showpage.= "<a href=\"".$this->url.rewrite::request("&page=1").$endpre."\">首页</a>";
        $showpage.= "<a href=\"".$this->url.rewrite::request("&page=".$PrevStart).$endpre."\">上一页</a>";
     }else{
		$showpage.= "<strong>首页</strong>";
        $showpage.= "<strong>上一页</strong>";		     
	 }
	 $snum=2;
	 $starti = $this->page-$snum < 1 ? 1 : $this->page-$snum;
	 $no=0;
	 $page_line=5;
	 for($i=$starti;$i<=$this->MaxPage&&$no<$page_line;$i++){
		$no++;
		if($i==$this->page){
          $showpage.= "<strong>$i</strong>";	
		}else{
          $showpage.= "<a href=\"".$this->url.rewrite::request("&page=".$i).$endpre."\">$i</a>";
		}
	 }
	 


     if($this->page < $this->MaxPage){
        $NextPage = $this->page + 1;
        $showpage.= "<a href=\"".$this->url.rewrite::request("&page=".$NextPage).$endpre."\">下一页</a>";
     }else{
	    $showpage.= "<strong>下一页</strong>";
     }
     if($this->page < $this->MaxPage){
        $LastRec = $this->total % $this->size;
        if($LastRec == 0){
           $LastStartRecord = $this->total - $this->size;
        }else{
           $LastStartRecord = $this->total - $LastRec;
        }
        $showpage.= "<a href=\"".$this->url.rewrite::request("&page=".$this->MaxPage).$endpre."\">尾页</a>";
     }else{
		$showpage.= "<strong>尾页</strong>";
     }
	 return $showpage;
  }
  
  function newpage($endpre=''){
	 $endpre = $endpre ? "#{$endpre}" : "";
	 if($this->size>=$this->total) return false;
	 $showpage='';
     if($this->page != 1){
        $PrevStart = $this->page - 1;
        $showpage.= "<a class=\"pageup\" href=\"".$this->url.rewrite::request("&page=1").$endpre."\">首页</a>";
        $showpage.= "<a class=\"pageup\" href=\"".$this->url.rewrite::request("&page=".$PrevStart).$endpre."\"><em></em>上一页</a>";
     }else{
		$showpage.= "<a class=\"pageup pageup-dis\">首页</a>";
        $showpage.= "<a class=\"pageup pageup-dis\"><em></em>上一页</a>";		     
	 }
	 $snum=2;
	 $starti = $this->page-$snum < 1 ? 1 : $this->page-$snum;
	 $no=0;
	 $page_line=5;
	 for($i=$starti;$i<=$this->MaxPage&&$no<$page_line;$i++){
		$no++;
		if($i==$this->page){
          $showpage.= "<a class='num current'>$i</a>";	
		}else{
          $showpage.= "<a class='num' href=\"".$this->url.rewrite::request("&page=".$i).$endpre."\">$i</a>";
		}
	 }
     if($this->page < $this->MaxPage){
        $NextPage = $this->page + 1;
        $showpage.= "<a class=\"pagedown\" href=\"".$this->url.rewrite::request("&page=".$NextPage).$endpre."\">下一页<em></em></a>";
     }else{
	    $showpage.= "<a class=\"pagedown pagedown-dis\">下一页<em></em></a>";
     }
     if($this->page < $this->MaxPage){
        $LastRec = $this->total % $this->size;
        if($LastRec == 0){
           $LastStartRecord = $this->total - $this->size;
        }else{
           $LastStartRecord = $this->total - $LastRec;
        }
        $showpage.= "<a class=\"pagedown\" href=\"".$this->url.rewrite::request("&page=".$this->MaxPage).$endpre."\">尾页</a>";
     }else{
		$showpage.= "<a class=\"pagedown pagedown-dis\">尾页</a>";
     }
	 return $showpage;
  }
  function minipage($endpre=''){
	 $endpre = $endpre ? "#{$endpre}" : "";
	 if($this->size>=$this->total) return false;
	 $showpage='<span>第'.$this->page.'/'.$this->MaxPage.'页</span>';
     if($this->page != 1){
        $PrevStart = $this->page - 1;
        $showpage.= "<a class='left' href='".$this->url.rewrite::request("&page=".$PrevStart)."' title='上一页'></a>";
     }else{
        $showpage.= "<a class=\"left left-dis\" title='上一页'></a>";		     
	 }
     if($this->page < $this->MaxPage){
        $NextPage = $this->page + 1;
        $showpage.= "<a class=\"right\" href=\"".$this->url.rewrite::request("&page=".$NextPage).$endpre."\" title=\"下一页\"></a>";
     }else{
	    $showpage.= "<a class=\"right right-dis\" title=\"下一页\"></a>";
     }
	 return $showpage;
  }
  
}
?>