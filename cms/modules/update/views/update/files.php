<?php if (!empty($files['new_files'])): ?>
<h4><?php echo __('New files'); ?></h4>
<ul>
	<?php foreach ($files['new_files'] as $link): ?>
	<li><?php echo HTML::anchor($link); ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($files['diff_files'])): ?>
<h4><?php echo __('Changed files'); ?></h4>
<ul>
	<?php foreach ($files['diff_files'] as $row): ?>
	<li><?php echo HTML::anchor($row['url']); ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($files['third_party_plugins'])): ?>
<h4><?php echo __('Third party plugins'); ?></h4>
<ul>
	<?php foreach ($files['third_party_plugins'] as $row): ?>
	<li><?php echo ucfirst($row); ?></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>