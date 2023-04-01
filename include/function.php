<?php
function decode($id){
  return urldecode(base64_decode($id));
}
function encode($id){
  return base64_encode(urlencode($id));
}
function Purl($urlkey){//常用链接
   if($urlkey=='go_back') return '-1';
   if(strpos($urlkey,'=')) return rewrite::request("{$urlkey}");
   $urlkey = explode('_',$urlkey);
   $url = $urlkey[1]=='' ? rewrite::request("?mod={$urlkey[0]}") : rewrite::request("?mod={$urlkey[0]}&act=$urlkey[1]");
   return $url;
}
function buytype($buytype){
   $a[1] = '动态投资';
   $a[2] = '投资金额';
   $a[3] = '动态+静态';
   return $a[$buytype];
}

function bfb($val,$money){
   $val = str_replace("%","",$val)+$money;
   if($val>6) $val=6;
   return $val."%";
}

function getval($val,$floor='0',$money='0'){
   if(strstr($val,"|")){
	 $val = explode('|',$val);
	 $val = $val[$floor];
   }
   if(strstr($val,"%")){
	 $val = str_replace("%","",$val)/100*$money;
   }
   return $val;
}
function language($message){//网站语言
   return $message;	
}
function errorpage(){//错误转向
   header('HTTP/1.1 404 Not Found');
   include template('errorpage','admin');
}
function makeorderid(){
   return date('Ymdhis').rand(1000,9999);
}
function formattime($time='',$format="Y-m-d H:i"){
   return $time ? date($format,$time) : '-';	
}
function untime($time){	
   return strtotime($time);
}
function formatnum($number,$length=4,$zero=''){
   $number = $number=='' ? 0 : $number;
   $array = explode(".",$number);
   $array[1] = substr($array[1],0,$length);
   if($array[1]==0){
	 $number = $zero=='' ? $array[0].".0000" : $array[0];
   }else{
     $number = $array[0].".".($array[1]>999 ? $array[1] : $array[1]."000"); 
   }
   return $number;
}
function get_img($images,$string=""){
   if($string=='') $string = 'thumb';
   return $images."_".$string.".".get_img_type($images);	
}
function get_img_type($filename){
   $type = preg_match("/\.(jpg|jpeg|gif|png)$/i",$filename, $matches) ? strtolower($matches[1]) : "string";
   return $type;
}
function resort($val,$key=false){
   $sort = explode('_',$val);
   $value = $sort[count($sort)-1];
   if($key===true) return $value;
   if($value=='desc') return 'asc';
   return 'desc';
}
function getsort($val){
   $sort = $_GET['sort'] ? $_GET['sort'] : $val;
   $sort =  explode('_',$sort);
   $show = "order by `";
   foreach($sort as $key=>$value){	
     if($key==0){
	   $show .= $value;
     }elseif(count($sort)==$key+1){
	   $show .= "` ".$value;  
     }else{
       $show .= "_".$value;  
     }	  
   }
   return $show;
}
function getorder($value,$tempdir,$field){
   $return = '<a href="'.can_url('sort',$field.'_'.resort($_GET['sort'])).'">'.$value;
   if(strstr($_GET['sort'],$field)){
	 $return .= '<img align="absmiddle" src="'.$tempdir.'images/sort_'.resort($_GET['sort'],true).'.gif" />';   
   }
   $return .= '</a>';
   return $return;
}
function orderby($value,$field){
  $return = '<a href="'.can_url('sort',$field.'_'.resort($_GET['sort'])).'"';
  if(strstr($_GET['sort'],$field)){
	$return .= ' class ="'.resort($_GET['sort'],true).'"';   
  }
  $return .= '><span>'.$value.'</span></a>';
  return $return;
}
function order_by($value,$field){
   $return = '<a href="'.can_url('sort',$field.'_'.resort($_GET['sort'])).'"';
   if(strstr($_GET['sort'],$field)){
	 $return .= ' class ="'.resort($_GET['sort'],true).'"';   
   }
   $return .= '>'.$value.'</a>';
   return $return;
}
function json($arr){
  $json = new Services_JSON();
  return $json->encode($arr);
}
function geturl(){
  if(isset($_SERVER['HTTP_X_REWRITE_URL'])){ 
     $request = $_SERVER['HTTP_X_REWRITE_URL'];
  }elseif(isset($_SERVER['REQUEST_URI'])){
     $request = $_SERVER['REQUEST_URI'];
  }elseif (isset($_SERVER['ORIG_PATH_INFO'])){ 
     $request = $_SERVER['ORIG_PATH_INFO'];
     if (!empty($_SERVER['QUERY_STRING'])) $request .= '?' . $_SERVER['QUERY_STRING'];
  }else{
     $request = null;
  }
  return $request;
}
function getip() {
  if(getenv("HTTP_CLIENT_IP")&&strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown")){
	 $ip = getenv("HTTP_CLIENT_IP");
  }elseif(getenv("HTTP_X_FORWARDED_FOR")&&strcasecmp(getenv("HTTP_X_FORWARDED_FOR"),"unknown")){
	 $ip = getenv("HTTP_X_FORWARDED_FOR");
  }elseif(getenv("REMOTE_ADDR")&&strcasecmp(getenv("REMOTE_ADDR"),"unknown")){
	 $ip = getenv("REMOTE_ADDR");
  }elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],"unknown")){
	 $ip = $_SERVER['REMOTE_ADDR'];
  }else{
     $ip = "unknown";
  }
  return ($ip);
}
function localPre($arr,$name='name'){
  foreach($arr as $key => $item){
    if($name=='name'){
	  preg_match('/\[.*?\]/',$item[$name],$localPre);
      if(isset($localPre[0])){
        $arrayKey = trim($localPre[0],'[]');
        $array[$arrayKey][] = $item;
      }
	}else{
      if(isset($item[$name])) $array[] = $item[$name];
	}
  }
  return $array;
}
function purviews($arr,$admin=''){
 $menu = $admin ? admin_menu_top() : user_menu();
 foreach($arr as $item){
  if(!$item['member']){
	$item['member']='other';
	$menu[$item['member']] = '其他分组';
  }
  $array[$item['member']]['title'] = $menu[$item['member']];
  $array[$item['member']]['purviews'][] = $item;  
 }
 return $array;
}
function _formatval($string,$value=''){
  $arr = explode(',',$string);
  foreach($arr as $key=>$val){
	$purview .= "[<".$val."><".$val.">]";
  }
  return $value.$purview;
}
function formatform($arr,$def='',$str='',$k=false){
  $arr = $str=='' ? $arr : explode($str,$arr);
  $form = $def=='' ? '' : "[<><".$def.">]";
  foreach($arr as $key=>$val){
	$hao = $k ? $key : $val; 
	$form .= "[<".$hao."><".$val.">]";
  }
  return $form;
}
function formval($arr,$key='purviews',$val='name'){
  foreach($arr as $v){
    $v[$val] = preg_replace('/\[.*?\]/','',$v[$val]);
	$purview .= "[<".$v[$key]."><".$v[$val].">]";
  }
  return $purview;
}
function formatval($arr){
  foreach($arr as $key=>$val){
	$purview .= "[<".$key."><".$val.">]";
  }
  return $purview;
}
function formatinput($arr,$namepre='namepre'){
  foreach($arr as $val){
	$purview .= config::form($namepre.$val['id'],$val['urltype'],'hidden','',"url='{$val['url']}'");
  }
  return $purview;
}
function nav_type($type){
  switch($type){
   case 1:
    return "顶部导航";
    break;
   case 2:
    return "中部导航";
    break;
   case 3:
    return "下部导航";
    break;
   default:
    return "";
  }
}
function order_check_admin($order){
    $arr[0] = '未付款';
    $arr[1] = '<span class="ordtin">已付款</span><p class="ordersend" orderid="'.$order['id'].'"><a>确认发货</a></p>';
    $arr[2] = '已成交';
    $arr[3] = '<span class="ordtin">退款中</span><p class="orderback" orderid="'.$order['id'].'"><a>确认退款</a></p><p class="noorderback" orderid="'.$order['id'].'"><a>拒绝退款</a></p>';
    $arr[4] = '已退款';
    $arr[5] = '已发货';
    return $arr[$order['checked']]; 
}
function order_check_user($order){
    $arr[0] = '未付款<br><input class="order-but" type="button" onclick="javascript:payorder('.$order[id].');" value="支付" /><br><a href="javascript:cancelorder('.$order[id].');">取消订单</a>';
	if($_GET['id']) $arr[0] = '未付款<br><input class="order-but order-but2" type="button" onclick="javascript:payorder('.$order[id].');" value="支付" />';
    $arr[1] = '待发货<br><input class="order-but order-but2" type="button" onclick="backmoney('.$order[id].');" value="退款" />';
    $arr[2] = '已成交';
    $arr[3] = '退款中';
    $arr[4] = '已退款';
    $arr[5] = '已发货<br><input class="order-but order-but2" type="button" onclick="yeshave('.$order[id].');" value="确认收货" />';
    return $arr[$order['checked']];
}
function user_menu($key=''){
   $arr = array('main'=>'会员中心','treeform'=>'账号管理','vocational'=>'业务管理','capital'=>'财务管理','trading'=>'交易系统','user'=>'个人设置');
   return $key=='' ? $arr : $arr[$key];
}
function member_menu($key){
   $menu['main'] = array('index'=>'玩家首页');
   $menu['treeform'] = array('status'=>'激活账号','referee'=>'推荐结构','record'=>'推荐列表','fuser'=>'新增账号');
   $menu['vocational'] = array('register'=>'注册会员','customs'=>'报单中心','list'=>'报单会员');
   $menu['capital'] = array('list'=>'金币明细','transfer'=>'金币转账','myatm'=>'现金提现');
   $menu['trading'] = array('list'=>'抢购金币','addorder'=>'金币卖出',);
   $menu['user'] = array('profile'=>'个人信息','password'=>'修改密码','safepwd'=>'修改密保','authemail'=>'邮箱验证','authphone'=>'手机绑定');
   return $menu[$key] ? $menu[$key] : array();
}

function admin_menu_top(){
   return array ('main'=>'系统管理','manager'=>'后台用户','user'=>'系统会员','site'=>'网站基础','census'=>'数据统计',);
}
function admin_menu_left($top=''){
   $menu['main'] = array('system'=>'系统信息','config'=>'系统设置','guestbook'=>'内部信件','database'=>'数据维护',);
   $menu['manager'] = array('control'=>'用户管理','group'=>'用户角色','password'=>'修改密码',);
   $menu['user'] = array('control'=>'会员管理','group'=>'会员级别','customs'=>'报单中心',);
   $menu['site'] = array('news'=>'新闻管理','about'=>'单页管理');
   $menu['census'] = array('money'=>'金币明细','atmlog'=>'提现记录');
   $menu['shop'] = array('goods'=>'产品管理','order'=>'订单管理'); 
   return $menu[$top] ? $menu[$top] : array();
}
function admin_menu_small($top='',$left=''){
   $menu['main_system'] = array('index'=>'系统信息','upgrade'=>'系统升级','cache'=>'更新缓存','givemoney'=>'分红结算',);
   $menu['main_config'] = array('basic'=>'基本设置','email'=>'邮件设置','pay'=>'支付配置','sms'=>'短信设置',);
   $menu['main_guestbook'] = array('list'=>'信件列表','add'=>'发送信件',); 
   $menu['main_database'] = array('back'=>'备份数据','unback'=>'恢复数据','emptydb'=>'清空数据',);
   $menu['manager_control'] = array('list'=>'用户列表','add'=>'添加用户','edit'=>'编辑用户-no',);
   $menu['manager_group'] = array('list'=>'角色列表','add'=>'添加角色','edit'=>'编辑角色-no',);
   $menu['manager_password'] = array('index'=>'修改密码');
   $menu['user_control'] = array('list'=>'会员列表','add'=>'添加会员','password'=>'重置密码','edit'=>'编辑会员-no',);
   $menu['user_group'] = array('list'=>'级别列表','edit'=>'编辑级别-no',);
   $menu['user_customs'] = array('list'=>'报单中心',);
   $menu['site_news'] = array('list'=>'新闻列表','add'=>'添加新闻','type'=>'新闻分类','edit'=>'编辑新闻-no',);
   $menu['site_about'] = array('list'=>'单页列表','add'=>'添加单页','type'=>'单页分类','edit'=>'编辑单页-no',);   
   $menu['census_money'] = array('list'=>'资金流水','chart'=>'综合统计','outchart'=>'拨出走势','inchart'=>'业绩走势');
   $menu['census_atmlog'] = array('list'=>'提现记录','chart'=>'提现走势');
   $menu['census_payorder'] = array('list'=>'抢购记录','chart'=>'充值走势');  
   $menu['shop_goods'] = array('list'=>'产品列表','add'=>'添加产品','edit'=>'编辑产品-no',);
   $menu['shop_order'] = array('list'=>'订单列表',);
   return $menu[$top.'_'.$left] ? $menu[$top.'_'.$left] : array();
}

function submit(){
   $submit = isset($_POST['stopoutenable']);
   unset($_POST['stopoutenable'],$_POST['submit'],$_SESSION['stopoutenable'],$_POST['button']); 
   return $submit; 
}
function location($url,$change=0){
   $url = $change ? $url : Purl($url); 
   @header("Location:".$url);
   exit;
}
function error($message=''){
   return config::get("error") ? exit(json(array($message))) : errorpage();	
}
function isEmail($email){ //邮箱验证
   return eregi("^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$",$email);
}
function isChinese($str){//是否全部是中文
   return preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$str);
}
function isinCn($str){//是否包含中文
   return preg_match("/[\x80-\xff]./",$str);      
}
function sintrim($val){//替换所有空字符
   return str_replace(" ","",trim($val));
}
function paycheck($i,$o,$id=''){
   $check[0] = '暂未汇款<span class="pay">(<a href="javascript:cancelpayorder(\''.$o.'\');">取消</a>)</span>';
   $check[1] = '<font color="#FF3300">已经汇款</font>';
   $check[2] = '<font color="#999999">交易成功</font>';
   if($id) $check[0] = '暂未汇款<span class="pay">(<a href="'.Purl("?mod=admin&act=census&get=payorder&id=".$id).'">取消</a>)</span>';
   return $check[$i];
}

function bankimages($name){
   switch ($name){
     case '工商银行':
       $bank = "1025.gif";
       break;
     case '邮政银行':
       $bank = "3230.gif";
       break;
     case '建设银行':
       $bank = "105.gif";
       break;
     case '农业银行':
       $bank = "103.gif";
       break;
     case '招商银行':
       $bank = "3080.gif";
       break;
     case '中国银行':
       $bank = "104.gif";
       break;
     case '支付宝':
       $bank = "alipay.gif";
       break;
     case '财付通':
       $bank = "tenpay.jpg";
       break;
     default:
       $bank = "3230.gif";
   }
   return $bank;
}

function tradcheck($i,$id){
   $check[1] = '寄售中';
   $check[2] = '<font color="#FF3300">已付款</font>';
   $check[3] = '<font color="#FF3300">交易成功</font>';
   return $check[$i];
}

function btradcheck($i,$id){
   $check[1] = '<font color="#FF3300" class="trading" aid="'.$id.'" check="'.$i.'">寄售中</font>';
   $check[2] = '<font color="#FF3300" class="trading" aid="'.$id.'">已付款,点击确认</font>';
   $check[3] = '<font color="#FF3300">交易成功</font>';
   return $check[$i];
}

function atmcheck($i){
   $check[0] = '待支付';
   $check[1] = '<font color="#FF3300">已支付</font>';
   return $check[$i];
}
function usercheck($check){
   return $check=='1' ? 'yes.gif' : 'no.gif';
}
function Sitize($value){
   $value = @iconv('utf-8', 'gb2312', $value);
   return $value;
}
function msubstr($string,$start=0,$length){
   $strlen=strlen($string);
	if($strlen<=$length) return $string;
	$string = str_replace(array('&nbsp;','&amp;','&quot;','&lt;','&gt;','&#039;'), array(' ','&','"','<','>',"'"), $string);
	$strcut = '';
	$n = $tn = $noc = 0;
	while($n<$strlen){
     $t = ord($string[$n]);
     if($t == 9||$t == 10||(32 <= $t && $t <= 126)){
	   $tn = 1; $n++; $noc++;
	 }elseif(194 <= $t && $t <= 223) {
	   $tn = 2; $n += 2; $noc += 2;
	 }elseif(224 <= $t && $t < 239) {
	   $tn = 3; $n += 3; $noc += 2;
	 }elseif(240 <= $t && $t <= 247) {
       $tn = 4; $n += 4; $noc += 2;
	 }elseif(248 <= $t && $t <= 251) {
	   $tn = 5; $n += 5; $noc += 2;
     }elseif($t == 252 || $t == 253) {
       $tn = 6; $n += 6; $noc += 2;
     }else{
	   $n++;
     }
     if($noc >= $length) break;
   }
   if($noc > $length) $n -= $tn;
   $strcut = substr($string, 0, $n);
   $strcut = str_replace(array('&','"','<','>',"'"), array('&amp;','&quot;','&lt;','&gt;','&#039;'), $strcut);
   return $strcut;
}
function dirpath($path){
	if($path=='') return false;
	$extarr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
    if ($handle = opendir($path)) {
	    $i = 0;
	    while(false !== ($filename = readdir($handle))) {
	 	    if ($filename{0} == '.') continue;
		    $file = $path .'/'.$filename;
		    if (is_dir($file)){			
			    $list[$i]['is_dir'] = true; //是否文件夹
				$has_file = dirpath($file);
			    $list[$i]['has_file'] = is_array($has_file); //文件夹是否包含文件
			    $list[$i]['filesize'] = 0; //文件大小
			    $list[$i]['is_photo'] = false; //是否图片
			    $list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				
			}else{
			    $list[$i]['is_dir'] = false;
			    $list[$i]['has_file'] = false;
			    $list[$i]['filesize'] = filesize($file);
			    $list[$i]['dir_path'] = '';
			    $file_ext = strtolower(array_pop(explode('.', trim($file))));
			    $list[$i]['is_photo'] = in_array($file_ext, $extarr);
			    $list[$i]['filetype'] = $file_ext;
		    }
		    $list[$i]['filename'] = $filename; //文件名，包含扩展名
		    $list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
		    $i++;		
	    }
	    closedir($handle);
    }
	return $list;
}
function cmp_func($a, $b) {
    $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);
	if ($a['is_dir'] && !$b['is_dir']) {
		return -1;
	} else if (!$a['is_dir'] && $b['is_dir']) {
		return 1;
	} else {
		if ($order == 'size') {
			if ($a['filesize'] > $b['filesize']) {
				return 1;
			} else if ($a['filesize'] < $b['filesize']) {
				return -1;
			} else {
				return 0;
			}
		} else if ($order == 'type') {
			return strcmp($a['filetype'], $b['filetype']);
		} else {
			return strcmp($a['filename'], $b['filename']);
		}
	}
}

function sendmail($sendto,$subject,$body,$username=''){
  require(PATH.'library/sendmail/class.phpmailer.php');
  $mail = new PHPMailer();

  if(config::get('mailsend')==1){
    $mail->IsSMTP();
    $mail->Host = config::get('mailserver');
    $mail->SMTPAuth = config::get('mailauth');
    $mail->Username = config::get('mailfrom');
    $mail->Password = config::get('mailpassword');
    $mail->From = config::get('mailfrom');
    $mail->FromName =  config::get('sitename');
    $mail->SMTPAuth=true;
  }else{

    $mail->AddReplyTo(config::get('mailfrom'),config::get('sitename'));
    $mail->SetFrom(config::get('mailfrom'),config::get('sitename'));  
  }  
  $mail->CharSet = "utf-8";
  $mail->Encoding = "base64"; 
  $mail->AddAddress($sendto,$username);
  $mail->IsHTML(true);
  $mail->Subject = $subject;
  $htmlfile = PATH."./template/admin/email.htm";
  $fp = @fopen($htmlfile, 'r');	
  $html = @fread($fp, filesize($htmlfile));
  fclose($fp);
  $html = str_replace('{subject}',$subject,$html);
  $html = str_replace('{sitedomain}',config::get('sitedomain'),$html);
  $html = str_replace('{sitename}',config::get('sitename'),$html);
  $html = str_replace('{body}',$body,$html);
  $mail->Body = $html;
  $mail->AltBody ="text/html";



  return $mail->Send();
}
function getpic($val,$num='1'){
  if($num=='') $num='1';
  if(!$val||!$num) return "/images/nopic.jpg";
  $num = $num-1;
  preg_match_all("/src=\"(.*?)\"/",$val,$pic);//
  $pics = $pic[1][0];
  return $pics=='' ? "/images/nopic.jpg" : $pics;
}
function noHtml($val){
  $val=strip_tags($val);
  $val=str_replace("&nbsp;","",$val);
  $val=str_replace("　","",$val);
  return $val;
}
function notag($val){
  $val = noHtml($val);
  $val = htmlspecialchars(str_replace('/','',$val));
  return $val;
}
function setgl($value){
  return '<font color="#FF3300">'.$value.'</font>';	
}
function canurl($act,$val,$empty=''){ 
  $url = config::get("rewrite") ?  preg_replace('#/'.$act.'-(\d+)#','',geturl()) : preg_replace('#&'.$act.'=(\d+)#','',geturl());
  $url = config::get("rewrite") ?  preg_replace('#/page-(\d+)#','',$url) : preg_replace('#&page=(\d+)#','',$url);
  if($url=='/'||$url=='/index.php') $url .= config::get("rewrite") ? 'index' : '?mod=index';
  if(!$empty) $url .= rewrite::request("&{$act}={$val}");
  return $url;
}
function can_url($act,$val){
  $pattern = config::get("rewrite")&&$_GET['mod']!='admin' ?  '#/'.$act.'-(\S+)(desc|asc)#' : '#&'.$act.'=(\S+)(desc|asc)#';
  $url = preg_replace($pattern,'',geturl());
  $url = config::get("rewrite")&&$url=='/' ? "/index" : $url;
  $url .= rewrite::request("&{$act}={$val}");
  return $url;
}
function preurl($act,$val){ 
  $url = config::get("rewrite") ?  preg_replace('#/'.$act.'-([0-9a-zA-Z]+)#','',geturl()) : preg_replace('#&'.$act.'=([0-9a-zA-Z]+)#','',geturl());
  $url .= rewrite::request("&{$act}={$val}");
  return $url;
}
function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){$kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
function sendsms($message,$mobiles){
  $message = urlencode("【".config::get("sitename")."】".$message);
  $mobiles = urlencode($mobiles);
  $uname = config::get("smsuname");
  $upwd = config::get("smspwd");
  $smsapi = config::get("smsapi");

  $url = $smsapi."sms?u=".$uname."&p=".md5($upwd)."&m=".$mobiles."&c=".$message;

 // $url = "http://tg.xmmb123.com/index.php?mod=index&act=sendsms&phones={$mobiles}&content={$message}&password=lijixingdong2";
  $fp = fopen($url,"r");
  $ret = fgetss($fp,255);
  fclose($fp);
  return $ret;
}
function isMobile($str){ 
  return preg_match("/^(13|15|18)\d{9}$/",$str);
}
function isNumber($str){
  return eregi("^[0-9]+$",$str);
}
function isSpecial($str){
  return eregi("^[a-z0-9|\_]+$",$str);
}
function isFirstNum($str){
  return isNumber(substr($str,0,1));	
}
function ob_gzip($content){    
  if(!headers_sent()&&extension_loaded("zlib")&&strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")){
     $content = gzencode($content,9);
     header("Content-Encoding: gzip"); 
     header("Vary: Accept-Encoding");
     header("Content-Length: ".strlen($content));
  }
  return $content;
}
function cat_options($spec_cat_id,$arr,$self){
  $level = $last_cat_id = 0;
  $options = $cat_id_array = $level_array = array();
  while(!empty($arr)){
    foreach($arr as $key => $value){		
      $cat_id = $value['cat_id'];
      if($level == 0 && $last_cat_id == 0){
        if($value['parent_id'] > 0) break;
        $options[$cat_id] = $value;
        $options[$cat_id]['level'] = $level;
        $options[$cat_id]['id']    = $cat_id;
        $options[$cat_id]['name']  = $value['cat_name'];
        unset($arr[$key]);
        if($value['has_children'] == 0) continue;
        $last_cat_id  = $cat_id;
        $cat_id_array = array($cat_id);
        $level_array[$last_cat_id] = ++$level;
        continue;
      }
      if($value['parent_id'] == $last_cat_id){
        $options[$cat_id] = $value;
        $options[$cat_id]['level'] = $level;
        $options[$cat_id]['id'] = $cat_id;
        $options[$cat_id]['name'] = $value['cat_name'];
        unset($arr[$key]);
        if($value['has_children'] > 0){
          if(end($cat_id_array) != $last_cat_id) $cat_id_array[] = $last_cat_id;
          $last_cat_id = $cat_id;
          $cat_id_array[] = $cat_id;
          $level_array[$last_cat_id] = ++$level;
        }
      }elseif($value['parent_id'] > $last_cat_id) break;
          }
          $count = count($cat_id_array);
           if($count > 1){
             $last_cat_id = array_pop($cat_id_array);
           }elseif($count == 1){
             if($last_cat_id != end($cat_id_array)){
               $last_cat_id = end($cat_id_array);
             }else{
               $level = 0;
               $last_cat_id = 0;
               $cat_id_array = array();
               continue;
             }
           }
           if($last_cat_id && isset($level_array[$last_cat_id])){
             $level = $level_array[$last_cat_id];
           }else{
             $level = 0;
           }
       }
       if(!$spec_cat_id){
        return $options;
       }else{
        if (empty($options[$spec_cat_id])){
            return array();
        }
        $spec_cat_id_level = $options[$spec_cat_id]['level'];
        foreach ($options as $key => $value){
            if ($key != $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }
		
        foreach ($options as $key => $value){
            if ($self&&$value['cat_id'] == $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options as $key => $value)
        {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level']))
            {
                break;
            }
            else
            {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;  
	   }
       return $options;
}
function mobileReg($mobile){
  $mobile = iconv('utf-8','gb2312',$mobile);
  $sendapi = "http://42.96.190.11:8080/tjhyk/usereg.jsp?userid=".$mobile."&id=855";
  $res = file_get_contents($sendapi);
  return iconv('gb2312','utf-8',$res);
}
function isAgent()
{ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 
function _time($field,$string='',$auto=''){
	if($auto==''){
      $_GET["time"] = $_GET["time"] ? $_GET["time"] : formattime(time()-3600*24*10,'Y-m-d');
      $_GET["timet"] = $_GET["timet"] ? $_GET["timet"] : formattime(time(),'Y-m-d');
	}
	if($_GET["time"]&&$_GET["timet"]){
      $arr['time'] = untime($_GET["time"]);  
      $arr['timet'] = untime($_GET["timet"]." 23:59"); 
	  $arr['where'] = " {$string} {$field} between {$arr['time']} and {$arr['timet']}";
      $arr['str'] = $_GET["time"].",".$_GET["timet"];
	}
	return $arr;
}
function datediff($part,$begin,$end){
    $timet = explode("-",formattime($begin,"Y-m-d-H"));
    $time = explode("-",formattime($end,"Y-m-d-H"));
    switch($part){
      case "y": $retval = $timet[0]-$time[0]; break;
      case "m": $retval = (($timet[0]-$time[0])*12)+($timet[1]-$time[1]); break;
      case "d": $retval = (($timet[0]-$time[0])*12*30)+(($timet[1]-$time[1])*30)+($timet[2]-$time[2])+1; break;
      case "h": $retval = (($timet[0]-$time[0])*12*30*24)+(($timet[1]-$time[1])*30*24)+(($timet[2]-$time[2])*24)+($timet[3]-$time[3]); break;
    }
    return $retval;
}

function dateadd($part,$number,$date){
    $array = getdate($date);
    $h = $array["hours"];
    $m = $array["mon"];
    $d = $array["mday"];
    $y = $array["year"];
    switch($part){
      case "y": $y += $number; break;
      case "m": $m += $number; break;
      case "d": $d += $number; break;
      case "h": $h += $number; break;
    }
    return date("Y-m-d H:i",mktime($h,0,0,$m,$d,$y));
}
function adminpre(){
    return 	"?mod={$_GET['mod']}&act={$_GET['act']}&get={$_GET['get']}&re={$_GET['re']}";
}
function memberpre($show=''){
	$value = $show=='' ? "&type={$_GET['type']}" : "";
    return 	"?mod={$_GET['mod']}&act={$_GET['act']}{$value}";
}
//备份数据修改
function Ebak_ChangeSize($size){
	if($size<1024)
	{
		$str=$size." B";
	}
	elseif($size<1024*1024)
	{
		$str=round($size/1024,2)." KB";
	}
	elseif($size<1024*1024*1024)
	{
		$str=round($size/(1024*1024),2)." MB";
	}
	else
	{
		$str=round($size/(1024*1024*1024),2)." GB";
	}
	return $str;
}
function WriteFile($filepath,$string){
	$fp=@fopen($filepath,"w");
	@fputs($fp,$string);
	@fclose($fp);
}
function updateFile($filepath,$string){
	$fp=@fopen($filepath,"a+");
	@fwrite($fp,$string);
	@fclose($fp);
}
//替换文件数
function Ebak_RepFilenum($p,$table,$file){
	if(empty($p)) $p=0;
	$text=ReadFiletext($file);
	$rep1="\$tb['".$table."']=0;";
	$rep2="\$tb['".$table."']=".$p.";";
	$text=str_replace($rep1,$rep2,$text);
	WriteFile($file,$text);
}
//取得文件内容
function ReadFiletext($filepath){
	$htmlfp=@fopen($filepath,"r");
	while($data=@fread($htmlfp,1000))
	{
		$string.=$data;
	}
	@fclose($htmlfp);
	return $string;
}
//字符过虑
function escape_str($str){
	$str=mysql_escape_string($str);
	$str=str_replace('\\\'','\'\'',$str);
	$str=str_replace("\\\\","\\\\\\\\",$str);
	$str=str_replace('$','\$',$str);
	return $str;
}
//输出恢复进度条
function Ebak_EchoReDataSt($tbname,$tbnum,$tb,$pnum,$p){
	$table=($tb+1).'/'.$tbnum;
	$record=$p.'/'.$pnum;
	$value = "&nbsp;&nbsp;&nbsp;&nbsp;Table Name&nbsp;:&nbsp;<b>{$tbname}</b><br>";
	$value .= "&nbsp;&nbsp;&nbsp;&nbsp;Table&nbsp;:&nbsp;<b>{$table}</b><br>";
	if($p!='no') $value .= "&nbsp;&nbsp;&nbsp;&nbsp;File&nbsp;:&nbsp;<b>{$record}</b>";
	return $value;
}
function Ebak_EchoBakSt($tbname,$tbnum,$tb,$rnum,$r){
	$table=($tb).'/'.$tbnum;
	$record=$r;
	if($rnum!=-1)
	{
		$record=$r.'/'.$rnum;
	}
	$value = "&nbsp;&nbsp;&nbsp;&nbsp;Table Name&nbsp;:&nbsp;<b>{$tbname}</b><br>";
	$value .= "&nbsp;&nbsp;&nbsp;&nbsp;Table&nbsp;:&nbsp;<b>{$table}</b><br>";
	if($r!='0'&&$rnum!='0') $value .= "&nbsp;&nbsp;&nbsp;&nbsp;Record&nbsp;:&nbsp;<b>{$record}</b>";
	return $value;
}
//打包目录
function ZipFile($path,$zipname){
    include(PATH.'library/pclzip.lib.php');
	$zip = new PclZip($zipname);
    $zip->create($path,PCLZIP_OPT_REMOVE_ALL_PATH);
}
function unzip($zipname,$path){
    include(PATH.'library/pclzip.lib.php');
	$unzip = new PclZip($zipname);
	$unzip->extract(PCLZIP_OPT_PATH,$path);
	return $unzip->errorCode();
}
?>
