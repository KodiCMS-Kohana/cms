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
 * class PagePart
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since Flexo version 0.1
 */
class PagePart extends Record
{
    const TABLE_NAME = 'page_part';
	
    const PART_NOT_PROTECTED = 0;
	const PART_PROTECTED = 1;
    
    public $name = 'body';
    public $filter_id = '';
    public $page_id = 0;
    public $content = '';
    public $content_html = '';
	public $is_protected = 0;
    
    public function beforeSave()
    {
		if (!empty($this->permissions))
			$this->savePermissions($this->permissions);
		
		unset($this->permissions);
		
        // apply filter to save is generated result in the database
        if ( ! empty($this->filter_id))
		{
			if (Filter::get($this->filter_id))
				$this->content_html = Filter::get($this->filter_id)->apply($this->content);
			
			foreach(Observer::getObserverList('filter_content') as $callback)
				$this->content_html = call_user_func($callback, $this->content_html);
		}
        else
            $this->content_html = $this->content;
        
        return true;
    }
    
    public static function findByPageId($id)
    {
        return self::findAllFrom('PagePart', 'page_id='.(int)$id.' ORDER BY id');
    }
    
    public static function deleteByPageId($page_id)
    {
		$parts = self::findAllFrom('PagePart', 'page_id = ' . $page_id);
		
		$result = true;
		
		foreach ($parts as $part)
		{
			if ( !$part->delete())
				$result = false;
		}
		
		return $result;
    }

} // end PagePart class