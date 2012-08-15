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
 * Handler for page_edit_plugins observer event
 */
function pf_view_page_edit_plugins_handler($page)
{
	$page_id = empty($page->id) ? 0: (int)$page->id;
	
	$fields = array();
	
	if ($page_id === 0 && isset($page->parent_id))
	{
		$parent_id = $page->parent_id;
		$big_sister = Record::findOneFrom('Page', 'parent_id=? ORDER BY id DESC', array($parent_id));
		
		if ($big_sister)
		{
			$where = 'page_id="'. $big_sister->id .'"';
			$fields = Record::findAllFrom('PFField', $where);
			
			foreach ($fields as $field)
			{
				$field->id = null;
				$field->value = null;
			}
		}
	}
	else
	{
		$where = 'page_id="'. $page_id .'"';
		
		$fields = Record::findAllFrom('PFField', $where);
	}
	
	echo new View('../../'.PLUGINS_DIR_NAME.'/page_fields/views/backend_fields', array(
		'fields' 	=> $fields,
		'page_id'  	=> $page_id
	));
} // end pf_view_page_edit_plugins_handler


/**
 * Handler for page_add_after_save observer event
 */
function pf_page_edit_after_save_handler( $page )
{
	$data = empty($_POST['pf_fields']) ? array(): $_POST['pf_fields'];
	
	$fields = Record::findAllFrom('PFField', 'page_id="'.$page->id.'"');
	
	foreach ($fields as $field)
	{
		if (isset($data[$field->name]))
		{
			$field->value = $data[$field->name];
			$field->save();
			
			unset($data[$field->name]);
		}
		else
		{
			$field->delete();
		}
	}
	
	$data = array_reverse($data);
	
	foreach ($data as $name => $value)
	{
		if (!empty($name))
		{
			$field = new PFField();
			$field->name = $name;
			$field->value = $value;
			$field->page_id = $page->id;
			$field->save();
		}
	}
} // end pf_page_add_after_save_handler


/**
 * Handler for page_delete observer event
 */
function pf_page_delete_handler( $page )
{
	$page_id = empty($page->id) ? 0: (int)$page->id;
	
	$fields = Record::findAllFrom('PFField', 'page_id="'.$page_id.'"');
	
	foreach ($fields as $field)
		$field->delete();
} //end pf_page_delete_handler


// Observe
Observer::observe('view_page_edit_plugins', 'pf_view_page_edit_plugins_handler');
Observer::observe('page_edit_after_save',   'pf_page_edit_after_save_handler');
Observer::observe('page_add_after_save',    'pf_page_edit_after_save_handler');
Observer::observe('page_delete',            'pf_page_delete_handler');

// Autoload models
AutoLoader::addFile('PFField',   PLUGINS_ROOT.'/page_fields/PFField.php');

// Add controller to plugins stack
// Plugin::addController('page_fields', 'page_fields', array('editor','developer','administrator'));