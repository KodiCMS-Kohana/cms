<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?></label>
	<div class="controls">
		<?php
		echo Form::textarea( $field->name, $value, array(
			'class' => 'input-block-level', 'id' => $field->name, 'rows' => 2
		) );
		?>
	</div>
</div>