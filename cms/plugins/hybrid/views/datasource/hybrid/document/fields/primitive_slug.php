<script>
	$(function() {
		$('button[name="copy_from_header"]').on('click', function() {
			$(this).prev().val(cms.convertSlug($('input[name="header"]').val(), '<?php echo $field->separator; ?>')).keyup();
			return false;
		});
	})
</script>

<div class="form-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="controls">
		<div class="row-fluid">
			<div class="input-append span10">
				<?php echo Form::input( $field->name, $value, array(
					'class' => 'input-xxlarge slug ' . (!empty($field->from_header) ? 'from-header' : ''), 'id' => $field->name,
					'maxlength' => 255, 'data-separator' => $field->separator
				) ); ?>

				<?php echo Form::button('copy_from_header', UI::icon('magnet'), array('class' => 'btn')); ?>
			</div>
		</div>
		
		<?php if($field->unique): ?>
		<span class="help-inline"><?php echo __('Field value must be unique'); ?></span>
		<?php endif; ?>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>
	</div>
</div>