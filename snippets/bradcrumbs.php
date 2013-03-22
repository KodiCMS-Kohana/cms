<?php if(!empty($pages)): ?>
	<ul class="breadcrumb">
		<?php foreach($pages as $page): ?>
		<?php if(end($pages) === $page): ?>
		<li class="active"><?php echo $page['title']; ?></li>
		<?php else: ?>
		<li><?php echo HTML::anchor($page['uri'], $page['title']); ?> <span class="divider">/</span></li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>