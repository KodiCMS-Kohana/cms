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
<ul class="map-level-<?php echo $level; ?>">
	<?php foreach($childrens as $child): ?>
	<li rel="<?php echo $child->id; ?>" <?php if($child->is_expanded) echo('class="item-expanded"'); ?>>
		<div class="item">
			<?php if( $child->has_children ): ?>
			<span class="item-expander <?php if($child->is_expanded) echo('item-expander-expand'); ?>"><!--x--></span>
			<?php endif; ?>
			
			<span class="title">
				<?php if( ! AuthUser::hasPermission($child->getPermissions()) ): ?>
				<img src="images/page-text-locked.png" title="<?php echo('You do not have permission to access the requested page!'); ?>" />
				<em title="/<?php echo $child->getUri(); ?>"><?php echo $child->title; ?></em>
				<?php else: ?>
				<img src="images/page-text.png" />
				<a href="<?php echo get_url('page/edit/'.$child->id); ?>" title="/<?php echo $child->getUri(); ?>"><?php echo $child->title; ?></a>
				<?php endif; ?>				
				<?php if( !empty($child->behavior_id) ): ?> <i>(<?php echo Inflector::humanize($child->behavior_id); ?>)</i><?php endif; ?>
				<a class="item-preview" href="<?php echo(CMS_URL . ($uri = $child->getUri()) . (strstr($uri, '.') === false ? URL_SUFFIX : '')); ?>" target="_blank" title="<?php echo __('View page'); ?>"><img src="images/newwindow.png" /></a>
			</span>
			<span class="date"><?php echo date('Y-m-d', strtotime($child->published_on)); ?></span>
			<span class="status">
				<?php switch ($child->status_id):
					case Page::STATUS_DRAFT:    echo('<em class="item-status-draft">'.__('Draft').'</em>');       break;
					case Page::STATUS_REVIEWED: echo('<em class="item-status-reviewed">'.__('Reviewed').'</em>'); break;
					case Page::STATUS_HIDDEN:   echo('<em class="item-status-hidden">'.__('Hidden').'</em>');     break;
					case Page::STATUS_PUBLISHED:
						if( strtotime($child->published_on) > time() )
							echo('<em class="item-status-pending">'.__('Pending').'</em>');
						else
							echo('<em class="item-status-published">'.__('Published').'</em>');
					break;
				endswitch; ?>
			</span>
			<span class="actions">
				<button rel="<?php echo get_url('page/add/'.$child->id); ?>" class="item-add-button" title="<?php echo __('Add child page'); ?>"><img src="images/add.png" /></button>
				<?php if( ! AuthUser::hasPermission($child->getPermissions()) ): ?>
				<button disabled><img src="images/remove.png" /></button>
				<?php else: ?>
				<button rel="<?php echo get_url('page/delete/'.$child->id); ?>" class="item-remove-button" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></button>
				<?php endif; ?>
			</span>
		</div>
		
		<?php if( $child->is_expanded ) echo($child->children_rows); ?>
	</li>
	<?php endforeach; ?>
</ul>