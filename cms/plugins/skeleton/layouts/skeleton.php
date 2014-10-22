<?php
/*
 * Пример расположение файла шаблона.
 * 
 * Если название шаблона совпадает с названием в папке layouts в корне сайта,
 * то шаблон плагина заменит основной шаблон
 * 
 */ 
?>
<!DOCTYPE html>
<html lang="<?php echo I18n::lang(); ?>">
	<head>
		<?php
		echo Meta::factory($page)
			->add(array('name' => 'author', 'content' => 'KodiCMS'))
			->package(array('jquery', 'bootstrap', 'skeleton')); 
		?>
	</head>
	<body>
		<?php Block::run('header'); ?>

		<div class="row">
			<div class="col-md-9">
				<?php Block::run('body'); ?>
			</div>
			<div class="col-md-3">
				<?php Block::run('sidebar'); ?>
				<?php Block::run('recent'); ?>
			</div>
		</div>

		<?php Block::run('footer'); ?>
	</body>
</html>