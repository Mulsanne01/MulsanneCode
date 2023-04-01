<?php
/**
 * 新起点团购玩家首页文件
 * ============================================================================
 * 版权所有 2005-2011 平顶山市新起点网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.phpwo.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: ZhaoGe $
 * $Id: index.php  $
*/
header("Content-Type:text/html;charset=utf-8");
require(dirname(__FILE__) . '/include/common.inc.php');
require(PATH . 'include/init.php');
$view = new view_class();
//ini_set('display_errors','On');
//error_reporting(E_ALL );
$view->display();

?>