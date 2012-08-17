<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' ); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo __( ucfirst( $controller ) ); ?> &ndash; <?php echo Setting::get( 'admin_title' ); ?></title>
		<base href="<?php echo BASE_URL . ADMIN_DIR_NAME . '/'; ?>" />
		<link href="<?php echo BASE_URL . ADMIN_DIR_NAME; ?>/favicon.ico" rel="favourites icon" />

		<script>
			var BASE_URL         = '<?php echo URL::site(); ?>';
			var SITE_URL         = '<?php echo URL::base(); ?>';
			var ADMIN_DIR_NAME   = '<?php echo ADMIN_DIR_NAME; ?>';
			var PUBLIC_URL       = '<?php echo PUBLIC_URL; ?>';
			var PLUGINS_URL      = '<?php echo PLUGINS_URL; ?>';
			var LOCALE           = '<?php echo I18n::lang(); ?>';
		</script>

		<?php
		echo HTML::style( 'libs/bootstrap/css/bootstrap.min.css' );
		echo HTML::style( 'stylesheets/backend.css' );
		echo HTML::style( 'libs/jquery-ui/jquery-ui-1.8.12.css' );
		echo HTML::style( 'libs/jgrowl/jquery.jgrowl.css' );

		echo HTML::script( 'libs/jquery-1.7.2.min.js' );
		echo HTML::script( 'libs/jquery-ui/jquery-ui-1.8.12.js' );
		echo HTML::script( 'libs/bootstrap/js/bootstrap.min.js' );
		echo HTML::script( 'libs/jquery.tubby-0.12.js' );
		echo HTML::script( 'libs/jgrowl/jquery.jgrowl_minimized.js' );
		echo HTML::script( 'javascripts/backend.js' );
		?>

		<?php echo $messages; ?>

		<?php
		foreach ( Plugins::$javascripts as $javascript )
			echo HTML::script( $javascript );
		foreach ( Plugins::$stylesheets as $stylesheet )
			echo HTML::style( $stylesheet );
		?>

<?php Observer::notify( 'layout_backend_head' ); ?>
	</head>
	<body id="body_<?php echo $page_body_id; ?>">

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<?php
					echo HTML::anchor( URL::site( Setting::get( 'default_tab', 'admin/page' ) ), Setting::get( 'admin_title' ), array(
						'class' => 'brand'
					) );
					?>

					<div class="btn-group pull-right">
<?php echo HTML::anchor( URL::site( 'admin/user/edit/' . AuthUser::getId() ), HTML::icon( 'user' ) . ' ' . AuthUser::getRecord()->name, array( 'class' => 'btn' ) ); ?>
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><?php echo HTML::anchor( URL::site( 'logout' ), __( 'Logout' ) ); ?></li>
						</ul>
					</div>

					<ul class="nav pull-right">
						<li><?php echo HTML::anchor( URL::base(), __( 'View Site' ), array( 'target' => '_blank' ) ); ?></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span2">
					<div id="sidebar-nav">
						<div class="well">
							<ul class="nav nav-list">
								<?php foreach ( Model_Navigation::get() as $nav_name => $nav ): ?>
									<li class="nav-header"><?php echo __( $nav_name ); ?></li>
	<?php foreach ( $nav->items as $item ): ?>
										<li <?php if ( $item->is_current ) echo('class="active"'); ?>><?php echo HTML::anchor( URL::site( $item->uri ), $item->name ); ?></li>
	<?php endforeach; ?>
<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="span10">
					<div id="content" class="well" >
		<?php echo $content; ?>
					</div> <!--/#content-->
				</div>
			</div>
		</div>
<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
			<hr />
			<div id="kohana-profiler"><?php echo View::factory( 'profiler/stats' ) ?></div>
<?php endif; ?> 
	</body>
</html>