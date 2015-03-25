<?php 
$attributes = array(
	'id' => $field->name, 
	'maxlength' => 7, 
	'size' => 7,
	'class' => 'form-control colorselector'
);
?>

<div class="form-group form-inline" id="<?php echo $field->name; ?>-container">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<div class="input-group">
			<?php echo Form::input($field->name, $value, $attributes); ?>
			<div class="input-group-addon colorPreview colorselector" style="background-color: <?php echo $value; ?>; cursor: pointer;">&nbsp;</div>
		</div>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>		
	</div>
</div>

<script type="text/javascript">
$(function() {
	$('#<?php echo $field->name; ?>-container .colorselector').ColorPicker({
		onChange: function (hsb, hex, rgb) {
			$('.colorPreview', '#<?php echo $field->name; ?>-container').css('background-color', '#' + hex);
			$('input[name="<?php echo $field->name; ?>"]').val('#' + hex);
		}
	});
});
</script>