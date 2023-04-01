<?php
/**
 * 新起点系统引入文件
 * ============================================================================
 * 版权所有 2005-2011 平顶山市新起点网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.phpwo.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: ZhaoGe $
 * $Id: init.php  $
*/

require(PATH.'library/upload_class.php');

require(PATH.'library/image_class.php');

require(PATH.'library/json_class.php');

require(PATH.'library/config_class.php');

require(PATH.'include/function.php');

require(PATH.'library/rewrite_class.php');

require(PATH.'library/module_class.php');

require(PATH.'library/mysql_class.php');

require(PATH.'library/controller_class.php');

require(PATH.'library/view_class.php');

require(PATH.'library/template_class.php');
		
require(PATH.'library/user_class.php');

require(PATH.'library/manager_class.php');

require(PATH.'library/member_class.php');

require(PATH.'library/page_class.php');

require(PATH.'library/cache_class.php');

rewrite::parameter();

?>