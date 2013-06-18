<div class="page-field" data-id="<?php echo $field->id; ?>">
	<?php if($field->loaded()): ?>
	<?php echo FORM::input('title', $field->title, array(
		'placeholder' => __('Field title'), 'data-slug' => '.field-slug',
		'class' => 'span3', 'disabled'
	)); ?>
	<?php else: ?>
	<?php echo FORM::input('title', $field->title, array(
		'placeholder' => __('Field title'), 'data-slug' => '.field-slug',
		'class' => 'span3'
	)); ?>
	<?php endif; ?>
	<?php if($field->loaded()): ?>
	<?php echo FORM::input('key', $field->key, array(
		'placeholder' => __('Field key'), 'disabled',
		'class' => 'span2 slug field-slug', 'data-separator' => '_'
	)); ?>
	<?php else: ?>
	<?php echo FORM::input('key', $field->key, array(
		'placeholder' => __('Field key'), 
		'class' => 'span2 slug field-slug', 'data-separator' => '_'
	)); ?>
	<?php endif; ?>

	<?php echo FORM::input('value', $field->value, array(
		'placeholder' => (empty($field->value) AND $field->loaded()) ? '' : __('Field value'), 
		'class' => 'span6'
	)); ?>

	<?php if($field->loaded()): ?>
	<?php echo FORM::button('remove_field', UI::icon( 'trash'), array(
		'class' => 'btn btn-danger btn-remove'
	)); ?>
	<?php else: ?>
	<?php echo FORM::button('add_field', UI::icon( 'plus'), array(
		'class' => 'btn btn-success btn-add'
	)); ?>
	<?php endif; ?>
	
	<?php if($field->loaded()): ?>
	<div class="clearfix"></div>
	<hr />
	<?php endif; ?>
</div>

	