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
 * @subpackage plugins.page_fields
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * PIFrontImages class
 */
class PFFrontField
{
	public $page_id;
	private static $cache = array();
	
	public function __construct($page_id)
	{
		$this->page_id = $page_id;
	}
	
	public function __get($name)
	{
		$cache_id = $this->page_id .':' . $name;
		
		if (!isset(self::$cache[$cache_id]))
		{
			$conn = Record::getConnection();
			self::$cache[$cache_id] = Record::findOneFrom('PFField', 'page_id = '. $conn->quote($this->page_id) .' AND name = '. $conn->quote($name));
		}
		
		return self::$cache[$cache_id];
	}
} // end PIFrontImages class


/**
 * Handler for frontpage_found observer event
 */
function pf_frontpage_found_handler($page)
{
	if (is_object($page) && !isset($page->fields))
		$page->fields = new PFFrontField($page->id);
}


// Add class PIImage to autoloader
AutoLoader::addFile('PFField', PLUGINS_ROOT . '/page_fields/PFField.php');

// Observe
Observer::observe('frontpage_found', 'pf_frontpage_found_handler');
Observer::observe('frontpage_byslug_found', 'pf_frontpage_found_handler');
Observer::observe('frontpage_children_found', 'pf_frontpage_found_handler');