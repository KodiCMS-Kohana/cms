<div class="widget-header">
	<h3><?php echo __('Permissions'); ?></h3>
</div>
<div class="widget-content widget-nopad">
<?php foreach($permissions as $title => $actions): ?>
	<table class="table">
		<colgroup>
			<col width="20px" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th></th>
				<th>
					<?php echo __('Section :section', array(
						':section' => UI::label(__(ucfirst($title)))
					)); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($actions as $action => $title): ?>
			<tr>
				<td>
					<?php echo Bootstrap_Form_Element_Checkbox::factory(array(
						'name' => 'role[permissions]['.$action.']', 'value' => 1
					))
					->attributes('id', 'permission-'.$action)
					->checked(in_array($action, $role_permissions)); ?>
				</td>
				<th><?php echo Form::label('permission-'.$action, __($title)); ?></th>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endforeach; ?>
</div>
	
