<script type="text/javascript">
	var ROLE_ID = <?php echo (int) $role->id; ?>;
</script>
	
<?php echo Form::open(Route::get('backend')->uri(array('controller' => 'roles', 'action' => $action, 'id' => $role->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>
<div class="widget">
	<?php echo View::factory('helper/tabbable'); ?>
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="control-group">
			<?php echo $role->label('name', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $role->field('name', array(
					'class' => 'input-medium slug',
					'prefix' => 'role',
					'data-separator' => '_'
				)); ?>	
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $role->label('description', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $role->field('description', array(
					'class' => 'input-xxlarge',
					'prefix' => 'role',
					'rows' => 5
				)); ?>	
			</div>
		</div>
	</div>

	<?php if (Acl::check( 'roles.change_permissions') AND ($role->id > 2 OR $role->id === NULL)): ?>
	<?php echo View::factory('roles/permissions', array(
			'permissions' => Acl::get_permissions(),
			'role_permissions' => $role->permissions()
		)); ?>
	<?php endif; ?>

	<div class="form-actions widget-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
</div>
<?php Form::close(); ?>