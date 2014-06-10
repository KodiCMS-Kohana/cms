<?php 
$class = 'input-auto'; 
if($field->use_filemanager)
{
	$class .= ' input-filemanager';
}
?>

<div class="control-group">
	<label class="control-label" for="<?php echo $field->name; ?>"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="controls">
		<div class="input-append">
			<?php echo Form::input( $field->name, $value, array(
				'id' => $field->name, 'maxlength' => $field->length, 'size' => $field->length,
				'class' => $class
			) ); ?>
		</div>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>		
	</div>
</div>