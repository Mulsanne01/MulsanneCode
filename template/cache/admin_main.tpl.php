<? if (!defined('ROOT')) exit('Can\'t Access !'); include template('admin_header','admin'); if($_GET['get']=='system') { if($_GET['re']=='givemoney') { ?>
<form method="post" action="" name="moneyfrom" id="moneyfrom" enctype="multipart/form-data" onsubmit="return returnmoney()">
  <?=config::form('button','马上结算','submit','','class=\'button\'');?>
</form>
<script language="javascript">
$(function(){
  returnmoney();
});
function returnmoney(){
  listTable.ajaxform('分红结算','moneyfrom','');   
  return false;
}
</script>
<? } if($_GET['re']=='index') { ?>
<div id="index_pool">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th></th>
      <th align="center">新进会员</th>
      <th align="center">营业总额</th>
      <th align="center">拨出总额</th>
      <th align="center">利润总额</th>
      <th align="center">提现总额</th>
      <th align="center">已付提现</th>
      <th align="center">金币抢购</th>
      <th align="center">人工抢购</th>
    </tr>
    <tr>
      <th>今日</th>
      <td align="center"><span class="red"><?=$this->todayregnumber?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->todaymoney['buymoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->todaymoney['outmoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=formatnum($this->todaymoney['buymoney']-$this->todaymoney['outmoney']); ?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->todaymoney['atmmoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->todaymoney['atmmoneyed']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->todaymoney['paymoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->todaymoney['_paymoney']?></span></td>
    </tr>
    <tr>
      <th>昨日</th>
      <td align="center"><span class="red"><?=$this->yestodayregnumber?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->yestodaymoney['buymoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->yestodaymoney['outmoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=formatnum($this->yestodaymoney['buymoney']-$this->yestodaymoney['outmoney']); ?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->yestodaymoney['atmmoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->yestodaymoney['atmmoneyed']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->yestodaymoney['paymoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->yestodaymoney['_paymoney']?></span></td>
    </tr>
    <tr>
      <th>全部</th>
      <td align="center"><span class="red"><?=$this->regnumber?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->allmoney['buymoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->allmoney['outmoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=formatnum($this->allmoney['buymoney']-$this->allmoney['outmoney']); ?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->allmoney['atmmoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->allmoney['atmmoneyed']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->allmoney['paymoney']?></span></td>
      <td align="center"><span class="red">&yen;<?=$this->allmoney['_paymoney']?></span></td>
    </tr>
  </table>
</div>
<br />
<div id="index_diagram">
  <div id="chartspie" style="width:49%;float:left;"></div>
  <div id="chartscolumn" style="width:49%;float:left;"></div>
</div>
<script language="javascript"> 
$(function() { 
   chart('chartscolumn',"column",['综合统计'],[{name:'收入',data:[<?=$this->allmoney['buymoney']?>]},{name:'支出',data:[<?=$this->allmoney['outmoney']?>]},{name:'利润',data: [<?=$this->allmoney[buymoney]-$this->allmoney[outmoney];; ?>]}]);
   chart('chartspie',"pie",['综合统计'],[['分红奖',<?=$this->allmoney['money']?>],['推荐奖',<?=$this->allmoney['refereemoney']?>]]);
});
</script>
<? } if($_GET['re']=='money') { ?>
<form method="post" action="" name="moneyform" id="moneyform" enctype="multipart/form-data" onsubmit="return moneycheckForm()">
  <div>
    <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
      <tbody>
        <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
          <td class="left">剩余金币</td>
          <td> <?=config::form('allmoney',$this->add[allmoney],'input','','class=\'skey\' style=\'width:100px;\'');?>  </td>
        </tr>
        <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
          <td class="left">已经售出</td>
          <td> <?=$this->add['money']?> </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="blank20"></div>
  <?=config::form('button','提交','submit','','class=\'button\'');?>
</form>
<? } } if($_GET['get']=='config') { ?>
<form method="post" action="" name="configform" id="configform" enctype="multipart/form-data" onsubmit="return configcheckForm()">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table" class="tad">
    <? $value = $this->configs[$_GET['re']]['arr']; ?>    <? if(is_array($value)) { foreach($value as $input) { ?>    <tr>
      <td width="11%" align="right"><strong><?=$input['3']?></strong></td>
      <td width="1%">&nbsp;</td>
      <td width="80%"><?=config::form($input['1'],$input['2'],$input['5'],$input['6'],$input['7']);?><span class="tipser"><?=$input['4']?></span></td>
    </tr>
    <? } } ?>  </table>
  <?=config::form('button','确认修改','submit','','class=\'button\'');?>
</form>
<? } if($_GET['get']=='database') { if($_GET['re']=='back') { ?>
<script language="javascript">
function getbegin(val,title){
 $("#sinbegin").val(val);
 listTable.ajaxform(title,'database');
}
</script>
<form method="post" name="database" id="database" action="">
  <table border="0" cellpadding="0" cellspacing="0" class="databasetop">
    <input type="hidden" name="baktype" value="0">
    <input type="hidden" name="bakstru" id="bakstru" value="1">
    <input type="hidden" name="bakdatatype" value="0">
    <input type="hidden" name="insertf" value="replace">
    <input name="sinbegin" type="hidden" id="sinbegin" value="doback">
    <input name="dbchar" type="hidden" id="dbchar" value="auto">
    <tr>
      <td width="120" align="right" style="border:0;"><strong>每组备份大小</strong>： </td>
      <td align="left" style="border:0;"><input type="text" name="filesize"  id="filesize" value="100"/>
        KB <font color="#666666">(1 MB = 1024 KB)</font></td>
      <td width="120" align="right" style="border:0;"><strong>数据存放目录</strong>： </td>
      <td align="left" style="border:0;"> database /
        <input name="mypath" type="text" id="mypath" value="data<?=date('YmdHis'); ?>"></td>
    </tr>
  </table>
  <table border="0" align="center" cellpadding="0" cellspacing="0" class="database">
    <tr>
      <th width="27%" class="right">表名</th>
      <th width="13%" class="right">类型</th>
      <th width="15%" class="right">编码</th>
      <th width="15%" class="right">记录数</th>
      <th width="14%" class="right">大小</th>
      <th width="11%" class="right _right">碎片</th>
    </tr>
    <? if(is_array($this->database)) { foreach($this->database as $value) { ?>    <tr>
      <td class="right"><?=$value['Name']?></td>
      <td class="right"><?=$value['Type'] ? $value['Type'] : $value['Engine']; ?></td>
      <td class="right"><?=$value['Collation'] ? $value['Collation'] : '-----'; ?></td>
      <td class="right"><?=$value['Rows']?></td>
      <td class="right"><?=Ebak_ChangeSize($value['Data_length']+$value['Index_length']); ?></td>
      <td class="right _right"><?=Ebak_ChangeSize($value['Data_free']); ?></td>
    </tr>
    <? } } ?>  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="database">
    <tr>
      <td align="center"  style="border:0;"> <?=config::stopoutenable(); ?>        <input type="button" name="button" id="button" value="备份数据" class='button' onclick="getbegin('doback','备份数据');">
        <input type="button" name="button" id="button" value="修复数据表" class='button' onclick="getbegin('dorep','修复数据表');">
        <input type="button" name="button" id="button" value="优化数据表" class='button' onclick="getbegin('doopi','优化数据表');"></td>
    </tr>
  </table>
</form>
<? } if($_GET['re']=='unback') { ?>
<table border="0" cellpadding="0" cellspacing="0" class="databasetop">
  <tr bgcolor="#ffffff">
    <td width="120" align="right" style="border:0;"><strong>上传备份数据：</strong></td>
    <td style="border:0;"><form method="post" action="" enctype="multipart/form-data">
        <input type="file" name="updatefile" id="updatefile" />
        <?=config::stopoutenable(); ?>        &nbsp;&nbsp;
        <input type="submit" name="button" id="button" class='button' value="确定上传">
      </form></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0"  class="database">
  <thead>
    <tr>
      <th class="right">备份目录</th>
      <th class="right">备份时间</th>
      <th class="right _right">操作</th>
    </tr>
  </thead>
  <? if(!is_array($this->arrayfile)) { ?>
  <tr>
    <td colspan="3" height="40" class="right _right">对不起，暂时没有备份数据</td>
  </tr>
  <? } ?>
  <? if(is_array($this->arrayfile)) { foreach($this->arrayfile as $value) { ?>  <tbody id="remove_<?=$value['filename']?>">
    <tr class="s_out" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
      <td class="right"><img src="<?=$this->tempdir?>images/icon07.gif">&nbsp;<?=$value['filename']?></td>
      <td class="right"><?=$value['filetime']?></td>
      <td class="right _right"><a href="javascript:listTable.unback('<?=$this->managershell?>','<?=$value['filename']?>');">恢复数据</a> <a href="<?=Purl("?mod=admin&act=main&get=database&re=unback&mypath=".$value['filename']."&down=1"); ?>" target="_blank">打包并下载数据</a> <a href="javascript:listTable.remove('<?=$value['filename']?>','确定要删除该备份数据吗');">删除备份</a></td>
    </tr>
  </tbody>
  <? } } ?></table>
<? } if($_GET['re']=='emptydb') { ?>
<form method="post" action="" name="emptydbfrom" id="emptydbfrom" enctype="multipart/form-data" onsubmit="return returnemptydb()">
  <?=config::form('button','清空数据','submit','','class=\'button\'');?>
</form>
<script language="javascript">
$(function(){
  returnemptydb();
});
function returnemptydb(){
  listTable.ajaxform('清空数据','emptydbfrom',''); 
  return false;
}
</script>
<? } } if($_GET['get']=='guestbook') { if($_GET['re']=='list') { ?>
<div class="headbar clearfix">
  <ul class="tab">
    <li <? if($_GET['type']=='') { ?>class="selected"<? } ?>><a href="<?=Purl(adminpre()); ?>">全部信件</a></li>
    <li <? if($_GET['type']=='1') { ?>class="selected"<? } ?>><a href="<?=Purl(adminpre().'&type=1'); ?>">收到的信件</a></li>
    <li <? if($_GET['type']=='2') { ?>class="selected"<? } ?>><a href="<?=Purl(adminpre().'&type=2'); ?>">发出的信件</a></li>
    <div class="tabright">
      <form method="get" action="">
        <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
        <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
        <input type="hidden" name="get" id="get" value="<?=$_GET['get']?>" />
        <input type="hidden" name="re" id="re" value="<?=$_GET['re']?>" />
        <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
        用户名：
        <input type="text" name="username" id="username" value="<?=$_GET['username']?>" class='skey' style='width:100px;'/>
        时间段：<?=config::form('time',$this->t['str'],'datas');?>
        <input type="submit" id="button" value="立即搜索" class='button'>
      </form>
    </div>
    <div style="clear:both;"></div>
  </ul>
</div>
<table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
  <thead>
    <tr>
      <th align="center">信件编号</th>
      <th align="center">信件主题</th>
      <th align="center">信件会员</th>
      <th align="center">信件类型</th>
      <th align="center">当前状态</th>
      <th align="center">发送时间</th>
      <th align="center">信件操作</th>
    </tr>
  </thead>
  <? if(is_array($this->message)) { foreach($this->message as $value) { ?>  <tbody>
    <tr class="s_out" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff" id="remove_<?=$value['id']?>">
      <td align="center"><?=$value['id']?></td>
      <td style="text-align:left; padding-left:10px;"><a href="javascript:listTable.message(<?=$value['id']?>,'1');"><?=$value['title']?></a></td>
      <td align="center"><?=$value['username']?></td>
      <td align="center"><?=$value['type']?></td>
      <td align="center" id="toggle_<?=$value['id']?>"><?=$value['action']?></td>
      <td align="center"><?=$value['addtime']?></td>
      <td align="center"><a href="javascript:listTable.remove('<?=$value['id']?>','确定要删除该信件吗');">彻底删除</a></td>
    </tr>
  </tbody>
  <? } } ?></table>
<div class="page"><span><?=$this->pagetotal?>条记录/<? echo $_GET['page'] ? $_GET['page'] : 1  ?>页</span><?=$this->showpage?></div>
<div class="blank20"></div>
<? } if($_GET['re']=='add') { ?>
<form method="post" action="" name="messageform" id="messageform" enctype="multipart/form-data" onsubmit="return messagecheckForm()">
  <div>
    <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
      <tbody>
        <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
          <td class="left">信件主题</td>
          <td> <?=config::form('title','','input','','class=\'skey\'');?> </td>
        </tr>
        <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
          <td class="left">接收会员</td>
          <td> <?=config::form('sendtype','','radio','[<0><全部会员>][<1><按会员组>][<2><手动输入>]');?>
           <span style="display:none;" id="sendtype_1"><?=config::form('groupid','','select',$this->usergroup);?></span>
           <span style="display:none;" id="sendtype_2"><?=config::form('username','','input','','class=\'skey\'');?> 每个会员之间以“,”号隔开</span>
          </td>
        </tr>
        <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
          <td class="left">信件内容</td>
          <td> <?=config::form('content','','textarea','','class=\'textarea1\'');?> </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="blank20"></div>
  <?=config::form('button','提交','submit','','class=\'button\'');?>
</form>
<? } } include template('admin_footer','admin'); ?>
