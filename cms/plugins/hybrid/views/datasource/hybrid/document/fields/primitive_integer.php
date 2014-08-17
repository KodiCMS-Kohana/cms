<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<?php echo Form::input( $field->name, $value, array(
			'class' => 'form-control', 'id' => $field->name,
			'maxlength' => $field->length, 'size' => $field->length
		) ); ?>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>
	</div>
</div>