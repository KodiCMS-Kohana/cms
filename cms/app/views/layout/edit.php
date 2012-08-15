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
 * @subpackage views
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */
 
?>
<h1>
	<a href="<?php echo get_url('layout'); ?>"><?php echo __('Layouts'); ?></a> &rarr;
	<?php echo __(ucfirst($action).' layout'); ?>
</h1>

<form id="layoutEditForm" action="<?php echo $action=='edit' ? get_url('layout/edit/'. $layout->name): get_url('layout/add/'); ?>" method="post">
	
	<div id="layoutEdit" class="box">
		<p id="layoutEditName">
			<label><?php echo __('Layout name'); ?></label>
			<span><input id="layoutEditNameField" class="input-text" type="text" name="layout[name]" value="<?php echo htmlspecialchars($layout->name, ENT_QUOTES); ?>" size="255" maxlength="255" tabindex="1" /></span>
		</p>
		
		<p id="layoutEditContent">
			<textarea id="layoutEditContentField" name="layout[content]" tabindex="7" spellcheck="false" wrap="off"><?php echo htmlentities($layout->content, ENT_COMPAT, 'UTF-8'); ?></textarea>
		</p>
		
		<p class="box-buttons">
			<button type="submit" name="commit"><img src="images/check.png" /> <?php echo __('Save and Close'); ?></button>
			<button type="submit" name="continue"><img src="images/check.png" /> <?php echo __('Save and Continue editing'); ?></button>
			<?php echo __('or'); ?> <a href="<?php echo get_url('layout'); ?>"><?php echo __('Cancel'); ?></a>
		</p>
	</div><!--/#layoutEdit-->
	
</form><!--/#layoutEditForm-->