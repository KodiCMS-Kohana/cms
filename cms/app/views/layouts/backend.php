<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' ); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo __( ucfirst( $controller ) ); ?> &ndash; <?php echo Setting::get( 'admin_title' ); ?></title>
		<base href="<?php echo ADMIN_URL; ?>" />
		<link href="<?php echo ADMIN_URL; ?>/favicon.ico" rel="favourites icon" />

		<script>
			var BASE_URL         = '<?php echo URL::site(); ?>';
			var SITE_URL         = '<?php echo URL::base(); ?>';
			var ADMIN_DIR_NAME   = '<?php echo ADMIN_DIR_NAME; ?>';
			var PUBLIC_URL       = '<?php echo PUBLIC_URL; ?>';
			var PLUGINS_URL      = '<?php echo PLUGINS_URL; ?>';
			var LOCALE           = '<?php echo I18n::lang(); ?>';
		</script>

		<?php
		echo HTML::style( ADMIN_URL . 'libs/bootstrap/css/bootstrap.css' ) . "\n";
		echo HTML::style( ADMIN_URL . 'stylesheets/backend.css' ) . "\n";
		echo HTML::style( ADMIN_URL . 'libs/jquery-ui/jquery-ui-1.8.12.css' ) . "\n";
		echo HTML::style( ADMIN_URL . 'libs/jgrowl/jquery.jgrowl.css' ) . "\n";

		echo HTML::script( ADMIN_URL . 'libs/jquery-1.7.2.min.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'libs/jquery-ui/jquery-ui-1.8.12.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'libs/bootstrap/js/bootstrap.min.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'libs/jquery.tubby-0.12.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'libs/jgrowl/jquery.jgrowl_minimized.js' ) . "\n";
		echo HTML::script( ADMIN_URL . 'javascripts/backend.js' ) . "\n";
		?>

		<?php echo $messages; ?>

		<?php
		foreach ( Plugins::$javascripts as $javascript )
		{
			echo HTML::script( $javascript ) . "\n";
		}
		foreach ( Plugins::$stylesheets as $stylesheet )
		{
			echo HTML::style( $stylesheet ) . "\n";
		}
		?>

<?php Observer::notify( 'layout_backend_head' ); ?>
	</head>
	<body id="body_<?php echo $page_body_id; ?>">

		<?php echo View::factory('layouts/blocks/navigation'); ?>
		
		<?php foreach ( Model_Navigation::get() as $nav_name => $nav ): ?>
		<?php if($nav->is_current AND count($nav->items) > 1):?>
		<div id="subnav" class="navbar navbar-static-top">
			<div class="navbar-inner">
				<ul class="nav">
					<?php foreach ( $nav->items as $item ): ?>
					<li class="<?php if($item->is_current): ?>active<?php endif; ?>">
						<?php echo HTML::anchor( URL::site( $item->uri ), $item->name ); ?>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<?php endforeach; ?>

		<div class="container-fluid">
			<?php if(isset($breadcrumbs)): ?>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $anchor): ?>
				<li><?php echo $anchor; ?> <span class="divider">/</span></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			
			<div id="content" class="well" >
			<?php echo $content; ?>
			</div> <!--/#content-->
		</div>
<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
			<hr />
			<div id="kohana-profiler"><?php echo View::factory( 'profiler/stats' ) ?></div>
<?php endif; ?> 
	</body>
</html>