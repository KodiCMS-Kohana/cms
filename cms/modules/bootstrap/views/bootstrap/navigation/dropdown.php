<?php if( !empty($items) ): ?>
<ul class="dropdown-menu">
	<?php foreach ($items as $item): ?>
	<li<?php echo $item['attributes']; ?>><?php echo $item['data']; ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>