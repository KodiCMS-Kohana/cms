<div class="panel-heading" data-icon="puzzle-piece">
	<span class="panel-title"><?php echo __('Plugins'); ?></span>
</div>
<div class="panel-body">
	<?php if (isset($plugins['test'])): ?>
	<div class="form-group">
		<label class="control-label col-xs-3"><?php echo __('Demo site'); ?></label>
		<div class="col-xs-9">
			<label id="insert-test-data" class="checkbox btn btn-success btn-checkbox">
				<?php echo Form::checkbox('install[insert_test_data]', 1, (bool) Arr::get($data, 'insert_test_data')); ?> <?php echo __('Install demo site'); ?>
			</label>
		</div>
	</div>
	<?php unset($plugins['test']); ?>
	<?php endif; ?>

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Install plugins'); ?></label>
		<div class="col-md-9">
			<?php foreach ($plugins as $id => $plugin): ?>
			<?php if (!$plugin->is_installable()) continue; ?>
			<div class="checkbox">
				<label>
					<?php echo Form::checkbox('install[plugins][' . $id . ']', $id, (bool) Arr::path($data, 'plugins.' . $id)); ?> <?php echo $plugin->title(); ?>
					<p class="text-muted"><?php echo $plugin->description(); ?></p>
				</label>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>