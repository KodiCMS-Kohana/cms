<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="<?php echo CMS_NAME . ' v.' . CMS_VERSION; ?>">
		<meta name="author" content="ButscH" />
		<title><?php echo $title; ?> &ndash; <?php echo Config::get('site', 'title'); ?></title>
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo $title ?> | Kohana <?php echo __('User Guide'); ?></title>
		<base href="<?php echo ADMIN_RESOURCES; ?>" />
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />

		<?php echo Assets::group('global', 'js_params'); ?>
		<?php Observer::notify( 'layout_backend_head_before' ); ?>
		<?php echo Assets::css(); ?>
		<?php echo Assets::js(); ?>
		<?php echo Assets::group('global', 'events'); ?>
		<?php Observer::notify( 'layout_backend_head_after' ); ?>
	</head>
	<body id="body_<?php echo $page_body_id; ?>" class="<?php echo $request->query('type'); ?> <?php echo $theme; ?> main-menu-fixed">
		<div id="main-wrapper">
			<?php if ($request->query('type') != 'iframe'): ?>
			<header>
				<?php echo View::factory('system/layout/navbar'); ?>
			</header>
			<div id="main-menu" role="navigation">
				<?php echo View::factory('system/layout/menu'); ?>
			</div>
			<div id="main-menu-bg"></div>
			<?php endif; ?>

			<div id="content-wrapper">
				<?php echo $breadcrumbs; ?>
				<div class="row">
					<div class="col-md-3">
						<div id="kodoc-topics" class="panel">
							<?php echo $menu ?>
						</div>
					</div>
					<div class="col-md-9">
						<?php if (!empty($title)): ?>
						<?php echo UI::page_header($title); ?>
						<?php endif; ?>
						<?php echo $content; ?>

						<div class="clearfix"></div>
					</div>
				</div>
				
				<?php echo $footer; ?>
			</div>
			
			<?php if (Config::get('site', 'profiling') == Config::YES): ?>
			<?php echo View::factory('profiler/stats') ?>
			<?php endif; ?>
		</div>
</body>
</html>
