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

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<?php if($field->use_filemanager): ?>
		<div class="input-group">
		<?php endif; ?>
			<?php echo Form::input($field->name, $value, $attributes); ?>
		<?php if($field->use_filemanager): ?>
			<div class="input-group-btn"></div>
		</div>
		<?php endif; ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>		
	</div>
</div>