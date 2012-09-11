<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo $title; ?></title>
		<base href="<?php echo ADMIN_RESOURCES; ?>" />
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />
		<?php
			echo HTML::style(ADMIN_RESOURCES . 'libs/bootstrap/css/bootstrap.min.css') . "\n";
			echo HTML::style(ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl.css' ) . "\n";
			echo HTML::style(ADMIN_RESOURCES . 'stylesheets/frontend.css') . "\n";

			echo HTML::script(ADMIN_RESOURCES . 'libs/jquery-1.7.2.min.js' ) . "\n";
			echo HTML::script(ADMIN_RESOURCES . 'libs/bootstrap/js/bootstrap.min.js' ) . "\n";
			echo HTML::script(ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl_minimized.js' ) . "\n";
		?>
		
		<?php echo $messages; ?>
	</head>
	<body id="body_frontend">
		<div class="container">			
			<?php echo $content; ?>
		</div>
	</body>
</html>