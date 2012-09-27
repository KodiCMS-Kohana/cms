<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo $title; ?></title>
		<base href="<?php echo ADMIN_RESOURCES; ?>" />
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />
		<?php
			echo HTML::style(ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl.css' ) . "\n";
			echo HTML::style(ADMIN_RESOURCES . 'css/common.css') . "\n";

			echo HTML::script(ADMIN_RESOURCES . 'libs/jquery-1.8.0.min.js' ) . "\n";
			echo HTML::script(ADMIN_RESOURCES . 'libs/bootstrap/js/bootstrap.min.js' ) . "\n";
			echo HTML::script(ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl_minimized.js' ) . "\n";
		?>
		
		<?php echo $messages; ?>
	</head>
	<body id="body_frontend">
		<div id="content-wrapper">
			<div class="container" id="content">			
				<?php echo $content; ?>
			</div>
		</div>
			
		<?php echo View::factory('layouts/blocks/footer'); ?>
		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
	
	
</html>