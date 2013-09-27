<?php if(!empty($header)): ?>
<h3><?php echo $header; ?></h3>
<?php endif; ?>

<?php if(!empty($pages)): ?>
<ul class="breadcrumb">
	<?php foreach($pages as $page): ?>
	<?php if(end($pages) === $page): ?>
	<li class="active"><?php echo $page['title']; ?></li>
	<?php else: ?>
	<li><?php echo HTML::anchor($page['uri'], $page['title']); ?></li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php endif; ?>