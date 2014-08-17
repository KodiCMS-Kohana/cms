<div class="form-group">
	<label class="control-label col-md-3" for="from_header"><?php echo __('Slug from header'); ?></label>
	<div class="col-md-9">
		<?php echo Form::checkbox('from_header', 1, $field->from_header == 1, array('id' => 'from_header' )); ?>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3" for="unique"><?php echo __('Unique value'); ?></label>
	<div class="col-md-9">
		<?php echo Form::checkbox('unique', 1, $field->unique == 1, array('id' => 'unique' )); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<label class="control-label col-md-3" for="separator"><?php echo __('Slug separator'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('separator', $field->separator, array(
			'id' => 'separator',
			'maxlength' => 1,
			'class' => 'input-mini'
		) ); ?>
	</div>
</div>