<? if (!defined('ROOT')) exit('Can\'t Access !'); include template('member_header','default/member'); ?>
<div id="main">
  <div class="right">
    <div class="opencard_main opencard_main2">
    
    
      <? if($_GET['type']=='status') { ?>
      <form id="ajaxformbox" name="ajaxformbox" method="post" onsubmit="return checkform();">
        <div class="opencards_title">
          <div class="opencards_title_b">激活账号</div>
          <div class="opencards_title_a">提示：<span class="text_x_12px">*</span> 为必填项目！</div>
        </div>
        <div class="order-prompt order-promptbommton">请务必确认登录密码与安全密码完全不同！密码相同会导致安全密码失效，使您的帐户资金无法得到安全保障。</div>
        <div class="opencard_box" style="padding-left:100px;">
          <div class="opencard_h">
            <div class="opencard_text"><span class="text_x_12px">*</span> 玩家编号</div>
            <div class="opencard_input_box"><span class="dis-input"><?=$this->member['username']?></span></div>
          </div>
          <div class="opencard_h">
            <div class="opencard_text"><span class="text_x_12px">*</span> 金币余额</div>
            <div class="opencard_input_box"><span class="dis-input"><?=$this->member['money']?></span></div>
          </div>
          <div class="opencard_h">
            <div class="opencard_text"><span class="text_x_12px">*</span> 激活所需金币</div>
            <div class="opencard_input_box"><span class="dis-input"><?=$this->member['usergroup']['buymoney']?></span></div>
          </div>
          <div class="opencard_h">
            <div class="opencard_text"><span class="text_x_12px">*</span> 激活玩家编号</div>
            <div class="opencard_input_box">
              <input name="username" id="username" class="myinput data" type="text" value="<?=$_GET['username']?>" onblur="checkusername()"/>
                <input name="mymoney" id="mymoney" type="hidden" value="<?=$this->member['money']?>"/>
                <input name="buymoney" id="buymoney" type="hidden" value="<?=$this->member['usergroup']['buymoney']?>"/>
                <span class="tips" id="usernametip"></span>
            </div>
          </div>
          <div class="opencard_h">
            <div class="opencard_text"> 报单中心编号</div>
            <div class="opencard_input_box">
              <input type="text" size="30" name="service" id="service" class="myinput data" onblur="checkservice()"/>
                 <span class="tips" id="servicetip"></span>
            </div>
          </div>
          
          <div class="opencard_button_box"><?=config::form('opcardbutton','确  定','submit');?></div>
        </div>
      </form>
      <? } ?>
      
      <? if($_GET['type']=='fuser') { ?>
      <div class="opencards_title">
        <div class="opencards_title_b">新增账号</div>
      </div>
      <? if($_GET['method']=='') { ?>
      <div class="member_mian member_mian2">
        <form method="GET" action="">
          <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
          <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
          <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
          <input type="hidden" name="method" id="method" value="<?=$_GET['method']?>" />
          <div class="ex_find">
            <div class="ex_text">激活日期</div>
            <div class="ex_time_box"><?=config::form('time',$this->time_str,'datas');?></div>
            <div class="ex_button_box">
              <input type="submit" id="button" value="查&nbsp;&nbsp;询" class="find_button" />
            </div>
          </div>
        </form>
        <div class="info_bg">
          <div class="info_text">查询统计：总共有 <b class="text_red_line"><?=$this->pagetotal?></b> 条记录</div>
        </div>
        <form method="post"  name="checkusermoney" id="checkusermoney" action="" onsubmit="return _checkusermoney()">
        <table class="sheet">
          <tr>
            <th width="60px"><input type="checkbox" id="chkall" name="chkall" onclick="checkall(this.form, 'uid')"></th>
            <th>账号编号</th>
            <th>所属主号</th>
            <th>激活时间</th>
            <th>金币余额</th>
            <th>金种子余额</th>
            <th>已经分红</th>
            <th>分红状态</th>
          </tr>
          <? if(is_array($this->record)) { foreach($this->record as $value) { ?>          <tr class="mybg">
            <td align="center" ><input type="checkbox" name="uid[]" value="<?=$value['uid']?>"></td>
            <td><?=$value['username']?></td>
            <td><?=$value['parentusername']?></td>
            <td><?=formattime($value['opentime']); ?></td>
            <td><?=$value['money']?></td>
            <td><?=$value['shopmoney']?></td>
            <td><?=$value['allmaxmoney']?></td>
            <td><?=$value['moneycheck'] ? "分红中" : '已停止'; ?></td>
          </tr>
          <? } } ?>        </table>
        <div class="no_info" style="margin-left:0; cursor:pointer;"><?=config::form('button','批量合并余额','submit','','class=\'opcardbutton\' style=\'cursor:pointer;\'');?></div>
        </form>
        <? if(!is_array($this->record)) { ?>
        <div class="no_info"><span class="no_info_ico"></span>暂无任何记录</div>
        <? } ?>
        <? if($this->newpage) { ?>
        <div class="pages"><?=$this->newpage?></div>
        <? } ?>
      </div>
      <? } ?>
      <? } ?>
    
      <? if($_GET['type']=='referee') { ?>
      <div class="opencards_title">
        <div class="opencards_title_b">推荐结构</div>
      </div>
      <div class="member_mian member_mian2">
      <form method="GET" action="">
        <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
        <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
        <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
        <input type="hidden" name="method" id="method" value="<?=$_GET['method']?>" />
        <div class="ex_find">
          <div class="ex_text">会员用户名</div>
          <div class="log_input_box">
            <input name="username" type="text" class="log_input" value="<?=$_GET['username']?>" />
          </div>
          <div class="ex_button_box">
            <input type="submit" id="button" value="查&nbsp;&nbsp;询" class="find_button" />
          </div>
        </div>
      </form>
      <div class="info_bg">
        <div class="info_text">图例注解： 所在层数[*] 用户名[******] 会员级别[******] 激活状态</div>
      </div>
      <div id="tree">
        <div class='node'>
          <div class='title'>
            <div class='click have_display_0' yesend="1" data='1' onClick="clickject(this);"></div>
            <span>[0][<?=$this->myuser['username']?>][<?=$this->myuser['groupname']?>][<?=$this->myuser['status'] ? '已激活' : '<em buymoney="'.$this->myuser['usergroup']['buymoney'].'" uid="'.$this->myuser['uid'].'" class="nowopen">未激活，点击激活</em>'; ?>]</span> </div>
          <div class='sub_0'>
            <? if(is_array($this->reuser)) { foreach($this->reuser as $key=>$value) { ?>            <? $yesend = count($this->reuser)-1>$key ? "2" : "1" ?>            <? if($value['renumber']>0) { ?>
            <div class="node">
              <div class="title">
                <div class="click have_<?=$yesend?>" yesend="<?=$yesend?>" onClick="clickject(this);" data='0' floor="1" username="<?=$value['username']?>"></div>
                <span>[1][<?=$value['username']?>][<?=$value['groupname']?>][<?=$value['status'] ? '已激活' : '<em buymoney="'.$value['usergroup']['buymoney'].'" uid="'.$value['uid'].'" class="nowopen">未激活，点击激活</em>'; ?>]</span> </div>
            </div>
            <? } else { ?>
            <div class='node'>
              <div class='title'>
                <div class="no_<?=$yesend?>" floor="1" yesend="<?=$yesend?>" username="<?=$value['username']?>"></div>
                <span>[1][<?=$value['username']?>][<?=$value['groupname']?>][<?=$value['status'] ? '已激活' : '<em style="color:#F00;">未激活</em>'; ?>]</span> </div>
            </div>
            <? } ?>
            <? } } ?>          </div>
        </div>
      </div>
      </div>
      <? } ?>
      <? if($_GET['type']=='record') { ?>
      <div class="opencards_title">
        <div class="opencards_title_b">推荐列表</div>
      </div>
      <div class="member_mian member_mian2">
        <form method="GET" action="">
          <input type="hidden" name="mod" id="mod" value="<?=$_GET['mod']?>" />
          <input type="hidden" name="act" id="act" value="<?=$_GET['act']?>" />
          <input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
          <input type="hidden" name="method" id="method" value="<?=$_GET['method']?>" />
          <div class="ex_find">
            <div class="ex_text">注册日期</div>
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
            <th>会员编号</th>
            <th>玩家编号</th>
            <th>会员级别</th>
            <th>推荐人数</th>
            <th>注册时间</th>
            <th>激活状态</th>
          </tr>
          <? if(is_array($this->record)) { foreach($this->record as $value) { ?>          <tbody id="remove_<?=$value['uid']?>">
          <tr class="mybg">
            <td><?=$value['uid']?></td>
            <td><?=$value['username']?></td>
            <td><?=$value['groupname']?></td>
            <td><?=$value['renumber']?></td>
            <td><?=formattime($value['regtime']); ?></td>
            <td><? if($value['status']) { ?><?=formattime($value['opentime']); } else { ?><a href="<?=Purl('?mod=member&act=treeform&type=status&username='.$value['username']); ?>" style="color:#F00">激活</a> <span uid="<?=$value['uid']?>" class="nowdelete">删除</span><? } ?></td>
          </tr>
          </tbody>
          <? } } ?>        </table>
        <? if(!is_array($this->record)) { ?>
        <div class="no_info"><span class="no_info_ico"></span>暂无任何记录</div>
        <? } ?>
        <? if($this->newpage) { ?>
        <div class="pages"><?=$this->newpage?></div>
        <? } ?>
      </div>
      <? } ?>
    </div>
  </div>
</div>
<? include template('member_footer','default/member'); ?>
