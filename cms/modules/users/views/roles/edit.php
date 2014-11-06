<?php echo Form::open(Route::get('backend')->uri(array('controller' => 'roles', 'action' => $action, 'id' => $role->id)), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>

	<div class="panel-heading">
		<span class="panel-title"><?php echo __('General information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<?php echo $role->label('name', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $role->field('name', array(
					'class' => 'form-control slug',
					'prefix' => 'role',
					'data-separator' => '_'
				)); ?>	
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $role->label('description', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $role->field('description', array(
					'class' => 'form-control',
					'prefix' => 'role',
					'rows' => 2
				)); ?>	
			</div>
		</div>
	</div>

	<?php if (Acl::check('roles.change_permissions') AND ($role->id != 2 AND $role->loaded())): ?>
	<?php echo View::factory('roles/permissions', array(
		'permissions' => Acl::get_permissions(),
		'role_permissions' => $role->permissions()
	)); ?>
	<?php endif; ?>

	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php Form::close(); ?>