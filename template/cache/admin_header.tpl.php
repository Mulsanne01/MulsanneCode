<? if (!defined('ROOT')) exit('Can\'t Access !'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="utf-8" />
<title>后台管理中心</title>
<script language="javascript">
var siteurl="<?=config::get('siteurl')?>",tempdir="<?=$this->tempdir?>",hempdir="<?=$this->hempdir?>",appdir="<?=$this->appdir?>",rewrite="<?=config::get('rewrite')?>",module="<?=$this->module?>",action="<?=$this->action?>",re="<?=$_GET['re']?>";//初始变量
</script><? $admin_menu_top = admin_menu_top();
   $admin_menu_left = admin_menu_left($_GET['act']);
   $ActName = $admin_menu_top[$_GET['act']];
   $getName = str_replace("-no","",$admin_menu_left[$_GET['get']]);
   $nav = $ActName." >> ".$getName;
   $admin_menu_small = admin_menu_small($_GET['act'],$_GET['get']);   
 ?><link rel="stylesheet" href="<?=$this->tempdir?>images/admin.css" type="text/css" /><link rel="stylesheet" href="<?=$this->appdir?>control/control.css" type="text/css" /><link rel="stylesheet" href="<?=$this->appdir?>kindeditor/themes/default/default.css" type="text/css" />
<script type="text/javascript" src="<?=$this->appdir?>jquery.js" ></script><script type="text/javascript" src="<?=$this->appdir?>function.js" ></script><script type="text/javascript" src="<?=$this->appdir?>areadata.js" ></script><script type="text/javascript" src="<?=$this->appdir?>myarea.js" ></script><script type="text/javascript" src="<?=$this->appdir?>utils.js" ></script><script type="text/javascript" src="<?=$this->appdir?>listtable.js" ></script><script type="text/javascript" src="<?=$this->appdir?>control/control.js" ></script><script type="text/javascript" src="<?=$this->appdir?>highcharts.js" ></script><script type="text/javascript" src="<?=$this->appdir?>jquery.form.js" ></script><script type="text/javascript" src="<?=$this->appdir?>editor/kindeditor.js" ></script><script type="text/javascript" src="<?=$this->appdir?>editor/lang/cn.js" ></script><script type="text/javascript" src="<?=$this->appdir?>date/WdatePicker.js" ></script><script type="text/javascript" src="<?=$this->appdir?>swfupload/swfupload.js" ></script><script type="text/javascript" src="<?=$this->appdir?>swfupload/multiupload.js" ></script><script type="text/javascript" src="<?=$this->tempdir?>images/admin.js" ></script>
</head>
<body>
<div id="top">
  <div style="background:#1b4070; width:100%; height:auto; display:block;">
    <div id="logo"><img src="<?=config::get('siteurl')?>template/admin/images/logo.gif" /></div>
    <div id="nav">
      <p><span style="float:right; font-size:12px;">您好，<strong><?=$this->manager['username']?> <font color="#FF0000">(<?=$this->manager['groupname']?>)</font>！</strong> [<a href="<?=Purl('admin_logout')?>">退出</a>] </span></p>
      <div class="nav">
      <? if(is_array($admin_menu_top)) { foreach($admin_menu_top as $key=>$val) { ?>      <? if($this->manager_class->gt_purview($key)) { ?>
       <a href="<?=Purl('admin_'.$key); ?>" <? if($key==$this->action) { ?>class="on"<? } ?>><?=$val?></a> 
      <? } ?>
      <? } } ?>       <a href="<?=config::get('siteurl')?>" class="home" target="_blank">会员平台</a> 
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
<div id="position">当前位置：<?=$nav?></div>
<div id="main">
  <div id="left">
    <div id="menu">
      <dl>
        <dt><?=$admin_menu_top[$this->action];; ?></dt>
      <? if(is_array($admin_menu_left)) { foreach($admin_menu_left as $key=>$val) { ?>       <? if($this->manager_class->rn_purview($this->module,$this->action,$key)) { ?>
         <? $class = $_GET['get']==$key ? 'class="on"' : '';  ?>         <dd <?=$class?>><a href="<?=Purl("?mod=admin&act=".$this->action."&get=".$key); ?>" <?=$class?>><?=$val?></a></dd>
       <? } ?>
      <? } } ?>      </dl>
    </div>
  </div>
  <div id="right">
    <div class="right" style="width:auto;overflow-x:hidden;overflow-y:auto;">
        <div class="tags">
          <div id="tagstitle"> 
            <? if(is_array($admin_menu_small)) { foreach($admin_menu_small as $key=>$val) { ?>            <? if(!strstr($val,"-no")) { ?>
             <a href="<?=Purl("?mod=admin&act=".$this->action."&get=".$_GET['get']."&re=".$key); ?>" <? if($_GET['re']==$key) { ?>class="hover"<? } ?>><?=$val?></a>
            <? } ?>
            <? } } ?>            <? if($_GET['re']=='edit') { ?>
            <a href="javascript:;" class="hover"><?=str_replace("-no","",$admin_menu_small['edit']);; ?></a>
            <? } ?>
          </div>
          <div id="tagscontent" >
            <div id="con_one_1">
              <div class="table_td">
              
