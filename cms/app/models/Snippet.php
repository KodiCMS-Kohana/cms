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
 * @subpackage models
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */
 
/**
 * Class Layout
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since Flexo version 0.1
 */
class Snippet
{
	public $name;
	
	private $_content;
	private $file;
	
	public function __construct( $snippet_name = '' )
	{
		$this->name = $snippet_name;
		
		$this->file = SNIPPETS_ROOT.DIRECTORY_SEPARATOR.$snippet_name.'.'.SNIPPETS_EXT;
	}
	
	public function __get($key)
	{
		if (method_exists($this, $key))
			return $this->{$key}();
	}
	
	public function __set($key, $value)
	{
		if ($key == 'content')
			$this->_content = $value;
	}
	
    public static function findAll()
    {
		$layouts = array();
		
		$SNIPPETS_dir = opendir(SNIPPETS_ROOT);
		
		while ($snippet_file = readdir($SNIPPETS_dir))
		{
			if (is_file(SNIPPETS_ROOT.DIRECTORY_SEPARATOR.$snippet_file) && substr($snippet_file, -strlen(SNIPPETS_EXT)) == SNIPPETS_EXT)
			{
				$layouts[] = new Layout(substr($snippet_file, 0, strrpos($snippet_file, '.'.SNIPPETS_EXT)));
			}
		}
		
		return $layouts;
    }
	
	public function content()
	{
		if ($this->_content === null)
		{
			if (file_exists($this->file))
				$this->_content = file_get_contents($this->file);
			else
				$this->_content = '';
		}
		
		return $this->_content;
	}

	public function save()
	{
		$new_file = SNIPPETS_ROOT.DIRECTORY_SEPARATOR.$this->name.'.'.SNIPPETS_EXT;
		
		if ( $new_file != $this->file )
		{			
			rename($this->file, $new_file);
			$this->file = $new_file;
		}
		
		$f = fopen($this->file, 'w+');
		$result = fwrite($f, $this->_content);
		fclose($f);
		
		return ($result === false ? false : true);
	}
	
	public function delete()
	{		
		return unlink($this->file);
	}
	
	public function isExists()
	{
		return file_exists($this->file);
	}
    
} // end Layout class