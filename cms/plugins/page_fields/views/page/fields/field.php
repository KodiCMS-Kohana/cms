<div class="page-field row" data-id="<?php echo $field->id; ?>">
	<div class="col-md-3 system-field">
		<?php if($field->loaded()): ?>
		<?php echo FORM::input('title', $field->title, array(
			'placeholder' => __('Field title'), 'data-slug' => '.field-slug',
			'class' => 'form-control', 'disabled'
		)); ?>
		<?php else: ?>

		<?php echo FORM::input('title', $field->title, array(
			'placeholder' => __('Field title'), 'data-slug' => '.field-slug',
			'class' => 'form-control'
		)); ?>
		<?php endif; ?>
	</div>
	
	<div class="col-md-2 system-field">
		<?php if($field->loaded()): ?>
		<?php echo FORM::input('key', $field->key, array(
			'placeholder' => __('Field key'), 'disabled',
			'class' => 'form-control slug field-slug', 'data-separator' => '_'
		)); ?>
		<?php else: ?>
		<?php echo FORM::input('key', $field->key, array(
			'placeholder' => __('Field key'), 
			'class' => 'form-control slug field-slug', 'data-separator' => '_'
		)); ?>
		<?php endif; ?>
	</div>

	<div class="col-md-7">
		<div class="input-group">
			<?php echo FORM::input('value', $field->value, array(
				'placeholder' => (empty($field->value) AND $field->loaded()) ? '' : __('Field value'), 
				'class' => 'form-control', 'data-filemanager' => 'true'
			)); ?>

			<div class="input-group-btn">
				<?php if($field->loaded()): ?>
				<?php echo FORM::button('remove_field', UI::icon( 'trash-o'), array(
					'class' => 'btn btn-danger btn-remove'
				)); ?>
				<?php else: ?>
				<?php echo FORM::button('add_field', UI::icon( 'plus'), array(
					'class' => 'btn btn-success btn-add'
				)); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<?php if($field->loaded()): ?>
	<div class="clearfix"></div>
	<hr />
	<?php endif; ?>
</div>

	