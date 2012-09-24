<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' ); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo __( ucfirst( $controller ) ); ?> &ndash; <?php echo Setting::get( 'admin_title' ); ?></title>
		<base href="<?php echo ADMIN_RESOURCES; ?>" />
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />

		<?php echo View::factory('layouts/blocks/jsvars'); ?>

		<?php
		foreach ( $styles as $style )
		{
			echo HTML::style( $style ) . "\n\t\t";
		}
		
		foreach ( $scripts as $script )
		{
			echo HTML::script( $script ) . "\n\t\t";
		}
		?>

		<?php echo $messages; ?>

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
			<?php echo View::factory('layouts/blocks/breadcrumbs', array(
				'breadcrumbs' => $breadcrumbs
			)); ?>
			<?php endif; ?>
			
			<div id="content" class="well" >
			<?php echo $content; ?>
			</div> <!--/#content-->
		</div>
		
		<?php echo $modal; ?>
		
		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
</html>