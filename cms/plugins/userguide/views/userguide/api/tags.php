<h4><?php echo __('Tags'); ?></h4>
<ul>
<?php foreach ($tags as $name => $set): ?>
<li><?php echo ucfirst($name).($set?' - '.implode(', ',$set):''); ?>
<?php endforeach ?>
</ul>