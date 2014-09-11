<?php $files = $field->load($value); ?>

<div class="form-group">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<div class="panel">
			<?php if(!empty($files)): ?>
			<div class="panel-heading panel-toggler" data-icon="chevron-down">
				<span class="panel-title"><?php echo __('Upload new files'); ?></span>
			</div>
			<?php endif; ?>
			<div class="panel-body padding-sm <?php if (!empty($files)): ?>panel-spoiler<?php endif; ?>">
				<?php echo Form::file( $field->name . '[]', array(
					'id' => $field->name, 
					'multiple', 
					'class' => 'upload-input',
					'data-target' => $field->name . '_preview',
					'data-size' => $field->max_size
				) ); ?>

				<p class="help-block">
					<?php echo __('Max file size: :size', array(
					':size' => Text::bytes($field->max_size)
					)); ?>
				</p>

				<?php if(!empty($files)): ?>
				<hr class="no-margin-b"/>
				<?php endif; ?>
			</div>
			<div id="<?php echo $field->name; ?>_preview" class="panel-body padding-sm no-padding-hr clearfix" style="display: none;"></div>
			<?php if(!empty($files)): ?>
			<div class="panel-body padding-sm no-padding-hr clearfix">
				<?php foreach ($files as $id => $file): ?>
				<div class="thumbnail pull-left margin-xs-hr">
					<a href="<?php echo PUBLIC_URL . $file; ?>" rel="<?php echo $field->name; ?>" class="popup" data-title="false">
						<?php echo HTML::image(Image::cache($file, 100, 100, Image::HEIGHT, TRUE)); ?>
					</a>
					<?php echo Form::hidden($field->name . '_remove[]', $id, array('disabled')); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>