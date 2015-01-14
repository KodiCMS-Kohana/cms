<div class="page-profile clearfix">
	
	<div class="profile-full-name">
		<span class="text-semibold"><?php echo $user->username; ?></span> <?php echo __('Last login'); ?> <?php echo Date::format($user->last_login); ?>
	</div>

	<div class="profile-row">
		<div class="left-col">
			<div class="profile-block">
				<div class="panel profile-photo">
					<?php echo HTML::anchor('http://gravatar.com/emails/', $user->gravatar(100, NULL), array(
						'target' => '_blank',
					)); ?>
				</div>

				<br />

				<?php if (Acl::check('users.edit') OR $user->id == Auth::get_id()): ?>
				<?php echo HTML::anchor(Route::get('backend')->uri(array(
					'controller' => 'users',
					'action' => 'edit',
					'id' => $user->id
				)), __('Edit profile'), array(
					'class' => 'btn btn-success btn-sm',
					'data-icon' => 'user'
				)); ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="right-col">
			<hr class="profile-content-hr no-grid-gutter-h">

			<div class="profile-content tabbable">

				<?php Observer::notify('view_user_profile_information', $user->id); ?>

				<?php if (!empty($permissions) AND ACL::check('users.view.permissions')): ?>
				<div class="panel-heading">
					<span class="panel-title"><?php echo __('Section permissions'); ?></span>
				</div>
				<div class="panel-body">
					<?php foreach($permissions as $title => $actions): ?>
					<div class="panel-heading">
						<span class="panel-title"><?php echo __(ucfirst($title)); ?></span>
					</div>
					<table class="table table-noborder">
						<tbody>
							<?php foreach($actions as $action => $title): ?>
							<tr>
								<td data-icon="check text-success" class=""><?php echo __($title); ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>