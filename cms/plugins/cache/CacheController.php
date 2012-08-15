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

class CacheController extends PluginController
{
	public function __construct()
	{
		$this->setLayout('backend');
	}
	
	public function settings()
	{
		if (get_request_method() == 'POST')
		{
			$settings = array(
				'cache_dynamic'       => 'no',
				'cache_static'        => 'no',
				'cache_remove_static' => 'no',
				'cache_lifetime'            => 86400
			);
			
			if (isset($_POST['setting']) && is_array($_POST['setting']))
			{
				foreach ($_POST['setting'] as $key => $val)
					$settings[$key] = $val;
			}
			
			Plugin::setAllSettings($settings, 'cache');
			
			Flash::set('success', __('Settings has been saved!'));
			redirect(get_url('plugin/cache/settings'));
		}
		
		$this->display('cache/views/settings', array(
			'setting' => Plugin::getAllSettings('cache')
		));
	}
	
	public function remove_cache()
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
		
		Flash::set('success', __('All cache removed!'));
		redirect(get_url('plugin/cache/settings'));
	}
} // end class CacheController