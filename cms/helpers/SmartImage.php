<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage helpers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */
 

/**
 * Smart, easy and simple Image Manipulation
 * 
 * @author Alessandro Coscia, Milano, Italy, php_staff@yahoo.it
 * @author Maslakov Alexander, Ukraine, jmas.ukraine@gmail.com
 * http://www.codicefacile.it/smartimage
 * @copyright LGPL
 * @version 0.8.9
 *
 */

class SmartImage
{
	/**
	 * Source file (path)
	 */
    private $src;

	/**
	 * GD image's identifier
	 */
    private $gdID;

	/**
	 * Image info
	 */
    private $info;

	/**
	 * Initialize image
	 *
	 * @param string $src
	 * @return void
	 */
    public function __construct($src)
	{
        // set data
        $this->src = $src;
        $this->info = getimagesize($src);
        $this->oldImages = array();
        // open file
        if ($this->info[2] == 2)
		{
			$this->gdID = @imagecreatefromjpeg($this->src);
		}
        elseif ($this->info[2] == 1 )
		{
			$this->gdID = @imagecreatefromgif($this->src);
		}
        elseif ($this->info[2] == 3)
		{
			$this->gdID = @imagecreatefrompng($this->src);
		}
    }
	
	/**
	 * Free memory
	 */
    public function __destroy()
	{
        @imagedestroy($this->gdID);
    }

	/**
	 * Resize an image
	 *
	 * @param integer $w
	 * @param integer $h
	 * @param boolean $cutImage
	 * @return boolean Everything is ok?
	 */
    public function resize($width, $height, $cutImage = false)
	{
        if ($cutImage)
			return $this->resizeWithCut($width, $height);
        else
			return $this->resizeNormal($width, $height);
    }

	/**
	 * Resize an image without cutting it, only do resize
	 * saving proportions and adapt it to the smaller dimension
	 *
	 * @param integer $w
	 * @param integer $h
	 */
    private function resizeNormal($w, $h)
	{
        // set data
        $size = $this->info;
        $im = $this->gdID;
        $newwidth = $size[0];
        $newheight = $size[1];
		
        if( $newwidth > $w )
		{
            $newheight = ($w / $newwidth) * $newheight;
            $newwidth = $w;
        }
		
        if( $newheight > $h )
		{
            $newwidth = ($h / $newheight) * $newwidth;
            $newheight = $h;
        }
		
        // optimize convertion with GD2
        if( ($this->GDVersion() == 2) and ($size[2] != 1) )
		{
            $new = imagecreatetruecolor($newwidth, $newheight);
			
			if ($this->info[2] == 3)
			{
				imagealphablending($new, false);
				imagesavealpha($new, true);
			}
			elseif ($this->info[2] == 1)
			{
				$black = imagecolorallocate($new, 0, 0, 0);
				// Make the background transparent
				imagecolortransparent($new, $black);
			}
			
            $result = imagecopyresampled($new, $im, 0, 0, 0, 0, $newwidth, $newheight, $size[0], $size[1]);
        }
        else
		{
            $new = imagecreate($newwidth, $newheight);
			
			if ($this->info[2] == 3)
			{
				imagealphablending($new, false);
				imagesavealpha($new, true);
			}
			elseif ($this->info[2] == 1)
			{
				$black = imagecolorallocate($new, 0, 0, 0);
				// Make the background transparent
				imagecolortransparent($new, $black);
			}
			
            $result = imagecopyresized($new, $im, 0, 0, 0, 0, $newwidth, $newheight, $size[0], $size[1]);
        }
		
        @imagedestroy($im);
        $this->gdID = $new;
        $this->updateInfo();
		
        return $result;
    }

	/**
	 * Resize an image cutting it, do resize
	 * adapt it resizing and cutting the original image
	 *
	 * @param integer $w
	 * @param integer $h
	 */
    private function resizeWithCut($w, $h)
	{
        // set data
        $size = $this->info;
        $im = $this->gdID;

        if( $size[0]>$w or $size[1]>$h )
		{
            $centerX = $size[0]/2;
            $centerY = $size[1]/2;

            $propX = $w / $size[0];
            $propY = $h / $size[1];

            if( $propX < $propY )
			{
                $src_x = $centerX - ($w*(1/$propY)/2);
                $src_y = 0;
                $src_w = ceil($w * 1/$propY);
                $src_h = $size[1];
            }
            else
			{
                $src_x = 0;
                $src_y = $centerY - ($h*(1/$propX)/2);
                $src_w = $size[0];
                $src_h = ceil($h * 1/$propX);
            }

            // Resize
            if( ($this->GDVersion() == 2) AND ($size[2] != 1) )
			{
                $new = imagecreatetruecolor($w, $h);
				
				if ($this->info[2] == 3)
				{
					imagealphablending($new, false);
					imagesavealpha($new, true);
				}
				elseif ($this->info[2] == 1)
				{
					$black = imagecolorallocate($new, 0, 0, 0);
					// Make the background transparent
					imagecolortransparent($new, $black);
				}
				
                $result = imagecopyresampled($new, $im, 0, 0, $src_x, $src_y, $w, $h, $src_w, $src_h);
            }
            else
			{
                $new = imagecreate($w, $h);
				
				if ($this->info[2] == 3)
				{
					imagealphablending($new, false);
					imagesavealpha($new, true);
				}
				elseif ($this->info[2] == 1)
				{
					$black = imagecolorallocate($new, 0, 0, 0);
					// Make the background transparent
					imagecolortransparent($new, $black);
				}
				
                $result = imagecopyresized($new, $im, 0, 0, $src_x, $src_y, $w, $h, $src_w, $src_h);
            }
            
            @imagedestroy($im);
        }
        else
		{
            $new = $im;
        }

        $this->gdID = $new;
        $this->updateInfo();

        return $result;
    }
	
	public function resizeSmart( $max_width, $max_height, $method = 'crop', $bgColour = null )
	{
		// get the current dimensions of the image
		$src_width = $this->getWidth();
		$src_height = $this->getHeight();
	 
		// if either max_width or max_height are 0 or null then calculate it proportionally
		if( !$max_width )
		{
			$max_width = $src_width / ($src_height / $max_height);
		}
		elseif( !$max_height )
		{
			$max_height = $src_height / ($src_width / $max_width);
		}
	 
		// initialize some variables
		$thumb_x = $thumb_y = 0;	// offset into thumbination image
	 
		// if scaling the image calculate the dest width and height
		$dx = $src_width / $max_width;
		$dy = $src_height / $max_height;
		
		if( $method == 'scale' )
		{
			$d = max($dx, $dy);
		}
		// otherwise assume cropping image
		else
		{
			$d = min($dx, $dy);
		}
		
		$new_width = $src_width / $d;
		$new_height = $src_height / $d;
		
		// sanity check to make sure neither is zero
		$new_width = max(1, $new_width);
		$new_height = max(1, $new_height);
	 
		$thumb_width = min($max_width, $new_width);
		$thumb_height = min($max_height, $new_height);
	 
		// if bgColour is an array of rgb values, then we will always create a thumbnail image of exactly
		// max_width x max_height
		if( is_array($bgColour) )
		{
			$thumb_width = $max_width;
			$thumb_height = $max_height;
			$thumb_x = ($thumb_width - $new_width) / 2;
			$thumb_y = ($thumb_height - $new_height) / 2;
		}
		else
		{
			$thumb_x = ($thumb_width - $new_width) / 2;
			$thumb_y = ($thumb_height - $new_height) / 2;
		}
	 
		// create a new image to hold the thumbnail
		$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
		
		if ($this->info[2] == 3)
		{
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
		}
		elseif ($this->info[2] == 1)
		{
			$black = imagecolorallocate($thumb, 0, 0, 0);
			// Make the background transparent
			imagecolortransparent($thumb, $black);
		}
		
		if( is_array($bgColour) )
		{
			$bg = imagecolorallocate($thumb, $bgColour[0], $bgColour[1], $bgColour[2]);
			imagefill($thumb,0,0,$bg);
		}
		
		// copy from the source to the thumbnail
		imagecopyresampled($thumb, $this->gdID, $thumb_x, $thumb_y, 0, 0, $new_width, $new_height, $src_width, $src_height);
		$this->gdID = $thumb;
	}
	
	public function resizeToHeight( $height )
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		
		$this->resize($width, $height);
	}


	public function resizeToWidth( $width )
	{
		$ratio 	= $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		
		$this->resize($width, $height);
	}
    
	/**
	 * Add a Water Mark to the image
	 * (filigrana)
	 *
	 * @param string $from
	 * @param string $waterMark
	 */
    public function addWaterMarkImage($waterMark, $opacity = 35, $x = 5, $y = 5)
	{
        // set data
        $size = $this->info;
        $im = $this->gdID;

        // set WaterMark's data    
        $waterMarkSM = new SmartImage($waterMark);
        $imWM = $waterMarkSM->getGDid();
    
        // Add it!
        imageCopyMerge($im, $imWM, $x, $y, 0, 0, imagesx($imWM), imagesy($imWM), $opacity);
        $waterMarkSM->close();
    
        $this->gdID = $im;
    }

	/**
	 * Show Image
	 *
	 * @param integer 0-100 $jpegQuality
	 */
    public function display($jpegQuality = 100)
	{
        $this->outPutImage(null, $jpegQuality);
    }

	/**
	 * Save the image on filesystem
	 *
	 * @param string $destination
	 * @param integer 0-100 $jpegQuality
	 */
    public function save($destination, $jpegQuality = 100)
	{
        $this->outPutImage($destination, $jpegQuality);
    }

	/**
	 * Output an image
	 * accessible throught printImage() and saveImage()
	 *
	 * @param unknown_type $dest
	 * @param unknown_type $jpegQuality
	 */
    private function outPutImage($dest = null, $jpegQuality = 100)
	{
        $size = $this->info;
        $im = $this->gdID;
        // select mime
        if (!empty($dest))
            list($size['mime'], $size[2]) = $this->findMime($dest);
        
        // if output set headers
        if (empty($dest))
			header('Content-Type: ' . $size['mime']);
        
        // output image
        if( $size[2] == 2 )         @imagejpeg($im, $dest, $jpegQuality);
        elseif ( $size[2] == 1 )    ($dest === null ? @imagegif($im): @imagegif($im, $dest));
        elseif ( $size[2] == 3 )    @imagepng($im, $dest);
    }

	/**
	 * Mime type for a file
	 *
	 * @param string $file
	 * @return string
	 */
    private function findMime($file)
	{
        $file .= ".";
        $bit = explode(".", $file);
        $ext = $bit[count($bit)-2];
        if ($ext == 'jpg')         return array('image/jpeg', 2);
        elseif ($ext == 'jpeg')    return array('image/jpeg', 2);
        elseif ($ext == 'gif')     return array('image/gif', 1);
        elseif ($ext == 'png')     return array('image/png', 3);
        else                       array('image/jpeg', 2);
    }

	/**
	 * Get the GD identifier
	 *
	 * @return GD Identifier
	 */
    public function getGDid()
	{
        return $this->gdID;
    }
    
	/**
	 * Set GD identifier
	 *
	 * @param GD Identifier $value
	 */
    public function setGDid($value)
	{
        $this->gdID = $value;
    }
    
	/**
	 * Update info class's variable
	 */
    private function updateInfo()
	{
        $info = $this->info;
        $im = $this->gdID;
        
        $info[0] = imagesx($im);
        $info[1] = imagesy($im);
        
        $this->info = $info;
    }

	/**
	 * GD Version
	 * @return integer
	 */
    public function GDVersion()
	{
        if ( !in_array('gd', get_loaded_extensions()) )
			return 0;
        elseif ( $this->isGD2supported() )
			return 2;
        else
			return 1;
    }

	/**
	 * Find GD Version
	 * @return mixed
	 */
    private function isGD2supported()
	{
        global $GD2;
		
        if( isset($GD2) and $GD2 )
			return $GD2;
        else
		{
            $php_ver_arr = explode('.', phpversion());
            $php_ver = intval($php_ver_arr[0])*100+intval($php_ver_arr[1]);

            if( $php_ver < 402 )
			{
				// PHP <= 4.1.x
                $GD2 = in_array('imagegd2',get_extension_funcs("gd"));
            }
            elseif( $php_ver < 403 )
			{
				// PHP = 4.2.x
                $im = @imagecreatetruecolor(10, 10);
                if( $im )
				{
                    $GD2 = 1;
                    @imagedestroy($im);
                }
                else $GD2 = 0;
            }
            else
			{
				// PHP = 4.3.x
                $GD2 = function_exists('imagecreatetruecolor');
            }
        }

        return $GD2;
    }
	
	public function getWidth()
	{
		return imagesx($this->gdID);
	}

	public function getHeight()
	{
		return imagesy($this->gdID);
	}
} // end class SmartImage