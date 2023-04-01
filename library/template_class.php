<?php
function template($file,$template_dir='default',$cache_dir='cache') {//模板引擎
	$tplfile = PATH."./template/$template_dir/$file.htm";
	$cachefile = PATH."./template/$cache_dir/".str_replace("../","",$file).".tpl.php";
	if(!file_exists($tplfile)) exit("Template file './template/$template_dir/$file.htm' not found!");
	if(@filemtime($tplfile) > @filemtime($cachefile)) parse_template($file,$template_dir,$cache_dir);
	return $cachefile;
}
function parse_template($file, $template_dir, $cache_dir) {
	$tplfile = PATH."./template/$template_dir/$file.htm";
	$cachefile = PATH."./template/$cache_dir/".str_replace("../","",$file).".tpl.php";
	$fp = @fopen($tplfile, 'r');	
	$template = @fread($fp, filesize($tplfile));
	fclose($fp);
    $var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";
	$template = preg_replace("/\{self\s+(.+?)\}/ies", "strself('\\1')", $template);
	$template = preg_replace("/\{config\s+(.+?)\}/ies", "strconfig('\\1')", $template);
	$template = preg_replace("/\{member\s+(.+?)\}/ies", "strmember('\\1')", $template);
	$template = preg_replace("/\{manager\s+(.+?)\}/ies", "strmanager('\\1')", $template);
    $template = preg_replace("/\{lang\s+(.+?)\}/ies", "languagevar('\\1')", $template);
	$template = preg_replace("/\{url\s+(.+?)\}/ies", "strurl('\\1')", $template);
	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
	$template = str_replace("{LF}", "<?=\"\\n\"?>", $template);
	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $template);
	$template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "addquote('<?=\\1?>')", $template);
	$template = "<? if (!defined('ROOT')) exit('Can\'t Access !'); ?>\n$template\n";
	$template = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include template('\\1','$template_dir'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/is", "\n<? include template('\\1','$template_dir'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{evaltemp\s+(.+?)\}[\n\r\t]*/is", "\n<? include template(\\1,'$template_dir'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? \\1 ?>','')", $template);
	$template = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<?=\\1; ?>','')", $template);
	$template = preg_replace("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "stripvtags('\\1<? } elseif(\\2) { ?>\\3','')", $template);
	$template = preg_replace("/([\n\r\t]*)\{else\}([\n\r\t]*)/is", "\\1<? } else { ?>\\2", $template);
	for($i=0;$i<5;$i++){
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<? } } ?>')", $template);
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<? } } ?>')", $template);
		$template = preg_replace("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r]*)(.+?)([\n\r]*)\{\/if\}([\n\r\t]*)/ies", "stripvtags('\\1<? if(\\2) { ?>\\3','\\4\\5<? } ?>\\6')", $template);
	}
	$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
	$template = str_replace('$this_getselfstr','$this->', $template);
    $template = preg_replace('/\$config\_getconfigstr(.+?)configend/is', "config::get('\\1')", $template);
	$template = preg_replace("/\{form\((.+?)\)\}/ies", "strform('\\1')", $template);
	$template = preg_replace('/\$membergetmemberstr(.+?)memberend/', '$this->member[\'\\1\']', $template);
	$template = preg_replace('/\$managergetmanagerstr(.+?)managerend/', '$this->manager[\'\\1\']', $template);
    $template = preg_replace('/\$urlgeturlstr(.+?)urlend/', 'Purl(\'\\1\')', $template);
    $template = preg_replace('/{script\s+(.+?)\}/ies',"scriptvar('\\1')", $template);
    $template = preg_replace('/{style\s+(.+?)\}/ies',"stylevar('\\1')", $template);
    makeDirectory(PATH."./template/$cache_dir/");
    if(!@$fp = fopen($cachefile, 'w')) {
		exit("Cache dir './template/$cache_dir/' not found!");
	}
	flock($fp, 2);
	fwrite($fp, $template);
	fclose($fp);
}
function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}
function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}
function strself($var) {
	return '{$this_getselfstr'.$var.'}';
}
function strform($var) {
	$var = preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $var);
	return '<?=config::form('.$var.');?>';
}
function strmember($var) {
	return '{$membergetmemberstr'.$var.'memberend}';
}
function strmanager($var) {
	return '{$managergetmanagerstr'.$var.'managerend}';
}
function strconfig($var) {
	return '{$config_getconfigstr'.$var.'configend}';
}
function strurl($var) {
	return '{$urlgeturlstr'.$var.'urlend}';
}
function languagevar($var) {
    return language($var);
}
function scriptvar($var) {
	if(config::get("rebug")){
	  $arr = explode(",",$var);
	  foreach($arr as $val){
		$html .= "<script type=\"text/javascript\" src=\"".$val."\" ></script>";  
	  }
	  return $html;
	}else{
	  return "<script type=\"text/javascript\" src=\"".config::get("siteurl")."min/?f=".$var."\" ></script>";	
	}
}
function stylevar($var) {
	if(config::get("rebug")){
	  $arr = explode(",",$var);
	  foreach($arr as $val){
		$html .= "<link rel=\"stylesheet\" href=\"$val\" type=\"text/css\" />";  
	  }
	  return $html;
	}else{
	  return '<link rel="stylesheet" href="'.config::get("siteurl").'min/?f='.$var.'" type="text/css" />';	
	}	
}
function makeDirectory($directoryName){
  $temp = $directoryName;
  if(!is_dir($temp)){
    $oldmask = umask(0);
    if(!mkdir($temp, 0777)) exit("不能建立目录 $temp");
    umask($oldmask);
  }
  return $temp;
}
?>