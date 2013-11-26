<div class="widget">
	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs"></ul>
		<div class="tab-content"></div>
	</div>
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>

	<div class="widget-content">
		<div class="row-fluid">
			<div class="span4">
				<div class="thumbnail">
					<?php echo HTML::anchor('http://gravatar.com/emails/', $user->gravatar(360, NULL, array()), array(
						'target' => '_blank'
					)); ?>
				</div>
				<br />
				<div class="list-group">
					<?php if ( Acl::check( 'users.edit') OR $user->id == AuthUser::getId() ): ?>
					<a href="<?php echo Route::url('backend', array(
							'controller' => 'users',
							'action' => 'edit',
							'id' => $user->id
						)); ?>" class="list-group-item">
						<i class="icon-user"></i>&nbsp;&nbsp;<?php echo __('Edit profile'); ?>
						<i class="list-group-chevron icon-chevron-right"></i>
					</a>
					<?php endif; ?>
					<?php if(!empty($user->email)): ?>
					<a href="mailto:<?php echo $user->email; ?>" class="list-group-item">
						<i class="icon-envelope-alt"></i>&nbsp;&nbsp;<?php echo $user->email; ?>
					</a>
					<?php endif; ?>
					<?php Observer::notify('view_user_profile_sidebar_list', $user->id); ?>
				</div>
				
				<p class="muted"><i class="icon-flag"></i> <?php echo __('Last login'); ?> <?php echo Date::format($user->last_login); ?></p>
				
				<?php Observer::notify('view_user_profile_sidebar', $user->id); ?>
			</div>
			<div class="span8">
				<h2><?php echo $user->username; ?> <?php if(!empty($user->profile->name)): ?><small><?php echo $user->profile->name; ?></small><?php endif; ?></h2>

				<div class="profile-toolbar well">
					<?php Observer::notify('view_user_profile_toolbar', $user->id); ?>
				</div>

				<?php Observer::notify('view_user_profile_information', $user->id); ?>

				<?php if ( !empty($activity) ): ?>
				<hr />
				<h3 class="page-header"><?php echo __('Activity'); ?></h3>
				<?php endif; ?>
			</div>
		</div>
		
	</div>
	
	<?php if ( !empty($permissions) ): ?>
		<div class="widget-header widget-section">
			<h2><?php echo __('Section permissions'); ?></h2>
		</div>
		
		<?php foreach($permissions as $title => $actions): ?>
		<div class="widget-header">
			<h3><?php echo __(ucfirst($title)); ?></h3>
		</div>
		<div class="widget-content">
			
			<table class="table "permissions-list">
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
</div>	