<div class="page-profile">
	
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
			
			<br>
			
			<?php if ( Acl::check( 'users.edit') OR $user->id == AuthUser::getId() ): ?>
					
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

	</div>
</div>

<?php /*
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('General information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="profile-header">
			<?php echo HTML::anchor('http://gravatar.com/emails/', $user->gravatar(100, NULL, array('class' => 'profile-avatar img-circle')), array(
				'target' => '_blank',
			)); ?>

			<h2 class="profile-username"><?php echo $user->username; ?> <small><?php echo __('Last login'); ?> <?php echo Date::format($user->last_login); ?></small></h2>
			<?php if(!empty($user->profile->name)): ?><p class="profile-name muted"><?php echo $user->profile->name; ?></p><?php endif; ?>
			
			<div class="clearfix"></div>
		</div>
		
		<div class="profile-toolbar">
			<?php Observer::notify('view_user_profile_toolbar', $user->id); ?>
		</div>
		
		<div class="row-fluid">
			
			<div class="span5">
				<div class="list-group">
					<?php if ( Acl::check( 'users.edit') OR $user->id == AuthUser::getId() ): ?>
					
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'users',
						'action' => 'edit',
						'id' => $user->id
					)), UI::icon('user') . '&nbsp;&nbsp' . __('Edit profile'). UI::icon('chevron-right list-group-chevron'), array(
						'class' => 'list-group-item'
					)); ?>
					<?php endif; ?>
					<?php if(!empty($user->email)): ?>
					<a href="mailto:<?php echo $user->email; ?>" class="list-group-item">
						<?php echo UI::icon('envelope-o'); ?>&nbsp;&nbsp;<?php echo $user->email; ?>
					</a>
					<?php endif; ?>
					<?php Observer::notify('view_user_profile_sidebar_list', $user->id); ?>
				</div>
				<?php Observer::notify('view_user_profile_sidebar', $user->id); ?>
			</div>
			<div class="span7">
				<?php Observer::notify('view_user_profile_information', $user->id); ?>
			</div>
		</div>
		
	</div>
	
	<?php if ( !empty($permissions) AND ACL::check('users.view.permissions') ): ?>
		<div class="widget-header widget-section">
			<h2><?php echo __('Section permissions'); ?></h2>
		</div>
		
		<?php foreach($permissions as $title => $actions): ?>
		<div class="widget-header">
			<h3><?php echo __(ucfirst($title)); ?></h3>
		</div>
		<div class="panel-body">
			<table class="table" id="permissions-list">
				<tbody>
					<?php foreach($actions as $action => $title): ?>
					<tr>
						<td><?php echo __($title); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
 * 
 */
?>
</div>	