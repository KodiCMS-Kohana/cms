<div class="page-header">
	<h2><?php echo __('User Guide'); ?></h2>
</div>

<h4><?php echo __('The following modules have userguide pages:'); ?></h4>
<br/>
<?php if( ! empty($modules)): ?>

	<?php foreach($modules as $url => $options): ?>
	<blockquote>
		<p><strong>
			<?php echo html::anchor(Route::get('docs/guide')->uri(array('module' => $url)), $options['name'], NULL, NULL, TRUE) ?>
		</strong></p>
		<small><?php echo $options['description'] ?></small>
	</blockquote>
	<?php endforeach; ?>
	
<?php else: ?>
<div class="alert alert-block">
	<p><?php echo __('I couldn`t find any modules with userguide pages.'); ?></p>
</div>
<?php endif; ?>