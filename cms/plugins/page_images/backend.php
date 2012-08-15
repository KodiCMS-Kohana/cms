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
 * Handler for page_edit_plugins observer event
 */
function pi_view_page_edit_plugins_handler($page)
{
	$page_id = empty($page->id) ? 0: (int)$page->id;
	
	$where = 'page_id="'. $page_id .'" ORDER BY position ASC';
	
	$images = Record::findAllFrom('PIImage', $where);
	
	echo new View('../../'.PLUGINS_DIR_NAME.'/page_images/views/backend_images', array(
		'images' 	=> $images,
		'page_id'  	=> $page_id
	));
} // end pi_view_page_edit_plugins_handler


/**
 * Handler for page_add_after_save observer event
 */
function pi_page_add_after_save_handler( $page )
{
	$images = Record::findAllFrom('PIImage', 'page_id="0"');

	foreach ($images as $image)
	{
		$image->page_id = $page->id;
		$image->save();
	}
    
    pi_page_edit_after_save_handler($page);
} // end pi_page_add_after_save_handler


/**
 * Handler for pi_page_edit_after_save_handler observer event
 */
function pi_page_edit_after_save_handler( $page )
{
    if (!isset($_POST['pi_description'])) {
        return;
    }
    foreach ($_POST['pi_description'] as $id=>$description) {
        $image = Record::findByIdFrom('PIImage', $id);
        $image->description = $description;
        $image->save();
    }
} // end pi_page_edit_after_save_handler


/**
 * Handler for page_delete observer event
 */
function pi_page_delete_handler( $page )
{
	$page_id = empty($page->id) ? 0: (int)$page->id;
	
	$images = Record::findAllFrom('PIImage', 'page_id="'.$page_id.'"');
	
	foreach ($images as $image)
		$image->delete();
} //end pi_page_delete_handler


// Observe
Observer::observe('view_page_edit_plugins', 'pi_view_page_edit_plugins_handler');
Observer::observe('page_add_after_save',    'pi_page_add_after_save_handler');
Observer::observe('page_edit_after_save',   'pi_page_edit_after_save_handler');
Observer::observe('page_delete',            'pi_page_delete_handler');

// Autoload models
AutoLoader::addFile('PIImage',   PLUGINS_ROOT.'/page_images/PIImage.php');

// Add controller to plugins stack
Plugin::addController('page_images', 'page_images', array('editor','developer','administrator'));