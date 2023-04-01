<? if (!defined('ROOT')) exit('Can\'t Access !'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=config::get('sitename')?>会员平台</title>
<? include template('../common','default/member'); ?>
<link href="<?=$this->tempdir?>css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$this->tempdir?>js/member.js" ></script><script type="text/javascript" src="<?=$this->tempdir?>js/<?=$_GET['act']?>_<?=$_GET['type']?>.js" ></script>
</head>
<body>
<div id="header">
 <div class="logo"></div>
 <div class="nav">
   
<? include template('member_left','default/member'); ?>
 </div>
 <div style="clear:both;"></div>
</div>
<div id="banner"><img src="<?=$this->tempdir?>images/lo2_05.jpg" /></div>

