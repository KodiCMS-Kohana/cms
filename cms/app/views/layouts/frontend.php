<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="<?php echo CMS_NAME . ' v.' . CMS_VERSION; ?>">
		<meta name="author" content="ButscH" />
		<title><?php echo $title; ?></title>
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />
		
		<script type="text/javascript">
		<?php echo $messages; ?>
		</script>

		<?php
		foreach ( $styles as $style ) echo HTML::style( $style ) . "\n\t\t";
		foreach ( $scripts as $script ) echo HTML::script( $script ) . "\n\t\t";
		?>
	</head>
	<body id="body_frontend">
		<div id="content-wrapper">
			<div class="container" id="content">
				<?php echo $content; ?>
			</div>
		</div>

		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
	
	
</html>