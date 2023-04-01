<?php
class ValidationCode{   
   private $width,$height,$codenum;   
   private $checkcode;     //产生的验证码   
   private $checkimage;    //验证码图片   
   private $disturbColor = ''; //干扰像素   
  
   function __construct($width='80',$height='20',$codenum='4'){   
      $this->width=$width;   
      $this->height=$height;   
      $this->codenum=$codenum;   
   }   
   function outImg(){   
      $this->outFileHeader();   
      $this->createCode();   
      $this->createImage();   
      $this->setDisturbColor();   
      $this->writeCheckCodeToImage();   
      imagepng($this->checkimage);   
      imagedestroy($this->checkimage);   
   }
   
   function outphone($phone='0375-8888873'){   
      $this->outFileHeader();   
      $this->createphone();  
      $this->setDisturbColor();
      $this->writePhoneToImage($phone);   
      imagepng($this->checkimage);   
      imagedestroy($this->checkimage);   
   } 
   private function createphone(){   
      $this->checkimage = @imagecreate($this->width,$this->height);   
      $back = imagecolorallocate($this->checkimage,255,255,255);   
      imagefilledrectangle($this->checkimage,0,0,$this->width,$this->height,$back); // 白色底     
   }
   private function writePhoneToImage($phone){   
     $color = imagecolorallocate($this->checkimage, rand(0,255), rand(0,128), rand(0,255));   
     imagestring($this->checkimage,5,5,5,$phone,$color);    
   }  
   
   
   private function outFileHeader(){   
      header ("Content-type: image/png");   
   }  
   private function createCode(){   
      $this->checkcode = substr(md5(rand()),0,$this->codenum);   
      $_SESSION['seccode'] = $this->checkcode;
   }   
   private function createImage(){   
      $this->checkimage = @imagecreate($this->width,$this->height);   
      $back = imagecolorallocate($this->checkimage,255,255,255);   
      $border = imagecolorallocate($this->checkimage, rand(0,255), rand(0,255), rand(0,255)); 
      imagefilledrectangle($this->checkimage,0,0,$this->width - 1,$this->height - 1,$back); // 白色底   
      imagerectangle($this->checkimage,0,0,$this->width - 1,$this->height - 1,$border);   // 黑色边框   
   }
   private function setDisturbColor(){   
      for($i=0;$i<=200;$i++){   
        $this->disturbColor = imagecolorallocate($this->checkimage, rand(0,255), rand(0,255), rand(0,255));   
        imagesetpixel($this->checkimage,rand(2,128),rand(2,38),$this->disturbColor);   
      }   
   }   
  
   private function writeCheckCodeToImage(){   
      for($i=0;$i<=$this->codenum;$i++){  
        $im   = $this->checkimage;
        $size = 17;
        $angle= 0;
        $px   = floor($this->width/$this->codenum)*$i+$this->width/16;
        $py   = rand($size+2,$this->height-2); 
        $font = PATH.'app/yahei.ttf';
        $text = $this->checkcode[$i];
        $color= imagecolorallocate ($this->checkimage, rand(0,255), rand(0,128), rand(0,255));
        imagettftext($im, $size, $angle, $px, $py, $color, $font, $text);

	  
	  
	  
/*         $bg_color = imagecolorallocate ($this->checkimage, rand(0,255), rand(0,128), rand(0,255));   
         $x = floor($this->width/$this->codenum)*$i+$this->width/16;   
         $y = rand(0,$this->height-15);   
         imagechar ($this->checkimage, rand(5,8), $x, $y, $this->checkcode[$i], $bg_color);   */
      }   
   }   
   function __destruct(){   
      unset($this->width,$this->height,$this->codenum);   
   }   
}
?>