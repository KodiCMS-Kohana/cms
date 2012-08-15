<?php if(!defined('CMS_ROOT')) die;

/**
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 *
 * This file is part of Frog CMS.
 *
 * Frog CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Frog CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Frog CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package flexo
 * @subpackage captcha
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2010
 */

class CaptchaController extends PluginController
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setLayout('backend');
	}

	public function index()
	{
		$this->settings();
	}

	public function settings()
	{
		if( !empty($_POST['captcha_settings']) )
		{
			if( Plugin::setAllSettings($_POST['captcha_settings'], 'captcha') )
			{
				Flash::set('success', __('Settings has been saved!'));
			}
			else
			{
				Flash::set('success', __('Settings do not saved!'));
			}
			
			redirect(get_url('setting/plugin'));
		}
		
		$this->display('../plugins/captcha/views/settings', array(
			'settings' => Plugin::getAllSettings('captcha')
		));
	}
}
