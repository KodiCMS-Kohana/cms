<?php $i = 1; $total = count($breadcrumbs); ?>
<ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $anchor): ?>
	<li><?php echo $anchor; ?>
		<?php if($i < $total): ?>
		<span class="divider">/</span>
		<?php endif; ?>
	</li>
	<?php $i++; ?>
	<?php endforeach; ?>
</ul>