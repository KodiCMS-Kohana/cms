<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo $title ?> | Kohana <?php echo __('User Guide'); ?></title>
		<base href="<?php echo ADMIN_RESOURCES; ?>" />
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />

		<?php echo View::factory('layouts/blocks/jsvars'); ?>

		<?php foreach ($styles as $style) echo HTML::style($style, NULL, TRUE), "\n" ?>
		<?php foreach ($scripts as $script) echo HTML::script($script, NULL, NULL, TRUE), "\n" ?>

		<?php echo $messages; ?>

		<?php Observer::notify( 'layout_backend_head' ); ?>
	</head>
	<body id="body_<?php echo $page_body_id; ?>">

		<div id="content-wrapper">
			<?php echo View::factory('layouts/blocks/navigation'); ?>

			<div class="container-fluid">
				<?php if(isset($breadcrumb)): ?>
				<?php echo View::factory('layouts/blocks/breadcrumbs', array(
					'breadcrumbs' => $breadcrumb
				)); ?>
				<?php endif; ?>
				
				<div id="content">
					<div class="row-fluid">
						<div class="span3">
							<div id="kodoc-topics">
								<?php echo $menu ?>
							</div>
						</div>
						<div class="span9">
							<?php echo $content; ?>

							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="clearfix"></div>
		</div>
		
		<?php echo View::factory('layouts/blocks/footer'); ?>

		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
</body>
</html>
