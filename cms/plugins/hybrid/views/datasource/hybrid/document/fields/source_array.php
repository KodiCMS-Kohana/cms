<div class="form-group">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<div class="input-group">
			<?php echo Form::hidden($field->name, implode(',', $field->get_related_docs($document->id)), array(
				'id' => $field->name, 'data-related-array' => $field->from_ds
			)); ?>
			
			<?php if($document->has_access_change()): ?>
			<div class="input-group-btn">
				<?php echo UI::button(__('Create new'), array(
					'href' => Route::get('datasources')->uri(array(
						'directory' => 'hybrid',
						'controller' => 'document',
						'action' => 'create'
					)) . URL::query(array('ds_id' => $field->from_ds), FALSE),
					'icon' => UI::icon('building'),
					'class' => 'btn-default popup fancybox.iframe',
					'data-target' => $field->name
				)); ?>
			</div>
			<?php endif; ?>
		</div>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>
