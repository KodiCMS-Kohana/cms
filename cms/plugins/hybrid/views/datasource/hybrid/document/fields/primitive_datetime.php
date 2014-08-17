<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<?php echo Form::input( $field->name, $value, array(
			'class' => 'form-control datetimepicker', 'id' => $field->name,
			'size' => 25
		) ); ?>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>
	</div>
</div>