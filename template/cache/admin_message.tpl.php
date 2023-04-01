<? if (!defined('ROOT')) exit('Can\'t Access !'); if($this->action!='login'&&$this->manager) { include template('admin_header','admin'); } else { ?>
<link rel="stylesheet" href="<?=config::get('siteurl')?>template/admin/images/admin.css" type="text/css" />
<style>
body{ background:none;}
</style>
<? } ?>
<link rel="stylesheet" href="<?=config::get('siteurl')?>template/admin/images/message.css" type="text/css" />
<script language="javascript">setTimeout("<?=$this->scode?>;",5000);</script>
<table cellspacing="1" cellpadding="3" border="1" align="center" class="table">
  <tbody>
  <th><?=$this->message?></th>
  <tr bgcolor="#ffffff">
    <td class="showmessage"><p class="op"><a href="<?=$this->jcode?>">确定</a>&nbsp;&nbsp;&nbsp;<a href="javascript:history.back(-1)">返回上一页</a></p></td>
  </tr>
  </tbody>  
</table>
<? if($this->action!='login') { include template('admin_footer','admin'); } ?>
