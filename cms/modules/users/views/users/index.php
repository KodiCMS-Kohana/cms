<div class="panel">
	<div class="panel-heading">
		<?php if (Acl::check('users.add')): ?>
		<?php echo UI::button(__('Add user'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'users', 'action' => 'add')), 
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a',
			'class' => 'btn-primary'
		)); ?>
		<?php endif; ?>
	</div>
	
	<table class="table table-primary table-striped table-hover">
		<colgroup>
			<col width="250px" />
			<col width="200px" />
			<col />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Username'); ?></th>
				<th class="hidden-xs"><?php echo __('E-mail'); ?></th>
				<th class="hidden-xs"><?php echo __('Roles'); ?></th>
				<th class="hidden-xs"><?php echo __('Last login'); ?></th>
				<th class="text-right"><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
			<tr class="item">
				<td class="name">
					<?php echo $user->gravatar(20, NULL, array('class' => 'img-circle')); ?>
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'users',
						'action' => 'profile',
						'id' => $user->id
					)), $user->username); ?>
				</td>
				<td class="email hidden-xs"><?php echo UI::label(HTML::mailto($user->email)); ?></td>
				<td class="roles hidden-xs">
					<?php $roles = explode(',', $user->roles); ?>
					<?php foreach($roles as $role): ?>
						<?php echo UI::label($role, 'default'); ?>
					<?php endforeach; ?>
				</td>
				<td class="last_login hidden-xs"><?php echo Date::format($user->last_login); ?></td>
				<td class="actions text-right">
					<?php 
					if ($user->id > 1 AND ACL::check('users.delete'))
					{
						echo UI::button(NULL, array(
							'href' => Route::get('backend')->uri(array(
								'controller' => 'users',
								'action' => 'delete',
								'id' => $user->id
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