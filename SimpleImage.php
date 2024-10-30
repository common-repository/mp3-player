<?php

    /**
    * This is a image-script. Code is taken from different souces on the internet
    * To use you must accept their licens 
    * SimpleImage is modified by Simon Hansen to use crop scale
    */

    /**
    * File: SimpleImage.php
    * 
    * Author: Simon Jarvis
    * Copyright: 2006 Simon Jarvis
    * Date: 08/11/06
    * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
    * 
    * This program is free software; you can redistribute it and/or 
    * modify it under the terms of the GNU General Public License 
    * as published by the Free Software Foundation; either version 2 
    * of the License, or (at your option) any later version.
    * 
    * This program is distributed in the hope that it will be useful, 
    * but WITHOUT ANY WARRANTY; without even the implied warranty of 
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
    * GNU General Public License for more details: 
    * http://www.gnu.org/licenses/gpl.html
    *
    * 
    * 
    **/


if(!class_exists('SimpleImage')){
	
	class SimpleImage {

		public $image;
        public $image_type;
        public $cacheDir;
        public $cacheDirUrl;
        
        function load($filename) {
			
			$image_info = getimagesize($filename);
			$this->image_type = $image_info[2];
           
            if( $this->image_type == IMAGETYPE_JPEG ) {
            
				$this->image = imagecreatefromjpeg($filename);
            
                } elseif( $this->image_type == IMAGETYPE_GIF ) {
				
					$this->image = imagecreatefromgif($filename);
            
                } elseif( $this->image_type == IMAGETYPE_PNG ) {
           
                    $this->image = imagecreatefrompng($filename);
           
                }
            }
            
		function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
        
			if( $image_type == IMAGETYPE_JPEG ) {
            
				imagejpeg($this->image,$filename,$compression);
                
			} elseif( $image_type == IMAGETYPE_GIF ) {
                 
                imagegif($this->image,$filename);         
                
            } elseif( $image_type == IMAGETYPE_PNG ) {
                 
                imagepng($this->image,$filename);
            }
                   
            if( $permissions != null) {
                
               chmod($filename,$permissions);
            
              }
            
            }
            
		function output($image_type=IMAGETYPE_JPEG) {
        
			if( $image_type == IMAGETYPE_JPEG ) {
        
				imagejpeg($this->image);
        
			} elseif( $image_type == IMAGETYPE_GIF ) {
        
				imagegif($this->image);         
        
			} elseif( $image_type == IMAGETYPE_PNG ) {
        
                    imagepng($this->image);
        
                }   
            }

		function getWidth() {
        
			return imagesx($this->image);
        
        }
        
        
        function getHeight() {
        
			return imagesy($this->image);
			
		}
            
		function resizeToHeight($height) {
        
            $ratio = $height / $this->getHeight();
            $width = $this->getWidth() * $ratio;
            $this->resize($width,$height);
        
        }
        
        function resizeToWidth($width) {
        
            $ratio = $width / $this->getWidth();
            $height = $this->getheight() * $ratio;
            $this->resize($width,$height);
        
        }
        
        function scale($scale) {
        
            $width = $this->getWidth() * $scale/100;
            $height = $this->getheight() * $scale/100; 
            $this->resize($width,$height);
        
        }
            
            
        /**
         * crop-scale resize
		 *
		 * */
            
		function resize($width,$height) {

			$new_height=$height;
            $new_width=$width;
            $image=$this->image;
                
            // Get original width and height
            $width = imagesx ($image);
            $height = imagesy ($image);
            $origin_x = 0;
            $origin_y = 0;

            // generate new w/h if not provided
            if ($new_width && !$new_height) {
                
				$new_height = floor ($height * ($new_width / $width));
                
			} else if ($new_height && !$new_width) {
                
                $new_width = floor ($width * ($new_height / $height));
                
            }


            // create a new true color image
            $canvas = imagecreatetruecolor ($new_width, $new_height);

            $src_x = $src_y = 0;
            $src_w = $width;
            $src_h = $height;

            $cmp_x = $width / $new_width;
            $cmp_y = $height / $new_height;

            // calculate x or y coordinate and width or height of source
            if ($cmp_x > $cmp_y) {

                    $src_w = round ($width / $cmp_x * $cmp_y);
                    $src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

                } else if ($cmp_y > $cmp_x) {

                        $src_h = round ($height / $cmp_y * $cmp_x);
                        $src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

                    }



                    imagecopyresampled($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);




                $this->image = $canvas;  


		}     




		function get($src,$width,$height,$shadowStyle='',$dontCache=0){
				
			//shadowStyle is deleted from script

            $cacheDir=$this->cacheDir;
            $caheUrlDir=$this->cacheDirUrl;
            $filename=$cacheDir.$width.'x'.$height.basename($src);


            // $dontCache=1; //for testing. Make sure its comment out on active site
            if(file_exists($src) and is_file($src)){


				if(!file_exists($filename) || $dontCache){
					
					echo 'FILES GENERATED'; 

                    $this->load($src);
                    $this->resize($width,$height);
                   
                    $this->save($filename);

                    imagedestroy($this->image);

				}
                    return $caheUrlDir.$width.'x'.$height.basename($src);
			}   

		}
	}

}
?>
