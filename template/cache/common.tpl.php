<? if (!defined('ROOT')) exit('Can\'t Access !'); ?>
<script language="javascript">
var siteurl="<?=config::get('siteurl')?>";//参数 
var tempdir="<?=$this->tempdir?>";
var hempdir="<?=$this->hempdir?>";
var appdir="<?=$this->appdir?>";
var rewrite="<?=config::get('rewrite')?>";
var module="<?=$this->module?>";
var action="<?=$this->action?>";
var islogin="<? if(is_array($this->member)) { ?>1<? } else { ?>0<? } ?>";
var paymenturl = '<?=Purl("?mod=member&act=capital&type=payment"); ?>';
</script>
<link rel="stylesheet" href="<?=$this->hempdir?>css/common.css" type="text/css" /><link rel="stylesheet" href="<?=$this->appdir?>control/control.css" type="text/css" />
<script type="text/javascript" src="<?=$this->appdir?>function.js" ></script><script type="text/javascript" src="<?=$this->appdir?>jquery.js" ></script><script type="text/javascript" src="<?=$this->appdir?>utils.js" ></script><script type="text/javascript" src="<?=$this->appdir?>jquery.form.js" ></script><script type="text/javascript" src="<?=$this->appdir?>listtable.js" ></script><script type="text/javascript" src="<?=$this->appdir?>control/control.js" ></script><script type="text/javascript" src="<?=$this->appdir?>highcharts.js" ></script><script type="text/javascript" src="<?=$this->hempdir?>js/common.js" ></script><script type="text/javascript" src="<?=$this->appdir?>date/WdatePicker.js" ></script>
