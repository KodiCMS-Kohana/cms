<?php if (!empty($modules)): ?>
<div class="panel-heading">
	<span class="panel-title"><?php echo __('Modules'); ?></span>
</div>
<ul class="list-group">
	<?php foreach ($modules as $url => $options): ?>
	<li class="list-group-item">
		<?php echo HTML::anchor(Route::get('docs/guide')->uri(array('module' => $url)), $options['name'], NULL, NULL, TRUE) ?>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<div class="alert alert-block">
	<p><?php echo __('No modules.'); ?></p>
</div>
<?php endif; ?>