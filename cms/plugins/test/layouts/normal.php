<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<?php
		echo Meta::factory($page)
				->group('author', '<meta name="author" content="butschster" />')
				->js('jquery', PUBLIC_URL . 'js/jquery-1.9.0.min.js')
				->js('bootstrap', PUBLIC_URL . 'js/bootstrap.min.js', 'jquery')
				->js('holder', PUBLIC_URL . 'js/holder.js', 'jquery')
				->css('bootstrap', PUBLIC_URL . 'css/bootstrap.min.css'); 
		?>
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