<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<title><?php echo $page->title(); ?> | <?php echo Setting::get('site_title'); ?></title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index, follow" />
		<meta name="description" content="<?php echo $page->description(Setting::get('site_description')); ?>" />
		<meta name="keywords" content="<?php echo $page->keywords('default, keywords, here'); ?>" />
		<meta name="author" content="Author Name" />
		
		<?php echo HTML::script( PUBLIC_URL . 'js/jquery-1.9.0.min.js' ) . "\n"; ?>
		<?php echo HTML::script( PUBLIC_URL . 'js/bootstrap.min.js' ) . "\n"; ?>
		<?php echo HTML::script( PUBLIC_URL . 'js/holder.js' ) . "\n"; ?>

		<?php echo HTML::style( PUBLIC_URL . 'css/bootstrap.min.css' ) . "\n"; ?>
	</head>
	<body>
		<div class="container">
			<?php Block::run('header'); ?>
			<?php Block::run('bradcrumbs'); ?>
			
			<?php Block::run('top_banner'); ?>

			<div class="row-fluid">
				<div class="span9">
					<div class="page-header">
						<h1><?php echo $page->title(); ?></h1>
					</div>
	
					<?php Block::run('body'); ?>
					<?php Block::run('pagination'); ?>
					
					<?php Block::run('extended'); ?>
				</div>
				<div class="span3">
					<?php Block::run('sidebar'); ?>
                    <?php Block::run('recent'); ?>
				</div>
			</div>
			<?php Block::run('footer'); ?>
		</div> <!-- end #page -->
	</body>
</html>