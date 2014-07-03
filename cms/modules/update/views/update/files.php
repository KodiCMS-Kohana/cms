<?php if(!empty($files['new_files'])): ?>
<h3><?php echo __('New files'); ?></h3>
<ul>
	<?php foreach ($files['new_files'] as $link): ?>
	<li><?php echo HTML::anchor($link); ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(!empty($files['diff_files'])): ?>
<h3><?php echo __('Changed files'); ?></h3>
<ul>
	<?php foreach ($files['diff_files'] as $row): ?>
	<li><?php echo HTML::anchor($row['url']); ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>