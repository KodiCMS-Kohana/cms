<?php if(count($crumbs) > 1): ?>
	<ul class="breadcrumb">
		<?php foreach($crumbs as $item): ?>
		<?php if($item->is_active()): ?>
		<li class="active"><?php echo $item->name; ?></li>
		<?php else: ?>
		<li><?php echo $item->link(); ?> <span class="divider">/</span></li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>