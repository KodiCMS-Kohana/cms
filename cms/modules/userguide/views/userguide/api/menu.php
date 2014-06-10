<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('Modules'); ?></h3>
	</div>
	<div class="widget-content">
		<ol class="menu">
		<?php foreach ($menu as $package => $categories): ksort($categories); ?>
		<li><span><strong><?php echo $package ?></strong></span>
			<ol>
			<?php foreach ($categories as $category => $classes): sort($classes); ?>
				<li><?php if ($category !== 'Base'): ?><span><?php echo $category ?></span>
					<ol><?php endif ?>
					<?php foreach ($classes as $class): ?>
						<li><?php echo $class ?></li>
					<?php endforeach ?>
					<?php if ($category !== 'Base'): ?></ol><?php endif ?>
				</li>
			<?php endforeach ?>
			</ol>
		<?php endforeach ?>
		</ol>
	</div>
</div>