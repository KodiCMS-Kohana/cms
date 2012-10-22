<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>&ndash; <?php echo Setting::get( 'admin_title' ); ?></title>
		<base href="<?php echo ADMIN_RESOURCES; ?>" />
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />

		<script type="text/javascript">
		<?php echo View::factory('layouts/blocks/jsvars'); ?>
		<?php echo $messages; ?>
		</script>

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

		<?php Observer::notify( 'layout_backend_head' ); ?>
	</head>
	<body id="body_<?php echo $page_body_id; ?>">
		<div id="content-wrapper">
			<?php echo View::factory('layouts/blocks/navigation'); ?>

			<?php echo $breadcrumbs; ?>
			
			<div class="container-fluid">
				<div id="content" >
				<?php if(!empty($title)): ?>
				<?php echo UI::page_header($title); ?>
				<?php endif; ?>
				<?php echo $content; ?>
				</div> <!--/#content-->
			</div>
		</div>
		<?php echo View::factory('layouts/blocks/footer'); ?>

		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
</html>