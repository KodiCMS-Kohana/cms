<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?></label>
	<div class="controls">
		<?php echo Form::select( $field->name, $field->select, $value ); ?>
	</div>
</div>