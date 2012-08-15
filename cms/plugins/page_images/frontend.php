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

/**
 * PIFrontImages class
 */
class PIFrontImages
{
	public $page_id;
	
	public function __construct($page_id)
	{
		$this->page_id = $page_id;
	}
	
	static function find($args = array())
	{
		// Collect attributes...
		$where   = isset($args['where'])  ? $args['where']: '1=1';
		$order   = isset($args['order'])  ? $args['order']: 'position ASC';
		$offset  = isset($args['offset']) ? $args['offset']: 0;
		$limit   = isset($args['limit'])  ? $args['limit']: 0;
		
		// Prepare query parts
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
		$where = "$where ORDER BY $order $limit_string";
        
		return Record::findAllFrom('PIImage', $where);
	}
	
	public function findAll($args = array())
	{
		$args['where'] = isset($args['where']) ? $args['where']: 'page_id="'. (int)$this->page_id .'"';
		
		return self::find($args);
	}
	
	public function findOne($offset = 0)
	{
		$items = self::find(array('where' => 'page_id="'. (int)$this->page_id .'"', 'limit' => 1, 'offset' => $offset));
		
		if ( !empty($items))
			return array_pop($items);
		else
			return false;
	}
	
	public function count()
	{
		return Record::countFrom('PIImage', 'page_id = "'. (int)$this->page_id .'"');
	}
} // end PIFrontImages class


/**
 * Handler for frontpage_found observer event
 */
function pi_frontpage_found_handler($page)
{
	if (is_object($page) && !isset($page->images))
		$page->images = new PIFrontImages($page->id);
}


// Add class PIImage to autoloader
AutoLoader::addFile('PIImage', PLUGINS_ROOT . '/page_images/PIImage.php');

// Observe
Observer::observe('frontpage_found', 'pi_frontpage_found_handler');
Observer::observe('frontpage_byslug_found', 'pi_frontpage_found_handler');
Observer::observe('frontpage_children_found', 'pi_frontpage_found_handler');