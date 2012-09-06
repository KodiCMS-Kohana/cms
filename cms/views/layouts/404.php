<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo Setting::get('admin_title'); ?></title>
		<?php
			echo HTML::style( '/admin/libs/bootstrap/css/bootstrap.min.css' );
			echo HTML::style( '/admin/stylesheets/base.css' );

			echo HTML::script( '/admin/libs/jquery-1.7.2.min.js' );
			echo HTML::script( '/admin/libs/bootstrap/js/bootstrap.min.js' );
		?>

	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="span12">
					<div class="error-container">
						<h1>Oops!</h1>

						<h2>404 Not Found</h2>

						<div class="error-details">
							<?php echo __('Sorry, an error has occured, Requested page not found!'); ?>
						</div> <!-- /error-details -->

						<div class="error-actions">
							<a href="/" class="btn btn-large btn-primary">
								<i class="icon-chevron-left"></i>
								<?php echo __('Back to Homepage'); ?>				
							</a>

						</div> <!-- /error-actions -->
					</div> <!-- /error-container -->			
				</div> <!-- /span12 -->
			</div> <!-- /row -->
		</div>
		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
			<hr />
			<div id="kohana-profiler"><?php echo View::factory( 'profiler/stats' ) ?></div>
		<?php endif; ?> 
	</body>
</html>