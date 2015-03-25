<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="<?php echo CMS_NAME . ' v.' . CMS_VERSION; ?>">
		<meta name="author" content="ButscH" />
		<title><?php echo $title; ?> &ndash; <?php echo Config::get('site', 'title' ); ?></title>
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />
		
		<?php echo Assets::group('global', 'js_params'); ?>
		<?php Observer::notify( 'layout_frontend_head_before' ); ?>
		<?php echo Assets::css(); ?>
		<?php echo Assets::js(); ?>
		<?php echo Assets::group('global', 'events'); ?>
		<?php Observer::notify( 'layout_frontend_head_after' ); ?>
	</head>
	<body id="body_frontend" class="theme-default">
		<?php echo $content; ?>
		
		<?php if ( Config::get('site', 'profiling' ) == Config::YES ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
</html>