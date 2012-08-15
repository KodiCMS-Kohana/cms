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
	<a href="<?php echo get_url('snippet'); ?>"><?php echo __('Snippets'); ?></a> &rarr;
	<?php echo __(ucfirst($action).' snippet'); ?>
</h1>

<form id="snippetEditForm" action="<?php echo $action=='edit' ? get_url('snippet/edit/'. $snippet->name): get_url('snippet/add/' . $snippet->name); ?>" method="post">
	
	<div id="snippetEdit" class="box">
		<p id="snippetEditName">
			<label><?php echo __('Snippet name'); ?></label>
			<span><input id="snippetEditNameField" class="input-text" type="text" name="snippet[name]" value="<?php echo htmlspecialchars($snippet->name, ENT_QUOTES); ?>" size="255" maxlength="255" tabindex="1" /></span>
		</p>
		
		<p id="snippetEditContent">
			<textarea id="snippetEditContentField" name="snippet[content]" tabindex="7" spellcheck="false" wrap="off"><?php echo htmlentities($snippet->content, ENT_COMPAT, 'UTF-8'); ?></textarea>
		</p>
		
		<p class="box-buttons">
			<button type="submit" name="commit"><img src="images/check.png" /> <?php echo __('Save and Close'); ?></button>
			<button type="submit" name="continue"><img src="images/check.png" /> <?php echo __('Save and Continue editing'); ?></button>
			<?php echo __('or'); ?> <a href="<?php echo get_url('snippet'); ?>"><?php echo __('Cancel'); ?></a>
		</p>
	</div><!--/#snippetEdit-->
	
</form><!--/#snippetEditForm-->