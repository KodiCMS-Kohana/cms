<div class="widget-header">
	<h3><?php echo __('Permissions'); ?></h3>
</div>
<div class="widget-content">
<?php foreach($permissions as $title => $actions): ?>
	<h4><?php echo __('Section ":section"', array(':section' => __(ucfirst($title)))); ?></h4>
	<?php foreach($actions as $action => $title): ?>
	<?php echo Bootstrap_Form_Element_Checkbox::factory(array(
			'name' => 'role[permissions]['.$action.']', 'value' => 1
		))
		->checked(in_array($action, $role_permissions))
		->label(UI::label($title), array('class' => 'inline')); ?>
	<?php endforeach; ?>
	<hr />
<?php endforeach; ?>
</div>
	
