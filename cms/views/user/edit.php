<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="page-header">
	<h1><?php echo $user->username; ?></h1> 
</div>

<ul class="breadcrumb">
	<li><a href="<?php echo URL::site('admin/user'); ?>"><?php echo __('Users'); ?></a> <span class="divider">/</span></li>
	<li class="active"><?php echo $user->username; ?></li>
</ul>

<form class="form-horizontal" action="<?php echo $action=='edit' ? URL::site('admin/user/edit/'.$user->id): URL::site('admin/user/add'); ?>" method="post">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="userEditEmailField"><?php echo __('E-mail'); ?></label>
			<div class="controls">
				<?php echo Form::input('user[email]', $user->email, array(
					'class' => 'input-xlarge', 'id' => 'userEditEmailField'
				)); ?>
				<p class="help-block"><?php echo __('Optional. Please use a valid e-mail address.'); ?></p>
			</div>
		</div>
		<?php if ($user->id > 1): ?>
		<div class="control-group">
			<label class="control-label" for="userEditUsernameField"><?php echo __('Username'); ?></label>
			<div class="controls">
				<?php echo Form::input('user[username]', $user->username, array(
					'class' => 'input-xlarge', 'id' => 'userEditUsernameField'
				)); ?>
				<p class="help-block"><?php echo __('At least 3 characters. Must be unique.'); ?></p>
			</div>
		</div>
		<?php endif; ?>
	</fieldset>

	<fieldset>
		<legend><?php echo __('Password'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="userEditPasswordField"><?php echo __('Password'); ?></label>
			<div class="controls">
				<?php echo Form::password('user[password]', NULL, array(
					'class' => 'input-xlarge', 'id' => 'userEditPasswordField'
				)); ?>
				<p class="help-block"><?php echo __('At least 3 characters. Must be unique.'); ?> <?php if($action=='edit') echo __('Leave password blank for it to remain unchanged.'); ?></p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="userEditPasswordConfirmField"><?php echo __('Confirm Password'); ?></label>
			<div class="controls">
				<?php echo Form::password('user[confirm]', NULL, array(
					'class' => 'input-xlarge', 'id' => 'userEditPasswordConfirmField'
				)); ?>
			</div>
		</div>
	</fieldset>

	<?php if (AuthUser::hasPermission('administrator')): ?>
	<fieldset>
		<legend><?php echo __('Roles'); ?></legend>
		<div class="control-group">
			<div class="controls">
				<?php $user_permissions = explode(',', $user->roles); ?>
				<?php foreach ($permissions as $perm): ?>
				<label class="checkbox" for="userEditPerms<?php echo ucwords($perm->name); ?>">
				<?php echo Form::checkbox('user_permission['.$perm->name.']', $perm->id, in_array($perm->id, $user_permissions), array(
					'id' => 'userEditPerms' . ucwords($perm->name)
				)) . ' ' .__(ucwords($perm->name)); ?>
				</label>
				<?php endforeach; ?>
				<p class="help-block"><?php echo __('Roles restrict user privileges and turn parts of the administrative interface on or off.'); ?></p>
			</div>
		</div>
	</fieldset>
	<?php endif; ?>

	<?php Observer::notify('view_user_edit_plugins', array($user)); ?>

	<div class="form-actions">
		<?php echo Form::actions($page_name); ?>
	</div>
</form>