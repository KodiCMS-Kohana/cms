<script type="text/javascript">
	var PAGE_ID = <?php echo (int) $page->id; ?>;

	<?php if($action == 'add'): ?>
	$(function() {
		$('.spoiler-toggle').click();
	})
	<?php endif; ?>
</script>
	
<?php echo Form::open($action=='edit' ? 'user/edit/'.$user->id : 'user/add', array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="widget">
		<div class="widget-header">
			<h3><?php echo __('General information'); ?></h3>
		</div>
		<div class="widget-content">
			<div class="row-fluid">
				<div class="span8">
				<?php 
					echo Bootstrap_Form_Element_Control_Group::factory(array(
						'element' => Bootstrap_Form_Element_Input::factory(array(
							'name' => 'user[name]', 'value' => $user->profile->name
						), array('id' => 'userEditNameField'))
						->label(__('Name'))
					));
					
					echo Bootstrap_Form_Element_Control_Group::factory(array(
						'element' => Bootstrap_Form_Element_Input::factory(array(
							'name' => 'user[email]', 'value' => $user->email
						), array('id' => 'userEditEmailField'))
						->label(__('E-mail'))
						->append(Bootstrap_Form_Element_Input::add_on(UI::icon('envelope')))
						->help_text(__('Use a valid e-mail address.'))
					));
					
					echo Bootstrap_Form_Element_Control_Group::factory(array(
						'element' => Bootstrap_Form_Element_Input::factory(array(
							'name' => 'user[username]', 'value' => $user->username
						), array('id' => 'userEditUsernameField'))
						->label(__('Username'))
						->append(Bootstrap_Form_Element_Input::add_on(UI::icon('user')))
						->help_text(__('At least :num characters. Must be unique.', array(
							':num' => 3
						)))
					)); 
				?>
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
			
			<hr />
			
			<?php echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Checkbox::factory(array(
					'name' => 'user[notice]', 'value' => 1
				))
				->checked($user->profile->notice == 1)
				->label(__('Subscribe to email notifications'))
			)); ?>
		</div>

		<div class="widget-header spoiler-toggle">
			<h3><?php echo __('Password'); ?></h3>
		</div>
		<div class="widget-content spoiler">
		<?php
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Password::factory(array(
					'name' => 'user[password]'
				), array('id' => 'userEditPasswordField', 'autocomplete' => 'off', 
					'placeholder' => __('Password')))
				->label(__('Password'))
				->append(Bootstrap_Form_Element_Input::add_on(UI::icon('lock')))
				->help_text(__('At least :num characters. Must be unique.', array(
					':num' => Kohana::$config->load('auth')->get( 'password_length' )
				)))
			));
			
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Password::factory(array(
					'name' => 'user[confirm]'
				), array('id' => 'userEditPasswordConfirmField', 
					'autocomplete' => 'off', 'placeholder' => __('Confirm Password')))
				->label(__('Confirm Password'))
			)); 
		?>
			<?php if($action == 'edit'): ?>
			<div class="alert alert-info">
				<?php echo __('Leave password blank for it to remain unchanged.'); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php if (AuthUser::hasPermission('administrator') AND $user->id > 1): ?>
		<div class="widget-header">
			<h3><?php echo __('Roles'); ?></h3>
		</div>
		<div class="widget-content">
			<div class="row-fluid">
			<?php 
				echo Form::select('user_permission[]', $permissions, $user->roles->find_all()->as_array('id'), array(
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
		
		<div class="form-actions widget-footer">
			<?php echo UI::actions($page_name); ?>
		</div>
	
	</div>
<?php Form::close(); ?>