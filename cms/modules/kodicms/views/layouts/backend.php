<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="generator" content="<?php echo CMS_NAME . ' v.' . CMS_VERSION; ?>">
		<meta name="author" content="ButscH" />
		<title><?php echo $title; ?> &ndash; <?php echo Setting::get( 'site_title' ); ?></title>
		<link href="<?php echo ADMIN_RESOURCES; ?>favicon.ico" rel="favourites icon" />

		<script type="text/javascript">
		<?php echo View::factory('layouts/blocks/jsvars'); ?>
		<?php echo $messages; ?>
		</script>

		<?php echo Assets::css(); ?>
		<?php echo Assets::js(); ?>

		<?php Observer::notify( 'layout_backend_head' ); ?>
	</head>
	<body id="body_<?php echo $page_body_id; ?>" class="<?php echo $request->query('type'); ?>">
		<div id="content-wrapper">
			<header>
				<nav>
					<?php echo View::factory('layouts/blocks/navigation'); ?>
				</nav>
				<?php echo $breadcrumbs; ?>
			</header>
			<div class="container-fluid">
				<section id="content" >
				<?php echo $content; ?>
				</section>
			</div>
		</div>
		
		<?php echo $footer; ?>

		<?php if ( Setting::get( 'profiling' ) == 'yes' ): ?>
		<hr />
		<?php echo View::factory( 'profiler/stats' ) ?>
		<?php endif; ?>
	</body>
</html>