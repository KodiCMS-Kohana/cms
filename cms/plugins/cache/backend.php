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
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

define('CACHE_FILE_EXT', 'php');
define('CACHE_DYNAMIC_ROOT', PLUGINS_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'dynamic');
define('CACHE_STATIC_ROOT', PLUGINS_ROOT.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'static');


/*
* Handler for page_delete observer event
*/
function cache_page_delete_handler($page)
{
	$uri = trim(CURRENT_URI, '/');
	
	$file_name = md5($uri) .'.'. CACHE_FILE_EXT;
	$file_path = CACHE_DYNAMIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
	
	if (file_exists($file_path))
		@unlink($file_path);
} // end cache_page_delete_handler


/*
* Handler for view_page_edit_options observer event
*/
function cache_view_page_edit_options_handler($page)
{
	if ( AuthUser::hasPermission(array('administrator','developer')) )
	{
		$page_caching = false;
	
		if (isset($page->id))
		{
			$conn = Record::getConnection();
			
			$sql = 'SELECT page_id FROM '.TABLE_PREFIX.'cache_page WHERE page_id=?';
			$sth = $conn->prepare($sql);
			$sth->execute(array($page->id));
			
			if ($sth->fetch())
				$page_caching = true;
		}
		else
		{
			$page_caching = true;
		}
		
		echo '<p id="CachePage"><input id="CachePageCheckbox" type="checkbox" name="cache[cache_page]" value="yes" '. ($page_caching === true ? 'checked': '') .' /> <label for="CachePageCheckbox">'.__('Cache this page').'</label><p>';
	}
} // end cache_view_page_edit_options_handler


/*
* Handler cache_delete_all_handler
*/
function cache_delete_all_handler($page)
{
	$dir = new DirectoryIterator(CACHE_DYNAMIC_ROOT);
			
	foreach ($dir as $file)
	{
		if (!$file->isDot() && $file->isFile())
			unlink($file->getPathname());
	}
	
	$dir = new DirectoryIterator(CACHE_STATIC_ROOT);
	
	foreach ($dir as $file)
	{
		if (!$file->isDot() && $file->isFile())
			unlink($file->getPathname());
	}	
} // end cache_delete_all_handler


function cache_page_after_save_handler($page)
{
	$conn = Record::getConnection();
	
	$uri = $page->getUri();
	
	if ($uri == '')
		$uri = '/';
	
	$file_name = md5($uri) .'.'. CACHE_FILE_EXT;
	
	if (isset($_POST['cache']['cache_page']) && $_POST['cache']['cache_page'] == 'yes')
	{		
		$sql = 'INSERT IGNORE '.TABLE_PREFIX.'cache_page(page_id, cache_id) VALUES(?, ?)';
		$sth = $conn->prepare($sql);
		$sth->execute(array($page->id, md5($uri)));
		
		$file_path = CACHE_STATIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
		
		// Remove cache
		$remove_static = Plugin::getSetting('cache_remove_static', 'cache');
		
		if ($remove_static == 'yes')
		{
			$dir = new DirectoryIterator(CACHE_STATIC_ROOT);
			
			foreach ($dir as $file)
			{
				if (!$file->isDot() && $file->isFile())
					unlink($file->getPathname());
			}
		}
		else
		{
			if (file_exists($file_path))
				unlink($file_path);
		}
	}
	else // remove page from cache
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'cache_page WHERE page_id=?';
		$sth = $conn->prepare($sql);
		$sth->execute(array($page->id));
	}

	$dynamic_file_path = CACHE_DYNAMIC_ROOT.DIRECTORY_SEPARATOR.$file_name;
	
	if (file_exists($dynamic_file_path))
		unlink($dynamic_file_path);	
} // end cache_page_after_save_handler


// Get settings
$cache_dynamic = Plugin::getSetting('cache_dynamic', 'cache');

// Observer
if ($cache_dynamic == 'yes')
{
	Observer::observe('page_edit_before_save', 'cache_page_delete_handler');
	Observer::observe('page_delete', 'cache_page_delete_handler');		
}

Observer::observe('view_page_edit_options', 'cache_view_page_edit_options_handler');

Observer::observe('page_add_after_save',    'cache_page_after_save_handler');
Observer::observe('page_edit_after_save',   'cache_page_after_save_handler');
Observer::observe('layout_after_edit',      'cache_delete_all_handler');
Observer::observe('snippet_after_edit',     'cache_delete_all_handler');

// Add controller
Plugin::addController('cache', 'cache', array('developer','administrator'));