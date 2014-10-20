<div id="main-navbar" class="navbar" role="navigation">
	<button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i>
		<span class="hide-menu-text"><?php echo __('Hide menu'); ?></span>
	</button>
	<div class="navbar-inner">
		<div class="navbar-header">
			<?php echo HTML::anchor(ADMIN_DIR_NAME, 'KodiCMS', array(
				'class' => 'navbar-brand'
			)); ?>
		</div>

		<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
			<div>
				<div class="right clearfix">
					<ul class="nav navbar-nav pull-right right-navbar-nav">
						<?php Observer::notify('view_navbar_menu'); ?>
						<li>
							<?php echo HTML::anchor(Route::get('backend')->uri(array('controller' => 'system', 'action' => 'settings')), UI::icon('cogs fa-lg')); ?>
						</li>
						<li>
							<?php echo HTML::anchor(URL::base(TRUE), UI::hidden(__('View Site')), array(
								'target' => 'blank', 'data-icon' => 'globe fa-lg text-info'
							)); ?>
						</li>
						<?php if(Auth::is_logged_in()): ?>
						<li class="dropdown user-menu">
							<a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
								<?php echo Auth::get_record()->gravatar(25); ?>
								<span><?php echo Auth::get_username(); ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<?php echo Auth::get_record()->gravatar(90, NULL, array(
										'class' => 'img-circle'
									)); ?>
									
									<p><?php echo Auth::get_record()->profile->name; ?> <small><?php echo Auth::get_record()->email; ?></small></p>
									
								</li>
								<li class="user-body">
									<div class="col-xs-6">
										<?php echo HTML::anchor(Route::get('backend')->uri(array('controller' => 'users', 'action' => 'profile')), __('Profile'), array('data-icon' => 'user')); ?>
									</div>
									<div class="col-xs-6">
										<?php echo HTML::anchor(Route::get('backend')->uri(array('controller' => 'users', 'action' => 'edit', 'id' => Auth::get_id())), __('Settings'), array('data-icon' => 'cog')); ?>
									</div>
                                </li>
								<li class="user-footer">
									<?php echo HTML::anchor(Route::get('user')->uri(array('action' => 'logout')), __('Logout'), array(
										'data-icon' => 'power-off text-danger',
										'class' => 'btn btn-default btn-xs text-bold pull-right'
									)); ?>
								</li>
							</ul>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

