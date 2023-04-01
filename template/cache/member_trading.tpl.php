<? if (!defined('ROOT')) exit('Can\'t Access !'); include template('member_header','default/member'); ?>
<div id="main">
  
  <div class="right">
    <div class="opencard_main">
      <? if($_GET['type']=='list') { ?>
      <div class="track_title"> 
<a class="<?=$_GET['method']==''?'menushow':'menu'; ?>" href="<?=Purl("?mod=member&act=trading&type=list"); ?>">抢购金币</a>
<a class="<?=$_GET['method']=='record'?'menushow':'menu'; ?>" href="<?=Purl("?mod=member&act=trading&type=list&method=record"); ?>">购买记录</a>
</div>
<? if($_GET['method']=='') { ?>
      <div class="member_mian">
        <form method="GET" action="">
          <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
          <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
          <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
          <input type="hidden" name="method" id="method" value="<?=$_GET['method']?>" />
          <div class="ex_find">
            <div class="ex_text">查询日期</div>
            <div class="ex_time_box"><?=config::form('time',$this->time_str,'datas');?></div>
            <div class="ex_button_box">
              <input type="submit" id="button" value="查&nbsp;&nbsp;询" class="find_button" />
            </div>
          </div>
        </form>
        <div class="info_bg">
          <div class="info_text">查询统计：总共有 <b class="text_red_line"><?=$this->pagetotal?></b> 条记录</div>
        </div>
      <table class="sheet">
        <tr>
          <th>挂单编号</th>
          <th>挂单金额</th>
          <th>挂单会员</th>
          <th>目前状态</th>
          <th>挂单时间</th>
        </tr>
        <? if(is_array($this->record)) { foreach($this->record as $value) { ?>        <? $buyusername = $this->mysql->value($this->pre."user","username","uid='".$value['bid']."'"); ?>        <tbody id="payorder_<?=$value['orderid']?>">
          <tr class="mybg">
            <td><?=$value['id']?></td>
            <td class="red">&yen;<?=$value['money']?></td>
            <td><?=$value['user']['username']?></td>
            <td><?=btradcheck($value['checked'],$value['id']); ?></td>
            <td><?=formattime($value['addtime']); ?></td>
          </tr>
        </tbody>
        <? } } ?>      </table>
        <? if(!is_array($this->record)) { ?>
        <div class="no_info"><span class="no_info_ico"></span>暂无任何记录</div>
        <? } ?>
        <? if($this->newpage) { ?>
        <div class="pages"><?=$this->newpage?></div>
        <? } ?>
      </div>
      
    <? } ?>
    <? if($_GET['method']=='record') { ?>
      <div class="member_mian">
        <form method="GET" action="">
          <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
          <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
          <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
          <input type="hidden" name="method" id="method" value="<?=$_GET['method']?>" />
          <div class="ex_find">
            <div class="ex_text">查询日期</div>
            <div class="ex_time_box"><?=config::form('time',$this->time_str,'datas');?></div>
            <div class="ex_button_box">
              <input type="submit" id="button" value="查&nbsp;&nbsp;询" class="find_button" />
            </div>
          </div>
        </form>
        <div class="info_bg">
          <div class="info_text">查询统计：总共有 <b class="text_red_line"><?=$this->pagetotal?></b> 条记录</div>
        </div>
      <table class="sheet">
        <tr>
          <th>挂单编号</th>
          <th>挂单金额</th>
          <th>挂单会员</th>
          <th>开户银行</th>
          <th>银行户名</th>
          <th>银行卡号</th>
          <th>目前状态</th>
          <th>购买时间</th>
          <th>挂单时间</th>
        </tr>
        <? if(is_array($this->record)) { foreach($this->record as $value) { ?>        <? $buyusername = $this->mysql->value($this->pre."user","username","uid='".$value['bid']."'"); ?>        <tbody id="payorder_<?=$value['orderid']?>">
          <tr class="mybg">
            <td><?=$value['id']?></td>
            <td class="red">&yen;<?=$value['money']?></td>
            <td><?=$value['user']['username']?></td>
            <td title="<?=$value['bankadd']?><?=$value['bankname']?>"><?=$value['bankname']?></td>
            <td><?=$value['truename']?></td>
            <td><?=$value['bankcard']?></td>
            <td><?=tradcheck($value['checked'],$value['id']); ?></td>
            <td><?=formattime($value['buytime']); ?></td>
            <td><?=formattime($value['addtime']); ?></td>
          </tr>
        </tbody>
        <? } } ?>      </table>
        <? if(!is_array($this->record)) { ?>
        <div class="no_info"><span class="no_info_ico"></span>暂无任何记录</div>
        <? } ?>
        <? if($this->newpage) { ?>
        <div class="pages"><?=$this->newpage?></div>
        <? } ?>
      </div>
      <? } ?>
      <? } ?>


    <? if($_GET['type']=='addorder') { ?>
    <div class="track_title"> <a class="<?=$_GET['method']==''?'menushow':'menu'; ?>" href="<?=Purl("?mod=member&act=trading&type=addorder"); ?>">金币卖出</a> <a class="<?=$_GET['method']=='record'?'menushow':'menu'; ?>" href="<?=Purl("?mod=member&act=trading&type=addorder&method=record"); ?>">卖出记录</a> </div>
    <? if($_GET['method']=='') { ?>
    <div class="member_mian">
    <div class="to-cash" id="to-cash">
      <div class="to-cash-tit"><span>选择收款银行</span>
        <? $count = 5-count($this->bank); ?>        <div class="add-banks <? if($count>0) { ?>showbanks<? } ?>" id="banks"> <a href="javascript:addbank();">添加银行卡</a>（还可添<a id="bankcount"><?=$count?></a>个） </div>
      </div>
      <table cellpadding="0" cellspacing="0" border="0">
        <tbody>
          <tr>
            <th>银行名称</th>
            <th>开户名</th>
            <th>银行卡号</th>
            <th>开户地址</th>
            <th>操作</th>
          </tr>
        </tbody>
        <tbody id="hasSetBanks" class="hasbanks <? if(!is_array($this->bank)) { ?>showbanks<? } ?>">
          <tr>
            <td colspan="15" align="center">您还没有设置用于挂单的银行！<a href="javascript:addbank();">添加银行卡</a></td>
          </tr>
        </tbody>
        <input name="atmbank" id="atmbank" type="hidden" value="" />
        <tbody id="banklist">
          <? if(is_array($this->bank)) { foreach($this->bank as $value) { ?>          <? $value['bankimages'] = bankimages($value['bankname']); ?>          <tr>
            <td onclick="changebank('<?=$value['id']?>')"><input name="bankid" id="bankid_<?=$value['id']?>" type="radio" value="<?=$value['id']?>" />
              <img src="<?=$this->hempdir?>images/chinabank0/<?=$value['bankimages']?>"/> <?=$value['bankname']?>
              </li></td>
            <td><?=$value['truename']?></td>
            <td><?=$value['bankcard']?></td>
            <td><?=$value['bankadd']?></td>
            <td><a href="javascript:editbank('<?=$value['id']?>');">编辑</a> <a href="javascript:delbank('<?=$value['id']?>');">删除</a></td>
          </tr>
          <? } } ?>        </tbody>
      </table>
    </div>
    <div class="to-cash">
      <div class="to-cash-tit"><span>确认挂单金额</span></div>
      <div class="opencard_box" style="margin-top:20px;">
        <div class="opencard_h">
          <div class="opencard_text"><span class="text_x_12px">*</span> 现金余额</div>
          <div class="opencard_input_box"><span class="dis-input"><?=$this->member['money']?></span><span class="tips" id="moneytip"></span></div>
        </div>
        <div class="opencard_h">
          <div class="opencard_text"><span class="text_x_12px">*</span> 挂单金额</div>
          <div class="opencard_input_box">
            <input name="howmoney" id="howmoney" class="myinput" type="text" onblur="checkmoney()"/>
            <input type="hidden" name="mymoney" id="mymoney" value="<?=$this->member['money']?>" />
            <span class="tips" id="howmoneytip"></span></div>
        </div>
      </div>
      <div class="opencard_button_box"><?=config::form('opcardbutton','确认挂单','submit');?><span class="tips" id="cashtip"></span></div>
    </div>
    </td>
    <? } ?>
    <? if($_GET['method']=='record') { ?>
    <div class="member_mian">
      <form method="GET" action="">
        <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
        <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
        <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
        <input type="hidden" name="method" id="method" value="<?=$_GET['method']?>" />
        <div class="ex_find">
          <div class="ex_text">查询日期</div>
          <div class="ex_time_box"><?=config::form('time',$this->time_str,'datas');?></div>
          <div class="ex_button_box">
            <input type="submit" id="button" value="查&nbsp;&nbsp;询" class="find_button" />
          </div>
        </div>
      </form>
      <div class="info_bg">
        <div class="info_text">查询统计：总共有 <b class="text_red_line"><?=$this->pagetotal?></b> 条记录</div>
      </div>
      <table class="sheet">
        <tr>
          <th>挂单编号</th>
          <th>挂单金额</th>
          <th>购买会员</th>
          <th>购买时间</th>
          <th>目前状态</th>
          <th>申请时间</th>
        </tr>
        <? if(is_array($this->record)) { foreach($this->record as $value) { ?>        <? $buyusername = $this->mysql->value($this->pre."user","username","uid='".$value['bid']."'"); ?>        <tbody id="payorder_<?=$value['orderid']?>">
          <tr class="mybg">
            <td><?=$value['id']?></td>
            <td class="red">&yen;<?=$value['money']?></td>
            <td><? if($value['checked']>1) { ?><?=$buyusername?><? } else { ?>未售出<? } ?></td>
            <td><? if($value['checked']>1) { ?><?=formattime($value['buytime']); } else { ?>未售出<? } ?></td>
            <td><?=btradcheck($value['checked'],$value['id']); ?></td>
            <td><?=formattime($value['addtime']); ?></td>
          </tr>
        </tbody>
        <? } } ?>      </table>
      <? if(!is_array($this->record)) { ?>
      <div class="no_info"><span class="no_info_ico"></span>暂无任何记录</div>
      <? } ?>
      <? if($this->newpage) { ?>
      <div class="pages"><?=$this->newpage?></div>
      <? } ?>
    </div>
    <? } ?>
    <? } ?>
  </div>
</div>
</div>
<? include template('member_footer','default/member'); ?>
