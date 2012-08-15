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
 * @subpackage plugins.file_manager
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

// Add routes
Dispatcher::addRoute(array(
	'/'.ADMIN_DIR_NAME.'/plugin/file_manager/'.PUBLIC_DIR_NAME         => 'plugin/file_manager/index',
	'/'.ADMIN_DIR_NAME.'/plugin/file_manager/'.PUBLIC_DIR_NAME.'/'     => 'plugin/file_manager/index',
	'/'.ADMIN_DIR_NAME.'/plugin/file_manager/'.PUBLIC_DIR_NAME.'/:any' => 'plugin/file_manager/index/$1'
));

// Add resources
Plugin::addJavascript('file_manager', 'fileuploader/fileuploader.js');
Plugin::addStylesheet('file_manager', 'fileuploader/fileuploader.css');

// Add controller
Plugin::addController('file_manager', 'file_manager', array('editor','developer','administrator'));

// Add navigation section
Plugin::addNav('Content', __('File manager'), 'plugin/file_manager', array('editor','developer','administrator'), 110);