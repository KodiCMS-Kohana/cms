<?php
if (!$document->loaded() AND $field->set_current === TRUE) $value = Auth::get_id(); 
?>

<div class="form-group form-inline">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<div class="input-group">
			<?php if ($field->only_current): ?>
			<?php echo Form::hidden($field->name, $value); ?>
			<?php echo Form::select('', $field->get_users(), $value, array('disabled')); ?>
			<?php else: ?>
			<?php echo Form::select($field->name, $field->get_users(), $value, array('class' => 'form-control', 'style' => 'width: 250px;')); ?>
			<?php endif; ?>
		
			<?php if($field->is_exists($value)): ?>
			<div class="input-group-btn">
				<?php echo HTML::anchor(Route::get('backend')->uri(array(
					'controller' => 'users',
					'action' => 'edit',
					'id' => $value
				)), __('Show profile'), array('class' => 'popup fancybox.iframe btn btn-default')); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>