<div class="panel-heading">
	<h3><?php echo __('Plugins'); ?></h3>
</div>
<div class="panel-body">
	<?php if(isset($plugins['test'])): ?>
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Demo site'); ?></label>
		<div class="controls">
			<label id="insert-test-data" class="checkbox btn btn-success btn-checkbox">
				<?php echo Form::checkbox('install[insert_test_data]', 1, (bool) Arr::get($data, 'insert_test_data')); ?> <?php echo __('Install demo site'); ?>
			</label>
		</div>
	</div>
	<?php unset($plugins['test']); ?>
	<?php endif; ?>

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Install plugins'); ?></label>
		<div class="controls">
			<?php foreach ($plugins as $id => $plugin): ?>
				<label class="checkbox">
					<?php echo Form::checkbox('install[plugins][' . $id . ']', $id, (bool) Arr::path($data, 'plugins.' . $id)); ?> <?php echo $plugin->title(); ?>
					<p class="muted"><?php echo $plugin->description(); ?></p>
				</label>
			<?php endforeach; ?>
		</div>
	</div>
</div>