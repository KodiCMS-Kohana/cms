<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<title><?php echo $this->title(); ?> | <?php echo Setting::get('admin_title'); ?></title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?php echo ($this->description() != '') ? $this->description() : 'Default description goes here'; ?>" />
		<meta name="keywords" content="<?php echo ($this->keywords() != '') ? $this->keywords() : 'default, keywords, here'; ?>" />
		<meta name="author" content="Author Name" />
		
		<?php
			echo HTML::style( PUBLIC_URL . 'themes/normal/screen.css' ) . "\n";
		?>

		<link rel="alternate" type="application/rss+xml" title="Frog Default RSS Feed" href="<?php echo URL::site('rss.xml', TRUE); ?>" />
	</head>
	<body>
		<div id="page">
			<?php $this->includeSnippet( 'header' ); ?>
			<div id="content">

				<h2><?php echo $this->title(); ?></h2>
				<?php echo $this->content(); ?> 
				<?php echo $this->content( 'extended' ); ?> 

			</div> <!-- end #content -->
			<div id="sidebar">

				<?php echo $this->content( 'sidebar', TRUE ); ?> 

			</div> <!-- end #sidebar -->
			<?php $this->includeSnippet( 'footer' ); ?>
		</div> <!-- end #page -->
	</body>
</html>