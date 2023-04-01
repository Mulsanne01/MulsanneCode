<? if (!defined('ROOT')) exit('Can\'t Access !'); ?>
<ul id="menu">
  <li><a href="<?=Purl("?mod=member"); ?>">玩家首页</a></li>
  <? if(is_array(user_menu())) { foreach(user_menu() as $key=>$val) { ?>  <? if($this->user->gt_purview($key)&&$key!='main') { ?>
  <li><a href="javascript:;"><?=$val?></a>
    <ul id="menu_<?=$key?>">
      <? if(is_array(member_menu($key))) { foreach(member_menu($key) as $k=>$v) { ?>      <? if($this->user->rn_purview('member',$key,$k)) { ?>
      <li><a href="<?=Purl("?mod=member&act=".$key."&type=".$k); ?>"><div class="ico i_menu"></div><?=$v?></a></li>
      <? } ?>
      <? } } ?>    </ul>
  </li>
  <? } ?>
  <? } } ?>  <li><a href="<?=Purl("?mod=member&act=notice"); ?>">玩家公告</a></li>
  <li><a href="<?=Purl("?mod=member&act=imessage"); ?>">站内信件</a></li>
  <li><a href="<?=Purl("?mod=member&act=logout"); ?>">退出系统</a></li>
</ul>
