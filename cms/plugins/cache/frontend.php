<?php if (!defined('CMS_ROOT')) die;

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
 * @subpackage plugins.cache
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

define('CACHE_FILE_EXT', 'php');
define('CACHE_DYNAMIC_ROOT', PLUGINS_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'dynamic');
define('CACHE_STATIC_ROOT', PLUGINS_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'static');


/*
* Handler for frontpage_byslug_found observer event
*/
function cache_frontpage_byslug_found_handler($page)
{
	$uri = trim(CURRENT_URI, '/');
	
	if ($uri == '')
		$uri = '/';
	
	$file_name = md5($uri) .'.'. CACHE_FILE_EXT;
	$file_path = CACHE_DYNAMIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
	
	$serialized_content = '<'.'?php die; ?>' . serialize($page);
	
	file_put_contents($file_path, $serialized_content);
} // end cache_frontpage_byslug_found_handler


/*
* Handler for frontpage_byslug_before_found observer event
*/
function cache_frontpage_byslug_before_found_handler($page, $slug, $parent)
{
	if ($page)
		$uri = trim($page->url, '/');
	else
		$uri = '/';
	
	$file_name = md5($uri) .'.'. CACHE_FILE_EXT;
	$file_path = CACHE_DYNAMIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
	
	if (file_exists($file_path))
	{
		$cache_lifetime = (int) Plugin::getSetting('cache_lifetime', 'cache');
		
		if (time() - filemtime($file_path) < $cache_lifetime)
		{
			// Read cache file, unserialize and execute
			$serialized_content = substr(file_get_contents($file_path), 13);
			
			$page = unserialize($serialized_content);
		}
	}
} // end cache_frontpage_byslug_before_found_handler


/*
* Handler for page_requested observer event
*/
function cache_frontpage_requested_handler($uri)
{
	$uri = trim(CURRENT_URI, '/');
	
	if ($uri == '')
		$uri = '/';
	
	$conn = Record::getConnection();
	
	$sql = 'SELECT page_id FROM '.TABLE_PREFIX.'cache_page WHERE cache_id=?';
	$sth = $conn->prepare($sql);
	$sth->execute(array(md5($uri)));
	
	if ($sth->fetch())
	{
		$file_name = md5($uri) .'.'. CACHE_FILE_EXT;
		$file_path = CACHE_STATIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
		
		if (file_exists($file_path))
		{
			$cache_lifetime = (int) Plugin::getSetting('cache_lifetime', 'cache');
			
			if (time() - filemtime($file_path) < $cache_lifetime)
			{
				$page_content = substr(file_get_contents($file_path), 13);
				
				echo $page_content;
				
				echo '<!-- from cache. time: '. execution_time() .'-->';
				die;
			}
		}
	}
} // end cache_page_requested_handler


/*
* Handler for frontpage_found observer event
*/
function cache_frontpage_found_handler($page)
{
	$uri = trim(CURRENT_URI, '/');
	
	if ($uri == '')
		$uri = '/';
	
	$conn = Record::getConnection();
	
	$sql = 'SELECT page_id FROM '.TABLE_PREFIX.'cache_page WHERE page_id=?';
	$sth = $conn->prepare($sql);
	$sth->execute(array($page->id));
	
	if ($sth->fetch())
	{
		$file_name = md5($uri) .'.'. CACHE_FILE_EXT;
		$file_path = CACHE_STATIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
		
		ob_start();
		$page->display();
		$page_content = ob_get_contents();
		ob_end_flush();
		
		$page_content = '<'.'?php die; ?>' . $page_content;
		
		file_put_contents($file_path, $page_content);
		
		die;
	}
} // end cache_frontpage_found_handler


// Get cache type
$cache_dynamic = Plugin::getSetting('cache_dynamic', 'cache');
$cache_static = Plugin::getSetting('cache_static', 'cache');

// Observer
if ($cache_dynamic == 'yes')
{
	Observer::observe('frontpage_byslug_before_found', 'cache_frontpage_byslug_before_found_handler');
	Observer::observe('frontpage_byslug_found', 'cache_frontpage_byslug_found_handler');
}

if ($cache_static == 'yes')
{
	Observer::observe('frontpage_requested', 'cache_frontpage_requested_handler');
	Observer::observe('frontpage_found', 'cache_frontpage_found_handler');
}