<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<title><?php echo $page->title(); ?> | <?php echo Setting::get('admin_title'); ?></title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?php echo ($page->description() != '') ? $page->description() : 'Default description goes here'; ?>" />
		<meta name="keywords" content="<?php echo ($page->keywords() != '') ? $page->keywords() : 'default, keywords, here'; ?>" />
		<meta name="author" content="Author Name" />
		
		<?php echo HTML::style( PUBLIC_URL . 'themes/normal/screen.css' ) . "\n"; ?>

		<link rel="alternate" type="application/rss+xml" title="Frog Default RSS Feed" href="<?php echo URL::site('rss.xml', TRUE); ?>" />
	</head>
	<body>
		<div id="page">
			<?php echo $page->includeSnippet( 'header' ); ?>
			<div id="content">
				<h2><?php echo $page->title(); ?></h2>
				<?php echo $page->content(); ?> 
				<?php echo $page->content( 'extended' ); ?> 
			</div> <!-- end #content -->

			<div id="sidebar">
				<?php echo $page->content( 'sidebar', TRUE ); ?> 
			</div> <!-- end #sidebar -->

			<?php echo $page->includeSnippet( 'footer' ); ?>
		</div> <!-- end #page -->
	</body>
</html>