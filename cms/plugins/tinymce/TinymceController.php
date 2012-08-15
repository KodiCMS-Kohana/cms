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
 * @subpackage plugins.tinymce
 *
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

class TinymceController extends PluginController
{
	public function __construct()
	{
		$this->setLayout('backend');
	}
	
	public function links_json()
	{
		$root = Record::findByIdFrom('Page', 1);
        $childs_content = $this->_getChildsContent( 1, 0 );
		
		echo('var tinyMCELinkList = new Array(["'. $root->title .'", "'. BASE_URL .'"]'. $childs_content .');');
	}
	
	private function _getChildsContent( $parent_id, $level )
	{
		$content = '';
		
		$childrens = Page::childrenOf($parent_id);
        
        foreach( $childrens as $index => $child )
        {
			$content .= ', ["'. str_repeat('—', $level+1) .' '. $child->title .'", "'. CMS_URL . ($uri = $child->getUri()) . (strstr($uri, '.') === false ? URL_SUFFIX : '') .'"]';
            $content .= $this->_getChildsContent( $child->id, $level+1 );
        }
        
        return $content;
	}
	
	public function settings()
	{
		$json_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.'tinymce'.DIRECTORY_SEPARATOR.'panel_buttons.json';
		
		if (!empty($_POST))
		{
			$buttons = !empty($_POST['buttons']) ? (array)$_POST['buttons']: array();
			
			$settings = $_POST['setting'];
			
			$stylesheet = isset($settings['stylesheet']) ? $settings['stylesheet']: '';
			
			Plugin::setSetting('stylesheet', $stylesheet, 'tinymce');
			
			if (file_put_contents($json_file, json_encode($buttons)))
				Flash::set('success', __('Settings has been saved!'));
			else
				Flash::set('error', __('Settings has not been saved!'));
			
			redirect(get_url('plugin/tinymce/settings'));
		}
		
		$settings = Plugin::getAllSettings('tinymce');
		
		$json_content = file_get_contents($json_file);
		$selected_buttons = json_decode($json_content);
		
		$ini_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.'tinymce'.DIRECTORY_SEPARATOR.'tinymce.ini';
		$ini = parse_ini_file($ini_file, false);
		
		$buttons_sets_panel1 = $ini['panel_buttons1'];
		$buttons_sets_panel2 = $ini['panel_buttons2'];
		$buttons_sets_panel3 = $ini['panel_buttons3'];
		$buttons_sets_panel4 = $ini['panel_buttons4'];
		
		$buttons_sets = array(
			!empty($buttons_sets_panel1) ? explode(',', $buttons_sets_panel1): array(),
			!empty($buttons_sets_panel2) ? explode(',', $buttons_sets_panel2): array(),
			!empty($buttons_sets_panel3) ? explode(',', $buttons_sets_panel3): array(),
			!empty($buttons_sets_panel4) ? explode(',', $buttons_sets_panel4): array()
		);
		
		$this->display('tinymce/views/settings', array(
			'buttons_sets'     => $buttons_sets,
			'selected_buttons' => $selected_buttons,
			'setting'          => $settings
		));
	}
	
} // end class TinymceController