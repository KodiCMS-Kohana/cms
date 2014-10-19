<div class="panel">
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Modules'); ?></span>
	</div>
	<div class="panel-body">
		<ol class="menu list-unstyled">
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