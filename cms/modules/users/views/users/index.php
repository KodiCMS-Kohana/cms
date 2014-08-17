<div class="panel">
	<div class="panel-heading">
		<?php if ( Acl::check( 'users.add')): ?>
		<?php echo UI::button(__('Add user'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'users', 'action' => 'add')), 
			'icon' => UI::icon('plus'),
			'data-hotkeys' => 'ctrl+a'
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
				<th><?php echo __('E-mail'); ?></th>
				<th><?php echo __('Roles'); ?></th>
				<th><?php echo __('Last login'); ?></th>
				<th><?php echo __('Actions'); ?></th>
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
				<td class="email"><?php echo UI::label(HTML::mailto($user->email)); ?></td>
				<td class="roles">
					<?php $roles = explode(',', $user->roles); ?>
					<?php foreach($roles as $role): ?>
						<?php echo UI::label($role, 'default'); ?>
					<?php endforeach; ?>
				</td>
				<td class="last_login"><?php echo Date::format($user->last_login); ?></td>
				<td class="actions">
					<?php 
					if ($user->id > 1 AND ACL::check( 'users.delete'))
					{
						echo UI::button(NULL, array(
							'href' => Route::get('backend')->uri(array(
								'controller' => 'users',
								'action' => 'delete',
								'id' => $user->id
							)), 
							'icon' => UI::icon('times fa-inverse'),
							'class' => 'btn btn-xs brn-danger btn-confirm'
						));
					} ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $pager; ?>