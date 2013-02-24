<script type="text/javascript">
	var PAGE_ID = <?php echo (int) $page->id; ?>;

	<?php if($action == 'add'): ?>
	$(function() {
		$('.spoiler-toggle').click();
	})
	<?php endif; ?>
</script>
	
<?php echo Form::open($action=='edit' ? 'user/edit/'.$user->id : 'user/add', array(
	'class' => 'form-horizontal'
)); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="widget">
		<div class="widget-header">
			<h3><?php echo __('General'); ?></h3>
		</div>
		<div class="widget-content">
			<div class="row-fluid">
				
				<div class="span8">
					<div class="control-group">
						<label class="control-label" for="userEditNameField"><?php echo __('Name'); ?></label>
						<div class="controls">
							<?php echo Form::input('user[name]', $user->name, array(
								'class' => 'input-medium', 'id' => 'userEditNameField'
							)); ?>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="userEditEmailField"><?php echo __('E-mail'); ?></label>
						<div class="controls">
							<?php echo Form::input('user[email]', $user->email, array(
								'class' => 'input-medium', 'id' => 'userEditEmailField'
							)); ?>
							<p class="help-block"><?php echo __('Use a valid e-mail address.'); ?></p>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="userEditUsernameField"><?php echo __('Username'); ?></label>
						<div class="controls">
							<?php echo Form::input('user[username]', $user->username, array(
								'class' => 'input-medium', 'id' => 'userEditUsernameField'
							)); ?>
							<p class="help-block"><?php echo __('At least :num characters. Must be unique.', array(
								':num' => 3
							)); ?></p>
						</div>
					</div>
				</div>
				<div class="span4 align-right">
					<?php if($user->id !== NULL): ?>
					<div id="UserGravatar">
						<?php echo HTML::anchor('http://gravatar.com/emails/', $user->gravatar(150, NULL, array(
							'class' => 'img-polaroid')), array(
							'target' => '_blank'
						)); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="widget-header spoiler-toggle">
			<h3><?php echo __('Password'); ?> <?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?></h3>
		</div>
		<div class="widget-content spoiler">
			<div class="control-group">
				<label class="control-label" for="userEditPasswordField"><?php echo __('Password'); ?></label>
				<div class="controls">
					<?php echo Form::password('user[password]', NULL, array(
						'class' => 'input-medium', 'autocomplete' => 'off'
					)); ?>
					<p class="help-block"><?php echo __('At least :num characters. Must be unique.', array(
						':num' => Kohana::$config->load('auth')->get( 'password_length' )
					)); ?> 
					<?php if($action=='edit') echo __('Leave password blank for it to remain unchanged.'); ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="userEditPasswordConfirmField"><?php echo __('Confirm Password'); ?></label>
				<div class="controls">
					<?php echo Form::password('user[confirm]', NULL, array(
						'class' => 'input-medium', 'autocomplete' => 'off'
					)); ?>
				</div>
			</div>
		</div>

		<?php if (AuthUser::hasPermission('administrator')): ?>

		<div class="widget-header">
			<h3><?php echo __('Roles'); ?></h3>
		</div>
		<div class="widget-content">
			<div class="row-fluid">
				<?php echo Form::select('user_permission[]', $permissions, $user->roles, array(
					'class' => 'span12'
				)); ?>

				<p class="help-block"><?php echo __('Roles restrict user privileges and turn parts of the administrative interface on or off.'); ?></p>
			</div>
		</div>
		<?php endif; ?>
		
		<?php Observer::notify('view_user_edit_plugins', array($user)); ?>
		
		<div class="form-actions widget-footer">
			<?php echo UI::actions($page_name); ?>
		</div>
	
	</div>
<?php Form::close(); ?>