<?php if($navigation !== NULL): ?>
<div class="navbar navbar-static-top navbar-inverse">
	<div class="navbar-inner">
		<?php
		echo HTML::anchor( Setting::get( 'default_tab', 'page' ), HTML::image( ADMIN_RESOURCES . 'images/logo.png'), array(
			'class' => 'brand'
		) );
		?>

		<div class="nav-collapse collapse navbar-responsive-collapse">
			<ul class="nav">
				<?php foreach ( $navigation as $nav ): ?>
					<?php if ( count($nav->get_pages() ) > 0 ): ?>
						<li class="dropdown <?php if ( $nav->is_active() ): ?>active<?php endif; ?>">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $nav->name(); ?> <b class="caret"></b>
							<?php if($nav->counter > 0): ?>
							<span class="counter"><?php echo $nav->counter; ?></span>	
							<?php endif; ?>
							</a>
							<ul class="dropdown-menu">
								<?php foreach ( $nav->get_pages() as $item ): ?>
									<?php if($item->divider === TRUE): ?>
									<li class="divider"></li>
									<?php endif; ?>
									<li <?php if ( $item->is_active() ): ?>class="active"<?php endif; ?>>

										<?php echo HTML::anchor( $item->url(), $item->name() ); ?>
										<?php if($item->counter > 0): ?>
										<span class="counter"><?php echo $item->counter; ?></span>	
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>

			<ul class="nav pull-right">
				
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<?php echo UI::icon( 'user icon-white' ) . ' ' .  AuthUser::getRecord()->username; ?> <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							<?php echo HTML::anchor( 'user/edit/' . AuthUser::getId(), __( 'Profile' ) ); ?>
						</li>
						<li>
							<?php echo HTML::anchor( 'logout', __( 'Logout' ) ); ?>
						</li>
					</ul>
				</li>
				<li class="divider-vertical"></li>
				<li><?php echo HTML::anchor( URL::base(TRUE), UI::label(UI::icon( 'globe icon-white' ) . ' ' .  __( 'View Site' )), array( 'target' => '_blank' ) ); ?></li>
				
			</ul>
		</div>
	</div>
</div>

<?php foreach ( $navigation as $nav ): ?>
<?php if($nav->is_active() AND count($nav->get_pages()) > 1):?>
<div id="subnav" class="navbar navbar-static-top">
	<div class="navbar-inner">
		<ul class="nav">
			<?php foreach ( $nav->get_pages() as $item ): ?>
			<?php if($item->divider === TRUE): ?>
			<li class="divider-vertical"></li>
			<?php endif; ?>
			<li class="<?php if($item->is_active()): ?>active<?php endif; ?>">
				<?php echo HTML::anchor( $item->url(), $item->name() ); ?>
				
				<?php if($item->counter > 0): ?>
				<span class="counter"><?php echo $item->counter; ?></span>	
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
<?php endforeach; ?>

<?php endif; ?>