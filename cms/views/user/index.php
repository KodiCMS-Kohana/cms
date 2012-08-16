<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Users'); ?></h1>  
</div>

<div class="map">
	<div class="well page-actions">
		<?php echo HTML::button(URL::site('admin/user/add'), __('Add user'), 'plus'); ?>
	</div>
	
	<table class="table_list" id="UserList">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Username'); ?></th>
				<th><?php echo __('Roles'); ?></th>
				<th><?php echo __('E-mail'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
			<tr class="item">
				<td class="name">
					<?php echo HTML::icon('user'); ?> 
					<?php echo HTML::anchor(URL::site('admin/user/edit/'.$user->id), $user->username); ?>
				</td>
				<td class="roles">
					<?php $roles = explode(',', $user->roles); ?>
					<?php foreach($roles as $role): ?>
						<?php echo HTML::label($role, 'default'); ?>
					<?php endforeach; ?>
				</td>
				<td class="email"><?php echo HTML::label($user->email); ?></td>
				<td class="actions">
					<?php 
					if ($user->id > 1)
						echo HTML::button(URL::site('admin/user/delete/'.$user->id), NULL, 'remove', 'btn btn-mini btn-confirm');
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div><!--/#userMap-->