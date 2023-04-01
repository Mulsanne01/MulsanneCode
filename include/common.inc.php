<?php
/**
 * 新起点团购系统引入文件
 * ============================================================================
 * 版权所有 2005-2011 平顶山市新起点网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.phpwo.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: ZhaoGe $
 * $Id: COMMON.INC.php  $
*/
session_start();

define('START',microtime(true));


define('PATH', substr(dirname(__FILE__), 0, -7));

define('ROOT', TRUE);

if (PHP_VERSION >= '5.1')
{
    date_default_timezone_set('PRC');
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);

//set_error_handler('php_error', E_ALL); //函数名，收集的错误级别

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}

set_time_limit(0);

/* 转义 */
function getmagicquotes($array)
{
	if(!get_magic_quotes_gpc())
	{
		if(is_array($array))
		{
			foreach($array as $key => $value) $array[$key] = getmagicquotes($value);
		}
		else
		{
			$array = addslashes($array);
		}
	}
	return $array;
}

//检查和免费注册外部提交的变量
foreach($_REQUEST as $_k=>$_v)
{
	if( strlen($_k)>0 && eregi('^(cfg_|GLOBALS)',$_k) )
	{
		exit('Request var not allow!');
	}
}

foreach(Array('_GET','_POST','_COOKIE') as $_request)
{
	foreach($$_request as $_k => $_v){
		${$_request}[$_k] = getmagicquotes($_v);
	}
}

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

?>