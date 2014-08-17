<div class="panel-heading">
	<span class="panel-title"><?php echo __('Section permissions'); ?></span>
</div>
<div class="panel-body" id="permissions-list">
	<?php foreach($permissions as $title => $actions):?>
	<div class="panel">
		<div class="panel-heading">
			<span class="panel-title"><?php echo __(ucfirst($title)); ?></span>
		</div>
		<table class="table table-hover">
			<colgroup>
				<col width="20px" />
				<col />
			</colgroup>
			<thead class="highlight">
				<tr>
					<th></th>
					<th>
						<a href="#" class="check_all editable editable-click"><?php echo __('Select all'); ?></a>
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
	</div>
	<?php endforeach; ?>
</div>