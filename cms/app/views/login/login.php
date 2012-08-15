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

<div id="dialog" class="box">
	<h1 class="box-title"><?php echo __('Login') .' &ndash; '. Setting::get('admin_title'); ?></h1>
	
	<form id="loginForm" action="<?php echo get_url('login/login'); ?>" method="post">
		<?php if( isset($redirect) ): ?>
		<input type="hidden" name="login[redirect]" value="<?php echo $redirect; ?>" />
		<?php endif; ?>
		
		<div class="fields-line">
			<p>
				<label for="loginUsernameField"><?php echo __('Username'); ?>:</label>
				<input id="loginUsernameField" class="input-text" type="text" name="login[username]" value="" tabindex="1" autocomplete="off" />
			</p>
			
			<p>
				<label for="loginPasswordField"><?php echo __('Password'); ?>:</label>
				<input id="loginPasswordField" class="input-text" type="password" name="login[password]" value="" tabindex="2" autocomplete="off" />
			</p>
		</div>
		
<?php Observer::notify('admin_login_form'); ?>
		
		<p id="loginRemember">
			<input id="loginRememberCheck" type="checkbox" name="login[remember]" value="checked" tabindex="9" />
			<label for="loginRememberCheck"><?php echo __('Remember me for 14 days'); ?></label>
		</p>

		<p class="box-buttons">
			<button type="submit" tabindex="10"><img src="images/unlock.png" /> <?php echo __('Login'); ?></button>
			<span><a href="<?php echo get_url('login/forgot'); ?>" tabindex="12"><?php echo __('Forgot password?'); ?></a></span>
		</p>
	</form>
</div>
