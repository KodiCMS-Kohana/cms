<?php if(!empty($header)): ?>
<h3><?php echo $header; ?></h3>
<?php endif; ?>
	
<ul>
<?php foreach ($pages as $page): ?>
    <li><?php echo $page->link(); ?></li>
<?php endforeach; ?>
</ul>