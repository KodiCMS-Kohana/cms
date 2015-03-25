<?php if($document->has_access_change()): ?>
<script>$(function(){ 
	cms.filters.switchOn('<?php echo $field->name; ?>', '<?php echo $field->filter; ?>', {height: 200});
});</script>
<?php endif; ?>
<div class="form-group">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<?php echo Form::textarea($field->name . '_html', $value, array(
			'class' => 'form-control', 'id' => $field->name, 'data-height' => '265'
		)); ?>
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>