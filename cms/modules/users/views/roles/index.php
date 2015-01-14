<div class="panel">
	<div class="panel-heading">
		<?php if (Acl::check('roles.add')): ?>
		<?php echo UI::button(__('Add role'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'roles', 'action' => 'add')), 
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="150px" />
			<col />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Description'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($roles as $role): ?>
			<tr class="item">
				<td class="name">
					<?php if (Acl::check('roles.edit')): ?>
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'roles',
						'action' => 'edit',
						'id' => $role->id
					)), $role->name, array(
						'data-icon' => 'unlock'
					)); ?>
					<?php else: ?>
					<?php echo UI::icon('lock'); ?> <?php echo $role->name; ?>
					<?php endif; ?>
				</td>
				<td class="description">
					<?php echo $role->description; ?>
				</td>
				<td class="actions text-center">
					<?php 
					if ($role->id > 2 AND ACL::check('roles.delete'))
					{
						echo UI::button(NULL, array(
							'href' => Route::get('backend')->uri(array(
								'controller' => 'roles',
								'action' => 'delete',
								'id' => $role->id
							)), 
							'icon' => UI::icon('times fa-inverse'),
							'class' => 'btn-xs btn-danger btn-confirm'
						));
					} ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $pager; ?>