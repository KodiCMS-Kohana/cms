<script type="text/javascript">
	var ROLE_ID = <?php echo (int) $role->id; ?>;
</script>
	
<?php echo Form::open(Route::url('backend', array('controller' => 'roles', 'action' => $action, 'id' => $role->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>
<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>
	<div class="widget-content">
		<?php 
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Input::factory(array(
					'name' => 'role[name]', 'value' => $role->name
				))
				->attributes('class', 'slug')
				->attributes('data-separator', '_')
				->label(__('Name'))
			));

			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Textarea::factory(array(
					'name' => 'role[description]', 'body' => $role->description
				))
				->attributes('class', 'input-xxlarge')
				->label(__('Description'))
			));
		?>
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