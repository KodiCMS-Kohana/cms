<?php 
$attributes = array(
	'id' => $field->name, 
	'maxlength' => $field->length, 
	'size' => $field->length,
	'class' => 'form-control'
);

if($field->use_filemanager)
{
	$attributes['data-filemanager'] = 'true';
}
?>

<div class="form-group">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<div class="input-group">
			<?php echo Form::input($field->name, $value, $attributes); ?>
		</div>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>		
	</div>
</div>