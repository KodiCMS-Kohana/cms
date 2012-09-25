<?php if( ! empty($modules)): ?>

<h2><?php echo __('Modules'); ?></h2>

<ul>
	<?php foreach($modules as $url => $options): ?>
	<li>
		<?php echo Html::anchor(Route::get('docs/guide')->uri(array('module' => $url)), $options['name'], NULL, NULL, TRUE) ?>
	</li>
	<?php endforeach; ?>
</ul>

<?php else: ?>
<div class="alert alert-block">
	<p><?php echo __('No modules.'); ?></p>
</div>
<?php endif; ?>