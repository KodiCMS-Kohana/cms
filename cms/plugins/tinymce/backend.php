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

// Add resources
Plugin::addJavascript('tinymce', 'tinymce/tiny_mce.js');

// Add tinymce to filter's list
Filter::add('tinymce', 'tinymce/filter_tinymce.php');

// Add controller
Plugin::addController('tinymce', 'tinymce', array('editor','developer','administrator'));

// tinymce_layout_backend_head_handler
function tinymce_layout_backend_head_handler()
{
	$ini_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.'tinymce'.DIRECTORY_SEPARATOR.'tinymce.ini';
	$ini = parse_ini_file($ini_file, false);
	
	$buttons_sets_panel1 = explode(',', $ini['panel_buttons1']);
	$buttons_sets_panel2 = explode(',', $ini['panel_buttons2']);
	$buttons_sets_panel3 = explode(',', $ini['panel_buttons3']);
	$buttons_sets_panel4 = explode(',', $ini['panel_buttons4']);
	
	$buttons_sets = array(
		$buttons_sets_panel1,
		$buttons_sets_panel2,
		$buttons_sets_panel3,
		$buttons_sets_panel4
	);
	
	$json_file = PLUGINS_ROOT.DIRECTORY_SEPARATOR.'tinymce'.DIRECTORY_SEPARATOR.'panel_buttons.json';
	$json_content = file_get_contents($json_file);
	$selected_buttons = json_decode($json_content);
	
	$html = '<script>';
	
	foreach ($buttons_sets as $i => $buttons_set)
	{
		$out = array();
		
		$separator = true;
		
		foreach ($buttons_set as $button)
		{			
			if ($button == '|' && !empty($out) && $separator === true)
			{
				$out[] = '|';
				$separator = false;
			}
			elseif (in_array($button, $selected_buttons))
			{
				$out[] = $button;
				$separator = true;
			}
		}
		
		$html .= 'cms.plugins.tinymce.settings.theme_advanced_buttons'.($i+1).' = "'.rtrim(join(',', $out), ',|').'";';
		
		
		if (($stylesheet = Plugin::getSetting('stylesheet', 'tinymce')) && !empty($stylesheet))
			$html .= 'cms.plugins.tinymce.settings.content_css = "'. $stylesheet .'";';
	}
	
	echo($html.'</script>');
} // end tinymce_layout_backend_head_handler

Observer::observe('layout_backend_head', 'tinymce_layout_backend_head_handler');