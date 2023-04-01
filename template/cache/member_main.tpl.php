<? if (!defined('ROOT')) exit('Can\'t Access !'); include template('member_header','default/member'); ?>
<div id="main">
  <div class="right" style="padding-top:20px;">
    <div class="uinfo">
<ul>
            <li>玩家編號：<span><?=$this->member['username']?></span></li>
            <li>身份等級：<span>无级初学乍练</span> <a href="#">說明</a></li>
            <li>信用指數：<span>★★★★★(五星)</span></li>
            <li>激活狀態：<span><?=$this->member['status'] ? "已经激活" : "暂未激活"; ?></span></li>
            <li>帳號類型：<span>主账号</span></li>
            <li>报单中心：<span>是</span></li>
            <li>新增帳號：<span class="f16">1</span>個</li>
            <li>金幣帳戶：<span class="PriceCss3"><?=$this->member['money']?></span></li>
            <li>金種子帳戶：<span class="PriceCss3"><?=$this->member['shopmoney']?></span></li>
            <li></li>
            <li></li>
        </ul>

    </div>
    <div class="kuaijie">
     <div>
       <div class="index_title">
         <div class="title_l">快捷导航</div>
         <div id="biao1" >&nbsp;您的推广链接：<input type="text"  style="width:50%" id="tixing" value="http://www.zhixiao360.com/index.php?mod=member&act=vocational&type=register&uid=<?=$this->member['username']?>"> 
         </div>
        
       </div>
       <div class="daohang">
        <a href="<?=Purl("?mod=member&act=vocational&type=register"); ?>"><img src="<?=$this->tempdir?>images/an_1_01.jpg" /></a>
        <a href="<?=Purl("?mod=member&act=capital&type=list"); ?>"><img src="<?=$this->tempdir?>images/an_1_02.jpg" /></a>
        <a href="<?=Purl("?mod=member&act=capital&type=transfer"); ?>"><img src="<?=$this->tempdir?>images/an_1_03.jpg" /></a>
        <a href="<?=Purl("?mod=member&act=treeform&type=referee"); ?>"><img src="<?=$this->tempdir?>images/an_1_05.jpg" /></a>
        <a href="<?=Purl("?mod=member&act=trading&type=list"); ?>"><img src="<?=$this->tempdir?>images/an_1_06.jpg" /></a>
        <a href="<?=Purl("?mod=member&act=trading&type=list&method=record"); ?>"><img src="<?=$this->tempdir?>images/an_1_07.jpg" /></a>
        <a href="<?=Purl("?mod=member&act=vocational&type=register"); ?>"><img src="<?=$this->tempdir?>images/an_1_08.jpg" /></a>
        <div style="clear:both;"></div>
       </div>
      </div>
      
      <div>
       <div class="index_title">
         <div class="title_l">玩家公告</div>
         <div class="title_r">&nbsp;</div>
       </div>
       <div class="member_mian" style=" padding-bottom:0;">
        <table class="sheet">
          <? if(is_array($this->record)) { foreach($this->record as $value) { ?>          <tr class="mybg">
            <td width="50"><?=$value['id']?></td>
            <td class="title"><a href="<?=$value['url']?>"><?=$value['title']?></a></td>
            <td><?=$value['typename']?></td>
            <td><?=$value['addtime']?></td>
          </tr>
          <? } } ?>        </table>
        <? if(!is_array($this->record)) { ?>
        <div class="no_info"><span class="no_info_ico"></span>暂无任何记录</div>
        <? } ?>
        <? if($this->newpage) { ?>
        <div class="pages"><?=$this->newpage?></div>
        <? } ?>
       </div>
      </div>
      
      
      <div style="padding-top:10px; width:100%;">
      <div class="index_title">
        <div class="title_l">收入数据</div>
        <div class="title_r">&nbsp;</div>
      </div>
      <div class="member_mian" style=" padding-bottom:0;">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <th class="td_line"></th>
          <th>推荐奖</th>
          <th>分红奖</th>
          <th>领导奖</th>
          <th class="td_line">报单奖</th>
          <th>合计</th>
        </tr>
        <tr>
          <th class="td_line">今日</th>
          <td><?=$this->todaymoney['refereemoney']?></td>
          <td><?=$this->todaymoney['money']?></td>
          <td><?=$this->todaymoney['leadmoney']?></td>
          <td class="td_line"><?=$this->todaymoney['regmoney']?></td>
          <td><span class="text_red14px"><?=$this->todaymoney['inmoney']?></span></td>
        </tr>
        <tr>
          <th class="td_line">昨天</th>
          <td><?=$this->yestodaymoney['refereemoney']?></td>
          <td><?=$this->yestodaymoney['money']?></td>
          <td><?=$this->yestodaymoney['leadmoney']?></td>
          <td class="td_line"><?=$this->yestodaymoney['regmoney']?></td>
          <td><span class="text_red14px"><?=$this->yestodaymoney['inmoney']?></span></td>
        </tr>
        <tr>
          <th class="td_line">全部</th>
          <td><?=$this->allmoney['refereemoney']?></td>
          <td><?=$this->allmoney['money']?></td>
          <td><?=$this->allmoney['leadmoney']?></td>
          <td class="td_line"><?=$this->allmoney['regmoney']?></td>
          <td><span class="text_red14px"><?=$this->allmoney['inmoney']?></span></td>
        </tr>
      </table>
      </div>
      </div>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<? include template('member_footer','default/member'); ?>
