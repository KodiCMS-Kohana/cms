<div class="form-group">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<?php echo Form::hidden($field->name, $value, array(
			'id' => $field->name,
			'class' => 'form-control col-md-12', 
			'data-ajax-url' => $field->source_url($document),
			'data-ajax-preload' => $field->preload ? 'true' : 'false'
		)); ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>