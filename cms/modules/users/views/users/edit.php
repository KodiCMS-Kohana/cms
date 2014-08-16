<script type="text/javascript">
	var USER_ID = <?php echo (int) $user->id; ?>;
</script>
	
<?php echo Form::open(Route::get('backend')->uri(array('controller' => 'users', 'action' => $action, 'id' => $user->id)), array(
	'class' => array(Bootstrap_Form::HORIZONTAL, 'panel')
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
		<div class="panel-heading">
			<span class="panel-title"><?php echo __('General information'); ?></span>
		</div>
		<div class="panel-body">
			<div class="row-fluid">
				<div class="span8">
					<div class="form-group">
						<?php echo $user->profile->label('name', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo $user->profile->field('name', array(
								'class' => 'input-medium',
								'prefix' => 'profile'
							)); ?>	
						</div>
					</div>
					
					<div class="form-group">
						<?php echo $user->label('email', array('class' => 'control-label')); ?>
						<div class="controls">
							<div class=" input-append">
								<?php echo $user->field('email', array(
									'class' => 'input-medium',
									'prefix' => 'user'
								)); ?>
								<span class="add-on"><?php echo UI::icon('envelope'); ?></span>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<?php echo $user->label('username', array('class' => 'control-label')); ?>
						<div class="controls">
							<div class="input-append">
								<?php echo $user->field('username', array(
									'class' => 'input-medium',
									'prefix' => 'user'
								)); ?>
								<span class="add-on"><?php echo UI::icon('user'); ?></span>
							</div>
							
							<span class="help-block"><?php echo __('At least :num characters. Must be unique.', array(
								':num' => 3
							)); ?></span>
						</div>
					</div>
				</div>
			</div>
			
			<hr class="panel-wide"/>
			
			<div class="form-group">
				<?php echo $user->profile->label('locale', array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo $user->profile->field('locale', array(
						'prefix' => 'profile'
					)); ?>	
				</div>
			</div>
		</div>
		
		<div class="panel-heading">
			<span class="panel-title"><?php echo __('Notifications'); ?></span>
		</div>
		
		<div class="panel-body">
			<div class="form-group">
				<div class="controls form-inline">
					<?php echo $user->profile->field('notice', array(
						'prefix' => 'profile',
					)); ?>	
					<?php echo $user->profile->label('notice', array(
						'class' => 'checkbox'
					)); ?>
				</div>
			</div>
			
			<?php Observer::notify('view_user_edit_notifications', $user->id); ?>
		</div>

		<?php if( ACL::check('users.change_password') OR $user->id == AuthUser::getId() OR !$user->loaded() ): ?>
		<div class="panel-heading spoiler-toggle" data-spoiler=".password-spoiler">
			<span class="panel-title"><?php echo __('Password'); ?></span>
		</div>
		<div class="panel-body spoiler password-spoiler">
			<?php if($action == 'edit'): ?>
			<div class="alert alert-warning">
				<?php echo UI::icon('lightbulb-o'); ?> <?php echo __('Leave password blank for it to remain unchanged.'); ?>
			</div>
			<?php endif; ?>
			<?php
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Password::factory(array(
					'name' => 'user[password]'
				), array('id' => 'userEditPasswordField', 'autocomplete' => 'off', 
					'placeholder' => __('Password')))
				->label(__('Password'))
				->append(Bootstrap_Form_Element_Input::add_on(UI::icon('lock')))
				->help_text(__('At least :num characters.', array(
					':num' => Kohana::$config->load('auth')->get( 'password_length' )
				)))
			));
			
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Password::factory(array(
					'name' => 'user[password_confirm]'
				), array('id' => 'userEditPasswordConfirmField', 
					'autocomplete' => 'off', 'placeholder' => __('Confirm Password')))
				->label(__('Confirm Password'))
			)); 
			?>
			<?php Observer::notify('view_user_edit_password', $user->id); ?>
		</div>
		<?php endif; ?>

		<?php if (Acl::check( 'users.change_roles') AND ($user->id === NULL OR $user->id > 1)): ?>
		<div class="panel-heading">
			<span class="panel-title"><?php echo __('Roles'); ?></span>
		</div>
		<div class="panel-body">
			<div class="row-fluid">
			<?php 
				echo Form::hidden('user_roles', (int) $user->id, array(
					'class' => 'span12'
				));
				
				echo Bootstrap_Form_Helper_Help::factory(array(
					'text' => __('Roles restrict user privileges and turn parts of the administrative interface on or off.')
				)); 
			?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php Observer::notify('view_user_edit_plugins', $user); ?>
		
		<div class="form-actions panel-footer">
			<?php echo UI::actions($page_name); ?>
		</div>
<?php Form::close(); ?>