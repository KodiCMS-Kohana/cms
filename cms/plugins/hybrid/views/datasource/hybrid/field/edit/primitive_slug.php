<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('from_header', 1, $field->from_header == 1, array(
					'id' => 'from_header'
				)); ?> <?php echo __('Slug from header'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('unique', 1, $field->unique == 1, array(
					'id' => 'unique'
				)); ?> <?php echo __('Unique value'); ?>
			</label>
		</div>
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="separator"><?php echo __('Slug separator'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('separator', $field->separator, array(
			'class' => 'form-control', 'id' => 'separator', 'size' => 1, 'maxlength' => 1
		)); ?>
	</div>
</div>