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
<h1><?php echo __('Users'); ?></h1> 

<div id="userMap" class="box map">
	
	<div id="userMapActions" class="box-actions">
		<button rel="<?php echo get_url('user/add'); ?>" id="userMapAddButton" class="button-image"><img src="images/add.png" /> <?php echo __('Add user'); ?></button>
	</div>
	
	<div class="map-header">
		<span class="name"><?php echo __('Name'); ?> / <?php echo __('Username'); ?></span>
		<span class="roles"><?php echo __('Roles'); ?></span>
		<span class="email"><?php echo __('E-mail'); ?></span>
		<span class="actions"><?php echo __('Actions'); ?></span>
	</div>
	
	<ul id="userMapItems" class="map-items">
	
		<?php foreach ($users as $user): ?>
		<li>
			<div class="item">
				<span class="name">
					<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($user->email); ?>&amp;default=<?php echo BASE_URL; ?>admin/images/user-25x25.png&amp;size=25" width="25" height="25" title="<?php echo __('Avatar from www.gravatar.com'); ?>" alt="" />
					<a href="<?php echo get_url('user/edit/'.$user->id); ?>"><?php echo $user->name; ?></a>
					<small><?php echo $user->username; ?></small>
				</span>
				<span class="roles"><?php echo implode(', ', $user->getPermissions()); ?></span>
				<span class="email"><?php echo $user->email; ?></span>
				<span class="actions">
					<?php if ($user->id > 1): ?>
					<button class="item-remove-button" rel="<?php echo get_url('user/delete/'.$user->id); ?>" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></button>
					<?php else: ?>
					<button disabled><img src="images/remove.png" /></button>
					<?php endif; ?>
				</span>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	
</div><!--/#userMap-->