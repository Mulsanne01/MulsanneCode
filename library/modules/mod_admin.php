<?php
if (!defined('ROOT')) exit('Can\'t Access !'); 
class mod_admin extends module_class{
 	function main(){
	  $nowtime = untime(formattime(time(),"Y-m-d 00:00"));
	  $query = $this->mysql->query("select * from {$this->pre}user where regtime<'{$nowtime}' and moneytime<'{$nowtime}' order by uid asc");
	  while($rs=$this->mysql->assoc($query)){
		$this->givemoney($rs['uid']);
	  }
	  if($_GET['get']=='system'){
	     if($_GET['re']=='givemoney'){
	      if(submit()){
		    if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			  $arr['error'] = "管理密码不正确";
			  echo json($arr);
		      exit;
		    }	  
			$arr['error'] = "0";
			$arr['_message'] = "初始化成功，正在进入数据结算．．．．";
			$arr['url'] = Purl("?mod=admin&act=main&get=system&re=givemoney&sin=start");
		    echo json($arr);
		    exit;
		   }else{
             if($_GET['sin']=='start'){
	           $nowtime = untime(formattime(time(),"Y-m-d 00:00"));
	           $user =$this->mysql->getarr("select * from {$this->pre}user where regtime<'{$nowtime}' and moneytime<'{$nowtime}' order by uid asc limit 5");
               if(!is_array($user)){				  
			     $arr['error'] = "0";
			     $arr['message'] = "恭喜你，数据结算完毕";
		         $arr['gourl'] = Purl("?mod=admin&act=census");
			     echo json($arr);
		         exit;
			   }else{
				 foreach($user as $val){
				   $this->givemoney($val['uid']);
				 }
			     $arr['error'] = "0";
			     $arr['_message'] = "一组数据结算完毕，正在进入下一组...";
			     $arr['url'] = Purl("?mod=admin&act=main&get=system&re=givemoney&sin=start");               
				 echo json($arr);
		         exit;
			   }
			 }
		   }
	     } 
	     if($_GET['re']=='index'){
		   $starttime = untime(formattime(time(),'Y-m-d')." 00:00");
		   $endtime = untime(formattime(time(),'Y-m-d')." 23:59:59");
		   $_starttime = untime(formattime(time()-(3600*24),'Y-m-d')." 00:00");
		   $_endtime = untime(formattime(time()-(3600*24),'Y-m-d')." 23:59:59");
		   $this->regnumber = $this->mysql->num("{$this->pre}user");
		   $this->todayregnumber = $this->mysql->num("{$this->pre}user","regtime between '{$starttime}' and '{$endtime}'");
		   $this->yestodayregnumber = $this->mysql->num("{$this->pre}user","regtime between '{$_starttime}' and '{$_endtime}'");		   
           $todaytime = untime(formattime(time(),'Y-m-d'));
           $yestodaytime = untime(formattime(time()-24*3600,'Y-m-d')); 
		   $this->todaymoney = $this->getrecord("where addtime='{$todaytime}'");
	       $this->yestodaymoney = $this->getrecord("where addtime='{$yestodaytime}'");
	       $this->allmoney = $this->getrecord();
	     }
	     if($_GET['re']=='money'){
		   $this->add = $this->mysql->select_one("select * from {$this->pre}money where id='1'");
		   if(submit()){
		    if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			  $arr['error'] = "管理密码不正确";
			  echo json($arr);
		      exit;
		    } 
            $this->mysql->query("update {$this->pre}money set allmoney='{$_POST['allmoney']}' where id='1'"); 
		    $json['error'] = '0';
            $json['message'] = '金币发行额更改成功';
		    echo json($json);
		    exit;
		   }
	     }		 
	     if($_GET['re']=='upgrade'){
		   $this->message('go_back','系统目前已经是最新版本');
	     }
	     if($_GET['re']=='cache'){
           $handler = opendir(PATH."./template/cache/");
           while(($filename = readdir($handler)) !== false){
             if($filename != "." && $filename != ".."){
			   @chmod(PATH."./template/cache/".$filename, 0777);
			   @unlink(PATH."./template/cache/".$filename);
		     }
		   }	
		   $this->message('go_back','更新缓存成功');
	     }
	  } 
	  if($_GET['get']=='config'){
         if(submit()){
		   if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			 $arr['error'] = "管理密码不正确";
			 echo json($arr);
		     exit;
		   }
           config::update($_POST);
		   $json['error'] = '0';
           $json['message'] = '系统基础部署成功';
		   echo json($json);
		   exit;
	     }else{
	       $configcontents = config::configs();
	       @preg_match_all("%//(\S+?)-(\S+?)\{%s", $configcontents, $config, PREG_PATTERN_ORDER);
	       foreach($config[1] as $key=>$val){
		      @preg_match_all("%//$val-(\S+?)\{(.+?)//\}%s", $configcontents, $result, PREG_PATTERN_ORDER); 
		      $configs[$val] = $result;  
	       }
	       foreach($configs as $key=>$val){
			  $pregexp='\"(.*?)\"=>\"(.*?)\", //{title=(.*?)}{prompt=(.*?)}{type=(.*?)}{values=(.*?)}{option=(.*?)}{unit=(.*?)}';
		      preg_match_all("%$pregexp%s",$val[2][0], $pregval, PREG_SET_ORDER);
			  foreach($pregval as $pk=>$pv) $pregval[$pk][2] = stripslashes($pregval[$pk][2]);
              $this->configs[$key]['key'] = $key;
		      $this->configs[$key]['name'] = $val[1][0];
		      $this->configs[$key]['arr'] = $pregval;
	       }
	     }
	  }	  
	  if($_GET['get']=='database'){
		if($_GET['re']=='back'){
          $this->database = $this->mysql->getarr("SHOW TABLE STATUS");  
	      if(submit()){
		    if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			  $arr['error'] = "管理密码不正确";
			  echo json($arr);
		      exit;
		    }
			if($_POST['sinbegin']=='doopi'){
	          foreach($this->database as $value){
		        $this->mysql->query("OPTIMIZE TABLE `{$value['Name']}`;");
              }
			  $arr['error'] = "0";
			  $arr['message'] = "恭喜你，优化数据表成功";
			  echo json($arr);
		      exit;
			}elseif($_POST['sinbegin']=='dorep'){
	          foreach($this->database as $value){
		        $this->mysql->query("REPAIR TABLE `{$value['Name']}`;");
              }
			  $arr['error'] = "0";
			  $arr['message'] = "恭喜你，修复数据表成功";
			  echo json($arr);
		      exit;
		    }elseif($_POST['sinbegin']=='doback'){
			  $filesize = (int)$_POST['filesize'];
			  makeDirectory(PATH."./database/{$_POST['mypath']}/");
	          foreach($this->database as $value){
		        $b_table.=$value['Name'].",";
	         	$d_table.="\$tb['".$value['Name']."']=0;\r\n";
              }
			  $b_table=substr($b_table,0,strlen($b_table)-1);
	          $bakstru = 1;
           	  $bakstrufour = 0;
	          $beover = 0;
	          $waitbaktime = 0;
	          $bakdatatype = 0;
              $insertf = 'replace';
			  $dbname = config::get("dbname");
	          $string="<?php\r\n";
			  $string.="\$b_table=\"".$b_table."\";\r\n";
              $string.=$d_table."\$b_baktype=".$_POST['baktype'].";\r\n";
              $string.="\$b_filesize=".$_POST['filesize'].";\r\n";
              $string.="\$b_bakline=500;\r\n";
              $string.="\$b_autoauf=1;\r\n";
              $string.="\$b_stru=".$bakstru.";\r\n";
              $string.="\$b_strufour=".$bakstrufour.";\r\n";
              $string.="\$b_dbchar=\"".addslashes($_POST['dbchar'])."\";\r\n";
              $string.="\$b_beover=".$beover.";\r\n";
              $string.="\$b_insertf=\"".addslashes($insertf)."\";\r\n";
              $string.="\$b_autofield=\",".addslashes($_POST['autofield']).",\";\r\n";
              $string.="\$b_bakdatatype=".$bakdatatype.";\r\n?>";
	          WriteFile(PATH."./database/{$_POST['mypath']}/config.php",$string);
	          WriteFile(PATH."./database/{$_POST['mypath']}/readme.txt",date("Y-m-d H:i:s"));			  
			  $arr['error'] = "0";
			  $arr['_message'] = "初使化备份成功，正在进入表备份．．．．";
			  $arr['url'] = Purl("?mod=admin&act=main&get=database&sin=backex&t=0&s=0&p=0&mypath={$_POST[mypath]}&waitbaktime={$waitbaktime}");
			  echo json($arr);
		      exit;
		    }else{
			  $arr['error'] = "悟空，你来错地方了";
			  echo json($arr);
		      exit;
			}
	      }else{
            if($_GET['sin']=='backex'){
			  @include(PATH."./database/{$_GET['mypath']}/config.php");
	          $t=$_GET['t'];
	          $s=$_GET['s'];
	          $p=$_GET['p'];
	          $mypath=$_GET['mypath'];
	          $alltotal=$_GET['alltotal'];
	          $thenof=$_GET['thenof'];
	          $fnum=$_GET['fnum'];
	          $stime=$_GET['stime'];
	          $waitbaktime = (int)$_GET['waitbaktime'];
	          if(empty($stime)) $stime=time();
	          $btb=explode(",",$b_table);
	          $count=count($btb);
	          $t=(int)$t;
	          $s=(int)$s;
	          $p=(int)$p;
              if($t>=$count){				  
			    $arr['error'] = "0";
			    $arr['message'] = "恭喜你，数据库备份完毕";
				$arr['gourl'] = Purl("?mod=admin&act=main&get=database&re=unback");
			    echo json($arr);
		        exit;
			  }
	          if($b_dbchar=='auto'){
	 	        if(empty($s)){
			      $status_r = $this->mysql->select_one("SHOW TABLE STATUS LIKE '".$btb[$t]."';");
			      $num = $limittype ? -1 : $status_r['Rows'];
		        }else{
			      $num = (int)$alltotal;
		        }
	          }else{
			    $arr['error'] = "悟空，你来了不改来的地方";
			    echo json($arr);
		        exit;
			  }
	          //备份数据库结构
	          if($b_stru&&empty($s)){
				$dumpsql .= "\$this->mysql->query(\"DROP TABLE IF EXISTS `".$btb[$t]."`;\");\r\n";
	            $create = $this->mysql->select_one("SHOW CREATE TABLE `{$btb[$t]}`;");
				$dumpsql .= "\$this->mysql->query(\"".str_replace("\"","\\\"",$create[1])."\");\r\n";
	          }
	          if(empty($fnum)){
	            $return_fr = $this->mysql->getarr("SHOW FIELDS FROM `".$btb[$t]."`");
		        $fieldnum = count($return_fr);
		        $noautof = $b_autofield;
	          }else{
	 	        $fieldnum = $fnum;
		        $noautof = $thenof;
	          }
              $query = $this->mysql->query("select * from `".$btb[$t]."` limit $s,$num");
	          $b=0;
	          while($r=$this->mysql->arr($query)){
		        $b=1;
		        $s++;
		        $dumpsql.="\$this->mysql->query(\"".$b_insertf." into `".$btb[$t]."`".$inf." values(";
		        $first=1;
		        for($i=0;$i<$fieldnum;$i++){
			      //首字段
			      if(empty($first)){
				    $dumpsql.=',';
			      }else{
				    $first=0;
			      }
			      $myi=$i+1;
			      if(!isset($r[$i])||strstr($noautof,','.$myi.',')){
				    $dumpsql.='NULL';
			      }else{
				    $dumpsql.='\''.escape_str($r[$i]).'\'';
			      }
		        }
		        $dumpsql.=");\");\r\n";
		        if(strlen($dumpsql)>=$b_filesize*1024){
			      $p++;//备份数据内容
			      $sfile=PATH."./database/{$_GET['mypath']}/".$btb[$t]."_".$p.".php";
			  	  $dumpsql = "<?php\r\n".$dumpsql."?>";
			      WriteFile($sfile,$dumpsql);
			      $arr['error'] = "0";
			      $arr['_message'] = "正在备份".$btb[$t]."表数据....<br><br>".Ebak_EchoBakSt($btb[$t],$count,$t,$num,$s);
			      $arr['url'] = Purl("?mod=admin&act=main&get=database&sin=backex&s=$s&p=$p&t=$t&mypath=$mypath&alltotal=$num&thenof=$noautof&fieldnum=$fieldnum&stime=$stime");
			      echo json($arr);
		          exit;
		        }
	          }
	          //备份完毕
	          if(empty($p)||$b==1){
		        $p++;
		        $sfile=PATH."./database/{$_GET['mypath']}/".$btb[$t]."_".$p.".php";
				$dumpsql = "<?php\r\n".$dumpsql."?>";
		        WriteFile($sfile,$dumpsql);
	          }
	          Ebak_RepFilenum($p,$btb[$t],PATH."./database/{$_GET['mypath']}/config.php");
	          $t++;
			  $arr['error'] = "0";
			  $arr['_message'] = $arr['_message'] = "备份".$btb[$t-1]."表完毕，进入下一个....<br><br>".Ebak_EchoBakSt($btb[$t-1],$count,$t,$num,$s);
			  $arr['url'] = Purl("?mod=admin&act=main&get=database&sin=backex&s=0&p=0&t=$t&mypath=$mypath&stime=$stime");
			  echo json($arr);
		      exit;
		    }
		  } 
		}
	    if($_GET['re']=='unback'){
          if(submit()){
			  if($_FILES['updatefile']['size']=='0'){
				$this->message("go_back",'对不起，请上传数据备份文件');
			  }else{
			    if($_FILES['updatefile']['error']=='1'||$_FILES['updatefile']['error']=='2') $this->message("go_back",'对不起，你上传的备份文件过大。');
			    if($_FILES['updatefile']['error']=='3') $this->message("go_back",'备份文件没有被完全上传，请重试。');
			  }
			  $file = unzip($_FILES["updatefile"]["tmp_name"],PATH."./database/data".date('YmdHis'));
			  if($file=='0'){
				if(is_readable(PATH."./database/data".date('YmdHis')."/config.php") == true){
				  $this->message("?mod=admin&act=main&get=database&re=unback",'恭喜你，备份文件上传成功！'); 	
			    }else{
				  $this->message("go_back",'对不起，你上传的备份文件格式有误。'); 	
				}
			  }else{
				$this->message("go_back",'对不起，你上传的备份文件有问题。'); 
			  }
		  }
		  if($_GET['mypath']){
			$mypath = str_replace('/','',$_GET['mypath']);
			$mypath = str_replace('\\','',$mypath);
			$mypath = str_replace('.','',$mypath);
		    if($_GET['down']){
			  $filepath = PATH."./database/$mypath/";
			  $filename = $filepath.time().".zip";
 	          ZipFile($filepath,$filename);
              ob_clean();
              header('Pragma: public');
              header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
              header('Cache-Control:no-store, no-cache, must-revalidate');
              header('Cache-Control:pre-check=0, post-check=0, max-age=0');
              header('Content-Transfer-Encoding:binary');
              header('Content-Encoding:none');
              header('Content-type:multipart/form-data');
              header('Content-Disposition:attachment; filename="'.date("YmdHis").'.zip"'); 
              header('Content-length:'.filesize($filename));
              $fp = fopen($filename, 'r');
              while(connection_status()==0&&$buf = @fread($fp, 8192)) echo $buf;
              fclose($fp);
              @unlink($filename);
              @flush();
              @ob_flush();
              exit();
			}
		  }
		  $this->managershell = config::get("cookie") ? $_COOKIE['managershell'] : $_SESSION['managershell'];
          $hand = @opendir(PATH."./database/");
          while($file=@readdir($hand)){
	        if($file!="."&&$file!=".."&&is_dir(PATH."./database/".$file)){
			  $r['filename'] = $file;
			  $r['filetime'] = ReadFiletext(PATH."./database/".$file."/readme.txt");
		      $this->arrayfile[] = $r;
            }
          }
	    }
	    if($_GET['re']=='emptydb'){
          if(submit()){
		    if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			  $arr['error'] = "管理密码不正确";
			  echo json($arr);
		      exit;
		    }
	        $this->mysql->query("delete from {$this->pre}user");
	        $this->mysql->query("delete from {$this->pre}atmbank");
	        $this->mysql->query("delete from {$this->pre}atmlog");
	        $this->mysql->query("delete from {$this->pre}completed");
	        $this->mysql->query("delete from {$this->pre}customs");
	        $this->mysql->query("delete from {$this->pre}log");
	        $this->mysql->query("delete from {$this->pre}message");
	        $this->mysql->query("delete from {$this->pre}payorder");
	        $this->mysql->query("delete from {$this->pre}record");
	        $this->mysql->query("delete from {$this->pre}records");
			$this->mysql->query("delete from {$this->pre}trading");
			
			
		    $json['error'] = '0';
            $json['message'] = '会员数据已清空';
			$json['url'] = Purl("?mod=admin&act=user");
		    echo json($json);
		    exit;
	      }
	    }
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			$arr['error'] = "管理密码不正确";
			echo json($arr);
		    exit;
		  }
		  if($_GET['do']=='remove'){
			 $mypath = $_GET['id'];
	         $handler = opendir(PATH."./database/$mypath/");
             while($filename = @readdir($handler)){
               if($filename != "." && $filename != ".."){
			     @chmod(PATH."./database/$mypath/$filename", 0777);
			     @unlink(PATH."./database/$mypath/$filename");
		       }
		     }
			 closedir($handler); 
			 @chmod(PATH."./database/$mypath/",0777);
			 @rmdir(PATH."./database/$mypath/");
		     $arr['error'] = 0;
	         echo json($arr);
		  }
		  exit;
	    } 
	  }
	  if($_GET['get']=='guestbook'){
	    if($_GET['re']=='list'){
		  $where = "where id>'0'";
		  if($_GET['type']) $where .= " and type='{$_GET['type']}'";
	      if($_GET['username']){
		    if(!is_array($user = $this->user->sql($_GET['username'],'username'))) $this->message('go_back','该会员不存在');
		    $where .= " and uid='{$user['uid']}'";
		  }
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];			
	      $this->pagetotal = $this->mysql->counts("select * from {$this->pre}message $where");
		  $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}message $where order by addtime desc,id desc ".$this->page->limit);
	      while($rs=$this->mysql->assoc($query)){
		    $rs['username'] = $this->mysql->value("{$this->pre}user",'username',"uid='{$rs[uid]}'");
			$rs['addtime'] = formattime($rs['addtime']);
			$rs['type'] = $rs['type']=='1' ? "接收" : "发送";
			$rs['action'] = $rs['checked'] ? '已读' : '未读';
			$rs['action'] .= $rs['isdel'] ? '，已删' : '，未删';
		    $this->message[] = $rs;
	      }
	    }		 
	    if($_GET['re']=='add'){
          if(submit()){
		    if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			  $arr['error'] = "管理密码不正确";
			  echo json($arr);
		      exit;
		    }
            $arr['title'] = $_POST['title'];
			$arr['content'] = $_POST['content'];
			if($arr['title']==''){
			  $arr['error'] = "请填写信件主题";
			  echo json($arr);
		      exit;	
			}
			
			if($arr['content']==''){
			  $arr['error'] = "请填写信件内容";
			  echo json($arr);
		      exit;	
			}
			$arr['addtime'] = time();
			$arr['type'] = '2';
			if($_POST['sendtype']=='0'){
			   $uid = $this->mysql->getid("select * from {$this->pre}user");
			}elseif($_POST['sendtype']=='1'){
			  $arr['error'] = "请选择会员组";
			  echo json($arr);
		      exit;	
			  $uid = $this->mysql->getid("select * from {$this->pre}user where groupid='{$_POST['groupid']}'");
			  if(!$uid){
			    $arr['error'] = "选择的会员组没有会员";
			    echo json($arr);
		        exit;	
			  }
			}elseif($_POST['sendtype']=='2'){
			  $uid = $this->mysql->getid("select * from {$this->pre}user where username in('".implode("','",explode(",",$_POST['username']))."')");
			  if(!$uid){
			    $arr['error'] = "输入的会员都不存在";
			    echo json($arr);
		        exit;	
			  }
			}else{
			  $arr['error'] = "请选择接收会员";
			  echo json($arr);
		      exit;	
			}
			$arr['uid'] = explode(",",$uid);
            $this->mysql->insert("{$this->pre}message",$arr,$arr['uid']); 
		    $json['error'] = '0';
            $json['message'] = '发送信件成功';
			$json['url'] = Purl("?mod=admin&act=main&get=guestbook");
		    echo json($json);
		    exit;
	      }
		  $this->usergroup = $this->getselect('usergroup','groupid','groupname','','order by groupid asc',"[<><请选择会员级别>]");
	    }	
	    if($_GET['re']=='ajax'){
	      if($_GET['do']=='read'){
             $message = $this->mysql->select_one("select * from {$this->pre}message where id='{$_GET['id']}'");
		     $this->mysql->query("update {$this->pre}message set checked='1' where id='{$_GET[id]}' and type='1'");
		     $message['error'] = '0';
		     echo json($message);
		     exit;
	      }
	      if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
		    $arr['error'] = "管理密码不正确";
		    echo json($arr);
		    exit;
	      }
		  if($_GET['do']=='remove'){
			 $this->mysql->delete("{$this->pre}message","id='{$_GET['id']}'");
		     $arr['error'] = 0;
	         echo json($arr);
		  }
		  if($_GET['do']=='checked'){
			 $arr['checked'] = $_GET['val'];
			 $this->mysql->update("{$this->pre}message",$arr,"id='{$_GET['id']}'");
			 $arr['error'] = 0;
	         echo json($arr);
		  }
		  if($_GET['do']=='reply'){
			 $message = $this->mysql->select_one("select * from {$this->pre}message where id='{$_GET['id']}'");
			 if(!is_array($message)){
			   $arr['error'] = '主题邮件不存在'; 
			   echo json($arr);
			   exit;
			 }
			 $arr['title'] = "回复：".$message['title'];
			 $arr['content'] = $_POST['content'];
  			 if($arr['title']==''){
			   $arr['error'] = '请填写回复主题'; 
			   echo json($arr);
			   exit;
			 }
			 if($arr['content']==''){
			   $arr['error'] = '请填写回复内容'; 
			   echo json($arr);
			   exit;
			 }
			 $arr['addtime'] = time();
			 $arr['type'] = '2';
			 $arr['uid'] = $message['uid'];
			 $arr['parentid'] = $message['id'];
             $this->mysql->insert("{$this->pre}message",$arr); 
			 $arr['error'] = 0;
	         echo json($arr);
		  }
		  exit;
	    } 
	  }
	}	
	function unbackdatabase(){
	  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']&&empty($_GET['t'])&&empty($_GET['p'])){
		$arr['error'] = "管理密码不正确";
		echo json($arr);
		exit;
	  }
	  $shell = config::get("cookie") ? $_COOKIE['managershell'] : $_SESSION['managershell'];
	  if($shell!=$_GET['shell']){
		$arr['error'] = "悟空，你来了不改来的地方";
	    echo json($arr);
		exit;  
	  }
	  @include(PATH."./database/{$_GET['mypath']}/config.php");
      $t = empty($_GET['t']) ? 0 : $_GET['t'];
	  $p = empty($_GET['p']) ? 1 : $_GET['p'];
      $btb = explode(",",$b_table);
      $tbcount = count($btb);
	  $filename = empty($_GET['filename']) ? $btb[$t]."_1.php" : $_GET['filename'];
	  if(!@include(PATH."./database/{$_GET['mypath']}/".$filename)){
		$arr['error'] = "备份文件不存在或已被删除";
	    echo json($arr);
		exit;  
	  }
      if($p>=$tb[$btb[$t]]){
	    $t++;
	    if($t>=$tbcount){
		  $arr['error'] = "0";
		  $arr['message'] = "恭喜您！数据还原完毕.";
		  $arr['gourl'] = Purl("?mod=admin&act=main&get=database");
		  echo json($arr);
		  exit;
	    }
		$arr['error'] = "0";
		$arr['_message'] = "还原".$btb[$t-1]."表完毕，进入下一个....<br><br>".Ebak_EchoReDataSt($btb[$t-1],$tbcount,$t-1,$tb[$btb[$t-1]],'no');
		$arr['url'] = Purl("?mod=admin&act=unbackdatabase&mypath={$_GET['mypath']}&shell={$shell}&t=$t&p=0&filename={$btb[$t]}_1.php");
		echo json($arr);
		exit;
      }
      $p++;
	  $arr['error'] = "0";
	  $arr['_message'] = "正在恢复".$btb[$t]."表数据....<br><br>".Ebak_EchoReDataSt($btb[$t],$tbcount,$t,$tb[$btb[$t]],$p);
	  $arr['url'] = Purl("?mod=admin&act=unbackdatabase&mypath={$_GET['mypath']}&shell={$shell}&t=$t&p=$p&filename={$btb[$t]}_{$p}.php");
	  echo json($arr);
	  exit;
	}
 	function login(){
	  if(is_array($this->manager)) location('admin');
	  if(submit()) $this->manager_class->login($_POST['username'],$_POST['password'],$_POST['seccode']);
	}	
 	function logout(){
	  $this->manager_class->logout('admin_login');
	}	
 	function manager(){
	  if($_GET['get']=='control'){
	    if($_GET['re']=='list'){
	      $pagetotal = $this->pagetotal = $this->mysql->counts("select * from {$this->pre}manager");
		  $this->pageclass($pagetotal);
		  $sql = "select * from {$this->pre}manager ".getsort("uid_desc")." {$this->page->limit}";
	      $query = $this->mysql->query($sql);
	      while($rs=$this->mysql->assoc($query)){
		    $rs['editurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=control&re=edit&uid=".$rs['uid']);
		    $rs['delurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=control&uid=".$rs['uid']);
		    $rs['group'] = $this->mysql->select_one("select * from {$this->pre}group where groupid='$rs[groupid]'");
		    $this->managerlist[]=$rs;
	      }
	    }		
	    if($_GET['re']=='add'||$_GET['re']=='edit'){
		  $uid = $_GET['uid'];
		  $this->formtitle = $_GET['re']=='add' ? '添加用户' : "编辑用户";
          if(submit()){
	        if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
		      $json['error'] = "管理密码不正确";
		      echo json($json);
		      exit;
	        }
		    if($uid){
			  $json['message'] = '编辑管理用户成功';
              $this->manager_class->update($_POST,"$uid");
		    }else{
			  if($_POST['username']==''){
		        $json['error'] = "对不起，用户名不能为空";
		        echo json($json);
		        exit;  
			  }
			  if($_POST['password']==''){
		        $json['error'] = "对不起，密码不能为空";
		        echo json($json);
		        exit;  
			  } 
			  $json['message'] = '添加管理用户成功';
              $this->manager_class->insert($_POST); 
		    }
		    $json['error'] = "0";
			$json['url'] = Purl("?mod=admin&act=manager");
		    echo json($json);
		    exit;
	      }
          $this->add = $this->mysql->select_one("select * from {$this->pre}manager where uid='$uid'");
	      if(!is_array($this->add)&&$_GET['re']=='edit') $this->message('go_back','对不起，该管理用户不存在');
		  $this->usergrouplist = $this->getselect('group','groupid','groupname','','order by groupid asc',"[<><请选择用户角色>]");
	    }
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			$arr['error'] = "管理密码不正确";
			echo json($arr);
			exit;
		  }
		  if($_GET['do']=='remove'){
			if($_GET['id']=='1'){
			  $arr['error'] = "对不起，这个管理员不能删除！";
			  echo json($arr);
			  exit;	
		    }
            $this->mysql->delete("{$this->pre}manager","uid={$_GET['id']}");  
		    $arr['error'] = 0;
	        echo json($arr);
		  }
		  exit;
	    } 	
	  }		
	  if($_GET['get']=='group'){
	    if($_GET['re']=='list'){
          $this->group = $this->mysql->getarr("select * from {$this->pre}group order by groupid asc");
	    }	    
	    if($_GET['re']=='add'||$_GET['re']=='edit'){
		  $groupid = $_GET['groupid'];
		  $this->formtitle = $_GET['re']=='add' ? '添加角色' : "编辑角色";
          if(submit()){
	        if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
		      $json['error'] = "管理密码不正确";
		      echo json($json);
		      exit;
	        }
		    foreach($_POST as $key => $value) {
			  if(is_array($value)) $value = implode(',',$value);
			  $arr[$key] = $value;
			  if($key=='groupname'&&$value==''){
			    $json['error'] = "对不起，角色名称不能为空！";
			    echo json($json);
			    exit;	
			  }
		    }
		    if($groupid){
			  $message = '编辑角色成功';
              $this->mysql->update("{$this->pre}group",$arr,"groupid='$groupid'"); 
		    }else{
			  $message = '添加角色成功';
              $this->mysql->insert("{$this->pre}group",$arr); 
		    }
			$json['error'] = "0";
			$json['message'] = $message;
			$json['url'] = Purl('?mod=admin&act=manager&get=group');
			echo json($json);
		    exit;

	      }
		  $this->right = localPre($this->mysql->getarr("select * from {$this->pre}purviews where admin='1' order by id desc"));
	 	  if($_GET['re']=='edit'){
            $this->add = $this->mysql->select_one("select * from {$this->pre}group where groupid='$groupid'");
	        if(!is_array($this->add )) $this->message('go_back','该角色信息不存在');
	        if($this->add ['system']==1) $this->message('go_back','系统角色是禁止修改的');	
		  }
	    } 	
		
	  }
      if($_GET['get']=='password'){		  
		if($_GET['re']=='index'){
           if(submit()){
             if($_POST['oldpassword']=='') $this->message('go_back','对不起，原始密码不能为空');
	         if($_POST['password']=='') $this->message('go_back','对不起，新密码不能为空');
	         if($_POST['repassword']=='') $this->message('go_back','对不起，确认密码不能为空');
	         if($_POST['repassword']!=$_POST['password']) $this->message('go_back','对不起，两次输入密码不一致');
			 if($this->manager_class->password($_POST['oldpassword'],$this->manager['salt'])!=$this->manager[password]){
			   $this->message('go_back','对不起，原始密码不正确'); 
			 }
			 $arr['password'] = $_POST['password'];
             $this->manager_class->update($arr,$this->manager['uid']);
		     $this->message('?mod=admin&act=manager&get=password',"修改密码成功");
	       }			
		} 		  
	  }
	}	
	function profile(){
	  $uid = $_GET['uid'];
	  if($_GET['get']=='getbank') $this->bank = $this->mysql->getarr("select * from {$this->pre}atmbank where uid='$uid'");
	}	
	function user(){		
	  if($_GET['get']=='control'){
		if($_GET['loginuid']){
		  $this->user->autologin($_GET['loginuid']);
		  exit;
		}
	    if($_GET['re']=='list'){
		  $getmember = is_array($this->mysql->select_one("select * from {$this->pre}user limit 0,1"));
		  if(!$getmember) $this->message('?mod=admin&act=user&get=control&re=add','暂时没有任何会员，请添加'); 
		  $where = "where uid>0";
		  if($_GET['groupid']) $where .= " and groupid='{$_GET['groupid']}'";
		  if($_GET['username']) $where .= " and username like '{$_GET['username']}%'";
		  $this->t = _time('regtime',"and",'1');
		  $where .= $this->t['where'];	
	      $this->pagetotal = $this->mysql->counts("select * from {$this->pre}user $where");
		  $this->pageclass($this->pagetotal,8);
	      $query = $this->mysql->query("select * from {$this->pre}user $where order by uid desc {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
		    $rs['usergroup'] = $this->mysql->select_one("select * from {$this->pre}usergroup where groupid='$rs[groupid]'");
			$rs['renumber'] = $this->mysql->counts("select uid from {$this->pre}user where referee='{$rs['username']}'");
		    $this->userlist[]=$rs;
	      }
	    }	
		
	    if($_GET['re']=='edit'){
		  $this->u = $this->user->sql($_GET['uid']);
		  $this->bank = $this->mysql->select_one("select * from {$this->pre}atmbank where uid='{$_GET['uid']}' limit 1");
		  if(submit()){
            if($this->manager_class->password($_GET['repass'],$this->manager['salt'])==$this->manager['password']){
			  $a['truename'] = $_POST['truename'];
			  $a['userphone'] = $_POST['userphone'];
			  $a['idcard'] = $_POST['idcard'];
			  $a['address'] = $_POST['address'];
			  $this->user->update($a,$_GET['uid']);
		      if($_POST['bankadd']&&$_POST['bankname']&&$_POST['bankcard']){
                $b['uid'] = $_GET['uid'];
		        $b['truename'] = $arr['truename'];
		        $b['bankadd'] = $_POST['bankadd'];
		        $b['bankname'] = $_POST['bankname'];
		        $b['bankcard'] = $_POST['bankcard'];
				if($_POST['bankid']){
				  $this->mysql->update("{$this->pre}atmbank",$b,"id='{$_POST['bankid']}'");	
				}else{
                  $this->mysql->insert("{$this->pre}atmbank",$b); 
				}
		      }
		      $json['error'] = '0';
		    }else{
              $json['error'] = "管理密码不正确";
		    }
		    $json['url'] = Purl("?mod=admin&act=user");
		    echo json($json);
		    exit;
		  }
	    } 
			
	    if($_GET['re']=='add'){
		  $this->getmember = is_array($this->mysql->select_one("select * from {$this->pre}user limit 0,1"));
		  if(submit()){
            if($this->manager_class->password($_GET['repass'],$this->manager['salt'])==$this->manager['password']){
			  $_POST['getmember'] = $this->getmember ? '' : '1';
			  $_POST['_repass'] = $_POST['repass'];
		      $json['error'] = $this->verifyinsertuser($_POST,'1');
		    }else{
              $json['error'] = "管理密码不正确";
		    }
		    $json['url'] = Purl("?mod=admin&act=user");
		    echo json($json);
		    exit;
		  }
		  if($this->getmember) $this->_referee = $this->user->sql($_GET['uid']);
		  $this->usergrouplist = $this->getselect('usergroup','groupid','groupname','','order by groupid asc',"[<><请选择会员级别>]");
	    } 	
	    if($_GET['re']=='treeform'){
		  $this->getmember = $this->mysql->select_one("select * from {$this->pre}user order by uid asc limit 0,1");
		  $getmember = is_array($this->getmember);
		  if(!$getmember) $this->message('?mod=admin&act=user&get=control&re=add','暂时没有任何会员，请添加'); 
		  if($_GET['username']){
            $user = $this->mysql->select_one("select * from {$this->pre}user where username = '{$_GET['username']}'  limit 0,1");
			if(!is_array($user)) $this->message('go_back','该用户不存在');
			$_GET['uid'] = $user['uid'];
		  }
		  $_GET['type'] = $_GET['type'] ? $_GET['type'] : 'arrange';
		  $uid = $_GET['uid'] ? $_GET['uid'] : $this->mysql->value("{$this->pre}user",'uid',"referee=''");
		  $_GET['username'] = $this->mysql->value("{$this->pre}user",'username',"uid='{$uid}'");
		  if($_GET['type']=='arrange'){
  	        $this->tree = $this->user->sql($uid);	
	        $this->tree['l'] = $this->user->sql($this->tree['_left'],'username');
		    $this->tree['r'] = $this->user->sql($this->tree['_right'],'username');
		    $this->tree['ll'] = $this->user->sql($this->tree['l']['_left'],'username');
		    $this->tree['lr'] = $this->user->sql($this->tree['l']['_right'],'username');
		    $this->tree['rl'] = $this->user->sql($this->tree['r']['_left'],'username');
		    $this->tree['rr'] = $this->user->sql($this->tree['r']['_right'],'username');
		  }
		  if($_GET['type']=='system'){
  	        $this->tree = $this->user->sql($uid);	
	        $this->tree['l'] = $this->user->sql($this->tree['_left'],'username');
		    $this->tree['r'] = $this->user->sql($this->tree['_right'],'username');
		    $this->tree['ll'] = $this->user->sql($this->tree['l']['_left'],'username');
		    $this->tree['lr'] = $this->user->sql($this->tree['l']['_right'],'username');
		    $this->tree['rl'] = $this->user->sql($this->tree['r']['_left'],'username');
		    $this->tree['rr'] = $this->user->sql($this->tree['r']['_right'],'username'); 
		  }
	    } 	
	    if($_GET['re']=='password'){
          if(submit()){
	        if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
		      $json['error'] = "管理密码不正确";
		      echo json($json);
		      exit;
	        }
		    if($_POST['username']==''){
			  $json['error'] = "对不起，请填写玩家编号！";
			  echo json($json);
			  exit;	
		    }else{
		      $user = $this->mysql->select_one("select * from {$this->pre}user where username='{$_POST[username]}'");
              if(!is_array($user)){
			    $json['error'] = "对不起，该会员不存在请核实！";
			    echo json($json);
			    exit;	
		      }
		    }		   
		    if($_POST['password']==''&&$_POST['repass']==''){
			  $json['error'] = "对不起，不能一个密码也不修改！";
			  echo json($json);
			  exit;	
		    }
			if($_POST['password']=='') unset($_POST['password']);
			if($_POST['repass']=='') unset($_POST['repass']);
			$this->user->update($_POST,$user['uid']);
			$json['error'] = "0";
			$json['message'] = '密码重置成功';
			$json['url'] = Purl('?mod=admin&act=user&get=group');
			echo json($json);
		    exit;
	      }
	    }	  
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
             $json['error'] = "管理密码不正确";
	         echo json($json);
	         exit;
		  }
		  if($_GET['do']=='changemoney'){
			 $uid = $_GET['uid'];
			 if($_GET['content']==''){
			   $arr['error'] = '请输入变更原因！'; 
			   echo json($arr);
			   exit;
			 }
			 if(!$_GET['rank_money']){
			   $arr['error'] = '请输入变动金额！'; 
			   echo json($arr);
			   exit;
			 }
			 if($_GET['rank_money']){
			   $add_money = $_GET['add_money']=='1' ? '+' : '-';
			   $parentid = $this->up_money($uid,$_GET['rank_money'],$add_money,$_GET['content']);
			 }
			 $user = $this->user->sql($uid);
/*			 if($_GET['add_money']=='1'){
		       $arr['orderid'] = makeorderid();
	           $arr['total_fee'] = $_GET['rank_money'];
		       $arr['checked'] = 1;
		       $arr['uid'] = $uid;
		       $arr['addtime'] = time();
		       $arr['paytype'] = $_GET['content'];
		       $this->mysql->insert("{$this->pre}payorder",$arr);
			   $this->record('_paymoney',$_GET['rank_money']);
			   $this->records($uid,'_paymoney',$_GET['rank_money']);
			 }*/
			 $arr['money'] = $user['money'];
			 $arr['error'] = 0;
	         echo json($arr);
		  }
		  if($_GET['do']=='canlogin'){
			 $arr['canlogin'] = $_GET['val'];
			 $this->mysql->update("{$this->pre}user",$arr,"uid='{$_GET['id']}'");
			 $arr['error'] = 0;
	         echo json($arr);
		  }
		  if($_GET['do']=='remove'){
			 if(is_array($user = $this->user->sql($_GET['id']))&&$user['status']=='0'){
			   $this->mysql->delete("{$this->pre}user","uid='{$_GET['id']}'");
			 }else{
			   $arr['error'] = '会员不存在或已激活，禁止删除'; 
			 }
			 $arr['error'] = 0;
	         echo json($arr);
			 exit;
		  }
		  if($_GET['do']=='service'){
			 $arr['service'] = $_GET['val'];
			 $ar['checked'] = $_GET['val'];
			 $a = $this->mysql->select_one("select * from {$this->pre}customs where uid='{$_GET['id']}'");
			 if(is_array($a)){
			   $this->mysql->update("{$this->pre}user",$arr,"uid='{$_GET['id']}'");
			   $this->mysql->update("{$this->pre}customs",$ar,"uid='{$_GET['id']}'");
			   $arr['error'] = 0;
			 }else{
			   $arr['error'] = '该会员未申请报单中心'; 
			 }
	         echo json($arr);
		  }
		  if($_GET['do']=='status'){
			 $status = $this->mysql->counts("select uid from {$this->pre}user")>'1' ? '1' : '0';
			 $arr['error'] = $this->status($_GET['uid'],$status,'1');
			 $arr['opentime'] = formattime(time());
	         echo json($arr);
		  }
		  exit;
	     } 	
	  }		
	  if($_GET['get']=='group'){
	    if($_GET['re']=='list'){
          $this->group = $this->mysql->getarr("select * from {$this->pre}usergroup order by groupid asc");
	    }	    
	    if($_GET['re']=='add'||$_GET['re']=='edit'){
          $this->formtitle = $_GET['re']=='add' ? '添加级别' : "编辑级别";
		  $groupid = $_GET['groupid'];
          if(submit()){
	        if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
		      $json['error'] = "管理密码不正确";
		      echo json($json);
		      exit;
	        }
			
			foreach($_POST['leadn'] as $key=>$val){
			  $leadask[$key]['leadf'] = $_POST['leadf'][$key];
			  $leadask[$key]['leads'] = $_POST['leads'][$key];
			  $leadask[$key]['leadn'] = $_POST['leadn'][$key];
			}
			$arr['leadask'] = serialize($leadask);
			unset($_POST['leadn'],$_POST['leadf'],$_POST['leads']);
			
			foreach($_POST['floorn'] as $key=>$val){
			  $floorask[$key]['floorf'] = $_POST['floorf'][$key];
			  $floorask[$key]['floors'] = $_POST['floors'][$key];
			  $floorask[$key]['floorn'] = $_POST['floorn'][$key];
			}
			$arr['floorask'] = serialize($floorask);
			unset($_POST['floorn'],$_POST['floorf'],$_POST['floors']);
			
			
		    foreach($_POST as $key => $value) {
			  if(is_array($value)) $value = implode(',',$value);
			  $arr[$key] = $value;
			  if($key=='groupname'&&$value==''){
			    $json['error'] = "对不起，分组名称不能为空！";
			    echo json($json);
			    exit;	
			  }
		    }
		    if($groupid){
			  $message = '编辑会员级别成功';
              $this->mysql->update("{$this->pre}usergroup",$arr,"groupid='$groupid'"); 
		    }else{
			  $message = '添加会员级别成功';
              $this->mysql->insert("{$this->pre}usergroup",$arr); 
		    }
			$json['error'] = "0";
			$json['message'] = $message;
			$json['url'] = Purl('?mod=admin&act=user&get=group');
			echo json($json);
		    exit;
	      }
		  $this->right = localPre($this->mysql->getarr("select * from {$this->pre}purviews where admin='0' order by ord asc,id asc"));
	 	  if($_GET['re']=='edit'){
            $this->add = $this->mysql->select_one("select * from {$this->pre}usergroup where groupid='$groupid'");
	        if(!is_array($this->add)) $this->message('go_back','该会员级别不存在');
		  }
		 
	    } 		
	  } 
	  if($_GET['get']=='customs'){
	    if($_GET['re']=='list'){
		  if($_GET["time"]&&$_GET["timet"]){
		   $time = untime($_GET["time"]);  
		   $timet = untime($_GET["timet"]." 23:59"); 
		   $where = "where addtime>='{$time}' and addtime<='{$timet}'";	
		   $this->time_str = $_GET["time"].",".$_GET["timet"];
	      }
	      $this->pagetotal = $this->mysql->counts("select * from {$this->pre}customs $where");
		  $this->pageclass($this->pagetotal);
	      $this->customs = $this->mysql->getarr("select * from {$this->pre}customs $where order by addtime desc ".$this->page->limit);
	    }
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
             $json['error'] = "管理密码不正确";
	         echo json($json);
	         exit;
		  }
		  if($_GET['do']=='checked'){
			 $arr['service'] = $_GET['val'];
			 $ar['checked'] = $_GET['val'];
			 $this->mysql->update("{$this->pre}user",$arr,"uid='{$_GET['id']}'");
			 $this->mysql->update("{$this->pre}customs",$ar,"uid='{$_GET['id']}'");
			 $arr['error'] = 0;
	         echo json($arr);
		  }
		  exit;
		}
	  }	  
	}	
 	function site(){	
	  if($_GET['get']=='news'){
	     if($_GET['re']=='list'){
          if(submit()){
		     if($_POST['id']){
		       $id = implode(',',$_POST['id']);
               $this->mysql->delete("{$this->pre}news","id in({$id})");
               $this->message('go_back','批量删除新闻成功'); 
		    }else{
			   $this->message('go_back','请选择要删除的新闻'); 
		    }
	      }
		  $where = "where id>'0'";
		  if($_GET['typeid']) $where .= " and typeid='{$_GET['typeid']}'";
		  if($_GET['content']) $where .= " and title like '%{$_GET['content']}%'";
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];	
	      $this->pagetotal = $this->mysql->counts("select * from {$this->pre}news $where");
		  $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}news $where order by addtime desc,id desc ".$this->page->limit);
	      while($rs=$this->mysql->assoc($query)){
		      $rs['editurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=news&re=edit&id=".$rs['id']);
		      $rs['delurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&re=list&id=".$rs['id']);
	  	      $rs['addtime'] = formattime($rs['addtime']);
		      $rs['typename'] = $this->mysql->value("{$this->pre}newstype",'typename',"id='$rs[typeid]'");
		      $this->newslist[]=$rs;
	      }
	     }		 
	     if($_GET['re']=='add'||$_GET['re']=='edit'){
		   $id = $_GET['id'];
           if(submit()){
		     $arr['title'] = sintrim($_POST['title']);
		     $arr['content'] = $_POST['content'];
		     $arr['typeid'] = $_POST['typeid'];
		     $arr['addtime'] = untime($_POST['addtime']);
		     if($id){
			    $message = '编辑新闻成功';
                $this->mysql->update("{$this->pre}news",$arr,"id='$id'"); 
		     }else{
			    $message = '添加新闻成功';
                $this->mysql->insert("{$this->pre}news",$arr); 
		     }
		     $this->message('admin_site',$message);
	       }
           $this->news = $this->mysql->select_one("select * from {$this->pre}news where id='$id'");
		   $this->news['addtime'] = $this->news['addtime'] ? $this->news['addtime'] : time();
	       if(!is_array($this->news)&&$_GET['get']=='edit') $this->message('go_back','对不起，该新闻信息不存在');	
           $this->typelist = $this->getselect('newstype','id','typename','','order by typeorder asc');
	     } 		 
	     if($_GET['re']=='type'){
           if(submit()){
		  	 $arrid = $_POST['id'];
			 $arrtypename = $_POST['typename'];
			 $arrsystem = $_POST['system'];
			 $arrtypeorder = $_POST['typeorder'];
			 foreach($arrid as $k=>$v){
			   $arr['typename'] = $arrtypename[$k];
			   $arr['system'] = $arrsystem[$k]; 
			   $arr['typeorder'] = $arrtypeorder[$k]; 
			   if($v == ''){
                 $this->mysql->insert("{$this->pre}newstype",$arr); 
			   }else{
                 $this->mysql->update("{$this->pre}newstype",$arr,"id='$v'"); 
			   }
			 }
	         $this->message('go_back','分类操作成功');	  
	       }
	       $query = $this->mysql->query("select * from {$this->pre}newstype order by typeorder asc");
	       $this->typelist=array();
	       while($rs=$this->mysql->assoc($query)){
		     $rs['delurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&re=type&id=".$rs['id']);
		     $this->typelist[]=$rs;
	       }
	     }
	     if($_GET['re']=='ajax'){
		   if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			 $arr['error'] = "管理密码不正确";
			 echo json($arr);
			 exit;
		   }
		   if($_GET['do']=='remove'){
             $this->mysql->delete("{$this->pre}news","id={$_GET['id']}");  
		     $arr['error'] = 0;
	         echo json($arr);
		   }elseif($_GET['do']=='removetype'){
		     $news = $this->mysql->select_one("select * from {$this->pre}news where typeid='{$_GET['id']}'");
		     if(is_array($news)){
		       $arr['error'] = '分类下有信息不能删除';
			 }else{
		       $this->mysql->delete("{$this->pre}newstype","id={$_GET['id']}");  
		       $arr['error'] = 0;
			 }
	         echo json($arr);
		   }
		   exit;
	     } 	
	  }
	  if($_GET['get']=='about'){
	    if($_GET['re']=='list'){
          if(submit()){
		    if($_POST['id']){
		      $id = implode(',',$_POST['id']);
              $this->mysql->delete("{$this->pre}about","id in({$id})");
              $this->message('go_back','批量删除单页成功'); 
		    }else{
			  $this->message('go_back','请选择要删除的单页'); 
		    }
	      }
		  if($_GET['typeid']) $where = "where typeid='{$_GET['typeid']}'";
	      $this->pagetotal = $this->mysql->counts("select * from {$this->pre}about $where");
		  $this->pageclass($this->pagetotal,10);
	      $query = $this->mysql->query("select * from {$this->pre}about $where order by typeid asc ".$this->page->limit);
	      while($rs=$this->mysql->assoc($query)){
		    $rs['editurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=about&re=edit&id=".$rs['id']);
		    $rs['delurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=about&id=".$rs['id']);
		    $rs['typename'] = $this->mysql->value("{$this->pre}abouttype",'typename',"id='$rs[typeid]'");
		    $this->aboutlist[] = $rs;
	      }
	    }	  
	    if($_GET['re']=='add'||$_GET['re']=='edit'){
		  $id = $_GET['id'];
          if(submit()){
		    $arr['name'] = sintrim($_POST['name']);
		    $arr['myurl'] = $_POST['myurl'];
		    $arr['urltype'] = $_POST['urltype'];
		    $arr['content'] = $_POST['content'];
		    $arr['typeid'] = $_POST['typeid'];
		    if($id){
			  $message = '编辑单页成功';
              $this->mysql->update("{$this->pre}about",$arr,"id='$id'"); 
		    }else{
			  $message = '添加单页成功';
              $this->mysql->insert("{$this->pre}about",$arr); 
		    }
		    $this->message('?mod=admin&act=site&get=about',$message);
	      }
          $this->about = $this->mysql->select_one("select * from {$this->pre}about where id='$id'");
	      if(!is_array($this->about)&&$_GET['get']=='edit') $this->message('go_back','对不起，该单页信息不存在');	 
		  $this->typelist = $this->getselect('abouttype','id','typename','','order by `typeorder` asc');
	    } 	  	  
	    if($_GET['re']=='type'){
          if(submit()){
			$arrid = $_POST['id'];
			$arrtypename = $_POST['typename'];
			$arrtypeorder = $_POST['typeorder'];
			foreach($arrid as $k=>$v){
			  $arr['typename'] = $arrtypename[$k];
			  $arr['typeorder'] = $arrtypeorder[$k]; 
			  if($v == ''){
                 $this->mysql->insert("{$this->pre}abouttype",$arr); 
			  }else{
                $this->mysql->update("{$this->pre}abouttype",$arr,"id='$v'"); 
			  }
			}
	        $this->message('go_back','分类操作成功');	  
	      }
	      $query = $this->mysql->query("select * from {$this->pre}abouttype order by typeorder asc");
	      $this->typelist=array();
	      while($rs=$this->mysql->assoc($query)){
		    $rs['delurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=about&re=type&id=".$rs['id']);
		    $this->typelist[]=$rs;
	      }
	    }
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			 $arr['error'] = "管理密码不正确";
			 echo json($arr);
			 exit;
		  }
		  if($_GET['do']=='remove'){
             $this->mysql->delete("{$this->pre}about","id={$_GET['id']}");  
		     $arr['error'] = 0;
	         echo json($arr);
		  }elseif($_GET['do']=='removetype'){
		     $about = $this->mysql->select_one("select * from {$this->pre}about where typeid='{$_GET['id']}'");
		     if(is_array($about)){
		       $arr['error'] = '分类下有信息不能删除';
			 }else{
		       $this->mysql->delete("{$this->pre}abouttype","id={$_GET['id']}");  
		       $arr['error'] = 0;
			 }
	         echo json($arr);
		  }
		  exit;
	    } 	
      }  
	}   
	function census(){		
	  if($_GET['get']=='money'){
		if($_GET['re']=='list'){
		  if($_GET['type']){
		    $where = "where typeid='{$_GET['type']}'";
	        if($_GET['username']){
              $user = $this->user->sql($_GET['username'],'username');
		      if(!is_array($user)) $this->message('go_back','该会员不存在');
		      $where .= " and uid='{$user['uid']}'";
		    }else{
		      if($_GET['uid']){
			    $where .= " and uid='{$_GET['uid']}'";
			    $_GET['username'] = $this->mysql->value($this->pre."user","username","uid=".$_GET['uid']);
		      }
	  	    }
			$this->t = _time('addtime',"and",'1');
			$where .= $this->t['where'];			
		    $this->pagetotal = $this->mysql->counts("select id from {$this->pre}log $where");
	        $this->pageclass($this->pagetotal);
	        $this->record = $this->mysql->getarr("select * from {$this->pre}log $where order by id desc {$this->page->limit}"); 
		  }else{
		    $where = "where parentid='0'";
	        if($_GET['username']){
              $user = $this->user->sql($_GET['username'],'username');
		      if(!is_array($user)) $this->message('go_back','该会员不存在');
		      $where .= " and uid='{$user['uid']}'";
		    }else{
		      if($_GET['uid']){
			    $where .= " and uid='{$_GET['uid']}'";
			    $_GET['username'] = $this->mysql->value($this->pre."user","username","uid=".$_GET['uid']);
		      }
	  	    }
			$this->t = _time('addtime',"and",'1');
			$where .= $this->t['where'];
		    $this->pagetotal = $this->mysql->counts("select id from {$this->pre}log $where");
		    $this->pageclass($this->pagetotal);
		    $query = $this->mysql->query("select * from {$this->pre}log $where order by id desc {$this->page->limit}");
	        while($rs=$this->mysql->assoc($query)){
		      $q = $this->mysql->query("select * from {$this->pre}log where parentid='{$rs['id']}'");
	          while($r=$this->mysql->assoc($q)){
			    $rs[$r['typeid']] = $r;
	          }
		      $rs[$rs['typeid']] = $rs;
	          $this->record[] = $rs;
	        }
		  }
		}
		if($_GET['re']=='chart'){
		  $this->t = _time('addtime','where','1');
	      $this->allmoney = $this->getrecord($this->t['where']);
		}
		if($_GET['re']=='outchart'){		  
		  $this->t = _time('addtime','where');
		  $partarr = $this->getchatdate($this->t['timet'],$this->t['time']);
		  for($i=0;$i<=$partarr['step'];$i++){
			$parttime = dateadd($partarr['part'],$i,$partarr['time']);  
			$formattime = formattime(untime($parttime),$partarr['format']);
			$addtime = '"'.$formattime.'"';
			$addkey = untime($formattime);
			if(!in_array($addtime,$categories)){
			  $categories[$addkey] = $addtime;
			}
			$record = $this->getrecord("where FROM_UNIXTIME(addtime,'".$partarr['_format']."')='".$formattime."'");
			$money[$addkey] = $record['money'];
			$_money[$addkey] = $record['_money'];
			$refereemoney[$addkey] = $record['refereemoney'];
			$floormoney[$addkey] = $record['floormoney'];
			$__money[$addkey] = $record['__money'];
			$leadmoney[$addkey] = $record['leadmoney'];
			$regmoney[$addkey] = $record['regmoney'];
		  }
		  $this->categories = implode(',',$categories);
		  $this->money = implode(",",$money);
		  $this->_money = implode(",",$_money);
		  $this->refereemoney = implode(",",$refereemoney);
		  $this->floormoney = implode(",",$floormoney);
		  $this->__money = implode(",",$__money);
		  $this->leadmoney = implode(",",$leadmoney);
		  $this->regmoney = implode(",",$regmoney);
		  $this->allmoney = $this->getrecord($this->t["where"]);
		}
		if($_GET['re']=='inchart'){
		  $this->t = _time('addtime','where');
		  $partarr = $this->getchatdate($this->t['timet'],$this->t['time']);
		  for($i=0;$i<=$partarr['step'];$i++){
			$parttime = dateadd($partarr['part'],$i,$partarr['time']);  
			$formattime = formattime(untime($parttime),$partarr['format']);
			$addtime = '"'.$formattime.'"';
			$addkey = untime($formattime);
			if(!in_array($addtime,$categories)){
			  $categories[$addkey] = $addtime;
			}
			$record = $this->getrecord("where FROM_UNIXTIME(addtime,'".$partarr['_format']."')='".$formattime."'");
			$outmoney[$addkey] = $record['outmoney'];
			$buymoney[$addkey] = $record['buymoney'];
		  }
		  $this->categories = implode(',',$categories);
		  $this->outmoney = implode(",",$outmoney);
		  $this->buymoney = implode(",",$buymoney);
		  $this->allmoney = $this->getrecord($this->t["where"]);
		}
	  }	
	  if($_GET['get']=='atmlog'){
		if($_GET['re']=='list'){
           if(submit()){
		     if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			   $arr['error'] = "管理密码不正确";
			   echo json($arr);
			   exit;
		     }
			 if(!is_array($_POST['id'])){
			   $arr['error'] = "请选择要处理的提现申请";
			   echo json($arr);
			   exit;
			 }
		     $id = implode(',',$_POST['id']);
	         $query = $this->mysql->query("select * from {$this->pre}atmlog where id in({$id}) and checked='0'");
	         while($rs=$this->mysql->assoc($query)){
			   $this->records($rs['uid'],'atmmoneyed',$rs['lognum']);
			   $this->record('atmmoneyed',$rs['lognum']);
		       $this->mysql->query("update {$this->pre}atmlog set checked='1' where id='{$rs['id']}' and checked='0'");
	         }
			 $arr['error'] = "0";
			 $arr['message'] = "批量处理提现申请成功";
			 $arr['url'] = 'location.href';
			 $arr['_url'] = '1';
			 echo json($arr);
			 exit;
	       }
		   $where = "where id>0";
		   if($_GET['username']){
             $user = $this->user->sql($_GET['username'],'username');
			 if(!is_array($user)) $this->message('go_back','该用户不存在');
			 $where .= " and uid='{$user['uid']}'";
		   }
		   if($_GET['type']){
			 $where .= " and checked = '".($_GET['type']-1)."'";
		   }
		   $this->t = _time('addtime','and','1');
		   $where .= $this->t['where'];
	       $this->pagetotal = $this->mysql->counts("select id from {$this->pre}atmlog $where");
	       $this->pageclass($this->pagetotal,5);
	       $this->atmlog = $this->mysql->getarr("select * from {$this->pre}atmlog $where order by id desc {$this->page->limit}");
	    }
		if($_GET['re']=='chart'){
		  $this->t = _time('addtime','where');
		  $partarr = $this->getchatdate($this->t['timet'],$this->t['time']);
		  for($i=0;$i<=$partarr['step'];$i++){
			$parttime = dateadd($partarr['part'],$i,$partarr['time']);  
			$formattime = formattime(untime($parttime),$partarr['format']);
			$addtime = '"'.$formattime.'"';
			$addkey = untime($formattime);
			if(!in_array($addtime,$categories)){
			  $categories[$addkey] = $addtime;
			}
			$record = $this->getrecord("where FROM_UNIXTIME(addtime,'".$partarr['_format']."')='".$formattime."'");
			$atmmoney[$addkey] = $record['atmmoney'];
			$atmmoneyed[$addkey] = $record['atmmoneyed'];
			$atmmoneyno[$addkey] = $record['atmmoneyno'];
		  }
		  $this->categories = implode(',',$categories);
		  $this->atmmoney = implode(",",$atmmoney);
		  $this->atmmoneyed = implode(",",$atmmoneyed);
		  $this->atmmoneyno = implode(",",$atmmoneyno);
		  $this->allmoney = $this->getrecord($this->t["where"]);
		}
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
             $json['error'] = "管理密码不正确";
	         echo json($json);
	         exit;
		  }
		  if($_GET['do']=='checked'){
			 $atmlog = $this->mysql->select_one("select * from {$this->pre}atmlog where id='{$_GET['id']}' and checked='0'");
		     if(!is_array($atmlog)){
			   $arr['error'] = '请不要非法操作';
	           echo json($arr); 
			   exit;
			 }
			 $this->records($atmlog['uid'],'atmmoneyed',$atmlog['lognum']);
			 $this->record('atmmoneyed',$atmlog['lognum']);
		     $arr['checked'] = 1;
	         $this->mysql->update("{$this->pre}atmlog",$arr,"id='{$_GET['id']}'");
			 $arr['error'] = 0;
			 $arr['message'] = '已经付款';
	         echo json($arr);
		  }
		  exit;
	     } 
	  }	 
	  if($_GET['get']=='payorder'){
		if($_GET['re']=='list'){
		   if($_GET['id']){
			 $pay = $this->mysql->select_one("select * from {$this->pre}payorder where id='{$_GET['id']}' and checked='0'");
			 $this->mysql->delete("{$this->pre}payorder","id='{$_GET['id']}' and checked='0'");
			 $this->mysql->query("update {$this->pre}money set allmoney=allmoney+'{$pay['total_fee']}',paymoney=paymoney-'{$pay['total_fee']}' where id='1'");
		   }
		   $where = "where id>0";
		   if($_GET['username']){
             $user = $this->user->sql($_GET['username'],'username');
			 if(!is_array($user)) $this->message('go_back','该用户不存在');
			 $where .= " and uid='{$user['uid']}'";
		   }
		   if($_GET['type']){
			 $where .= " and checked = '".($_GET['type']-1)."'";
		   }
		   $this->t = _time('addtime','and','1');
		   $where .= $this->t['where'];
	       $this->pagetotal = $this->mysql->counts("select id from {$this->pre}payorder $where");
	       $this->pageclass($this->pagetotal);
	       $this->paylog = $this->mysql->getarr("select * from {$this->pre}payorder $where order by id desc {$this->page->limit}");
	    }
		if($_GET['re']=='chart'){
		  $this->t = _time('addtime','where');
		  $partarr = $this->getchatdate($this->t['timet'],$this->t['time']);
		  for($i=0;$i<=$partarr['step'];$i++){
			$parttime = dateadd($partarr['part'],$i,$partarr['time']);  
			$formattime = formattime(untime($parttime),$partarr['format']);
			$addtime = '"'.$formattime.'"';
			$addkey = untime($formattime);
			if(!in_array($addtime,$categories)){
			  $categories[$addkey] = $addtime;
			}
			$record = $this->getrecord("where FROM_UNIXTIME(addtime,'".$partarr['_format']."')='".$formattime."'");
			$paymoney[$addkey] = $record['paymoney'];
			$_paymoney[$addkey] = $record['_paymoney'];
			$allpaymoney[$addkey] = $record['allpaymoney'];
		  }
		  $this->categories = implode(',',$categories);
		  $this->paymoney = implode(",",$paymoney);
		  $this->_paymoney = implode(",",$_paymoney);
		  $this->allpaymoney = implode(",",$allpaymoney);
		  $this->allmoney = $this->getrecord($this->t["where"]);
		}
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
             $json['error'] = "管理密码不正确";
	         echo json($json);
	         exit;
		  }
		  if($_GET['do']=='checked'){
	         $order =  $this->mysql->select_one("select * from {$this->pre}payorder where id='{$_GET['id']}' and checked=1");
			 if(is_array($order)){
			   $uid = $order['uid'];
               $this->mysql->update("{$this->pre}payorder",array('checked'=>2),"orderid='{$order['orderid']}' and uid='{$uid}'");
			   $this->up_money($uid,$order['total_fee'],"+","金币抢购");
			   $this->record('paymoney',$order['total_fee']);
			   $this->records($uid,'paymoney',$order['total_fee']);
			   $this->mysql->query("update {$this->pre}money set paymoney=paymoney-'{$order['total_fee']}',money=money+'{$order['total_fee']}' where id='1'");
			   $arr['error'] = 0;
			   $arr['message'] = paycheck('2');
		     }else{
			   $arr['error'] = '订单信息读取错误';
	           echo json($arr); 
			   exit; 
			 }
	         echo json($arr);
		  }
		  exit;
	     } 
	  }	 	  
	}
	function shop(){  
	  if($_GET['get']=='goods'){
		if($_GET['re']=='list'){
		  $where = "where goods_id>0";
		  $keyword = $_GET['keyword'];
		  if($keyword) $where.=" and goods_name like '%{$keyword}%'";
	      $this->pagetotal = $this->mysql->counts("select * from {$this->pre}goods $where");
		  $this->pageclass($this->pagetotal);
	      $query = $this->mysql->query("select * from {$this->pre}goods $where ".getsort("goods_id_desc")." {$this->page->limit}");
	      while($rs=$this->mysql->assoc($query)){
		    $rs['editurl'] = rewrite::request("?mod={$this->module}&act={$this->action}&get=goods&re=edit&id=".$rs['goods_id']);
		    $this->goodslist[]=$rs;
	      }
		}		
	    if($_GET['re']=='add'||$_GET['re']=='edit'){
		  $id = $_GET['id'];
		  $this->shoptitle = $_GET['re']=='add' ? "添加商品" : "编辑商品";
		  $this->add = $this->mysql->select_one("select * from {$this->pre}goods where goods_id='$id'");
          if(submit()){
			$json['error'] = "0";
		    if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
              $json['error'] = "管理密码不正确";
	          echo json($json);
	          exit;
		    }
			$arr['goods_name'] = $_POST['goods_name'];
			$arr['goods_desc'] = $_POST['goods_desc'];
			$arr['goods_thumb'] = implode(",",$_POST['thumb_list']);
            $arr['shop_price'] = $_POST['shop_price'];
			$arr['market_price'] = $_POST['market_price'];
			$arr['commission'] = $_POST['commission'];
            $arr['balance'] = $_POST['balance'];			
			$arr['margin'] = $_POST['margin'];
			$arr['stock'] = $_POST['stock'];
		    if($id){
			  $json['message'] = '编辑商品成功';
              $this->mysql->update("{$this->pre}goods",$arr,"goods_id='$id'"); 
		    }else{
			  $arr['addtime'] = time();
		 	  $json['message'] = '添加商品成功';
              $this->mysql->insert("{$this->pre}goods",$arr); 
		    }
			$json['url'] = Purl("?mod=admin&act=shop&get=goods");
			echo json($json);
			exit;
	      }
	      if($_GET['re']=='edit'){
		     if(!is_array($this->add)) $this->message('go_back','对不起，该商品信息不存在');	
			 $this->add['goods_thumb'] = explode(',',$this->add['goods_thumb']);
		  }
	    }
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			 $arr['error'] = "管理密码不正确";
			 echo json($arr);
			 exit;
		  }
		  if($_GET['do']=='ischeck'){
			 $arr['ischeck'] = $_GET['val'];
			 $this->mysql->update("{$this->pre}goods",$arr,"goods_id='{$_GET['id']}'");
			 $arr['error'] = 0;
	         echo json($arr);
		  }elseif($_GET['do']=='remove'){
			 $this->mysql->delete("{$this->pre}goods","goods_id='{$_GET['id']}'");
		     $arr['error'] = 0;
	         echo json($arr);
		  }
		  exit;
	    } 		
		
	  }
	  if($_GET['get']=='order'){
	    if($_GET['re']=='list'){
		  $where = "where id>0";
		  if($_GET['checked']>='0') $where .= " and checked='{$_GET['checked']}'";
		  if($_GET['orderid']) $where .= " and orderid like '{$_GET['orderid']}%'";
		  $this->t = _time('addtime',"and",'1');
		  $where .= $this->t['where'];	
		  if($_GET['id']) $where .= " and id='{$_GET['id']}'";
	      $this->pagetotal = $this->mysql->counts("select id from {$this->pre}order {$where}");
	      $this->pageclass($this->pagetotal);
	      $this->order = $this->mysql->getarr("select * from {$this->pre}order {$where} order by id desc {$this->page->limit}");
		  $this->express = _formatval(config::get("express"),'[<><请选择快递>]');
	    }
	    if($_GET['re']=='ajax'){
		  if($this->manager_class->password($_GET['repass'],$this->manager['salt'])!=$this->manager['password']){
			 $arr['error'] = "管理密码不正确";
			 echo json($arr);
			 exit;
		  }
		  if($_GET['do']=='backmoney'){
			 $order = $this->mysql->select_one("select * from {$this->pre}order where id='{$_GET['id']}'");
			 $arr['error'] = $this->turnorder($order,'4');
	         echo json($arr);
	      }
		  if($_GET['do']=='ordersend'){
			 $order = $this->mysql->select_one("select * from {$this->pre}order where id='{$_GET['id']}'");
			 $order['express'] = $_GET['express'];
			 $order['expressnumber'] = $_GET['expressnumber'];
			 if($order['express']==''||$order['expressnumber']==''){
			   $arr['error'] = '请正确填写快递信息';
			   echo json($arr);
			   exit;
			 }
			 $arr['error'] = $this->turnorder($order,'5');
	         echo json($arr);
	      }
		  if($_GET['do']=='nobackmoney'){
			 $order = $this->mysql->select_one("select * from {$this->pre}order where id='{$_GET['id']}'");
			 $order['messagea'] = $_GET['message'];
			 if($order['messagea']){
			   $arr['error'] = $this->turnorder($order,'1');
			 }else{
			   $arr['error'] = '请输入拒绝原因';	 
			 }
			 echo json($arr);
	      }
		  exit;
	    } 
	  }
	} 
}
?>