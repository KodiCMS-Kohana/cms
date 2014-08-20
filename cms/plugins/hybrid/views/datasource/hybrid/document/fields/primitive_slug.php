<script>
$(function() {
	$('button[name="copy_from_header"]').on('click', function(e) {
		$(this).parent().prev().val(getSlug($('input[name="header"]').val(), {separator: '<?php echo $field->separator; ?>'})).keyup();
		e.preventDefault();
	});
})
</script>

<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<div class="input-group">
			<?php echo Form::input( $field->name, $value, array(
				'class' => 'form-control slug ' . (!empty($field->from_header) ? 'from-header' : ''), 'id' => $field->name,
				'maxlength' => 255, 'data-separator' => $field->separator
			) ); ?>
			<div class="input-group-btn">
				<?php echo Form::button('copy_from_header', UI::icon('magnet'), array('class' => 'btn')); ?>
			</div>
		</div>
		
		<?php if($field->unique): ?>
		<span class="help-inline"><?php echo __('Field value must be unique'); ?></span>
		<?php endif; ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>