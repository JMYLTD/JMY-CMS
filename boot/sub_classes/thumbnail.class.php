<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

class Thumbnail {
    var $quality=75;
    var $output_format='jpg';
    var $jpeg_progressive=0;
    var $allow_enlarge=false;
    var $img_watermark='';
    /**
    *@access public
    *@var string [OPTIONAL] set watermark vertical position, TOP | CENTER | BOTTOM
    **/
    var $img_watermark_Valing='CENTER';
    /**
    *@access public
    *@var string [OPTIONAL] set watermark horizonatal position, LEFT | CENTER | RIGHT
    **/
    var $img_watermark_Haling='CENTER';

    /**
    *@access public
    *@var string [OPTIONAL] set watermark text [RECOMENDED ONLY WITH GD 2 ]
    **/
    var $txt_watermark='';
    /**
    *@access public
    *@var string [OPTIONAL] set watermark text color , RGB Hexadecimal[RECOMENDED ONLY WITH GD 2 ]
    **/
    var $txt_watermark_color='FFFFFF';
    /**
    *@access public
    *@var integer [OPTIONAL] set watermark text font: 1,2,3,4,5
    **/
    var $txt_watermark_font=1;
    /**
    *@access public
    *@var string  [OPTIONAL] set watermark text vertical position, TOP | CENTER | BOTTOM
    **/
    var $txt_watermark_Valing='TOP';
    /**
    *@access public
    *@var string [OPTIONAL] set watermark text horizonatal position, LEFT | CENTER | RIGHT
    **/
    var $txt_watermark_Haling='LEFT';
    /**
    *@access public
    *@var integer [OPTIONAL] set watermark text horizonatal margin in pixels
    **/
    var $txt_watermark_Hmargin=10;
    /**
    *@access public
    *@var integer [OPTIONAL] set watermark text vertical margin in pixels
    **/
    var $txt_watermark_Vmargin=10;
    /**
    *@access public
    *@var bool [OPTIONAL] set resample algorithm to bicubic
    **/
    var $bicubic_resample=false;

    /**
    *@access public
    *@var string [OPTIONAL] set maximun memory usage, default 8 MB ('8M'). (use '16M' for big images)
    **/
    var $memory_limit='31111111111111111111111111111111112M';

    /**
    *@access public
    *@var string [OPTIONAL] set maximun execution time, default 30 seconds ('30'). (use '60' for big images)
    **/
    var $max_execution_time='99999999';

    /**
    *@access public
    *@var string  errors mensage
    **/
    var $error_msg='';


    /**
    *@access private
    *@var mixed images
    **/
    var $img;

    /**
    *open source image
    *@access public
    *@param string filename of the source image file
    *@return boolean
    **/
	function Thumbnail($imgfile) 	{
    	$img_info =  getimagesize( $imgfile );
        //detect image format
        switch( $img_info[2] ){
	    		case 2:
	    			//JPEG
	    			$this->img["format"]="JPEG";
	    			$this->img["src"] = ImageCreateFromJPEG ($imgfile);
        		break;
	    		case 3:
	    			//PNG
	    			$this->img["format"]="PNG";
	    			$this->img["src"] = ImageCreateFromPNG ($imgfile);
                    $this->img["des"] =  $this->img["src"];
  	    		break;
	    		default:
	                $this->error_msg="Not Supported File";
	 				return false;
	    }//case
		$this->img["x"] = $img_info[0];  //original dimensions
		$this->img["y"] = $img_info[1];
        $this->img["x_thumb"]= $this->img["x"];  //thumbnail dimensions
        $this->img["y_thumb"]= $this->img["y"];
        $this->img["des"] =  $this->img["src"]; // thumbnail = original
		return true;
	}

    /**
    *set height for thumbnail
    *@access public
    *@param integer height
    *@return boolean
    **/
	function size_height($size=100) {
            //height
            $this->img["y_thumb"]=$size;
            if ($this->allow_enlarge==true) {
        	    $this->img["y_thumb"]=$size;
            } else {
                if ($size < ($this->img["y"])) {
                    $this->img["y_thumb"]=$size;
                } else {
                    $this->img["y_thumb"]=$this->img["y"];
                }

            }
            if ($this->img["y"]>0) {
                $this->img["x_thumb"] = ($this->img["y_thumb"]/$this->img["y"])*$this->img["x"];
            } else {
                $this->error_msg="Invalid size : Y";
                return false;
            }
	}

    /**
    *set width for thumbnail
    *@access public
    *@param integer width
    *@return boolean
    **/
	function size_width($size=100)  {
    	//width
            if ($this->allow_enlarge==true) {
        	    $this->img["x_thumb"]=$size;
            } else {
                if ( $size < ($this->img["x"])) {
                    $this->img["x_thumb"]=$size;
                } else {
                    $this->img["x_thumb"]=$this->img["x"];
                }

            }
            if ($this->img["x"]>0) {
                $this->img["y_thumb"] = ($this->img["x_thumb"]/$this->img["x"])*$this->img["y"];
            } else {
                $this->error_msg="Invalid size : x";
                return false;
            }
    }

    /**
    *set the biggest width or height for thumbnail
    *@access public
    *@param integer width or height
    *@return boolean
    **/
	function size_auto($size=100)   {
		//size
		$this->size_width($size);
		$this->size_height((($size/100)*75));
		 /**
		if ($this->img["x"]>=$this->img["y"]) {
    		$this->size_width($size);
		} else {
    		$this->size_height($size);
 		}
		**/
	}


    /**
    *set the biggest width and height for thumbnail
    *@access public
    *@param integer width
    *@param integer height
    *@return boolean
    **/
	function size($size_x,$size_y)   {
		//size
		if ( (($this->img["x"])/$size_x) >=  (($this->img["y"])/$size_y) ) {
    		$this->size_width($size_x);
		} else {
    		$this->size_height($size_y);
 		}
	}


    /**
    *show your thumbnail, output image and headers
    *@access public
    *@return void
    **/
	function show() {
		//show thumb
		Header("Content-Type: image/".$this->img["format"]);
        if ($this->output_format=="PNG") { //PNG
    	imagePNG($this->img["des"]);
    	} else {
            imageinterlace( $this->img["des"], $this->jpeg_progressive);
         	imageJPEG($this->img["des"],"",$this->quality);
        }
	}

    /**
    *return the result thumbnail
    *@access public
    *@return mixed
    **/
	function dump() {
		//dump thumb
		return $this->img["des"];
	}

    /**
    *save your thumbnail to file
    *@access public
    *@param string output file name
    *@return boolean
    **/
	function save($save="")	{
		//save thumb
	    if (empty($save)) {
            $this->error_msg='Not Save File';
            return false;
        }
        if ($this->output_format=="PNG") { //PNG
    	    imagePNG($this->img["des"],"$save");
    	} else {
           imageinterlace( $this->img["des"], $this->jpeg_progressive);
           imageJPEG($this->img["des"],"$save",$this->quality);
        }
        return true;
	}

    /**
    *generate image
    *@access public
    *@return boolean
    **/
    function process () {

        ini_set('memory_limit',$this->memory_limit);
        ini_set('max_execution_time',$this->max_execution_time);

        $X_des =$this->img["x_thumb"];
        $Y_des =$this->img["y_thumb"];

   		//if ($this->checkgd2()) {
        $gd_version=$this->gdVersion();
        if ($gd_version>=2) {
        //if (false) {

        		$this->img["des"] = ImageCreateTrueColor($X_des,$Y_des);

                if ($this->txt_watermark!='' ) {
                    sscanf($this->txt_watermark_color, "%2x%2x%2x", $red, $green, $blue);
                    $txt_color=imageColorAllocate($this->img["des"] ,$red, $green, $blue);
                }

                if (!$this->bicubic_resample) {
                    imagecopyresampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $X_des, $Y_des, $this->img["x"], $this->img["y"]);
                } else {
                    $this->imageCopyResampleBicubic($this->img["des"], $this->img["src"], 0, 0, 0, 0, $X_des, $Y_des, $this->img["x"], $this->img["y"]);
                }

                if ($this->img_watermark!='' && file_exists($this->img_watermark)) {
                    $this->img["watermark"]=ImageCreateFromPNG ($this->img_watermark);
                    $this->img["x_watermark"] =imagesx($this->img["watermark"]);
                    $this->img["y_watermark"] =imagesy($this->img["watermark"]);
                    imagecopyresampled ($this->img["des"], $this->img["watermark"], $this->calc_position_H (), $this->calc_position_V (), 0, 0, $this->img["x_watermark"], $this->img["y_watermark"],$this->img["x_watermark"], $this->img["y_watermark"]);
                }

                if ($this->txt_watermark!='' ) {
                    imagestring ( $this->img["des"], $this->txt_watermark_font, $this->calc_text_position_H() , $this->calc_text_position_V(), $this->txt_watermark,$txt_color);
                }
        } else {
         		$this->img["des"] = ImageCreate($X_des,$Y_des);
                if ($this->txt_watermark!='' ) {
                    sscanf($this->txt_watermark_color, "%2x%2x%2x", $red, $green, $blue);
                    $txt_color=imageColorAllocate($this->img["des"] ,$red, $green, $blue);
                }
                // pre copy image, allocating color of water mark, GD < 2 can't resample colors
                if ($this->img_watermark!='' && file_exists($this->img_watermark)) {
                    $this->img["watermark"]=ImageCreateFromPNG ($this->img_watermark);
                    $this->img["x_watermark"] =imagesx($this->img["watermark"]);
                    $this->img["y_watermark"] =imagesy($this->img["watermark"]);
                    imagecopy ($this->img["des"], $this->img["watermark"], $this->calc_position_H (), $this->calc_position_V (), 0, 0, $this->img["x_watermark"], $this->img["y_watermark"]);
                }
                imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $X_des, $Y_des, $this->img["x"], $this->img["y"]);
                @imagecopy ($this->img["des"], $this->img["watermark"], $this->calc_position_H (), $this->calc_position_V (), 0, 0, $this->img["x_watermark"], $this->img["y_watermark"]);
                if ($this->txt_watermark!='' ) {
                    imagestring ( $this->img["des"], $this->txt_watermark_font, $this->calc_text_position_H() , $this->calc_text_position_V(), $this->txt_watermark, $txt_color); // $this->txt_watermark_color);
                }
        }
        $this->img["src"]=$this->img["des"];
        $this->img["x"]= $this->img["x_thumb"];  
        $this->img["y"]= $this->img["y_thumb"];

    }

    /**
    *Calculate JPEG quality factor for a specific size in bytes
    *@access public
    *@param integer maximun file size in bytes
    **/
    function CalculateQFactor($size)  {
        //based on: JPEGReducer class version 1,  25 November 2004,  Author: huda m elmatsani, Email :justhuda@netscape.net

        //calculate size of each image. 75%, 50%, and 25% quality
        ob_start(); imagejpeg($this->img["des"],'',75);  $buffer = ob_get_contents(); ob_end_clean();
        $size75 = mb_strlen($buffer);
        ob_start(); imagejpeg($this->img["des"],'',50);  $buffer = ob_get_contents(); ob_end_clean();
        $size50 = mb_strlen($buffer);
        ob_start(); imagejpeg($this->img["des"],'',25);  $buffer = ob_get_contents(); ob_end_clean();
        $size25 = mb_strlen($buffer);

        //calculate gradient of size reduction by quality
        $mgrad1 = 25/($size50-$size25);
        $mgrad2 = 25/($size75-$size50);
        $mgrad3 = 50/($size75-$size25);
        $mgrad  = ($mgrad1+$mgrad2+$mgrad3)/3;
        //result of approx. quality factor for expected size
        $q_factor=round($mgrad*($size-$size50)+50);

        if ($q_factor<25) {
            $this->quality=25;
        } elseif ($q_factor>100) {
            $this->quality=100;
        } else {
            $this->quality=$q_factor;
        }
    }

    /**
    *@access private
    *@return integer
    **/
    function calc_text_position_H () {
        $W_mark =  imagefontwidth  ($this->txt_watermark_font)*mb_strlen($this->txt_watermark);
        $W = $this->img["x_thumb"];
        switch ($this->txt_watermark_Haling) {
             case 'CENTER':
                 $x = $W/2-$W_mark/2;
                 break;
             case 'RIGHT':
                 $x = $W-$W_mark-($this->txt_watermark_Hmargin);
                 break;
             default:
             case 'LEFT':
                $x = 0+($this->txt_watermark_Hmargin);
                 break;
         }
         return $x;
    }

    /**
    *@access private
    *@return integer
    **/
    function calc_text_position_V () {
        $H_mark = imagefontheight ($this->txt_watermark_font);
        $H = $this->img["y_thumb"];
        switch ($this->txt_watermark_Valing) {
             case 'CENTER':
                 $y = $H/2-$H_mark/2;
                 break;
             case 'BOTTOM':
                 $y = $H-$H_mark-($this->txt_watermark_Vmargin);
                 break;
             default:
             case 'TOP':
                $y = 0+($this->txt_watermark_Vmargin);
                 break;
         }
         return $y;
    }

    /**
    *@access private
    *@return integer
    **/
    function calc_position_H () {
        $W_mark = $this->img["x_watermark"];
        $W = $this->img["x_thumb"];
        switch ($this->img_watermark_Haling) {
             case 'CENTER':
                 $x = $W/2-$W_mark/2;
                 break;
             case 'RIGHT':
                 $x = $W-$W_mark;
                 break;
             default:
             case 'LEFT':
                $x = 0;
                 break;
         }
         return $x;
    }

    /**
    *@access private
    *@return integer
    **/
    function calc_position_V () {
        $H_mark = $this->img["y_watermark"];
        $H = $this->img["y_thumb"];
        switch ($this->img_watermark_Valing) {
             case 'CENTER':
                 $y = $H/2-$H_mark/2;
                 break;
             case 'BOTTOM':
                 $y = $H-$H_mark;
                 break;
             default:
             case 'TOP':
                $y = 0;
                 break;
         }
         return $y;
    }


    /**
    *@access private
    *@return boolean
    **/
    function checkgd2(){
        // TEST the GD version
          if (extension_loaded('gd2') && function_exists('imagecreatetruecolor')) {
            return false;
          } else {
            return true;
          }
    }


    /**
    * Get which version of GD is installed, if any.
    *
    * Returns the version (1 or 2) of the GD extension.
    */
    function gdVersion($user_ver = 0)
    {
       if (! extension_loaded('gd')) { return; }
       static $gd_ver = 0;
       // Just accept the specified setting if it's 1.
       if ($user_ver == 1) { $gd_ver = 1; return 1; }
       // Use the static variable if function was called previously.
       if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
       // Use the gd_info() function if possible.
       if (function_exists('gd_info')) {
           $ver_info = gd_info();
           preg_match('/\d/', $ver_info['GD Version'], $match);
           $gd_ver = $match[0];
           return $match[0];
       }
       // If phpinfo() is disabled use a specified / fail-safe choice...
       if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
           if ($user_ver == 2) {
               $gd_ver = 2;
               return 2;
           } else {
               $gd_ver = 1;
               return 1;
           }
       }
       // ...otherwise use phpinfo().
       ob_start();
       phpinfo(8);
       $info = ob_get_contents();
       ob_end_clean();
       $info = stristr($info, 'gd version');
       preg_match('/\d/', $info, $match);
       $gd_ver = $match[0];
       return $match[0];
    } // End gdVersion()

    function imageCopyResampleBicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
      $scaleX = ($src_w - 1) / $dst_w;
      $scaleY = ($src_h - 1) / $dst_h;
      $scaleX2 = $scaleX / 2.0;
      $scaleY2 = $scaleY / 2.0;
      $tc = imageistruecolor($src_img);

      for ($y = $src_y; $y < $src_y + $dst_h; $y++) {
       $sY  = $y * $scaleY;
       $siY  = (int) $sY;
       $siY2 = (int) $sY + $scaleY2;

       for ($x = $src_x; $x < $src_x + $dst_w; $x++) {
         $sX  = $x * $scaleX;
         $siX  = (int) $sX;
         $siX2 = (int) $sX + $scaleX2;

         if ($tc) {
           $c1 = imagecolorat($src_img, $siX, $siY2);
           $c2 = imagecolorat($src_img, $siX, $siY);
           $c3 = imagecolorat($src_img, $siX2, $siY2);
           $c4 = imagecolorat($src_img, $siX2, $siY);

           $r = (($c1 + $c2 + $c3 + $c4) >> 2) & 0xFF0000;
           $g = ((($c1 & 0xFF00) + ($c2 & 0xFF00) + ($c3 & 0xFF00) + ($c4 & 0xFF00)) >> 2) & 0xFF00;
           $b = ((($c1 & 0xFF)  + ($c2 & 0xFF)  + ($c3 & 0xFF)  + ($c4 & 0xFF))  >> 2);

           imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
         }  else {
           $c1 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY2));
           $c2 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY));
           $c3 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY2));
           $c4 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY));

           $r = ($c1['red']  + $c2['red']  + $c3['red']  + $c4['red']  ) << 14;
           $g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) << 6;
           $b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'] ) >> 2;

           imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
         }
       }
      }
    }

    /**
    *generate a unique filename in a directory like prefix_filename_randon.ext
    *@access public
    *@param string path of the destination dir. Example '/img'
    *@param string name of the file to save. Example 'my_foto.jpg'
    *@param string [optional] prefix of the name Example 'picture'
    *@return string full path of the file to save. Exmaple '/img/picture_my_foto_94949.jpg'
    **/
    function unique_filename ( $archive_dir , $filename , $file_prefix='') {
    	// checkemaos if file exists
    	$extension= mb_strtolower( mb_substr( strrchr($filename, ".") ,1) );
    	$name=str_replace(".".$extension,'',$filename);

    	//	only alfanumerics characters
    	$string_tmp = $name;
    	$name='';
    	while ($string_tmp!='') {
    		$character=mb_substr ($string_tmp, 0, 1);
    		$string_tmp=mb_substr ($string_tmp, 1);
    		if (eregStrt("[abcdefghijklmnopqrstuvwxyz0-9]", $character)) {
    			$name=$name.$character;
    		} else {
    			$name=$name.'_';
    		}

    	}

    	$destination = $file_prefix."_".$name.".".$extension;

    	while (file_exists($archive_dir."/".$destination)) {
    		// if exist, add a random number to the file name
    		srand((double)microtime()*1000000); // random number inizializzation
    		$destination = $file_prefix."_".$name."_".rand(0,999999999).".".$extension;
    	}


    	return ($destination);
    }



        /**
        * NOT USED : to do: mezclar imagenes a tamaсo original, preservar canal alpha y redimensionar
        * Merge multiple images and keep transparency
        * $i is and array of the images to be merged:
        * $i[1] will be overlayed over $i[0]
        * $i[2] will be overlayed over that
        * @param mixed
        * @retrun mixed the function returns the resulting image ready for saving
        **/
        function imagemergealpha($i) {

         //create a new image
         $s = imagecreatetruecolor(imagesx($i[0]),imagesy($i[1]));

         //merge all images
         imagealphablending($s,true);
         $z = $i;
         while($d = each($z)) {
          imagecopy($s,$d[1],0,0,0,0,imagesx($d[1]),imagesy($d[1]));
         }

         //restore the transparency
         imagealphablending($s,false);
         $w = imagesx($s);
         $h = imagesy($s);
         for($x=0;$x<$w;$x++) {
          for($y=0;$y<$h;$y++) {
           $c = imagecolorat($s,$x,$y);
           $c = imagecolorsforindex($s,$c);
           $z = $i;
           $t = 0;
           while($d = each($z)) {
           $ta = imagecolorat($d[1],$x,$y);
           $ta = imagecolorsforindex($d[1],$ta);
           $t += 127-$ta['alpha'];
           }
           $t = ($t > 127) ? 127 : $t;
           $t = 127-$t;
           $c = imagecolorallocatealpha($s,$c['red'],$c['green'],$c['blue'],$t);
           imagesetpixel($s,$x,$y,$c);
          }
         }
         imagesavealpha($s,true);
         return $s;
        }
}
?>