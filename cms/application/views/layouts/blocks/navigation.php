<?php if($navigation !== NULL): ?>
<div class="navbar navbar-static-top navbar-inverse">
	<div class="navbar-inner">
		<?php echo UI_Navigation::brand(HTML::image( ADMIN_RESOURCES . 'images/logo.png'), Setting::get( 'default_tab', 'page' )); ?>

		<div class="nav-collapse collapse navbar-responsive-collapse">
			<ul class="nav">
				<?php foreach ( $navigation as $nav ): ?>
					<?php if ( count($nav->get_pages() ) > 0 ): ?>
						<li class="dropdown <?php if ( $nav->is_active() ): ?>active<?php endif; ?>">
							<?php echo UI_Navigation_Dropdown::link($nav->name() . UI::counter($nav->counter)); ?>
							
							<?php $dropdown = UI_Navigation_Dropdown::factory(); 
								foreach ( $nav->get_pages() as $item )
								{
									if( ! AuthUser::hasPermission($item->permissions) ) continue;

									if($item->divider === TRUE)
									{
										$dropdown->add_item(NULL, FALSE, array(
											'class' => UI_Navigation_Dropdown::DIVIDER
										));
									}
									
									$dropdown->add_item(HTML::anchor( $item->url(), $item->name() ) . UI::counter($item->counter), $item->is_active());
								}
					
								echo $dropdown;
							?>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>

			<ul class="nav pull-right">
				<li class="dropdown">
					
					<?php echo UI_Navigation_Dropdown::link( UI::label( UI::icon( 'user icon-white' ) . ' ' .  AuthUser::getRecord()->username, 'warning')); ?>
					
					<?php echo UI_Navigation_Dropdown::factory()
						->add_item(HTML::anchor( 'user/edit/' . AuthUser::getId(), __( 'Profile' ) ))
						->add_item(NULL, FALSE, array(
							'class' => UI_Navigation_Dropdown::DIVIDER
						))
						->add_item(HTML::anchor( 'logout', __( 'Logout' ) )); 
					?>
				</li>
				<?php echo UI_Navigation::divider(); ?>
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
			<?php if( ! AuthUser::hasPermission($item->permissions) ) continue; ?>
			
			<?php if($item->divider === TRUE): ?>
			<?php echo UI_Navigation::divider(); ?>
			<?php endif; ?>
			<li class="<?php if($item->is_active()): ?>active<?php endif; ?>">
				<?php echo HTML::anchor( $item->url(), $item->name() ); ?>
				<?php echo UI::counter($item->counter); ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
<?php endforeach; ?>

<?php endif; ?>