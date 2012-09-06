<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title><?php echo __('Login') .' &ndash; '. Setting::get('admin_title'); ?></title>
		
		<base href="<?php echo BASE_URL.ADMIN_DIR_NAME.'/'; ?>" />
		
		<link href="<?php echo BASE_URL.ADMIN_DIR_NAME; ?>/favicon.ico" rel="favourites icon" />
		
		<?php
			echo HTML::style('libs/bootstrap/css/bootstrap.min.css');
			echo HTML::style('stylesheets/login.css');

			echo HTML::script( 'libs/jquery-1.7.2.min.js' );
			echo HTML::script( 'libs/bootstrap/js/bootstrap.min.js' );
		?>

	</head>
	<body>
		<div class="navbar navbar-static-top">
			<div class="navbar-inner">
				<div class="container">
					<?php echo HTML::anchor(URL::site(Setting::get('default_tab', 'page/index')), Setting::get('admin_title'), array(
						'class' => 'brand'
					)); ?>

					<div class="nav-collapse">
						<ul class="nav pull-right">					
							<li>				
								<?php echo HTML::anchor(URL::base(), HTML::icon('chevron-left') . ' ' .__('Back to Homepage'), array('target' => '_blank')); ?>
							</li>
						</ul>
					</div><!--/.nav-collapse -->	
				</div> <!-- /container -->
			</div> <!-- /navbar-inner -->
		</div> <!-- /navbar -->
		
		<div class="account-container well">
			<div class="content clearfix">
				<?php echo $content; ?>
			</div> <!-- /content -->
		</div> <!-- /account-container -->
		
		
	</body>
</html>