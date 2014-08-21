<li class="nav-icon-btn nav-icon-btn-danger nav-logs">
	<a href="#notifications" class="dropdown-toggle" data-toggle="dropdown">
		<span class="label counter"></span>
		<i class="fa fa-bug fa-lg"></i>
		<span class="small-screen-text"><?php echo __('Errors'); ?></span>
	</a>

	<div class="dropdown-menu widget-notifications no-padding" style="width: 500px">
		<div class="notifications-list" id="main-navbar-notifications"></div>
		<a href="<?php echo URL::site(Route::get('backend')->uri(array('controller' => 'logs'))); ?>" class="notifications-link">
			<?php echo __('More logs'); ?>
		</a>
	</div>
</li>