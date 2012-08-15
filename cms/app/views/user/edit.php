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
	<a href="<?php echo get_url('user'); ?>"><?php echo __('Users'); ?></a> &rarr;
	<?php echo __(ucfirst($action).' user'); ?>
</h1>

<div id="userEdit" class="box">
	
	<form id="userEditForm" class="form" action="<?php echo $action=='edit' ? get_url('user/edit/'.$user->id): get_url('user/add'); ; ?>" method="post">
		
		<section>
			<label for="userEditNameField"><?php echo __('Name'); ?></label>
			<span><input id="userEditNameField" class="input-text" type="text" name="user[name]" maxlength="255" size="50" value="<?php echo $user->name; ?>" /></span>
		</section>
		
		<section>
			<label for="userEditEmailField"><?php echo __('E-mail'); ?> <em><?php echo __('Optional. Please use a valid e-mail address.'); ?></em></label>
			<span><input id="userEditEmailField" class="input-text" type="text" name="user[email]" maxlength="255" size="50" value="<?php echo $user->email; ?>" /></span>
		</section>
		
		<section>
			<label for="userEditUsernameField"><?php echo __('Username'); ?> <em><?php echo __('At least 3 characters. Must be unique.'); ?></em></label>
			<span><input id="userEditUsernameField" class="input-text" type="text" name="user[username]" maxlength="255" size="50" value="<?php echo $user->username; ?>" /></span>
		</section>
		
		<section>
			<label for="userEditPasswordField"><?php echo __('Password'); ?> <em><?php echo __('At least 3 characters. Must be unique.'); ?> <?php if($action=='edit') echo __('Leave password blank for it to remain unchanged.'); ?></em></label>
			<span><input id="userEditPasswordField" class="input-text" type="password" name="user[password]" maxlength="255" size="50" autocomplete="off" /></span>
		</section>
		
		<section>
			<label for="userEditConfirmField"><?php echo __('Confirm Password'); ?></label>
			<span><input id="userEditPasswordField" class="input-text" type="password" name="user[confirm]" maxlength="255" size="50" autocomplete="off" /></span>
		</section>
		
		<?php if (AuthUser::hasPermission('administrator')): ?>
		<section>
			<label><?php echo __('Roles'); ?> <em><?php echo __('Roles restrict user privileges and turn parts of the administrative interface on or off.'); ?></em></label>
			<span>
				<?php $user_permissions = ($user instanceof User) ? $user->getPermissions() : array('editor'); ?>
				<?php foreach ($permissions as $perm): ?>
				<i class="radio"><input id="userEditPerms<?php echo ucwords($perm->name); ?>" type="checkbox" name="user_permission[<?php echo $perm->name; ?>]" value="<?php echo $perm->id; ?>" <?php if( in_array($perm->name, $user_permissions) ) echo('checked="checked"'); ?> /> <label for="userEditPerms<?php echo ucwords($perm->name); ?>"><?php echo __(ucwords($perm->name)); ?></label></i>
				<?php endforeach; ?>
			</span>
		</section>
		<?php endif; ?>
		
		<section>
			<label for="userEditLanguage"><?php echo __('Interface language'); ?> <em><?php echo __('Individual language for administration interface. Create your own <a href=":url">translation</a>.', array(':url' => get_url('translate'))); ?></em></label>
			<span>
				<select id="userEditLanguage" name="user[language]">
					<?php foreach( I18n::getLanguages() as $code => $label ): ?>
					<option value="<?php echo $code; ?>" <?php if( $code == $user->language ) echo('selected="selected"'); ?> ><?php echo __($label); ?></option>
					<?php endforeach; ?>
				</select>
			</span>
		</section>
		
		<?php Observer::notify('view_user_edit_plugins', array($user)); ?>
		
		<div class="box-buttons">
			<button type="submit"><img src="images/check.png" /> <?php echo __($action == 'edit' ? 'Save changes' : 'Add user'); ?></button>
			<?php echo __('or'); ?> <a href="<?php echo get_url('user'); ?>"><?php echo __('Cancel'); ?></a>
		</div>
		
	</form>
	
</div><!--/#userEdit-->