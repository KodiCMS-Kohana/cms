<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<?php
		echo Meta::factory($page)
				->add(array('name' => 'author', 'content' => 'KodiCMS'))
				->js('jquery', PLUGINS_URL . 'test/public/js/jquery-1.9.0.min.js')
				->js('bootstrap', PLUGINS_URL . 'test/public/js/bootstrap.min.js', 'jquery')
				->js('holder', PLUGINS_URL . 'test/public/js/holder.js', 'jquery')
				->css('bootstrap', PLUGINS_URL . 'test/public/css/bootstrap.min.css'); 
		?>
	</head>
	<body>
		<div class="container">
			<?php Block::run('header'); ?>
			<?php Block::run('bradcrumbs'); ?>
			
			<?php Block::run('top_banner'); ?>

			<div class="row-fluid">
				<div class="col-md-9">
					<div class="page-header">
						<h1><?php echo $page->title(); ?></h1>
					</div>
	
					<?php Block::run('body'); ?>
					<?php Block::run('pagination'); ?>
					
					<?php Block::run('extended'); ?>
				</div>
				<div class="col-md-3">
					<?php Block::run('sidebar'); ?>
                    <?php Block::run('recent'); ?>
				</div>
			</div>
			<?php Block::run('footer'); ?>
		</div> <!-- end #page -->
	</body>
</html>