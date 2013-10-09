<div class="tags_cloud text-center">
	<?php foreach($tags as $tag => $params): ?>
	<?php echo HTML::anchor($tag, $tag, array(
		'style' => "font-size: {$params['size']}px; color: {$params['color']}"
	)); ?>
	<?php endforeach; ?>
</div>