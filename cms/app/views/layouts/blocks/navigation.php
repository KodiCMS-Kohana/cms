<div class="navbar navbar-static-top navbar-inverse">
	<div class="navbar-inner">
		<?php
		echo HTML::anchor( URL::site( Setting::get( 'default_tab', 'page' ) ), Setting::get( 'admin_title' ), array(
			'class' => 'brand'
		) );
		?>

		<ul class="nav">
			<?php foreach ( Model_Navigation::get() as $nav_name => $nav ): ?>
				<?php if ( !empty( $nav->items ) ): ?>
					<li class="dropdown <?php if ( $nav->is_current ): ?>active<?php endif; ?>">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo __( $nav_name ); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php foreach ( $nav->items as $item ): ?>
								<li <?php if ( $item->is_current ): ?>class="active"<?php endif; ?>><?php echo HTML::anchor( URL::site( $item->uri ), $item->name ); ?></li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>

		<div class="btn-group pull-right">
			<?php echo UI::button( AuthUser::getRecord()->name, array( 
				'href' => 'user/edit/' . AuthUser::getId(), 'icon' => UI::icon( 'user' ) ) ); ?>
	
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><?php echo HTML::anchor( URL::site( 'logout' ), __( 'Logout' ) ); ?></li>
			</ul>
		</div>

		<ul class="nav pull-right">
			<li><?php echo HTML::anchor( URL::base(TRUE), __( 'View Site' ), array( 'target' => '_blank' ) ); ?></li>
		</ul>
	</div>
</div>