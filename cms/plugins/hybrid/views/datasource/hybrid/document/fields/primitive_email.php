<div class="form-group form-inline">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<?php echo Form::input( $field->name, $value, array(
			'class' => 'form-control', 'id' => $field->name,
			'maxlength' => 60, 'size' => 60
		) ); ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>