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
<h1><?php echo __('Snippets'); ?></h1> 

<div id="snippetMap" class="box map">
	
	<div id="snippetMapActions" class="box-actions">
		<button rel="<?php echo get_url('snippet/add'); ?>" id="snippetMapAddButton" class="button-image"><img src="images/add.png" /> <?php echo __('Add snippet'); ?></button>
	</div>
	
	<div class="map-header">
		<span class="name"><?php echo __('Snippet name'); ?></span>
		<span class="direction"><?php echo __('Direction'); ?></span>
		<span class="actions"><?php echo __('Actions'); ?></span>
	</div>
	
	<ul id="snippetMapItems" class="map-items">
	
		<?php foreach ($snippets as $snippet): ?>
		<li>
			<div class="item">
				<span class="name"><img src="images/snippet.png" /> <a href="<?php echo get_url('snippet/edit/'.$snippet->name); ?>"><?php echo $snippet->name; ?></a></span>
				<span class="direction"><?php echo '/'. SNIPPETS_DIR_NAME .'/'. $snippet->name .'.'. SNIPPETS_EXT; ?></span>
				<span class="actions">
					<button class="item-remove-button" rel="<?php echo get_url('snippet/delete/'. $snippet->name); ?>" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></button>
				</span>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	
</div><!--/#snippetMap-->