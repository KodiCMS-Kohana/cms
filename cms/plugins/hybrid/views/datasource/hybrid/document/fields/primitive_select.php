<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="controls">
		<?php echo Form::select( $field->name, $field->get_options(), $value, array(
			'id' => $field->name
		) ); ?>
		
		<?php if($field->custom_option): ?>
		<?php echo Form::input( $field->name . '_custom', NULL, array(
			'id' => $field->name . '_custom',
			'maxlength' => 50, 'placeholder' => __('Custom value')
		) ); ?>
		<?php endif; ?>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>
	</div>
</div>