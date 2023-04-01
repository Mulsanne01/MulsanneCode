<?php
/*  
 * Implementation of the SAX xml reader  
 * created by simonyi peng 2010-04-28
 */  
	  
	//-------------------------------------------------------------------------------
	function initPublicKey($path)
	{
	  //file operate	  
	  $f = fopen($path,'r');
	  $data = fread($f,4096);
	  $arr = explode(PHP_EOL,$data);
	  $publickey = explode('=',$arr[0]);
	  $modulus = explode('=',$arr[1]);
	  $val[0] = $publickey[1];
	  $val[1] = $modulus[1];
	  return $val;
	  
	}

?>