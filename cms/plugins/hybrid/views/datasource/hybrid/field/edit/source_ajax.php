<div class="form-group">
	<label class="control-label col-md-3" for="url"><?php echo __('Source (URL)'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('url', $field->url, array(
			'class' => 'form-control', 'id' => 'url'
		)); ?>
		
		<p class="help-block"><?php echo __('Example: :example', array(
			':example' => Route::url('api', array(
				'backend' => ADMIN_DIR_NAME,
				'directory' => 'datasource/hybrid',
				'controller' => 'document', 
				'action' => 'find'
			), TRUE)
		)); ?></p>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('preload', 1, $field->preload == 1, array(
					'id' => 'preload'
				)); ?> <?php echo __('Preload data'); ?>
				
				<p class="help-block"><?php echo __('Load remote data on page load'); ?></p>
			</label>
		</div>
	</div>
</div>

<hr class="panel-wide" />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="inject_key"><?php echo __('Widget inject key'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('inject_key', $field->inject_key, array(
			'class' => 'form-control', 'id' => 'inject_key', 'size' => 50, 'maxlength' => 50
		)); ?>
		
		<p class="help-block"><?php echo __('The key is used for injection into the related widget'); ?></p>
	</div>
</div>