<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class config{
   function get($key) {
	 $config=include(PATH.'include/config.php');
	 if(isset($config[$key])) {
	   return $config[$key];
	 }else{
	   return false;
	 }
   }    
   function remove($name,$value=null,$values=null,$option=null){
	   $return = "<img src='".config::get("siteurl")."template/admin/images/icon_drop.gif'";
	   $return .= 'onclick="listTable.remove(\''.$name.'\',\''.$value.'\',\''.$values.'\');return false;" style="cursor:pointer;"/>';
       return  $return;
   }
    
   function input($name,$value=null,$values=null,$option=null){
       return  "<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\"  $option/>";
   }
   
   function data($name,$value=null,$values=null,$option=null){
	   $format = $values ? ",dateFmt:'yyyy-MM-dd HH:mm'" : "";
       return  "<input class=\"Wdate $option\" type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\"  onFocus=\"WdatePicker({isShowClear:false,readOnly:true{$format}})\" />";
   }
   
   function datasub($name,$value=null,$values=null,$option=null){
	   $format = $values ? ",dateFmt:'{$values}'" : ",dateFmt:'yyyy-MM-dd HH:mm:ss'";
       return  "<input class=\"Wdate $option\" type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\"  onFocus=\"WdatePicker({isShowClear:false,readOnly:true{$format},onpicked:function(){parent.showSub.submit();}})\" />";
   }
   
   function datas($name,$value=null,$values=null,$option=null){
	   $value = explode(',',$value);
	   $format = $values ? ",dateFmt:'yyyy-MM-dd HH:mm'" : "";
	   $return = "<input class=\"Wdate datatime\" type=\"text\" name=\"$name\" id=\"$name\" value=\"{$value[0]}\"  ";
	   $return .= 'onclick="var '.$name.'t=$dp.$(\''.$name.'t\');WdatePicker({onpicked:function(){'.$name.'t.focus();}'.$format.'})" '.$option.'/>';
	   $return .= "&nbsp;—&nbsp;<input class=\"Wdate datatime\" type=\"text\" name=\"{$name}t\" id=\"{$name}t\" value=\"{$value[1]}\" ";
	   $return .= ' onFocus="WdatePicker({minDate:\'#F{$dp.$D(\\\''.$name.'\\\')}\''.$format.'})" '.$option.'>';
       return  $return;
   }

   function password($name,$value=null,$values=null,$option=null) {
       return  "<input type=\"password\" name=\"$name\" id=\"$name\" value=\"$value\"  $option/>";
   }

   function textarea($name,$value=null,$values=null,$option=null) {
       return  "<textarea name=\"$name\" id=\"$name\" $option>$value</textarea>";
   }

   function image($name,$value=null,$values=null,$option=null){
	   if($value){
	     $return = "<img src='".$value."' width='30' height='30' style='vertical-align:middle;cursor:pointer' onmouseover='showImg(this);' onmouseout='Close();'/>&nbsp;";
	   }
	   $return .= "<input type=\"file\" name=\"{$name}\" id=\"{$name}\" $option />";
       return  $return;
   }
   
   static function editor($name,$value=null,$values=null,$option='',$imgpath='') {
	   @preg_match_all("%\<(.+?)@(.+?)\>%s",$option, $uf, PREG_PATTERN_ORDER);
	   $option = preg_replace("%\<(.+?)@(.+?)\>%s","",$option);
	   if($option=='') $option = "style='width:600px;height:300px;'";
	   $uploadJson = Purl("?mod=tools&act=upload&mychatpath=".$imgpath);	   
	   $fileManagerJson = Purl("tools_filemanager");
	   $allowFileManager = $uf[2][0] ? "true" : "false";
	   $allowImageUpload = $uf[1][0] ? "true" : "false";
	   $UploadJson = $uf[1][0] ? "allowFileManager:{$allowManager},imageUploadJson:'{$upload}'" : ",allowUpload : false";
	   $kindeditor = "<script>var {$name}_editor;KindEditor.ready(function(K) {
				{$name}_editor = K.create('textarea[name=\"{$name}\"]',{cssPath:'".config::get("siteurl")."template/admin/images/edit.css',resizeType:1,allowPreviewEmoticons:false,allowImageUpload:{$allowImageUpload},uploadJson:'{$uploadJson}',allowFileManager:{$allowFileManager},fileManagerJson:'{$fileManagerJson}',items:['source','fenlan','|','undo','redo','|','preview','cut','copy','paste','plainpaste','wordpaste','|','justifyleft','justifycenter','justifyright','justifyfull','insertorderedlist','insertunorderedlist','indent','outdent','baidumap','subscript','superscript','clearhtml','selectall','|','fullscreen','/','formatblock','fontname','fontsize','|','forecolor','hilitecolor','bold','italic','underline','strikethrough','lineheight','removeformat','|','image','multiimage','flash','media','|','insertfile','table','hr','pagebreak','anchor','link','unlink']});
			});</script>";
       return  "<div class='nop'>".$kindeditor."<textarea name=\"$name\" id=\"$name\" $option>$value</textarea></div>";
   }
   static function simple($name,$value=null,$values=null,$option='',$imgpath='') {
	   $kindeditor = "<script>var {$name}_editor;			KindEditor.ready(function(K) {
				{$name}_editor = K.create('textarea[name=\"content\"]', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link']
				});
			});</script>";
       return  "<div class='nop'>".$kindeditor."<textarea name=\"$name\" id=\"$name\" $option>$value</textarea></div>";
   }
   

   function select($name,$value=null,$values=null,$option=null) {
       $select = "<select id=\"$name\" name=\"$name\" $option>";
       foreach($values as $val){
		   $selected = $val[value]==$value ? 'selected ' : '';
           $select.="<option value=\"$val[value]\" $selected>$val[title]</option>"; 
	   }           
       $select.="</select>";
       return $select;
   }

   function radio($name,$value=null,$values=null,$option=null) {
	   $radio = '';
       foreach($values as $val){
		   $checked = $val[value]==$value ? 'checked="checked" ' : '';
           $radio .= "<input name=\"$name\" type=\"radio\" value=\"$val[value]\" $checked $option/>&nbsp;".$val[title]."&nbsp;";
	   }
       return $radio;
   }
   function checkbox($name,$value=null,$values=null,$option=null) {
	   $checkbox = '';
	   $value = explode(',',$value);
       foreach($values as $val){
		   $checked = in_array($val[value],$value) ? 'checked="checked" ' : '';
           $checkbox .= "<span><input type=\"checkbox\" name=\"{$name}[]\" id=\"$name\" value=\"$val[value]\" $checked $option/>".$val[title]."</span>";
	   }
       return $checkbox;
   }


   function hidden($name,$value=null,$values=null,$option=null){
       return "<input type=\"hidden\" name=\"$name\" id=\"$name\" value=\"$value\" $option/>";
   }


   function submit($name,$value=null,$values=null,$option=null) {	   
       return config::stopoutenable()."\n<input type=\"submit\" name=\"$name\" id=\"$name\" value=\"$value\" $option>";
   }
   
   function form($name,$value,$type,$values='',$option='',$imgpath='') {
	   $values = config::values($values);
       return $imgpath ? config::$type($name,$value,$values,$option,$imgpath) : config::$type($name,$value,$values,$option);
   }
   
   function stopoutenable(){
	   $_SESSION['stopoutenable'] = md5(substr(uniqid(rand()), -6)); 
	   return "<input type=\"hidden\" name=\"stopoutenable\" id=\"stopoutenable\" value=\"{$_SESSION['stopoutenable']}\"/>";
   }
   function seccode($v='',$wh=''){
	   $wh = $wh=='' ? 'width="60" height="20"' : $wh;
       return "<img src=\"".config::get("siteurl")."index.php?mod=tools&act=seccode\" {$wh} onclick=\"reSeccode(this)\" style=\"cursor:pointer;\"  {$v}/>";
   }
   
   function values($values){
      if(preg_match_all('%\[(.*?)\]%', $values, $rs, PREG_PATTERN_ORDER)==0) return $values;
	  $i = 0;
      foreach($rs[1] as $value) {
         if(preg_match_all('%\<(.*?)\>%',$value,$r, PREG_PATTERN_ORDER)==0) return $values;
		 $val[$i]['title'] = $r[1][1];
		 $val[$i]['value'] = $r[1][0]; 
		 $i++;
      }
	  return $val;
   }
   static function configs(){
	 return @file_get_contents(PATH."./include/config.php");	   
   }
   
   static function update($arr){
	 $configs = config::configs();
	 foreach($arr as $key => $value) {
       if(is_array($value)) $value = implode(',',$value);
       $configs = preg_replace("%(\"$key\"=>)\".*?\"(,\s*//)%s", "$1\"$value\"$2" ,$configs);
     }
     @file_put_contents(PATH.'./include/config.php',$configs);
   }
   
   
}
?>