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
<h1><?php echo __('Pages'); ?></h1>
					
<div id="pageMap" class="box map">
	<div class="box-actions">
		<p id="pageMapSearch"><input id="pageMapSearchField" type="search" name="query" placeholder="<?php echo __('Find page'); ?>" /></p>
		
		<button id="pageMapReorderButton" class="page-map-reorder-button button-image"><img src="images/reorder.png" /> <?php echo __('Reorder'); ?></button>
		<button id="pageMapCopyButton" class="page-map-copy-button button-image"><img src="images/copy.png" /> <?php echo __('Copy'); ?></button>
	</div>
	
	<div id="pageMapHeader" class="map-header">
		<span class="title"><?php echo __('Page'); ?></span>
		<span class="status"><?php echo __('Status'); ?></span>
		<span class="date"><?php echo __('Date'); ?></span>
		<span class="actions"><?php echo __('Actions'); ?></span>
	</div>
	
	<ul id="pageMapItems" class="map-items">
		<li rel="<?php echo $root->id; ?>" class="map-level-0">
			<div class="item">
				<span class="title">
					<?php if( ! AuthUser::hasPermission($root->getPermissions()) ): ?>
					<img src="images/page-text-locked.png" title="<?php echo('You do not have permission to access the requested page!'); ?>" />
					<em title="/"><?php echo $root->title; ?></em>
					<?php else: ?>
					<img src="images/page-text.png" />
					<a href="<?php echo get_url('page/edit/1'); ?>" title="/"><?php echo $root->title; ?></a>
					<?php endif; ?>
					<a class="item-preview" href="<?php echo(CMS_URL); ?>" target="_blank"><img src="images/newwindow.png" title="<?php echo __('View page'); ?>" /></a>
				</span>
				<span class="date"><?php echo date('Y-m-d', strtotime($root->published_on)); ?></span>
				<span class="status"><em class="item-status-published"><?php echo __('Published'); ?></em></span>
				<span class="actions">
					<button rel="<?php echo get_url('page/add/'.$root->id); ?>" class="item-add-button" title="<?php echo __('Add child page'); ?>"><img src="images/add.png" /></button>
					<button disabled><img src="images/remove.png" /></button>
				</span>
			</div>
			
			<?php echo $content_children; ?>
		</li>
	</ul><!--/#pageMapItems-->
	
	<ul id="pageMapSearchItems" class="map-items"><!--x--></ul>
	
</div>