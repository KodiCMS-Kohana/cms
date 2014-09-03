<?php echo Assets_Package::load('colorpicker'); ?>
<script type="text/javascript">
$(function() {
	$('.colorSelector').ColorPicker({
		onChange: function (hsb, hex, rgb) {
			$('.colorPreview').css('background-color', '#' + hex);
			$('input[name="default"]').val('#' + hex);
		}
	});
});
</script>
<div class="form-group form-inline">
	<label class="control-label col-md-3" for="primitive_default"><?php echo __('Default value'); ?></label>
	<div class="col-md-9">
		<div class="input-group">
			<?php echo Form::input('default', $field->default, array(
				'class' => 'form-control colorSelector', 'id' => 'primitive_default', 'size' => 7, 'maxlength' => 7,
				'autocomplete' => 'off'
			)); ?>
			<div class="input-group-addon colorSelector colorPreview" style="background-color: <?php echo $field->default; ?>;">&nbsp;</div>
		</div>
	</div>
</div>