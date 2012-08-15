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
 * @subpackage plugins.image_resizing
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class ImageResizingController extends Controller
{
	public function resize()
	{
		$args = array();
		
		foreach (func_get_args() as $i => $arg)
		{
			if ($arg != '..' && $arg != '.')
				$args[] = $arg;
		}
		
		$file_name = array_pop($args);
		
		if ( ! preg_match('/^([0-9]+)x([0-9]+)\-([^\.]*)\.(png|jpg|jpeg|gif)$/', $file_name, $matches))
		{
			$this->imageNotFound();
		}
		else
		{
			$resize_width  = $matches[1];
			$resize_height = $matches[2];
			
			$file_ext  = $matches[4];
			$file_name = $matches[3].'.'.$file_ext;
			
			$file_root = PUBLIC_ROOT. (!empty($args) ? DIRECTORY_SEPARATOR. join(DIRECTORY_SEPARATOR, $args): '');
			$file_path = $file_root.DIRECTORY_SEPARATOR.$file_name;
			
			if ( ! file_exists($file_path))
			{
				$this->imageNotFound();
			}
			else
			{
				if( strstr($_SERVER["HTTP_USER_AGENT"], 'MSIE') === false )
				{
					header("Cache-Control: private, max-age=10800, pre-check=10800");
					header("Pragma: private");
					header("Expires: " . date(DATE_RFC822, strtotime(" 2 day")));
					
					// Cache image
					$file_mtime = filemtime($file_path);
					
					if( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $file_mtime) )
					{
						// send the last mod time of the file back
						header('Last-Modified: '. gmdate('D, d M Y H:i:s', $file_mtime) .' GMT', true, 304);
						exit;
					}
					
					header('Last-Modified: '. gmdate('D, d M Y H:i:s', $file_mtime) .' GMT', true, 302);
				}
				
				use_helper('SmartImage');
				
				$image = new SmartImage($file_path);
				
				$image->resizeSmart($resize_width, $resize_height);
				
				$quality = (int) Plugin::getSetting('quality', 'image_resizing');
				
				$cache_sizes = Plugin::getSetting('cache_sizes', 'image_resizing');
				
				if (!empty($cache_sizes))
					$cache_sizes = unserialize($cache_sizes);
				
				if (is_array($cache_sizes) && in_array($resize_width.'x'.$resize_height, $cache_sizes))
				{
					$file_thumb_path = $file_root .DIRECTORY_SEPARATOR. $resize_width.'x'.$resize_height.'-'.$file_name;
					$image->save($file_thumb_path, $quality);
				}
				
				$image->display($quality);
			}
		}
	}
	
	private function imageNotFound()
	{
		header('Content-type: image/jpeg');
		readfile(PLUGINS_ROOT.'/image_resizing/image_not_found.jpg');
		die;
	}
} // end class ImageResizingController


// Add ftontend route
Dispatcher::addRoute(array(
	'/'.PUBLIC_DIR_NAME.'/:any' => 'image_resizing/resize/$1'
));