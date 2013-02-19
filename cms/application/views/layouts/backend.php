<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="<?php echo CMS_NAME . ' v.' . CMS_VERSION; ?>">
		<meta name="author" content="ButscH" />
		<title><?php echo $title; ?> &ndash; <?php echo Setting::get( 'admin_title' ); ?></title>
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
	<body id="body_<?php echo $page_body_id; ?>" class="<?php echo $request->query('type'); ?>">
		<div id="content-wrapper">
			<div id="navigation">
				<?php echo View::factory('layouts/blocks/navigation'); ?>
			</div>
			<?php echo $breadcrumbs; ?>
			
			<div class="container-fluid">
				<div id="content" >
				<?php if(!empty($title)): ?>
				<?php //echo UI::page_header($title); ?>
				<?php endif; ?>
				<?php echo $content; ?>
				</div> <!--/#content-->
			</div>
		</div>
		
		<?php echo $footer; ?>

		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
</html>