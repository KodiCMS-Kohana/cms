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
 * @subpackage plugins.page_images
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class PIImage extends Record
{
	const TABLE_NAME = 'pi_image';
	
	function beforeInsert()
	{
		$this->created_date = date('Y-m-d H:i:s');
		
		return true;
	}
	
	function beforeDelete()
	{			
		$file_path = PUBLIC_ROOT.DIRECTORY_SEPARATOR.'page_images'.DIRECTORY_SEPARATOR.$this->file_name;
		
		unlink($file_path);
		
		return true;
	}
	
	public function url( $width = null, $height = null )
	{
		$file_name = $this->file_name;
		
		if ( ($width !== null || $height !== null) && Plugin::isEnabled('image_resizing') )
		{
			$file_name = ( $width ? $width : 0 ) . 'x' . ( $height ? $height : 0 ) . '-' . $file_name;
		}
		
		return PUBLIC_URL . 'page_images/' . $file_name;
	}
	
	public function size()
	{
		$file_path = PUBLIC_ROOT.DIRECTORY_SEPARATOR.'page_images'.DIRECTORY_SEPARATOR.$this->file_name;
		
		$width = 0;
		$height = 0;
		
		if (file_exists($file_path))
		{
			if ($size = getimagesize($file_path))
			{
				list($width, $height) = $size;
			}
		}
		
		return (object) array(
			'width' => $width,
			'height' => $height
		);
	}
} // end class PIImage